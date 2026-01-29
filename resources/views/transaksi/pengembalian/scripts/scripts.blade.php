@push("js")
    <script>
        function reloadPengembalianTable() {
            $('#table-pengembalian').DataTable().ajax.reload(null, false);
        }

        // Create pengembalian
        async function createPengembalian(parameter) {
            const routeStore = '{{ route("api.pengembalian.store") }}';
            try {
                const response = await $.ajax({
                    url: routeStore,
                    type: 'POST',
                    data: parameter
                });

                if (!response.success) {
                    const message = response.message || 'Ada error ketika membuat pengembalian';
                    alert(message);
                    throw new Error(message);
                }

                return response.data || {};

            } catch (xhr) {
                const message = xhr.responseJSON?.message || 'Terjadi kesalahan server';
                alert(message);
                throw xhr;
            }
        }

        // Delete pengembalian
        async function deletePengembalian(id) {
            const confirmed = confirm('Apakah anda yakin ingin menghapus pengembalian ini?');
            if (!confirmed) return;

            const route = '{{ route("api.pengembalian.destroy", ":id") }}'.replace(':id', id);
            try {
                const response = await $.ajax({
                    url: route,
                    type: 'DELETE'
                });

                if (!response.success) {
                    const message = response.message || 'Ada error ketika hapus pengembalian';
                    alert(message);
                    throw new Error(message);
                }

                alert(response.message || 'Pengembalian berhasil dihapus');
                reloadPengembalianTable();
                return response.data || {};

            } catch (xhr) {
                const message = xhr.responseJSON?.message || 'Terjadi kesalahan server';
                alert(message);
                throw xhr;
            }
        }

        $(document).ready(function() {
            // Init table
            $('#table-pengembalian').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route("api.pengembalian.index") }}',
                    type: 'POST',
                    data: function(data) {
                        return {
                            filter: data?.search?.value
                        };
                    }
                },
                columns: [{
                        data: 'tanggal_pinjam'
                    },
                    {
                        data: null,
                        render: d => `${d.nama} (${d.no_anggota})`
                    },
                    {
                        data: 'total_pinjam'
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: d =>
                            `<button class="btn btn-sm btn-danger" onclick="deletePengembalian('${d.id}')">Hapus</button>`
                    }
                ]
            });

            // Modal show reset
            $('#tambahPengembalianModal').on('shown.bs.modal', function() {
                $('#form-tambah-pengembalian')[0].reset();
                $('#table-pengembalian-details tbody').html(
                    '<tr><td colspan="2" class="text-center">Pilih peminjaman terlebih dahulu</td></tr>'
                );
            });

            // Load details when peminjaman selected
            $('#peminjaman_id').on('change', async function() {
                const peminjamanId = $(this).val();
                const tbody = $('#table-pengembalian-details tbody');

                if (!peminjamanId) {
                    tbody.html(
                        '<tr><td colspan="2" class="text-center">Pilih peminjaman terlebih dahulu</td></tr>'
                    );
                    return;
                }

                tbody.html('<tr><td colspan="2" class="text-center">Loading...</td></tr>');

                try {
                    const response = await $.ajax({
                        url: `{{ url("api/peminjaman-detail/detail-peminjaman") }}/${peminjamanId}`,
                        type: 'GET'
                    });

                    const details = response.data || [];
                    tbody.empty();
                    if (details.length > 0) {
                        details.forEach(item => {
                            tbody.append(
                                `<tr><td>${item.judul_buku ?? 'Unknown'}</td><td>${item.total_pinjam ?? 1}</td></tr>`
                            );
                        });
                    } else {
                        tbody.html('<tr><td colspan="2" class="text-center">Tidak ada buku</td></tr>');
                    }
                } catch (err) {
                    tbody.html(
                        '<tr><td colspan="2" class="text-center">Terjadi kesalahan server</td></tr>'
                    );
                }
            });

            // Save pengembalian
            $('#btn-simpan-pengembalian').on('click', async function() {
                const formData = $('#form-tambah-pengembalian').serializeArray();
                const data = {};
                formData.forEach(item => data[item.name] = item.value);
                data._token = '{{ csrf_token() }}';

                try {
                    await createPengembalian(data);
                    alert('Pengembalian berhasil disimpan!');
                    $('#tambahPengembalianModal').modal('hide');
                    reloadPengembalianTable();
                } catch (err) {
                    console.error(err);
                }
            });
        });
    </script>
@endpush
