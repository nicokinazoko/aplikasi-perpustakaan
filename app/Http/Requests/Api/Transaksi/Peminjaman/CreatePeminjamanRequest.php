<?php

namespace App\Http\Requests\Api\Transaksi\Peminjaman;

use Illuminate\Foundation\Http\FormRequest;

class CreatePeminjamanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tanggal_pinjam' => ['required', 'date_format:Y-m-d'],
            'anggota_id' => ['required', 'uuid'],

            // Detail peminjaman validation
            'detail_peminjaman' => ['required', 'array', 'min:1'],
            'detail_peminjaman.*.buku_id' => ['required', 'uuid'],
            'detail_peminjaman.*.total_pinjam' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal_pinjam.required' => 'Tanggal pinjam wajib diisi.',
            'tanggal_pinjam.date_format' => 'Tanggal pinjam harus sesuai format Y-m-d.',
            'anggota_id.required' => 'ID anggota wajib diisi.',
            'anggota_id.uuid' => 'ID anggota harus format UUID.',

            // Detail peminjaman
            'detail_peminjaman.required' => 'Data detail peminjaman wajib diisi.',
            'detail_peminjaman.array' => 'Data detail peminjaman harus berupa array.',
            'detail_peminjaman.min' => 'Data detail peminjaman minimal 1 item.',
            'detail_peminjaman.*.buku_id.required' => 'ID buku wajib diisi.',
            'detail_peminjaman.*.buku_id.uuid' => 'ID buku harus format UUID.',
            'detail_peminjaman.*.total_pinjam.required' => 'Jumlah pinjam wajib diisi.',
            'detail_peminjaman.*.total_pinjam.integer' => 'Jumlah pinjam harus berupa angka.',
            'detail_peminjaman.*.total_pinjam.min' => 'Jumlah pinjam minimal 1.',
        ];
    }
}
