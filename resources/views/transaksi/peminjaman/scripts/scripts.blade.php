@push("js")
    <script>
        function reloadPeminjamanTable() {
            $('#table-peminjaman').DataTable().ajax.reload(null, false); // false = stay on current page
        }

        // For delete peminjaman
        async function deletePeminjaman(idPeminjaman) {
            const confirmed = confirm('Apakah anda yakin ingin menghapus buku ini?');

            console.log(confirmed);
            if (!confirmed) return;

            const route = '{{ route("api.peminjaman.destroy", ":id") }}'.replace(':id', idPeminjaman);

            try {
                const response = await $.ajax({
                    url: route,
                    type: 'DELETE',
                });

                const success = response && response.success === true;

                if (!success) {
                    const message = (response && response.message) || 'Ada error ketika hapus peminjaman';
                    alert(message);
                    throw new Error(message);
                }

                const message = (response && response.message) || 'Hapus peminjaman berhasil';
                alert(message);

                // Reload table
                reloadPeminjamanTable();

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
            let detailIndex = 1;

            // Initialize DataTable
            const table = $('#table-peminjaman').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route("api.peminjaman.index") }}',
                    type: 'POST',
                    data: function(d) {
                        d._token = '{{ csrf_token() }}';
                    }
                },
                columns: [{
                        data: 'tanggal_pinjam'
                    },
                    {
                        data: 'nama_anggota'
                    },
                    {
                        data: 'total_buku'
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                        <button class="btn btn-sm btn-danger" onclick="deletePeminjaman('${row.id}')">Hapus</button>
                    `;
                        }
                    }
                ]
            });

            // Add new detail row
            $('#btn-add-detail').on('click', function() {
                const newDetail = $('.peminjaman-detail-item').first().clone();
                newDetail.find('select, input').each(function() {
                    const name = $(this).attr('name').replace(/\d+/, detailIndex);
                    $(this).attr('name', name).val('');
                });
                $('#peminjaman-detail-wrapper').append(newDetail);
                detailIndex++;
            });

            // Remove detail row
            $(document).on('click', '.btn-remove-detail', function() {
                if ($('.peminjaman-detail-item').length > 1) {
                    $(this).closest('.peminjaman-detail-item').remove();
                }
            });

            // Save peminjaman
            $('#button-simpan').on('click', async function() {
                const formData = $('#form-tambah-peminjaman').serializeArray();
                const data = {};
                formData.forEach(item => data[item.name] = item.value);
                data._token = '{{ csrf_token() }}';

                try {
                    const response = await $.ajax({
                        url: '{{ route("api.peminjaman.store") }}',
                        type: 'POST',
                        data: $('#form-tambah-peminjaman').serialize()
                    });

                    if (response.success) {
                        alert('Peminjaman berhasil dibuat!');
                        $('#tambahPeminjamanModal').modal('hide');
                        reloadPeminjamanTable();
                    } else {
                        alert(response.message || 'Gagal membuat peminjaman');
                    }
                } catch (err) {
                    alert(err.responseJSON?.message || 'Terjadi kesalahan server');
                }
            });
        });
    </script>
@endpush
