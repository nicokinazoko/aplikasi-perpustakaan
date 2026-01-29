@extends("template.master")
@section("content")
    <div class="card">
        <div class="card-header">
            <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#tambahBukuModal">
                Tambah Buku
            </button>
        </div>
        <div class="card-body">
            <table class="table table-striped" id="table-buku">
                <thead>
                    <tr>
                        <th>Judul Buku</th>
                        <th>Penerbit</th>
                        <th>Dimensi</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="tambahBukuModal" tabindex="-1" role="dialog" aria-labelledby="tambahBukuModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="form-tambah-buku">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tambahBukuModalLabel">Tambah Buku</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="judul_buku">Judul Buku</label>
                            <input type="text" class="form-control" id="judul_buku" name="judul_buku" required>
                        </div>
                        <div class="form-group">
                            <label for="penerbit">Penerbit</label>
                            <input type="text" class="form-control" id="penerbit" name="penerbit" required>
                        </div>
                        <div class="form-group">
                            <label for="dimensi">Dimensi</label>
                            <input type="text" class="form-control" id="dimensi" name="dimensi" required>
                        </div>
                        <div class="form-group">
                            <label for="stok">Stok</label>
                            <input type="number" class="form-control" id="stok" name="stok" min="1"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary" id="button-simpan-buku">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push("js")
    @include("master.buku.scripts.scripts")
@endpush
