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

                <form id="form-penerimaan" action="{{ route('transaksi') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" value="0" name="jenis">
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="mb-3">
                                <label for="exampleInputKode" class="form-label">Kode Transaksi</label>
                                <input type="text" class="form-control" id="exampleInputKode" name="kode_transaksi"
                                    value="{{ $kode }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exampleInputKode" class="form-label">Jenis Transaksi</label>
                                <input type="text" class="form-control" id="exampleInputKode" value="Penerimaan"
                                    readonly>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="exampleInputTanggal" class="form-label">Tanggal Penerimaan<span
                                    class="text-danger">*</span></label>
                            <input type="date" name="tanggal" class="form-control" id="exampleInputTanggal">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="col-md-6">
                            <label for="exampleInputPenerima" class="form-label">Nama Penerima<span
                                    class="text-danger">*</span></label>
                            <input type="text" name="nama_pihak_transaksi" class="form-control"
                                placeholder="Masukkan nama penerima" id="exampleInputPenerima">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    {{-- KEPERLUAN --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="exampleInputKeperluan" class="form-label">Keperluan <span
                                    class="text-danger">*</span></label>
                            <select name="keperluan" class="form-select" aria-label="Default select example">
                                <option value="">Pilih Keperluan</option>
                                <option value="YANBUNG">YANBUNG</option>
                                <option value="P2TL">P2TL</option>
                                <option value="GANGGUAN">GANGGUAN</option>
                                <option value="PLN">PLN</option>
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

                    <div class="mb-4">
                        <div class="p-3 rounded border bg-light">
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
                                                <small class="text-danger min-stok-warning" style="display: none;"></small>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control satuan-display"
                                                    name="materials[0][satuan]" readonly>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm btn-remove-row"
                                                    disabled>
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                    <div class="mb-4">
                        <label class="form-label">Dokumentasi Bukti Penerimaan <span class="text-danger">*</span></label>


                        <div class="upload-area border rounded-3 p-4 text-center bg-white" id="upload-bukti-penerimaan"
                            style="border-style: dashed; min-height: 180px; display:flex; align-items:center; justify-content:center; flex-direction:column; cursor:pointer;">


                            <img id="preview-foto-penerimaan" class="foto-preview-doc" src=""
                                style="max-width: 220px; display:none; margin-bottom:10px; border-radius:6px; object-fit:cover;" />


                            <i class="bi bi-cloud-upload fs-1 mb-2 text-secondary icon-upload-penerimaan"></i>
                            <p class="mb-1 text-muted text-upload-text">Klik untuk upload foto</p>
                            <small class="text-muted mb-2">PNG, JPG, JPEG maksimal 5MB</small>
                        </div>


                        <input type="file" name="foto_bukti" id="foto-bukti-penerimaan"
                            class="d-none foto-dokumentasi" accept=".png,.jpg,.jpeg">

                        <div class="invalid-feedback d-block"></div>
                    </div>




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

            $('#upload-bukti-penerimaan').on('click', function() {
                $('#foto-bukti-penerimaan').click();
            });

            $('#foto-bukti-penerimaan').on('change', function(e) {
                const file = e.target.files[0];
                const preview = $('#preview-foto-penerimaan');
                const icon = $('.icon-upload-penerimaan');
                const text = $('.text-upload-text');

                if (!file) {
                    preview.hide();
                    icon.show();
                    text.text('Klik untuk upload foto');
                    return;
                }

                if (!file.type.match('image.*')) {
                    Swal.fire('Oops!', 'Hanya file gambar (PNG/JPG/JPEG) yang diizinkan!', 'error');
                    $(this).val('');
                    preview.hide();
                    icon.show();
                    text.text('Klik untuk upload foto');
                    return;
                }

                const maxSize = 5 * 1024 * 1024; // 5MB
                if (file.size > maxSize) {
                    Swal.fire('Oops!', 'Ukuran maksimal 5MB!', 'error');
                    $(this).val('');
                    preview.hide();
                    icon.show();
                    text.text('Klik untuk upload foto');
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

            // =======================
            //  TAMBAH / HAPUS ROW MATERIAL
            // =======================
            let rowIndex = 1;

            function updateRemoveButtons() {
                const count = $(".material-row").length;
                $(".btn-remove-row").prop("disabled", count === 1);
            }

            $("#btn-add-material").on("click", function() {
                const newRow = `
            <tr class="material-row">
                <td>
                    <select class="form-select material-select" name="materials[${rowIndex}][id]" data-index="${rowIndex}" required>
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
                    <input type="text" class="form-control satuan-display" readonly>
                </td>

                <td>
                    <button type="button" class="btn btn-danger btn-sm btn-remove-row">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>
        `;

                $("#material-rows").append(newRow);
                rowIndex++;
                updateRemoveButtons();
            });

            $(document).on("click", ".btn-remove-row", function() {
                $(this).closest(".material-row").remove();
                updateRemoveButtons();
            });


            $(document).on("change", ".material-select", function() {
                const opt = $(this).find("option:selected");
                const satuan = opt.data("satuan");
                const stok = opt.data("stok");
                const minStok = opt.data("min-stok");

                const row = $(this).closest("tr");

                row.find(".satuan-display").val(satuan || "");
                const jumlah = row.find(".jumlah-input");

                jumlah.attr("max", stok).attr("data-min-stok", minStok);
                jumlah.val("");

                row.find(".min-stok-warning").hide().text("");
            });

            // =======================
            //  VALIDASI JUMLAH MATERIAL
            // =======================
            $(document).on("input", ".jumlah-input", function() {
                const jumlah = parseInt($(this).val());
                const max = parseInt($(this).attr("max"));
                const minStok = parseInt($(this).attr("data-min-stok"));

                const row = $(this).closest("tr");
                const warning = row.find(".min-stok-warning");

                if (max && jumlah > max) {
                    Swal.fire("Peringatan", "Jumlah melebihi stok tersedia (" + max + ")", "warning");
                    $(this).val(max);
                }

                if (minStok && jumlah) {
                    const sisa = max - jumlah;
                    if (sisa < minStok) {
                        warning
                            .text("Sisa stok " + sisa + " lebih kecil dari minimal stok " + minStok)
                            .show();
                        $(this).addClass("border-danger");
                    } else {
                        warning.hide();
                        $(this).removeClass("border-danger");
                    }
                }
            });

            // =======================
            //  SUBMIT FORM VIA AJAX
            // =======================
            $("#form-penerimaan").on("submit", function(e) {
                e.preventDefault();

                const tanggal = $("input[name='tanggal']").val().trim();
                const nama = $("input[name='nama_pihak_transaksi']").val().trim();
                const keperluan = $("select[name='keperluan']").val();
                const foto = $("#foto_bukti").val();

                // FRONT VALIDATION
                if (!tanggal) return Swal.fire("Oops!", "Tanggal wajib diisi!", "error");
                if (!nama) return Swal.fire("Oops!", "Nama penerima wajib diisi!", "error");
                if (!keperluan) return Swal.fire("Oops!", "Keperluan wajib dipilih!", "error");
                if (!foto) return Swal.fire("Oops!", "Upload bukti foto wajib!", "error");

                let hasMaterial = false;
                $(".material-select").each(function() {
                    if ($(this).val()) hasMaterial = true;
                });
                if (!hasMaterial) return Swal.fire("Oops!", "Minimal satu material harus dipilih!",
                    "error");

                const formData = new FormData(this);

                $.ajax({
                    url: $(this).attr("action"),
                    method: $(this).attr("method"),
                    data: formData,
                    processData: false,
                    contentType: false,

                    beforeSend() {
                        Swal.fire({
                            title: "Menyimpan...",
                            text: "Mohon tunggu sebentar",
                            allowOutsideClick: false,
                            didOpen: () => Swal.showLoading()
                        });
                    },

                    success(response) {
                        Swal.fire("Berhasil!", "Penerimaan material disimpan!", "success")
                            .then(() => {
                                window.location.href = response.redirect ||
                                    "{{ route('material-masuks') }}";
                            });
                    },

                    error(xhr) {
                        let msg = "Terjadi kesalahan.";

                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            msg = Object.values(errors)[0][0];
                        }

                        Swal.fire("Error", msg, "error");
                    }
                });
            });

        });
    </script>
@endsection
