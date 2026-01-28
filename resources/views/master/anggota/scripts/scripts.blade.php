@push("js")
    <script>
        console.log('masuk');

        $(document).ready(function() {
            $('#table-anggota').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route("api.anggota.index") }}',
                    type: 'POST',
                    data: function(data) {
                        const filter = data?.search?.value;

                        return {
                            filter,
                        }
                    }
                },
                columns: [{
                        data: 'nomor_anggota'
                    },
                    {
                        data: 'nama'
                    },
                    {
                        data: 'sisa_pinjaman'
                    },
                    {
                        data: 'aksi'
                    }
                ]
            });
        });
    </script>
@endpush
