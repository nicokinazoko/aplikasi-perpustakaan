<?php

namespace App\Http\Requests\Api\Master\Buku;

use Illuminate\Foundation\Http\FormRequest;

class CreateBukuRequest extends FormRequest
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
            'judul_buku' => ['required', 'string'],
            'penerbit' => ['required', 'string'],
            'dimensi' => ['required', 'string'],
            'stok' => ['required', 'integer'],
        ];
    }

    public function messages()
    {
        return [
            'judul_buku.required' => 'Judul buku wajib diisi.',
            'judul_buku.string' => 'Judul buku harus berupa teks.',

            'penerbit.required' => 'Penerbit wajib diisi.',
            'penerbit.string' => 'Penerbit harus berupa teks.',

            'dimensi.required' => 'Dimensi buku wajib diisi.',
            'dimensi.string' => 'Dimensi buku harus berupa teks.',

            'stok.required' => 'Stok buku wajib diisi.',
            'stok.integer' => 'Stok buku harus berupa angka.',
        ];
    }
}
