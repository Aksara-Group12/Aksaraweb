<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\TransaksiKeluar;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        // Query transaksi_keluar yang is_invoice = true (tagihan piutang)
        $query = TransaksiKeluar::where('user_id', auth()->user()->id)
            ->where('is_invoice', true)
            ->with('items.product');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_no', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }

        $perPage = (int) $request->input('per_page', 20);
        if ($perPage <= 0) {
            $perPage = 20;
        }

        $invoices = $query
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->appends($request->only('search', 'per_page'));

        return view('invoice.index', compact('invoices'));
    }

    /**
     * Catat pembayaran untuk invoice/piutang
     */
    public function recordPayment(Request $request, $id)
    {
        $validated = $request->validate([
            'nominal_bayar' => 'required|numeric|min:0',
        ]);

        $transaksi = TransaksiKeluar::where('id', $id)
            ->where('user_id', auth()->user()->id)
            ->where('is_invoice', true)
            ->firstOrFail();

        $nominalBayar = floatval($validated['nominal_bayar']);

        DB::transaction(function () use ($transaksi, $nominalBayar) {
            // Cek apakah sudah ada invoice record untuk transaksi ini
            $invoice = Invoice::where('sales_transaction_id', $transaksi->id)->first();

            if (!$invoice) {
                // Buat invoice baru jika belum ada
                $invoice = Invoice::create([
                    'user_id' => $transaksi->user_id,
                    'nomor_invoice' => 'INV-' . $transaksi->invoice_no,
                    'pelanggan_id' => null, // Bisa diambil dari transaksi jika perlu
                    'tanggal_invoice' => $transaksi->tanggal_transaksi,
                    'jatuh_tempo' => null,
                    'subtotal' => $transaksi->total_harga,
                    'ppn' => 0, // PPN bisa dihitung jika perlu
                    'total' => $transaksi->total_harga,
                    'status' => 'unpaid',
                    'keterangan' => 'Auto-generated from transaction',
                    'sales_transaction_id' => $transaksi->id,
                    'sisa_tagihan' => $transaksi->total_harga,
                ]);
            }

            // Update sisa tagihan
            $invoice->sisa_tagihan -= $nominalBayar;

            // Update status invoice
            if ($invoice->sisa_tagihan <= 0) {
                $invoice->status = 'paid';
                $invoice->sisa_tagihan = 0;
            } else {
                $invoice->status = 'partial';
            }

            $invoice->save();
        });

        return redirect()->route('invoice.index')->with('success', 'Pembayaran berhasil dicatat');
    }

    /**
     * Buat invoice manual (opsional untuk kasus khusus)
     */
    public function createManual()
    {
        $pelanggan = Pelanggan::where('user_id', auth()->user()->id)
            ->orderBy('nama')
            ->get();

        return view('invoice.create', compact('pelanggan'));
    }

    public function storeManual(Request $request)
    {
        $validated = $request->validate([
            'pelanggan_id' => 'nullable|exists:pelanggan,id',
            'tanggal_invoice' => 'required|date',
            'jatuh_tempo' => 'nullable|date',
            'keterangan' => 'nullable|string',
            'items' => 'required|array',
            'items.*.deskripsi' => 'required|string',
            'items.*.qty' => 'required|numeric|min:0',
            'items.*.harga_satuan' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated, &$invoiceRef) {
            $nomorInvoice = 'INV-' . date('Ymd') . '-' . str_pad(Invoice::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);

            $subtotal = 0;
            foreach ($validated['items'] as $item) {
                $itemSubtotal = ($item['qty'] * $item['harga_satuan']) - ($item['diskon'] ?? 0);
                $subtotal += $itemSubtotal;
            }

            $ppn = $subtotal * 0.11;
            $total = $subtotal + $ppn;

            $invoice = Invoice::create([
                'user_id' => auth()->user()->id,
                'nomor_invoice' => $nomorInvoice,
                'pelanggan_id' => $validated['pelanggan_id'] ?? null,
                'tanggal_invoice' => $validated['tanggal_invoice'],
                'jatuh_tempo' => $validated['jatuh_tempo'] ?? null,
                'subtotal' => $subtotal,
                'ppn' => $ppn,
                'total' => $total,
                'status' => 'unpaid',
                'keterangan' => $validated['keterangan'] ?? null,
                'sales_transaction_id' => null,
                'sisa_tagihan' => $total,
            ]);

            foreach ($validated['items'] as $item) {
                $itemSubtotal = ($item['qty'] * $item['harga_satuan']) - ($item['diskon'] ?? 0);
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'deskripsi' => $item['deskripsi'],
                    'qty' => $item['qty'],
                    'harga_satuan' => $item['harga_satuan'],
                    'diskon' => $item['diskon'] ?? 0,
                    'subtotal' => $itemSubtotal,
                ]);
            }

            $invoiceRef = $invoice;
        });

        return redirect()->route('invoice.index')->with('success', 'Invoice manual berhasil dibuat');
    }
}
