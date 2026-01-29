<?php

namespace App\Http\Controllers\View\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\PeminjamanModel;
use App\Models\PengembalianModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ViewPengembalianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pinjamans = PeminjamanModel::select(
            'peminjamans.id',
            'peminjamans.anggota_id',
            'peminjamans.tanggal_pinjam',
            'anggotas.nama as nama_anggota',
            'anggotas.no_anggota as no_anggota',
            DB::raw('SUM(peminjaman_details.total_pinjam) as total_buku')
        )
            ->leftJoin('peminjaman_details', 'peminjaman_details.peminjaman_id', '=', 'peminjamans.id')
            ->leftJoin('anggotas', 'anggotas.id', '=', 'peminjamans.anggota_id')
            ->whereNull('peminjamans.deleted_at')
            ->groupBy(
                'peminjamans.id',
                'peminjamans.anggota_id',
                'peminjamans.tanggal_pinjam',
                'anggotas.nama',
                'anggotas.no_anggota'
            )->get();

        return view(
            'transaksi.pengembalian.index',
            compact(
                'pinjamans'
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
