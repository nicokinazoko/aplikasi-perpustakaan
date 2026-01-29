@push("js")
    <script>
        // For fetch latest nomor anggota
        async function fetchRekanan() {
            // Define route for fetch rekanan
            const routeFetch = '{{ route("api.anggota.get-anggota-number") }}';

            return new Promise((resolve, reject) => {
                // Fetch data rekanan
                $.ajax({
                    url: routeFetch,
                    type: 'GET',
                    success: function(response) {
                        // Get response status
                        const responseStatus = getValueFromObject(response, 'success', true);

                        // If success
                        if (responseStatus) {
                            // Get data list rekanan
                            const listRekanan = getValueFromObject(response, 'data', []);

                            // Remove existing rekanan first
                            mapListRekananForDropdown.clear();

                            // Loop per each rekanan
                            for (const rekanan of listRekanan) {
                                // Define field map that want to get
                                const fieldMapping = {
                                    'kodeRekanan': 'kodeRekanan',
                                    'namaRekanan': 'namaRekanan',
                                }
                                const {
                                    kodeRekanan,
                                    namaRekanan
                                } = getDataFromObjectUsingMapField(fieldMapping, rekanan);

                                // Set data rekanan for map
                                const dataRekanan = {
                                    kodeRekanan,
                                    namaRekanan
                                };

                                // Set data to map
                                mapListRekananForDropdown.set(kodeRekanan, dataRekanan);
                            }

                            // Convert map list rekanan to array
                            const arrayListRekanan = Array.from(mapListRekananForDropdown
                                .values());

                            // Resolve with array list rekanan
                            return resolve(arrayListRekanan);
                        } else {
                            // Get message from response
                            const message = getValueFromObject(response, 'message',
                                'Ada error ketika memilih rekanan')

                            // Display swal with message
                            Swal.fire('Info!', message, 'info');

                            // Reject the promise with message
                            reject(new Error(message));
                        }
                    },
                    error: function(xhr, status, error) {
                        // Get status code
                        const statusCode = getValueFromObject(xhr, 'status', 500);
                        const response = getValueFromObject(xhr, 'responseJSON', {});
                        const listErrors = getValueFromObject(response, 'errors', {});

                        // Check for each status code
                        if (statusCode === 500) {
                            // Get data message from response
                            const message = getValueFromObject(response, 'message',
                                'Terjadi error server.');

                            Swal.fire('Perhatian!', message, 'warning');
                        } else {
                            const message = getValueFromObject(response, 'message',
                                'Terjadi kesalahan.');

                            Swal.fire('Info!', message, 'info');
                        }
                        reject(xhr);

                    }
                });
            });
        }
        $(document).ready(function() {
            // For init tabel anggota
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


            // For init button modal show
            $('#tambahAnggotaModal').on('shown.bs.modal', function() {
                // reset form tiap modal dibuka
                $('#form-tambah-anggota')[0].reset();

                // auto focus ke nomor anggota
                $('#no_anggota').focus();

                $()

            });



            // For init button simpan anggota
            $('#button-simpan').on('click', async function() {
                // Get data from input
                // const nomo
            })
        });
    </script>
@endpush
