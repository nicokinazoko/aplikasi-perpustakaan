@push("js")
    <script>
        function reloadBukuTable() {
            $('#table-buku').DataTable().ajax.reload(null, false); // false = stay on current page
        }

        // For create buku
        async function createBuku(parameter) {
            const routeStore = '{{ route("api.buku.store") }}';

            try {
                const response = await $.ajax({
                    url: routeStore,
                    type: 'POST',
                    data: parameter,
                });

                const success = response && response.success === true;

                if (!success) {
                    const message = (response && response.message) || 'Ada error ketika membuat buku';
                    alert(message);
                    throw new Error(message);
                }

                return (response && response.data) || {};

            } catch (xhr) {
                const statusCode = (xhr && xhr.status) || 500;
                const response = (xhr && xhr.responseJSON) || {};
                const message = response.message || 'Terjadi kesalahan.';

                alert(message);
                throw xhr;
            }
        }

        // For delete buku
        async function deleteBuku(idBuku) {
            const confirmed = confirm('Apakah anda yakin ingin menghapus buku ini?');

            if (!confirmed) return;

            const route = '{{ route("api.buku.destroy", ":id") }}'.replace(':id', idBuku);

            try {
                const response = await $.ajax({
                    url: route,
                    type: 'DELETE',
                });

                const success = response && response.success === true;

                if (!success) {
                    const message = (response && response.message) || 'Ada error ketika hapus buku';
                    alert(message);
                    throw new Error(message);
                }

                const message = (response && response.message) || 'Hapus buku berhasil';
                alert(message);

                // Reload table
                reloadBukuTable();

                return (response && response.data) || {};

            } catch (xhr) {
                const statusCode = (xhr && xhr.status) || 500;
                const response = (xhr && xhr.responseJSON) || {};
                const message = response.message || 'Terjadi kesalahan.';

                alert(message);
                throw xhr;
            }
        }

        $(document).ready(function() {
            // Init tabel buku
            $('#table-buku').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route("api.buku.index") }}',
                    type: 'POST',
                    data: function(data) {
                        return {
                            filter: data?.search?.value
                        };
                    }
                },
                columns: [{
                        data: 'judul_buku'
                    },
                    {
                        data: 'penerbit'
                    },
                    {
                        data: 'dimensi'
                    },
                    {
                        data: 'stok'
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                            <button class="btn btn-sm btn-danger" onclick="deleteBuku('${row.id}')">Hapus</button>
                        `;
                        }
                    }
                ]
            });

            // Modal show event
            $('#tambahBukuModal').on('shown.bs.modal', function() {
                $('#form-tambah-buku')[0].reset();
                $('#judul_buku').focus();
            });

            // Button simpan buku
            $('#button-simpan-buku').on('click', async function() {
                const parameter = {
                    judul_buku: $('#judul_buku').val(),
                    penerbit: $('#penerbit').val(),
                    dimensi: $('#dimensi').val(),
                    stok: $('#stok').val(),
                };

                try {
                    await createBuku(parameter);

                    alert('Buku berhasil dibuat!');

                    // Reload table
                    reloadBukuTable();

                    // Close modal
                    $('#tambahBukuModal').modal('hide');

                } catch (error) {
                    console.error('Error creating buku:', error);
                }
            });
        });
    </script>
@endpush
