<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
class ReportController extends Controller
{
    $pdf = PDF::loadView('reports.transactions', compact('transactions'));
    return $pdf->download('laporan-transaksi.pdf');

    public function chart()
    {
        $data = Transaction::selectRaw('DATE(date) as tanggal, SUM(total) as total')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        $labels = $data->pluck('tanggal');
        $totals = $data->pluck('total');

        return view('reports.chart', compact('labels', 'totals'));
    }
}
