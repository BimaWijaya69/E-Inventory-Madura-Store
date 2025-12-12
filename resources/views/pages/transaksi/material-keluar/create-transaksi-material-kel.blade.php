@extends('layouts.template')

@section('content')
    @include('layouts.breadcrumb')
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3 pt-3">
                            <h5 class="fw-semibold mb-1">Form Pengeluaran Material</h5>
                            <p class="text-muted mb-0">
                                Isi form berikut untuk menambahkan pengeluaran material
                            </p>
                        </div>

                        <hr>
                        <form action="" id="form-pengeluaran" action="{{ route('transaksi') }}" method="POST">
                            @csrf
                            <input type="hidden" value="1" name="jenis">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="exampleInputKode" class="form-label">Kode Transaksi</label>
                                        <input type="text" class="form-control" id="exampleInputKode"
                                            name="kode_transaksi" value="{{ $kode }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="exampleInputKode" class="form-label">Jenis Transaksi</label>
                                        <input type="text" class="form-control" id="exampleInputKode" value="Pengeluaran"
                                            readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="exampleInputTanggal" class="form-label">Tanggal <span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="exampleInputTanggal" name="tanggal">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="exampleInputPengambil" class="form-label">Nama Pengambil <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="exampleInputPengambil"
                                            name="nama_pihak_transaksi">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="exampleInputKeperluan" class="form-label">Keperluan <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select" aria-label="Default select example" name="keperluan">
                                            <option value="">Pilih Keperluan</option>
                                            <option value="YANBUNG">YANBUNG</option>
                                            <option value="P2TL">P2TL</option>
                                            <option value="GANGGUAN">GANGGUAN</option>
                                            <option value="PLN">PLN</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="exampleInputPengambil" class="form-label">Nomor Pelanggan </label>
                                        <input type="text" class="form-control" id="exampleInputPelanggan"
                                            name="nomor_pelanggan">
                                    </div>
                                </div>
                            </div>

                            <!-- Material Section -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="mb-0">Material yang Dikeluarkan</h6>

                                        <button type="button" class="btn btn-primary btn-sm" id="btn-add-material">
                                            <i class="bi bi-plus-lg me-1"></i> Tambah Material
                                        </button>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="table-light">
                                                <tr>
                                                    <th width="40%">Material <span class="text-danger">*</span></th>
                                                    <th width="20%">Jumlah <span class="text-danger">*</span></th>
                                                    <th width="20%">Satuan</th>
                                                    <th width="20%">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="material-rows">
                                                <tr class="material-row">
                                                    <td>
                                                        <select class="form-select material-select" name="materials[0][id]"
                                                            data-index="0" required>
                                                            <option value="">Pilih Material</option>
                                                            @foreach ($materials as $m)
                                                                <option value="{{ $m->id }}"
                                                                    data-satuan="{{ $m->satuan }}"
                                                                    data-stok="{{ $m->stok }}"
                                                                    data-min-stok="{{ $m->min_stok }}">
                                                                    {{ $m->nama_material }} - Stok: {{ $m->stok }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control jumlah-input"
                                                            name="materials[0][jumlah]" min="1" required>
                                                        <small class="text-danger min-stok-warning"
                                                            style="display: none;"></small>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control satuan-display"
                                                            name="materials[0][satuan]" readonly>
                                                    </td>
                                                    <td>
                                                        <button type="button"
                                                            class="btn btn-danger btn-sm btn-remove-row" disabled>
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h6 class="mb-3">Dokumentasi</h6>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Foto SR Sebelum</label>
                                        <div class="upload-area text-center p-4 border border-2 border-dashed rounded"
                                            id="upload-sr-sebelum"
                                            style="cursor: pointer; min-height: 200px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                            <i class="bi bi-camera" style="font-size: 3rem; color: #6c757d;"></i>
                                            <p class="mt-2 mb-0 text-muted">Upload Foto</p>
                                            <small class="text-muted">Opsional</small>
                                        </div>
                                        <input type="file" class="d-none foto-dokumentasi" id="foto-sr-sebelum"
                                            name="foto_sr_sebelum" accept="image/*">
                                        <div class="preview-container-doc mt-2" style="display: none;">
                                            <img src="" class="img-thumbnail foto-preview-doc"
                                                style="width: 100%; max-height: 200px; object-fit: cover;">
                                            <button type="button"
                                                class="btn btn-sm btn-danger mt-2 btn-remove-foto-doc w-100">
                                                <i class="bi bi-trash"></i> Hapus Foto
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Foto SR Setelah</label>
                                        <div class="upload-area text-center p-4 border border-2 border-dashed rounded"
                                            id="upload-sr-setelah"
                                            style="cursor: pointer; min-height: 200px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                            <i class="bi bi-camera" style="font-size: 3rem; color: #6c757d;"></i>
                                            <p class="mt-2 mb-0 text-muted">Upload Foto</p>
                                            <small class="text-muted">Opsional</small>
                                        </div>
                                        <input type="file" class="d-none foto-dokumentasi" id="foto-sr-setelah"
                                            name="foto_sr_setelah" accept="image/*">
                                        <div class="preview-container-doc mt-2" style="display: none;">
                                            <img src="" class="img-thumbnail foto-preview-doc"
                                                style="width: 100%; max-height: 200px; object-fit: cover;">
                                            <button type="button"
                                                class="btn btn-sm btn-danger mt-2 btn-remove-foto-doc w-100">
                                                <i class="bi bi-trash"></i> Hapus Foto
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Bukti Pengeluaran <span
                                                class="text-danger">*</span></label>
                                        <div class="upload-area text-center p-4 border border-2 border-dashed rounded"
                                            id="upload-bukti-pengeluaran"
                                            style="cursor: pointer; min-height: 200px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                            <i class="bi bi-file-earmark-text"
                                                style="font-size: 3rem; color: #6c757d;"></i>
                                            <p class="mt-2 mb-0 text-muted">Upload Foto</p>
                                            <small class="text-muted">Wajib diisi</small>
                                        </div>
                                        <input type="file" class="d-none foto-dokumentasi" id="foto-bukti-pengeluaran"
                                            name="foto_bukti" accept="image/*">
                                        <div class="preview-container-doc mt-2" style="display: none;">
                                            <img src="" class="img-thumbnail foto-preview-doc"
                                                style="width: 100%; max-height: 200px; object-fit: cover;">
                                            <button type="button"
                                                class="btn btn-sm btn-danger mt-2 btn-remove-foto-doc w-100">
                                                <i class="bi bi-trash"></i> Hapus Foto
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-12 text-end">
                                        <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                                            Batal
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            Simpan Pengeluaran
                                        </button>
                                    </div>
                                </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        $(document).ready(function() {
            let rowIndex = 1;

            function updateRemoveButtons() {
                let rowCount = $('.material-row').length;
                if (rowCount === 1) {
                    $('.btn-remove-row').prop('disabled', true);
                } else {
                    $('.btn-remove-row').prop('disabled', false);
                }
            }

            $('#btn-add-material').click(function() {
                let newRow = `
                    <tr class="material-row">
                        <td>
                            <select class="form-select material-select" name="materials[${rowIndex}][id]" data-index="${rowIndex}" required>
                                <option value="">Pilih Material</option>
                                @foreach ($materials as $m)
                                    <option value="{{ $m->id }}" data-satuan="{{ $m->satuan }}" data-stok="{{ $m->stok }}">
                                        {{ $m->nama_material }} - Stok: {{ $m->stok }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="number" class="form-control jumlah-input" name="materials[${rowIndex}][jumlah]" min="1" required>
                            <small class="text-danger min-stok-warning" style="display: none;"></small>
                        </td>
                        <td>
                            <input type="text" class="form-control satuan-display" name="materials[${rowIndex}][satuan]" readonly>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm btn-remove-row">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;

                $('#material-rows').append(newRow);
                rowIndex++;
                updateRemoveButtons();
            });

            $(document).on('click', '.btn-remove-row', function() {
                $(this).closest('.material-row').remove();
                updateRemoveButtons();
            });

            $(document).on('change', '.material-select', function() {
                let selectedOption = $(this).find('option:selected');
                let satuan = selectedOption.data('satuan');
                let stok = selectedOption.data('stok');
                let minStok = selectedOption.data('min-stok');
                let row = $(this).closest('tr');
                let jumlahInput = row.find('.jumlah-input');

                row.find('.satuan-display').val(satuan || '');

                if (stok) {
                    jumlahInput.attr('max', stok);
                    jumlahInput.attr('title', 'Stok tersedia: ' + stok);
                }

                if (minStok) {
                    jumlahInput.attr('data-min-stok', minStok);
                }

                row.find('.min-stok-warning').hide().text('');
                jumlahInput.val('');
            });

            $(document).on('input', '.jumlah-input', function() {
                let row = $(this).closest('tr');
                let max = parseInt($(this).attr('max'));
                let value = parseInt($(this).val());
                let minStok = parseInt($(this).attr('data-min-stok'));
                let stok = max;
                let warning = row.find('.min-stok-warning');

                if (max && value > max) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan',
                        text: 'Jumlah melebihi stok yang tersedia (' + max + ')'
                    });
                    $(this).val(max);
                    value = max;
                }

                if (minStok && value) {
                    let sisaStok = stok - value;

                    if (sisaStok < minStok) {
                        warning.text('Peringatan: Sisa stok ' + sisaStok +
                            ' akan kurang dari minimal stok ' + minStok + '');
                        warning.show();
                        $(this).addClass('border-danger');
                    } else {
                        warning.hide();
                        $(this).removeClass('border-danger');
                    }
                } else {
                    warning.hide();
                    $(this).removeClass('border-danger');
                }
            });

            $('.upload-area').click(function() {
                let targetId = $(this).attr('id');
                let inputId = targetId.replace('upload-', 'foto-');
                $('#' + inputId).click();
            });

            $(document).on('change', '.foto-dokumentasi', function() {
                let file = this.files[0];
                let parent = $(this).parent();
                let uploadArea = parent.find('.upload-area');
                let preview = parent.find('.foto-preview-doc');
                let previewContainer = parent.find('.preview-container-doc');

                if (file) {
                    let maxSize = 2 * 1024 * 1024;

                    if (file.size > maxSize) {
                        Swal.fire({
                            icon: 'error',
                            title: 'File Terlalu Besar',
                            text: 'Ukuran file maksimal 2MB. Ukuran file Anda: ' + (file.size / (
                                1024 * 1024)).toFixed(2) + 'MB'
                        });
                        $(this).val('');
                        return;
                    }

                    if (!file.type.match('image.*')) {
                        Swal.fire({
                            icon: 'error',
                            title: 'File Tidak Valid',
                            text: 'Hanya file gambar yang diperbolehkan (JPG, PNG, GIF, dll)'
                        });
                        $(this).val('');
                        return;
                    }

                    let reader = new FileReader();
                    reader.onload = function(e) {
                        preview.attr('src', e.target.result);
                        uploadArea.hide();
                        previewContainer.show();
                    }
                    reader.readAsDataURL(file);
                }
            });

            $(document).on('click', '.btn-remove-foto-doc', function(e) {
                e.stopPropagation();
                let parent = $(this).closest('.mb-3');
                let uploadArea = parent.find('.upload-area');
                let previewContainer = parent.find('.preview-container-doc');
                let input = parent.find('.foto-dokumentasi');

                input.val('');
                previewContainer.hide();
                parent.find('.foto-preview-doc').attr('src', '');
                uploadArea.show();
            });

            $('#form-pengeluaran').submit(function(e) {
                e.preventDefault();

                let kode_transaksi = $("input[name='kode_transaksi']").val().trim();
                let tanggal = $("input[name='tanggal']").val().trim();
                let nama_pengambil = $("input[name='nama_pihak_transaksi']").val().trim();
                let keperluan = $("select[name='keperluan']").val();
                let foto_bukti = $("input[name='foto_bukti']").val();

                if (kode_transaksi === "") {
                    Swal.fire('Oops!', 'Kode transaksi wajib diisi!', 'error');
                    return false;
                }
                if (tanggal === "") {
                    Swal.fire('Oops!', 'Tanggal wajib diisi!', 'error');
                    return false;
                }
                if (nama_pengambil === "") {
                    Swal.fire('Oops!', 'Nama pengambil wajib diisi!', 'error');
                    return false;
                }
                if (keperluan === "") {
                    Swal.fire('Oops!', 'Keperluan wajib dipilih!', 'error');
                    return false;
                }
                if (!foto_bukti || foto_bukti === "") {
                    Swal.fire('Oops!', 'Upload bukti foto wajib diisi!', 'error');
                    return false;
                }

                let hasMaterial = false;
                $('.material-select').each(function() {
                    if ($(this).val() !== "") {
                        hasMaterial = true;
                        return false;
                    }
                });

                if (!hasMaterial) {
                    Swal.fire('Oops!', 'Minimal satu material harus dipilih!', 'error');
                    return false;
                }

                let isValid = true;
                $('.material-row').each(function() {
                    let materialVal = $(this).find('.material-select').val();
                    let jumlahVal = $(this).find('.jumlah-input').val();

                    if (materialVal !== "" && (jumlahVal === "" || jumlahVal <= 0)) {
                        isValid = false;
                        return false;
                    }
                });

                if (!isValid) {
                    Swal.fire('Oops!', 'Semua material yang dipilih harus memiliki jumlah!', 'error');
                    return false;
                }

                let formData = new FormData(this);

                $.ajax({
                    url: '{{ route('transaksi') }}',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Menyimpan...',
                            text: 'Harap tunggu sebentar',
                            allowOutsideClick: false,
                            didOpen: () => Swal.showLoading()
                        });
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Pengeluaran material berhasil disimpan.'
                            }).then(() => {
                                window.location.href = response.redirect ||
                                    '/pengeluaran-material';
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: response.message ||
                                    'Terjadi kesalahan saat menyimpan data.'
                            });
                        }
                    },
                    error: function(xhr) {
                        let message = 'Terjadi kesalahan pada server.';

                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            message = Object.values(errors)[0][0];
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: message
                        });
                    }
                });
            });
        });
    </script>
@endsection
