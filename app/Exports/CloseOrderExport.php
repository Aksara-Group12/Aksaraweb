<?php

namespace App\Exports;

use App\Models\CloseOrderShift;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class CloseOrderExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
	protected $startDate;
	protected $endDate;

	public function __construct($startDate = null, $endDate = null)
	{
		$this->startDate = $startDate;
		$this->endDate = $endDate;
	}

	public function collection()
	{
		$query = CloseOrderShift::with('user')->where('user_id', auth()->id());

		if ($this->startDate && $this->endDate) {
			$query->whereBetween('opened_at', [$this->startDate, $this->endDate]);
		}

		return $query->orderByDesc('opened_at')->get();
	}

	public function headings(): array
	{
		return [
			'ID',
			'Operator',
			'Waktu Buka',
			'Waktu Tutup',
			'Durasi (Menit)',
			'Total Pendapatan',
			'Jumlah Transaksi',
			'Status',
		];
	}

	public function map($shift): array
	{
		$duration = 0;
		if ($shift->closed_at && $shift->opened_at) {
			$duration = $shift->closed_at->diffInMinutes($shift->opened_at);
		}

		return [
			$shift->id,
			$shift->operator_name,
			$shift->opened_at ? $shift->opened_at->format('d/m/Y H:i:s') : '-',
			$shift->closed_at ? $shift->closed_at->format('d/m/Y H:i:s') : '-',
			$duration,
			'Rp ' . number_format($shift->total_revenue, 0, ',', '.'),
			$shift->total_transactions,
			ucfirst($shift->status),
		];
	}

	public function styles(Worksheet $sheet)
	{
		$sheet->getStyle('A1:H1')->getFont()->setBold(true);
		$sheet->getStyle('A1:H1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$sheet->getStyle('A1:H' . ($sheet->getHighestRow()))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
	}
}
