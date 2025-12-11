@extends('layouts.template')

@section('content')
    @include('layouts.breadcrumb')
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            Edit Penerimaan Material
                        </h5>

                        {{-- FORM EDIT PENERIMAAN --}}
                        <form id="form-edit-penerimaan" data-url="{{ route('transaksi.update', $t->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="jenis" value="0">
                            <input type="hidden" name="id" value="{{ $t->id }}">

                            {{-- DATA UMUM --}}
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
                                        <input type="text" class="form-control" value="Penerimaan" readonly>
                                    </div>
                                </div>
                            </div>

                            {{-- TANGGAL & PENERIMA --}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tanggal Penerimaan
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" class="form-control" name="tanggal"
                                            value="{{ $t->tanggal }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Penerima
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control" name="nama_pihak_transaksi"
                                            value="{{ $t->nama_pihak_transaksi }}">
                                    </div>
                                </div>
                            </div>

                            {{-- KEPERLUAN --}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Keperluan
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select" name="keperluan">
                                            <option value="">Pilih Keperluan</option>
                                            <option value="YANBUNG" {{ $t->keperluan == 'YANBUNG' ? 'selected' : '' }}>
                                                YANBUNG</option>
                                            <option value="P2TL" {{ $t->keperluan == 'P2TL' ? 'selected' : '' }}>
                                                P2TL</option>
                                            <option value="GANGGUAN" {{ $t->keperluan == 'GANGGUAN' ? 'selected' : '' }}>
                                                GANGGUAN</option>
                                            <option value="PLN" {{ $t->keperluan == 'PLN' ? 'selected' : '' }}>
                                                PLN</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- MATERIAL --}}
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="mb-0">Material yang Diterima</h6>
                                        <button type="button" class="btn btn-success btn-sm" id="btn-add-material">
                                            <i class="bi bi-plus-circle"></i> Tambah Material
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
                                                                style="display:none;"></small>
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

                            {{-- BUKTI PENERIMAAN --}}
                            @php
                                function showPreviewPenerimaan($path)
                                {
                                    return $path && Storage::disk('public')->exists($path);
                                }
                            @endphp

                            <div class="mb-4">
                                <label class="form-label">Dokumentasi Bukti Penerimaan <span
                                        class="text-danger">*</span></label>

                                <div class="border rounded-3 p-4 text-center bg-white" id="upload-bukti-penerimaan"
                                    style="border-style: dashed; min-height: 180px; display:flex; align-items:center; justify-content:center; flex-direction:column; cursor:pointer;">

                                    <img id="preview-foto-penerimaan"
                                        src="{{ isset($t->foto_bukti) && Storage::disk('public')->exists($t->foto_bukti) ? asset('storage/' . $t->foto_bukti) : '' }}"
                                        style="max-width: 200px; {{ isset($t->foto_bukti) && Storage::disk('public')->exists($t->foto_bukti) ? '' : 'display:none;' }} margin-bottom:10px; border-radius:6px;" />

                                    <i class="bi bi-cloud-upload fs-1 mb-2 text-secondary icon-upload-penerimaan"
                                        style="{{ isset($t->foto_bukti) && Storage::disk('public')->exists($t->foto_bukti) ? 'display:none;' : '' }}"></i>

                                    <p class="mb-1 text-muted text-upload-text">
                                        {{ isset($t->foto_bukti) && Storage::disk('public')->exists($t->foto_bukti)
                                            ? 'Klik untuk ganti foto'
                                            : 'Klik untuk upload foto' }}
                                    </p>

                                    <small class="text-muted mb-2">PNG, JPG, JPEG maksimal 5MB</small>
                                </div>

                                <input type="file" name="foto_bukti" id="foto-bukti-penerimaan" class="d-none"
                                    accept=".png,.jpg,.jpeg">

                                <div class="invalid-feedback d-block"></div>
                            </div>




                            {{-- BUTTONS --}}
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

    {{-- SCRIPT EDIT PENERIMAAN --}}
    <script>
        $(document).ready(function() {
            let rowIndex = {{ $t->detail_transaksi->count() }};
            updateRemoveButtons();

            function updateRemoveButtons() {
                let rowCount = $('.material-row').length;
                $('.btn-remove-row').prop('disabled', rowCount === 1);
            }

            // Tambah baris material
            $('#btn-add-material').click(function() {
                let newRow = `
                    <tr class="material-row">
                        <td>
                            <select class="form-select material-select"
                                    name="materials[${rowIndex}][id]"
                                    data-index="${rowIndex}" required>
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
                                   name="materials[${rowIndex}][jumlah]" min="1" required>
                            <small class="text-danger min-stok-warning" style="display:none;"></small>
                        </td>
                        <td>
                            <input type="text" class="form-control satuan-display"
                                   name="materials[${rowIndex}][satuan]" readonly>
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

            // Hapus baris material
            $(document).on('click', '.btn-remove-row', function() {
                $(this).closest('.material-row').remove();
                updateRemoveButtons();
            });

            // Saat pilih material -> set satuan + max stok
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

            // Validasi jumlah vs stok (optional: basic)
            $(document).on('input', '.jumlah-input', function() {
                let max = parseInt($(this).attr('max'));
                let value = parseInt($(this).val());

                if (max && value > max) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan',
                        text: 'Jumlah melebihi stok saat ini (' + max + ')'
                    });
                    $(this).val(max);
                }
            });

            // Upload bukti penerimaan
            $('#upload-bukti-penerimaan').on('click', function() {
                $('#foto-bukti-penerimaan').click();
            });

            $('#foto-bukti-penerimaan').on('change', function(e) {
                const file = e.target.files[0];
                const preview = $('#preview-foto-penerimaan');
                const icon = $('.icon-upload-penerimaan');
                const text = $('.text-upload-text');

                if (!file) {
                    // Kalau user batal pilih file
                    if (preview.attr('src') && preview.attr('src') !== '') {
                        // Masih ada foto lama -> biarkan preview
                        preview.show();
                        icon.hide();
                        text.text('Klik untuk ganti foto');
                    } else {
                        // Tidak ada foto sama sekali
                        preview.hide();
                        icon.show();
                        text.text('Klik untuk upload foto');
                    }
                    return;
                }

                if (!file.type.match('image.*')) {
                    Swal.fire('Oops!', 'Hanya file gambar (PNG/JPG/JPEG) yang diizinkan!', 'error');
                    $(this).val('');
                    if (!preview.attr('src')) {
                        preview.hide();
                        icon.show();
                        text.text('Klik untuk upload foto');
                    }
                    return;
                }

                const maxSize = 5 * 1024 * 1024; // 5MB
                if (file.size > maxSize) {
                    Swal.fire('Oops!', 'Ukuran maksimal 5MB!', 'error');
                    $(this).val('');
                    if (!preview.attr('src')) {
                        preview.hide();
                        icon.show();
                        text.text('Klik untuk upload foto');
                    }
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(ev) {
                    preview.attr('src', ev.target.result).show();
                    icon.hide();
                    text.text('Klik untuk ganti foto');
                };
                reader.readAsDataURL(file);
            });

            // SUBMIT EDIT PENERIMAAN
            $('#form-edit-penerimaan').submit(function(e) {
                e.preventDefault();

                let url = $(this).data('url');
                let tanggal = $("input[name='tanggal']").val().trim();
                let nama_penerima = $("input[name='nama_pihak_transaksi']").val().trim();
                let keperluan = $("select[name='keperluan']").val();
                let uploadArea = $('#upload-bukti-penerimaan');
                let buktiInput = $("input[name='foto_bukti']").val();

                if (tanggal === "") {
                    Swal.fire('Oops!', 'Tanggal penerimaan wajib diisi!', 'error');
                    return false;
                }
                if (nama_penerima === "") {
                    Swal.fire('Oops!', 'Nama penerima wajib diisi!', 'error');
                    return false;
                }
                if (keperluan === "") {
                    Swal.fire('Oops!', 'Keperluan wajib dipilih!', 'error');
                    return false;
                }

                // Kalau belum ada foto sama sekali dan upload-area masih kelihatan -> wajib upload
                if (uploadArea.is(':visible') && (!buktiInput || buktiInput === "")) {
                    Swal.fire('Oops!', 'Upload bukti foto wajib diisi!', 'error');
                    return false;
                }

                // Minimal satu material
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

                // Semua material yang dipilih harus punya jumlah
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
                                text: 'Data penerimaan berhasil diperbarui'
                            }).then(() => {
                                window.location.href = response.redirect;
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.message ||
                                    'Terjadi kesalahan saat menyimpan data.'
                            });
                        }
                    },
                    error: function(xhr) {
                        let message = "Terjadi kesalahan";
                        if (xhr.responseJSON && xhr.responseJSON.message) {
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
