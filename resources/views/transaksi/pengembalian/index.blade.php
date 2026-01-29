@extends("template.master")
@section("content")
    <div class="card">
        <div class="card-header">
            <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#tambahPengembalianModal">
                Tambah Pengembalian
            </button>
        </div>
        <div class="card-body">
            <table class="table table-striped" id="table-pengembalian">
                <thead>
                    <tr>
                        <th>Tanggal Pinjam</th>
                        <th>Anggota</th>
                        <th>Jumlah Buku</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="tambahPengembalianModal" tabindex="-1" role="dialog"
        aria-labelledby="tambahPengembalianModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form id="form-tambah-pengembalian">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tambahPengembalianModalLabel">Tambah Pengembalian</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="peminjaman_id">Pilih Peminjaman</label>
                            <select class="form-control" id="peminjaman_id" name="peminjaman_id" required>
                                <option value="">-- Pilih Peminjaman --</option>
                                @foreach ($pengembalians as $p)
                                    <option value="{{ $p->id }}">{{ $p->nama_anggota }} ({{ $p->no_anggota }}) | Tgl
                                        Pinjam: {{ $p->tanggal_pinjam }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="tanggal_kembali">Tanggal Kembali</label>
                            <input type="date" class="form-control" id="tanggal_kembali" name="tanggal_kembali" required>
                        </div>

                        <div class="form-group">
                            <label>Daftar Buku</label>
                            <table class="table table-striped" id="table-pengembalian-details">
                                <thead>
                                    <tr>
                                        <th>Judul Buku</th>
                                        <th>Jumlah Dipinjam</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="2" class="text-center">Pilih peminjaman terlebih dahulu</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary" id="btn-simpan-pengembalian">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection


@push("js")
    @include("transaksi.pengembalian.scripts.scripts")
@endpush
