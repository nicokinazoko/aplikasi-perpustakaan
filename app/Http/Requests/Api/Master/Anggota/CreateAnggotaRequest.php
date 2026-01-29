<?php

namespace App\Http\Requests\Api\Master\Anggota;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateAnggotaRequest extends FormRequest
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
            'no_anggota' => [
                'required',
                'string',
                Rule::unique('anggotas', 'no_anggota')->whereNull('deleted_at')
            ],
            'tanggal_lahir' => ['required', 'date', 'date_format:Y/m/d'],
            'nama' => ['required', 'string'],
            'max_pinjam' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'no_anggota.required' => 'Nomor anggota wajib diisi.',
            'no_anggota.string' => 'Nomor anggota harus berupa teks.',
            'no_anggota.unique' => 'Nomor anggota sudah digunakan.',

            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.date' => 'Tanggal lahir harus berupa tanggal yang valid.',
            'tanggal_lahir.date_format' => 'Tanggal lahir harus sesuai format YYYY/MM/DD.',

            'nama.required' => 'Nama wajib diisi.',
            'nama.string' => 'Nama harus berupa teks.',

            'max_pinjam.required' => 'Jumlah pinjaman maksimum wajib diisi.',
            'max_pinjam.integer' => 'Jumlah pinjaman maksimum harus berupa angka.',
            'max_pinjam.min' => 'Jumlah pinjaman maksimum minimal 1.',
        ];
    }
}
