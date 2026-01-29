@extends("template.master")
@section("content")
    <div class="card">
        <div class="card-header">
            <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#tambahAnggotaModal">
                Tambah Anggota
            </button>
            <!-- /.card-tools -->
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table class="table table-striped" id="table-anggota">
                <thead>
                    <tr>
                        <th>Nomor Anggota</th>
                        <th>Nama</th>
                        <th>Sisa pinjaman</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->

    <!-- Button to open modal -->

    <!-- Modal -->
    <div class="modal fade" id="tambahAnggotaModal" tabindex="-1" role="dialog" aria-labelledby="tambahAnggotaModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="form-tambah-anggota">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tambahAnggotaModalLabel">Tambah Anggota</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="no_anggota">Nomor Anggota</label>
                            <input type="text" class="form-control" id="no_anggota" name="no_anggota" required>
                        </div>
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        <div class="form-group">
                            <label for="tanggal_lahir">Tanggal Lahir</label>
                            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required>
                        </div>
                        <div class="form-group">
                            <label for="max_pinjam">Maks Pinjaman</label>
                            <input type="number" class="form-control" id="max_pinjam" name="max_pinjam" min="1"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary" id='button-simpan'>Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push("js")
    @include("master.anggota.scripts.scripts")
@endpush
