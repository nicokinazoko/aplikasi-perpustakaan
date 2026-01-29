@extends("template.master")
@section("content")
    <div class="card">
        <div class="card-header">
            <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#tambahPeminjamanModal">
                Tambah Peminjaman
            </button>
        </div>
        <div class="card-body">
            <table class="table table-striped" id="table-peminjaman">
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
    <div class="modal fade" id="tambahPeminjamanModal" tabindex="-1" role="dialog"
        aria-labelledby="tambahPeminjamanModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form id="form-tambah-peminjaman">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tambahPeminjamanModalLabel">Tambah Peminjaman</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Tanggal Pinjam -->
                        <div class="form-group">
                            <label for="tanggal_pinjam">Tanggal Pinjam</label>
                            <input type="date" class="form-control" id="tanggal_pinjam" name="tanggal_pinjam" required>
                        </div>

                        <!-- Anggota -->
                        <div class="form-group">
                            <label for="anggota_id">Anggota</label>
                            <select class="form-control" id="anggota_id" name="anggota_id" required>
                                <option value="">-- Pilih Anggota --</option>
                                @foreach ($anggotaList as $anggota)
                                    <option value="{{ $anggota->id }}">{{ $anggota->nama }} ({{ $anggota->no_anggota }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Detail Peminjaman -->
                        <div id="peminjaman-detail-wrapper">
                            <label>Detail Peminjaman</label>
                            <div class="row peminjaman-detail-item mb-2">
                                <div class="col-6">
                                    <select class="form-control buku_id" name="detail_peminjaman[0][buku_id]" required>
                                        <option value="">-- Pilih Buku --</option>
                                        @foreach ($bukuList as $buku)
                                            <option value="{{ $buku->id }}">{{ $buku->judul_buku }} (Stok:
                                                {{ $buku->stok }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-4">
                                    <input type="number" class="form-control total_pinjam"
                                        name="detail_peminjaman[0][total_pinjam]" min="1" placeholder="Jumlah"
                                        required>
                                </div>
                                <div class="col-2">
                                    <button type="button" class="btn btn-danger btn-remove-detail">X</button>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-secondary" id="btn-add-detail">Tambah Buku</button>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary" id="button-simpan">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push("js")
    @include("transaksi.peminjaman.scripts.scripts")
@endpush
