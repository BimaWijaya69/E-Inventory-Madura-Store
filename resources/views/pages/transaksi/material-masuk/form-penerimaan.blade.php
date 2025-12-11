@extends('layouts.template')

@section('content')
    @include('layouts.breadcrumb')
    <section class="section">
        <div class="card shadow-sm">
            <div class="card-body">

                {{-- HEADER FORM --}}
                <div class="mb-3 pt-3">
                    <h5 class="fw-semibold mb-1">Form Penerimaan Material</h5>
                    <p class="text-muted mb-0">
                        Isi form berikut untuk menambahkan penerimaan material
                    </p>
                </div>

                <hr>

                <form id="form-penerimaan" action="#" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- TANGGAL + NAMA PENERIMA --}}
                    <div class="row mb-3">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label class="form-label">Tanggal Penerimaan <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" class="form-control">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="col-md-8">
                            <label class="form-label">Nama Penerima <span class="text-danger">*</span></label>
                            <input type="text" name="nama_penerima" class="form-control"
                                placeholder="Masukkan nama penerima/supplier">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    {{-- KEPERLUAN --}}
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Keperluan <span class="text-danger">*</span></label>
                            <select name="keperluan" class="form-select">
                                <option value="">Pilih Keperluan</option>
                                <option value="pemeliharaan">Pemeliharaan</option>
                                <option value="gangguan">Gangguan</option>
                                <option value="pengembangan">Pengembangan Jaringan</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    {{-- TITLE + BUTTON MATERIAL --}}
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">Material yang Diterima</h6>
                        <button type="button" class="btn btn-sm btn-primary" id="btn-add-material">
                            <i class="bi bi-plus-lg me-1"></i> Tambah Material
                        </button>
                    </div>

                    {{-- WRAPPER MATERIAL (KAYAK CARD DI DALAM) --}}
                    <div class="mb-4">
                        <div class="p-3 rounded border bg-light" id="material-list">

                            {{-- ROW MATERIAL 1 --}}
                            <div class="row g-3 align-items-end mb-3 material-item">
                                <div class="col-md-6">
                                    <label class="form-label">Material <span class="text-danger">*</span></label>
                                    <select name="materials[0][material_id]" class="form-select">
                                        <option value="">Pilih Material</option>
                                        {{-- nanti diisi dari DB --}}
                                        <option value="1">MAT-001 - Testing Material</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                                    <input type="number" name="materials[0][jumlah]" class="form-control" value="1"
                                        min="1">
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label">Satuan</label>
                                    <input type="text" class="form-control" value="PCS" disabled>
                                </div>

                                <div class="col-md-2 d-grid">
                                    <button type="button" class="btn btn-danger btn-remove-material">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>


                    <div class="mb-4">
                        <label class="form-label">Dokumentasi Bukti Penerimaan <span class="text-danger">*</span></label>

                        <div class="border rounded-3 p-4 text-center bg-white"
                            style="border-style: dashed; min-height: 180px; display:flex; align-items:center; justify-content:center; flex-direction:column;"
                            id="upload-wrapper">

                            <img id="preview-foto" src=""
                                style="max-width: 200px; display:none; margin-bottom:10px; border-radius:6px;" />

                            <i class="bi bi-cloud-upload fs-1 mb-2 text-secondary" id="upload-icon"></i>

                            <p class="mb-1">Klik untuk upload foto</p>
                            <small class="text-muted mb-2">PNG, JPG, JPEG maksimal 5MB</small>

                            <input type="file" name="foto_bukti" id="foto_bukti" class="form-control w-auto mt-2"
                                accept=".png,.jpg,.jpeg">

                            <div class="invalid-feedback d-block"></div>
                        </div>
                    </div>


                    {{-- BUTTONS --}}
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('material-masuks') }}" class="btn btn-outline-secondary">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary" id="btn-submit">
                            <i class="bi bi-save me-1"></i> Simpan Penerimaan
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </section>



    <script>
        $(document).ready(function() {
            $('#foto_bukti').on('change', function(e) {
                const file = e.target.files[0];

                if (!file) {
                    $('#preview-foto').hide();
                    $('#upload-icon').show();
                    return;
                }

                if (!file.type.match('image.*')) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "File bukti harus berupa gambar (PNG/JPG/JPEG)!"
                    });
                    $(this).val('');
                    $('#preview-foto').hide();
                    $('#upload-icon').show();
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#preview-foto').attr('src', e.target.result).show();
                    $('#upload-icon').hide();
                };
                reader.readAsDataURL(file);
            });

            $('#btn-add-material').on('click', function() {
                const $list = $('#material-list');
                const $items = $list.find('.material-item');
                const index = $items.length;

                const $clone = $items.first().clone();

                // reset value
                $clone.find('select').val('');
                $clone.find('input[type="number"]').val(1);

                // reset error
                $clone.find('.is-invalid').removeClass('is-invalid');
                $clone.find('.invalid-feedback').text('');

                // ganti index [0] -> [index]
                $clone.find('select, input').each(function() {
                    let name = $(this).attr('name');
                    if (!name) return;
                    name = name.replace(/\[\d+\]/, '[' + index + ']');
                    $(this).attr('name', name);
                });

                $list.append($clone);
            });

            $('#material-list').on('click', '.btn-remove-material', function() {
                const $items = $('#material-list .material-item');
                if ($items.length <= 1) return; // minimal 1
                $(this).closest('.material-item').remove();
            });

            function resetErrors() {
                $('#form-penerimaan').find('.is-invalid').removeClass('is-invalid');
                $('#form-penerimaan').find('.invalid-feedback').text('');
            }

            function validateFrontEnd() {
                let isValid = true;

                const tanggal = $('input[name="tanggal"]').val().trim();
                const namaPenerima = $('input[name="nama_penerima"]').val().trim();
                const keperluan = $('select[name="keperluan"]').val();
                const fileBukti = $('#foto_bukti')[0].files[0];

                // tanggal
                if (tanggal === '') {
                    $('input[name="tanggal"]').addClass('is-invalid');
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Tanggal penerimaan harus diisi!"
                    });
                    return false;
                }

                // nama penerima
                if (namaPenerima === '') {
                    $('input[name="nama_penerima"]').addClass('is-invalid');
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Nama penerima harus diisi!"
                    });
                    return false;
                }

                // keperluan
                if (!keperluan) {
                    $('select[name="keperluan"]').addClass('is-invalid');
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Keperluan harus dipilih!"
                    });
                    return false;
                }

                // minimal 1 material valid
                let hasValidMaterial = false;
                $('#material-list .material-item').each(function() {
                    const materialId = $(this).find('select[name^="materials"]').val();
                    const jumlah = $(this).find('input[name^="materials"][type="number"]').val();

                    if (!materialId || materialId === '') {
                        $(this).find('select[name^="materials"]').addClass('is-invalid');
                    } else if (!jumlah || jumlah <= 0) {
                        $(this).find('input[name^="materials"][type="number"]').addClass('is-invalid');
                    } else {
                        hasValidMaterial = true;
                    }
                });

                if (!hasValidMaterial) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Minimal 1 material dengan jumlah valid harus diisi!"
                    });
                    return false;
                }

                // bukti foto wajib (kalau requirement begitu)
                if (!fileBukti) {
                    $('#foto_bukti').addClass('is-invalid');
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Dokumentasi bukti penerimaan wajib diupload!"
                    });
                    return false;
                }

                return isValid;
            }


            $('#form-penerimaan').on('submit', function(e) {
                e.preventDefault();

                resetErrors();

                // cek dulu FE validation
                if (!validateFrontEnd()) {
                    return;
                }

                const $form = $(this);
                const formData = new FormData(this);

                const $btn = $('#btn-submit');
                const oldHtml = $btn.html();

                $btn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...');

                $.ajax({
                    url: $form.attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,

                    success: function(res) {
                        Swal.fire({
                            title: "Berhasil",
                            text: "Penerimaan material berhasil disimpan.",
                            icon: "success",
                            confirmButtonColor: "#18a342"
                        }).then(() => {
                            // kalau BE nanti kirim redirect, pakai itu
                            if (res.redirect) {
                                window.location.href = res.redirect;
                            } else {
                                window.location.href = '{{ route('material-masuks') }}';
                            }
                        });
                    },

                    error: function(xhr) {
                        // error validasi Laravel (422)
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            const errors = xhr.responseJSON.errors;

                            $.each(errors, function(field, messages) {
                                let name = field
                                    .replace(/\.(\d+)\./g, '[$1][')
                                    .replace(/\.(\w+)$/g, '][$1]');

                                const $input = $('[name="' + name + '"]', $form);

                                if ($input.length) {
                                    $input.addClass('is-invalid');

                                    let $feedback = $input.siblings(
                                        '.invalid-feedback');
                                    if (!$feedback.length) {
                                        $feedback = $input.parent().find(
                                            '.invalid-feedback').first();
                                    }
                                    $feedback.text(messages[0]);
                                }
                            });

                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "Beberapa input belum valid. Silakan cek kembali form Anda."
                            });
                        } else {
                            console.error(xhr);
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "Terjadi kesalahan pada server."
                            });
                        }
                    },

                    complete: function() {
                        $btn.prop('disabled', false).html(oldHtml);
                    }
                });
            });

        });
    </script>
@endsection
