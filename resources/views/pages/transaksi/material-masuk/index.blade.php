@extends('layouts.template')

@section('content')
    @include('layouts.breadcrumb')
    <section class="section keluar">
        <div class="row">

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title ">
                            <a href="{{ route('material-masuks.create') }}">
                                <button class="btn btn-small btn-outline-primary">Tambah Penerimaan Material</button></a>
                        </h5>

                        <!-- Table with stripped rows -->
                        <div class="table-responsive">
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Kode</th>
                                        <th>Tanggal</th>
                                        <th>Nama Penerima</th>
                                        <th>Keperluan</th>
                                        <th class="text-center">Status</th>
                                        @if ($data->role == '1')
                                            <th class="text-center">Verifikasi</th>
                                        @endif
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($transaksis as $index => $t)
                                        @if ($t->delet_at == '0')
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $t->kode_transaksi }}</td>
                                                <td>{{ $t->tanggal }}</td>
                                                <td>{{ $t->nama_pihak_transaksi }}</td>
                                                <td>{{ $t->keperluan }}</td>
                                                @php
                                                    $map = [
                                                        0 => ['class' => 'text-bg-warning', 'text' => 'Menunggu'],
                                                        1 => ['class' => 'text-bg-success', 'text' => 'Disetujui'],
                                                        2 => ['class' => 'text-bg-danger', 'text' => 'Dikembalikan'],
                                                    ];

                                                    $status = $t->verifikasi_transaksi?->status;
                                                @endphp
                                                <td class="align-middle">
                                                    <div class="d-flex gap-2 justify-content-center">
                                                        <span
                                                            class="badge {{ $map[$status]['class'] ?? 'text-bg-secondary' }}">
                                                            {{ $map[$status]['text'] ?? 'Unknown' }}
                                                        </span>
                                                        @if ($t->verifikasi_transaksi?->status == 2)
                                                            <span class="badge text-bg-secondary btn-ajuan"
                                                                data-id="{{ $t->id }}"
                                                                style="cursor: pointer;">Ajukan Kembali</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                @if ($data->role == '1')
                                                    <td class="align-middle">
                                                        @if ($t->verifikasi_transaksi->status == 0)
                                                            <div class="d-flex gap-2 justify-content-center">
                                                                <button type="button"
                                                                    class="btn btn-success btn-sm btn-confirm"
                                                                    data-id="{{ $t->id }}"><i
                                                                        class="bi bi-check-circle"></i></button>
                                                                <button type="button"
                                                                    class="btn btn-danger btn-sm btn-decline"
                                                                    data-id="{{ $t->id }}"><i
                                                                        class="bi bi-x-circle"></i></button>
                                                            </div>
                                                        @endif
                                                    </td>
                                                @endif
                                                <td class="align-middle">
                                                    <div class="d-flex gap-2 justify-content-center">
                                                        <a href="{{ route('material.detail-masuk', $t->id) }}"
                                                            class="btn btn-secondary btn-sm">
                                                            <i class="bi bi-info-circle"></i>
                                                        </a>
                                                        @if (
                                                            ($t->verifikasi_transaksi->status == 0 && $data->role == 1) ||
                                                                ($t->verifikasi_transaksi->status == 2 && $data->role != 1))
                                                            <a href="{{ route('material-masuks.edit', ['id' => $t->id]) }}"
                                                                class="btn btn-info btn-sm btn-edit-material">
                                                                <i class="bi bi-pencil"></i>
                                                            </a>
                                                        @endif
                                                        <button type="button" class="btn btn-danger btn-sm btn-hps"
                                                            data-id="{{ $t->id }}">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @empty
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
    <script>
        $(document).ready(function() {
            $(document).on('click', '.btn-hps', function() {
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
                            url: "/transaksi/" + id,
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
