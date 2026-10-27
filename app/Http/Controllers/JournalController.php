<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use Illuminate\Http\Request;

class JournalController extends Controller
{
    public function index(Request $request)
    {
        $query = Journal::where('user_id', auth()->user()->id)
            ->with('items');

        if ($search = $request->input('search')) {
            $query->where('nomor_jurnal', 'like', "%{$search}%");
        }

        $perPage = (int) $request->input('per_page', 20);
        if ($perPage <= 0) {
            $perPage = 20;
        }

        $journals = $query
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->appends($request->only('search', 'per_page'));

        return view('journal.index', compact('journals'));
    }
}
