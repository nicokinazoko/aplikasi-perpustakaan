@extends("template.master")
@section("content")
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">List data anggota</h3>
            <div class="card-tools">
                <!-- Buttons, labels, and many other things can be placed here! -->
                <!-- Here is a label for example -->
                <span class="badge badge-primary">Label</span>
            </div>
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
@endsection

@push("js")
    @include("master.anggota.scripts.scripts")
@endpush
