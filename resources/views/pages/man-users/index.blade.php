@extends('layouts.template')

@section('content')
    @include('layouts.breadcrumb')
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Data Pengguna</h5>

                            <div class="d-flex gap-2">
                                <button class="btn btn-small btn-outline-primary" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal">Tambah Pengguna</button>
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
                                            Nama
                                        </th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th class="">Is Active</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($users as $index => $u)
                                        @if ($u->delet_at == '0')
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $u->name }}</td>
                                                <td>{{ $u->email }}</td>
                                                @php
                                                    $map = [
                                                        1 => ['text' => 'Admin'],
                                                        2 => ['text' => 'Petugas'],
                                                        3 => ['text' => 'Petugas Yanbung'],
                                                    ];
                                                @endphp
                                                <td>{{ $map[$u->role]['text'] ?? 'Unknown' }}</td>
                                                <td class="text-center">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input toggle-active" type="checkbox"
                                                            data-id="{{ $u->id }}"
                                                            {{ $u->is_active ? 'checked' : '' }}>
                                                    </div>
                                                </td>

                                                <td>
                                                    <div class="d-flex gap-2 justify-content-center">
                                                        <button type="button" class="btn btn-info btn-sm btn-edit-pengguna"
                                                            data-id="{{ $u->id }}">
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-danger btn-sm btn-decline"
                                                            data-id="{{ $u->id }}">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @empty
                                        <tr>
                                            <td colspan="7">
                                                <center>Tidak ada pengguna</center>
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
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Akun Pengguna</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('users.store') }}" id="form-pengguna" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Pengguna <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Masukan nama pengguna">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="Masukan email pengguna">
                            <div id="validasiEmail" class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Role <span class="text-danger"">*</span></label>
                            <select class="form-select" id="role" aria-label="Default select example" name="role">
                                <option value="">Pilih Role</option>
                                <option value="1">Admin</option>
                                <option value="2">Petugas</option>
                                <option value="3">Petugas Yanbung</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="password1" class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password1"
                                placeholder="Masukan password pengguna">
                            <div id="validasiPassword1" class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Confirmasi Password <span
                                    class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Masukan confirmasi password pengguna">
                            <div id="validasiPassword" class="invalid-feedback"></div>
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
    <div class="modal fade" id="modalEditPengguna" tabindex="-1" aria-labelledby="modalEditPenggunaLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditPenggunaLabel">Edit Akun Pengguna</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="form-edit-pengguna">
                    @csrf
                    <input type="hidden" id="edit_id" name="edit_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Pengguna <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="e_name" name="e_name"
                                placeholder="Masukan nama pengguna">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="e_email" name="e_email"
                                placeholder="Masukan email pengguna" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Role <span class="text-danger"">*</span></label>
                            <select class="form-select" id="e_role" aria-label="Default select example"
                                name="e_role">
                                <option value="">Pilih Role</option>
                                <option value="1">Admin</option>
                                <option value="2">Petugas</option>
                                <option value="3">Petugas Yanbung</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="password1" class="form-label">Password Lama<span
                                    class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="old_password"
                                placeholder="Masukan password lama" name="old_password">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password Baru<span
                                    class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="new_password" name="new_password"
                                placeholder="Masukan password baru">
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

            $("#email").on("input", function() {
                var email = $(this).val();
                var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if (email.trim() === "") {
                    $("#email").addClass("is-invalid");
                    $("#validasiEmail").text("Email tidak boleh kosong!").show();
                } else if (!emailRegex.test(email)) {
                    $("#email").addClass("is-invalid");
                    $("#validasiEmail").text("Email tidak valid!").show();
                } else {
                    $("#email").removeClass("is-invalid").addClass("is-valid");
                    $("#validasiEmail").hide();
                }
            });

            $("#password").on("input", function() {
                var confirmPassword = $(this).val();
                var newPassword = $("#password1").val();

                if (confirmPassword.trim() === "") {
                    $("#password").addClass("is-invalid").removeClass("is-valid");
                    $("#validasiPassword").text("Konfirmasi Password tidak boleh kosong!").show();
                } else if (confirmPassword !== newPassword) {
                    $("#password").addClass("is-invalid").removeClass("is-valid");
                    $("#validasiPassword").text("Konfirmasi Password tidak cocok!").show();
                } else {
                    $("#password").removeClass("is-invalid").addClass("is-valid");
                    $("#validasiPassword").hide();
                }
            });

            $("#password1").on("input", function() {
                $("#password").trigger("input");
            });

            $("#form-pengguna").submit(function(e) {
                e.preventDefault();
                let form = $(this);

                let name = $("#name").val();
                let email = $("#email").val();
                let role = $("#role").val();
                let password1 = $("#password1").val();
                let password = $("#password").val();

                if (name === "") {
                    Swal.fire('Oops!', 'Nama pengguna wajib diisi!', 'error');
                    return false;
                }
                if (email === "") {
                    Swal.fire('Oops!', 'Email wajib diisi!', 'error');
                    return false;
                }
                if (role === "") {
                    Swal.fire('Oops!', 'Role wajib dipilih!', 'error');
                    return false;
                }
                if (password1 === "") {
                    Swal.fire('Oops!', 'Password wajib diisi!', 'error');
                    return false;
                }
                if (password === "") {
                    Swal.fire('Oops!', 'Confirmasi password wajib diisi!', 'error');
                    return false;
                }

                if (password1 !== password) {
                    Swal.fire('Oops!', 'Password dan Confirmasi password tidak sesuai!', 'error');
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
                                text: 'Pengguna berhasil ditambahkan.'
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
                            if (errors.email) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Email Error',
                                    text: errors.email[0]
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

            $(document).on("click", ".btn-edit-pengguna", function() {
                let id = $(this).data("id");
                $.ajax({
                    url: "/manajemen-users/" + id,
                    method: "GET",
                    success: function(res) {
                        $("#edit_id").val(res.id);
                        $("#e_name").val(res.name);
                        $("#e_email").val(res.email);
                        $("#e_role").val(res.role);
                        $("#modalEditPengguna").modal("show");
                    }
                });
            });

            $("#form-edit-pengguna").submit(function(e) {
                e.preventDefault();

                let oldPassword = $("#old_password").val();
                let newPassword = $("#new_password").val();

                if (newPassword === "") {
                    Swal.fire('Oops!', 'Password baru wajib diisi!', 'warning');
                    return false;
                }

                if (oldPassword === "") {
                    Swal.fire('Oops!', 'Password lama wajib diisi untuk konfirmasi!', 'warning');
                    return false;
                }

                let id = $("#edit_id").val();

                $.ajax({
                    url: "/manajemen-users/update/" + id,
                    method: "POST",
                    data: $(this).serialize(),
                    beforeSend: function() {
                        Swal.fire({
                            title: "Updating...",
                            text: "Harap tunggu",
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            willOpen: () => Swal
                                .showLoading()
                        });
                    },
                    success: function(res) {
                        Swal.fire("Berhasil!", "Data berhasil diperbarui.", "success")
                            .then(() => location.reload());
                    },
                    error: function(xhr) {
                        let message =
                            "Terjadi kesalahan pada server (Error 500).";
                        if (xhr.status === 422) {
                            if (xhr.responseJSON && xhr.responseJSON.errors) {
                                message = Object.values(xhr.responseJSON.errors)[0][0];
                            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                                message = xhr.responseJSON.message;
                            }
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
                            url: "/manajemen-users/delete/" + id,
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

            $('.toggle-active').on('change', function() {
                let id = $(this).data('id');
                let toggle = $(this);
                $.ajax({
                    url: `/manajemen-users/is_active/${id}`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(res) {
                        Swal.fire({
                            icon: 'success',
                            title: res.is_active ? 'User diaktifkan' :
                                'User dinonaktifkan',
                            timer: 1200,
                            showConfirmButton: false
                        });
                    },
                    error: function() {
                        Swal.fire('Error', 'Gagal mengubah status', 'error');
                        toggle.prop('checked', !toggle.prop('checked'));
                    }
                });
            });

        });
    </script>
@endsection
