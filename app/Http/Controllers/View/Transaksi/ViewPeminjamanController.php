<?php

namespace App\Http\Controllers\View\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\AnggotaModel;
use App\Models\BukuModel;
use Illuminate\Http\Request;

class ViewPeminjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $anggotaList = AnggotaModel::whereNull('deleted_at')->get();
        $bukuList = BukuModel::whereNull('deleted_at')->get();
        return view('transaksi.peminjaman.index', compact(
            'anggotaList',
            'bukuList'
        ));
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
