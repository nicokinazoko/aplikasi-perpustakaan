@push("js")
    <script>
        function reloadAnggotaTable() {
            $('#table-anggota').DataTable().ajax.reload(null, false); // false = stay on current page
        }

        // For create anggota
        async function createAnggota(parameter) {
            const routeStore = '{{ route("api.anggota.store") }}';

            try {
                const response = await $.ajax({
                    url: routeStore,
                    type: 'POST',
                    data: parameter,
                });

                const success = response && response.success === true;

                if (!success) {
                    const message = (response && response.message) || 'Ada error ketika membuat anggota';
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

        // For delete anggota
        async function deleteAnggota(idAnggota) {
            const confirmed = confirm('Apakah anda yakin ingin menghapus anggota ini?');

            if (!confirmed) return;

            const route = '{{ route("api.anggota.destroy", ":id") }}'.replace(':id', idAnggota);

            try {
                const response = await $.ajax({
                    url: route,
                    type: 'DELETE',
                });

                const success = response && response.success === true;

                if (!success) {
                    const message = (response && response.message) || 'Ada error ketika hapus anggota';
                    alert(message);
                    throw new Error(message);
                }

                const message = (response && response.message) || 'Hapus anggota berhasil';
                alert(message);

                // Reload table
                reloadAnggotaTable();

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
            // Init tabel anggota
            $('#table-anggota').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route("api.anggota.index") }}',
                    type: 'POST',
                    data: function(data) {
                        return {
                            filter: data?.search?.value
                        };
                    }
                },
                columns: [{
                        data: 'no_anggota'
                    },
                    {
                        data: 'nama'
                    },
                    {
                        data: 'max_pinjam'
                    },
                    {
                        data: null, // we don’t need a field from backend
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                            <button class="btn btn-sm btn-primary" onclick="editAnggota('${row.id}')">Edit</button>
                            <button class="btn btn-sm btn-danger" onclick="deleteAnggota('${row.id}')">Hapus</button>
                        `;
                        }
                    }
                ]
            });

            // Modal show event
            $('#tambahAnggotaModal').on('shown.bs.modal', function() {
                $('#form-tambah-anggota')[0].reset();
                $('#no_anggota').focus();
            });

            // Button simpan anggota
            $('#button-simpan').on('click', async function() {
                const parameter = {
                    no_anggota: $('#no_anggota').val(),
                    tanggal_lahir: $('#tanggal_lahir').val(),
                    nama: $('#nama').val(),
                    max_pinjam: $('#max_pinjam').val(),
                };

                try {
                    await createAnggota(parameter);

                    alert('Anggota berhasil dibuat!');

                    // Reload table
                    reloadAnggotaTable();

                    // Close modal
                    $('#tambahAnggotaModal').modal('hide');

                } catch (error) {
                    console.error('Error creating anggota:', error);
                }
            });
        });
    </script>
@endpush
