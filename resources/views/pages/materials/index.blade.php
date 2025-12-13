@extends('layouts.template')

@section('content')
    @include('layouts.breadcrumb')
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Data Material</h5>

                            <div class="d-flex gap-2">
                                <button class="btn btn-small btn-outline-primary" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal">Tambah Material</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Table with stripped rows -->
                        <div class="table-responsive">
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>
                                            Kode Material
                                        </th>
                                        <th>Nama Material</th>
                                        <th>Satuan</th>
                                        <th>Stok Awal</th>
                                        <th>Min Stok</th>
                                        <th>Stok</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($materials as $index => $material)
                                        @if ($material->delet_at == '0')
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $material->kode_material }}</td>
                                                <td>{{ $material->nama_material }}</td>
                                                <td>{{ $material->satuan }}</td>
                                                <td>{{ $material->stok_awal }}</td>
                                                <td>{{ $material->min_stok }}</td>
                                                <td
                                                    @if ($material->stok < $material->min_stok) class= "text-danger" @else class= "text-info hover:cursor-pointer" @endif>
                                                    {{ $material->stok }}</td>
                                                <td>
                                                    <div class="d-flex gap-2 justify-content-center">
                                                        <button type="button" class="btn btn-info btn-sm btn-edit-material"
                                                            data-id="{{ $material->id }}">
                                                            <i class="bi bi-pencil"></i>
                                                        </button>

                                                        <button type="button" class="btn btn-danger btn-sm btn-decline"
                                                            data-id="{{ $material->id }}">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @empty
                                        <tr>
                                            <td colspan="7">
                                                <center>Tidak ada material</center>
                                            </td>
                                        </tr>
                                    @endforelse

                                </tbody>
                            </table>
                        </div>
                        <!-- End Table with stripped rows -->

                    </div>
                </div>

            </div>
        </div>

    </section>
    {{-- modal create --}}
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Material</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('material-post') }}" id="form-material">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Kode Material</label>
                            <input type="text" class="form-control" id="exampleFormControlInput1" name="kode_material"
                                placeholder="" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Nama Material <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="exampleFormControlInput1" name="nama_material"
                                placeholder="Masukan nama material">
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Satuan <span
                                    class="text-danger"">*</span></label>
                            <select class="form-select" aria-label="Default select example" name="satuan">
                                <option value="">Pilih Satuan</option>
                                <option value="PCS (Buah)">PCS (Buah)</option>
                                <option value="KG (Kilogram)">KG (Kilogram)</option>
                                <option value="M (Meter)">M (Meter)</option>
                                <option value="ROLL (Gulung)">ROLL (Gulung)</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Stok Awal <span
                                            class="text-danger"">*</span></label>
                                    <input type="number" class="form-control" id="exampleFormControlInput1"
                                        name="stok_awal" placeholder="Masukan stok awal">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Minimal Stok <span
                                            class="text-danger"">*</span></label>
                                    <input type="number" class="form-control" id="exampleFormControlInput1" name="min_stok"
                                        placeholder="Masukan minimal stok">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- end --}}
    {{-- modal edit --}}
    <div class="modal fade" id="modalEditMaterial" tabindex="-1" aria-labelledby="modalEditMaterialLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditMaterialLabel">Edit Material</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="form-edit-material">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="mb-3">
                            <label class="form-label">Kode Material</label>
                            <input type="text" class="form-control" name="e_kode_material" id="edit_kode_material"
                                readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Material <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="e_nama_material" id="edit_nama_material">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Satuan <span class="text-danger">*</span></label>
                            <select class="form-select" name="e_satuan" id="edit_satuan">
                                <option value="">Pilih Satuan</option>
                                <option value="PCS (Buah)">PCS (Buah)</option>
                                <option value="KG (Kilogram)">KG (Kilogram)</option>
                                <option value="M (Meter)">M (Meter)</option>
                                <option value="ROLL (Gulung)">ROLL (Gulung)</option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Stok Awal <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="e_stok_awal" id="edit_stok_awal">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Minimal Stok <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="e_min_stok" id="edit_min_stok">
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    {{-- end --}}
    <script>
        $(document).ready(function() {
            $('#exampleModal').on('shown.bs.modal', function() {
                $.ajax({
                    url: "/material/generate-kode",
                    method: "GET",
                    success: function(res) {
                        $("input[name='kode_material']").val(res.kode);
                    }
                });
            });

            $("#form-material").submit(function(e) {
                e.preventDefault();
                let form = $(this);

                let kode_material = $("input[name='kode_material']").val().trim();
                let nama_material = $("input[name='nama_material']").val().trim();
                let satuan = $("select[name='satuan']").val();
                let stok_awal = $("input[name='stok_awal']").val().trim();
                let min_stok = $("input[name='min_stok']").val().trim();

                if (nama_material === "") {
                    Swal.fire('Oops!', 'Nama material wajib diisi!', 'error');
                    return false;
                }
                if (satuan === "") {
                    Swal.fire('Oops!', 'Satuan wajib dipilih!', 'error');
                    return false;
                }
                if (stok_awal === "") {
                    Swal.fire('Oops!', 'Stok awal wajib diisi!', 'error');
                    return false;
                }
                if (min_stok === "") {
                    Swal.fire('Oops!', 'Minimal stok wajib diisi!', 'error');
                    return false;
                }

                $.ajax({
                    url: form.attr("action"),
                    method: "POST",
                    data: form.serialize(),
                    dataType: "json",

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
                                text: 'Material berhasil ditambahkan.'
                            }).then(() => {
                                $("#exampleModal").modal("hide");
                                form[0].reset();
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Terjadi kesalahan saat menyimpan data.'
                            });
                        }
                    },

                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            if (errors.nama_material) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Nama Material Error',
                                    text: errors.nama_material[0]
                                });
                            }
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Terjadi kesalahan pada server.'
                            });
                        }
                    }
                });

            });

            $(document).on("click", ".btn-edit-material", function() {
                let id = $(this).data("id");

                $.ajax({
                    url: "/materials/" + id,
                    method: "GET",
                    success: function(res) {
                        $("#edit_id").val(res.id);
                        $("#edit_kode_material").val(res.kode_material);
                        $("#edit_nama_material").val(res.nama_material);
                        $("#edit_satuan").val(res.satuan);
                        $("#edit_stok_awal").val(res.stok_awal);
                        $("#edit_min_stok").val(res.min_stok);
                        $("#modalEditMaterial").modal("show");
                    }
                });
            });

            $("#form-edit-material").submit(function(e) {
                e.preventDefault();
                let id = $("#edit_id").val();
                $.ajax({
                    url: "/materials/update/" + id,
                    method: "POST",
                    data: $(this).serialize(),
                    beforeSend: function() {
                        Swal.fire({
                            title: "Updating...",
                            allowOutsideClick: false,
                            didOpen: () => Swal.showLoading()
                        });
                    },
                    success: function(res) {
                        Swal.fire("Berhasil!", "Data berhasil diperbarui.", "success")
                            .then(() => location.reload());
                    },
                    error: function(xhr) {
                        let message = "Terjadi kesalahan.";
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            message = Object.values(errors)[0][0];
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: message,
                            confirmButtonColor: '#d33'
                        });
                    }
                });
            });

            $(document).on('click', '.btn-decline', function() {
                let id = $(this).data('id');

                Swal.fire({
                    title: "Yakin hapus?",
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Ya, hapus",
                    cancelButtonText: "Batal",
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                            url: "/materials/delete/" + id,
                            type: "DELETE",
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire("Berhasil!", "Data berhasil dihapus.",
                                            "success")
                                        .then(() => location.reload());

                                } else {
                                    Swal.fire({
                                        icon: "error",
                                        title: "Gagal",
                                        text: response.message
                                    });
                                }
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: "error",
                                    title: "Error!",
                                    text: xhr.responseJSON?.message ||
                                        "Terjadi kesalahan saat menghapus data."
                                });
                            }
                        });

                    }
                });
            });


        });
    </script>
@endsection
