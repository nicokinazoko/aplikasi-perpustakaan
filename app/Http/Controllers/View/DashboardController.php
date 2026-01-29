<?php

namespace App\Http\Controllers\View;

use App\Http\Controllers\Controller;
use App\Models\PeminjamanModel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Get the start of the current week (Monday)
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        // Get peminjaman count per day for current week
        $peminjamanPerHari = PeminjamanModel
            ::selectRaw('DATE(tanggal_pinjam) as date, COUNT(*) as total')
            ->whereBetween('tanggal_pinjam', [$startOfWeek, $endOfWeek])
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date'); // ['2026-01-27' => 5, ...]

        // Fill missing days with 0
        $days = [];
        for ($i = 0; $i < 7; $i++) {
            $day = $startOfWeek->copy()->addDays($i)->format('Y-m-d');
            $days[$day] = $peminjamanPerHari[$day] ?? 0;
        }

        return view('dashboard', [
            'days' => array_keys($days),
            'totals' => array_values($days),
        ]);
    }
}
