@extends('layouts.template')

@section('content')
    @include('layouts.breadcrumb')
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            Edit Pengeluaran Material
                        </h5>

                        <form id="form-edit-pengeluaran" data-url="{{ route('transaksi.update', $t->id) }}">
                            @csrf
                            @method('PUT')

                            <input type="hidden" value="1" name="jenis">
                            <input type="hidden" value="{{ $t->id }}" name="id">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Kode Transaksi</label>
                                        <input type="text" class="form-control" name="kode_transaksi"
                                            value="{{ $t->kode_transaksi }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Jenis Transaksi</label>
                                        <input type="text" class="form-control" value="Pengeluaran" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="tanggal"
                                            value="{{ $t->tanggal }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Pengambil <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="nama_pihak_transaksi"
                                            value="{{ $t->nama_pihak_transaksi }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Keperluan <span class="text-danger">*</span></label>
                                        <select class="form-select" name="keperluan">
                                            <option value="">Pilih Keperluan</option>
                                            <option value="YANBUNG" {{ $t->keperluan == 'YANBUNG' ? 'selected' : '' }}>
                                                YANBUNG</option>
                                            <option value="P2TL" {{ $t->keperluan == 'P2TL' ? 'selected' : '' }}>P2TL
                                            </option>
                                            <option value="GANGGUAN" {{ $t->keperluan == 'GANGGUAN' ? 'selected' : '' }}>
                                                GANGGUAN</option>
                                            <option value="PLN" {{ $t->keperluan == 'PLN' ? 'selected' : '' }}>PLN
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nomor Pelanggan</label>
                                        <input type="text" class="form-control" name="nomor_pelanggan"
                                            value="{{ $t->nomor_pelanggan }}">
                                    </div>
                                </div>
                            </div>

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
                                                @foreach ($t->detail_transaksi as $index => $detail)
                                                    <tr class="material-row">
                                                        <td>
                                                            <select class="form-select material-select"
                                                                name="materials[{{ $index }}][id]"
                                                                data-index="{{ $index }}" required>
                                                                <option value="">Pilih Material</option>
                                                                @foreach ($materials as $m)
                                                                    <option value="{{ $m->id }}"
                                                                        data-satuan="{{ $m->satuan }}"
                                                                        data-stok="{{ $m->stok }}"
                                                                        data-min-stok="{{ $m->min_stok }}"
                                                                        {{ $detail->material_id == $m->id ? 'selected' : '' }}>
                                                                        {{ $m->nama_material }} - Stok Saat Ini:
                                                                        {{ $m->stok }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="number" class="form-control jumlah-input"
                                                                name="materials[{{ $index }}][jumlah]"
                                                                min="1" value="{{ $detail->jumlah }}" required>
                                                            <small class="text-danger min-stok-warning"
                                                                style="display: none;"></small>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control satuan-display"
                                                                name="materials[{{ $index }}][satuan]"
                                                                value="{{ $detail->material->satuan ?? '' }}" readonly>
                                                        </td>
                                                        <td>
                                                            <button type="button"
                                                                class="btn btn-danger btn-sm btn-remove-row">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-12">
                                    <h6 class="mb-3">Dokumentasi</h6>
                                </div>

                                @php
                                    function showPreview($path)
                                    {
                                        return $path && Storage::disk('public')->exists($path);
                                    }
                                @endphp

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Foto SR Sebelum</label>
                                        <div class="upload-area text-center p-4 border border-2 border-dashed rounded"
                                            id="upload-sr-sebelum"
                                            style="{{ showPreview($t->foto_sr_sebelum) ? 'display: none;' : '' }} cursor: pointer; min-height: 200px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                            <i class="bi bi-camera" style="font-size: 3rem; color: #6c757d;"></i>
                                            <p class="mt-2 mb-0 text-muted">Klik untuk Ganti Foto</p>
                                        </div>

                                        <input type="file" class="d-none foto-dokumentasi" id="foto-sr-sebelum"
                                            name="foto_sr_sebelum" accept="image/*">

                                        <div class="preview-container-doc mt-2"
                                            style="{{ showPreview($t->foto_sr_sebelum) ? 'display: block;' : 'display: none;' }}">
                                            <img src="{{ showPreview($t->foto_sr_sebelum) ? asset('storage/' . $t->foto_sr_sebelum) : '' }}"
                                                class="img-thumbnail foto-preview-doc"
                                                style="width: 100%; max-height: 200px; object-fit: cover;">
                                            <button type="button"
                                                class="btn btn-sm btn-danger mt-2 btn-remove-foto-doc w-100">
                                                <i class="bi bi-trash"></i> Hapus / Ganti Foto
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Foto SR Setelah</label>
                                        <div class="upload-area text-center p-4 border border-2 border-dashed rounded"
                                            id="upload-sr-setelah"
                                            style="{{ showPreview($t->foto_sr_sesudah) ? 'display: none;' : '' }} cursor: pointer; min-height: 200px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                            <i class="bi bi-camera" style="font-size: 3rem; color: #6c757d;"></i>
                                            <p class="mt-2 mb-0 text-muted">Klik untuk Ganti Foto</p>
                                        </div>
                                        <input type="file" class="d-none foto-dokumentasi" id="foto-sr-setelah"
                                            name="foto_sr_sesudah" accept="image/*">

                                        <div class="preview-container-doc mt-2"
                                            style="{{ showPreview($t->foto_sr_sesudah) ? 'display: block;' : 'display: none;' }}">
                                            <img src="{{ showPreview($t->foto_sr_sesudah) ? asset('storage/' . $t->foto_sr_sesudah) : '' }}"
                                                class="img-thumbnail foto-preview-doc"
                                                style="width: 100%; max-height: 200px; object-fit: cover;">
                                            <button type="button"
                                                class="btn btn-sm btn-danger mt-2 btn-remove-foto-doc w-100">
                                                <i class="bi bi-trash"></i> Hapus / Ganti Foto
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
                                            style="{{ showPreview($t->foto_bukti) ? 'display: none;' : 'display: flex;' }} cursor: pointer; min-height: 200px; flex-direction: column; align-items: center; justify-content: center;">

                                            <i class="bi bi-file-earmark-text"
                                                style="font-size: 3rem; color: #6c757d;"></i>
                                            <p class="mt-2 mb-0 text-muted">Klik untuk Ganti Foto</p>
                                        </div>
                                        <input type="file" class="d-none foto-dokumentasi" id="foto-bukti-pengeluaran"
                                            name="foto_bukti" accept="image/*">

                                        <div class="preview-container-doc mt-2"
                                            style="{{ showPreview($t->foto_bukti) ? 'display: block;' : 'display: none;' }}">
                                            <img src="{{ showPreview($t->foto_bukti) ? asset('storage/' . $t->foto_bukti) : '' }}"
                                                class="img-thumbnail foto-preview-doc"
                                                style="width: 100%; max-height: 200px; object-fit: cover;">
                                            <button type="button"
                                                class="btn btn-sm btn-danger mt-2 btn-remove-foto-doc w-100">
                                                <i class="bi bi-trash"></i> Hapus / Ganti Foto
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-12 text-end">
                                    <button type="button" class="btn btn-secondary"
                                        onclick="window.history.back()">Batal</button>
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
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
            let rowIndex = {{ $t->detail_transaksi->count() }};
            updateRemoveButtons();

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
                                    <option value="{{ $m->id }}" data-satuan="{{ $m->satuan }}" data-stok="{{ $m->stok }}" data-min-stok="{{ $m->min_stok }}">
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
                if (stok !== undefined) {
                    jumlahInput.attr('max', stok);
                    jumlahInput.attr('title', 'Stok tersedia saat ini: ' + stok);
                }

                if (minStok) {
                    jumlahInput.attr('data-min-stok', minStok);
                }

                row.find('.min-stok-warning').hide().text('');
            });

            $(document).on('input', '.jumlah-input', function() {
                let row = $(this).closest('tr');
                let max = parseInt($(this).attr('max'));
                let value = parseInt($(this).val());
                let minStok = parseInt($(this).attr('data-min-stok'));
                let warning = row.find('.min-stok-warning');
                if (max && value > max) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan',
                        text: 'Jumlah melebihi stok saat ini (' + max + ')'
                    });
                    $(this).val(max);
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

            $('#form-edit-pengeluaran').submit(function(e) {
                e.preventDefault();

                let url = $(this).data('url');
                let nama_pengambil = $("input[name='nama_pihak_transaksi']").val().trim();
                let keperluan = $("select[name='keperluan']").val();
                let uploadArea = $('#upload-bukti-pengeluaran');
                let buktiInput = $("input[name='foto_bukti']").val();

                if (uploadArea.is(':visible') && (!buktiInput || buktiInput === "")) {
                    Swal.fire('Oops!', 'Upload bukti foto wajib diisi!', 'error');
                    return false;
                }

                let formData = new FormData(this);
                formData.append('_method', 'PUT');

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Mengupdate...',
                            allowOutsideClick: false,
                            didOpen: () => Swal.showLoading()
                        });
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: 'Data berhasil diperbarui'
                                })
                                .then(() => window.location.href = response.redirect);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr) {
                        let message = "Terjadi kesalahan";
                        if (xhr.responseJSON && xhr.responseJSON.message) message = xhr
                            .responseJSON.message;
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
