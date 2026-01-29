<?php

namespace App\Http\Requests\Api\Transaksi\Pengembalian;

use Illuminate\Foundation\Http\FormRequest;

class CreatePengembalianRequest extends FormRequest
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
            'tanggal_kembali' => ['required', 'date_format:Y-m-d'],
            'peminjaman_id' => ['required', 'uuid']
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal_kembali.required' => 'Tanggal pinjam wajib diisi.',
            'tanggal_kembali.date_format' => 'Tanggal pinjam harus sesuai format Y-m-d.',
            'peminjaman_id.required' => 'ID peminjaman wajib diisi.',
            'peminjaman_id.uuid' => 'ID peminjaman harus format UUID.',
        ];
    }
}
