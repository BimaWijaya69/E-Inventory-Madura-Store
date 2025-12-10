@extends('layouts.template')

@section('content')
    @include('layouts.breadcrumb')
    <section class="section">
        <div class="card">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center pt-3 pb-2">
                    <div>
                        <h5 class="card-title mb-0">Daftar Penerimaan Material</h5>
                        <small class="text-muted">Riwayat material yang sudah diterima</small>
                    </div>

                    <a href="{{ route('material-masuks.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Penerimaan
                    </a>
                </div>

                <div class="table-responsive mt-3">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Nama Penerima</th>
                                <th scope="col">Keperluan</th>
                                <th scope="col">Status</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Dummy data dulu, nanti diganti dari controller --}}
                            @for ($i = 1; $i <= 5; $i++)
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>12/10/2025</td>
                                    <td>Petugas {{ $i }}</td>
                                    <td>Pengadaan material rutin</td>
                                    <td>
                                        <span class="badge bg-success">Disetujui</span>
                                        {{-- contoh lain: bg-warning, bg-danger, dll --}}
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="#" class="btn btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="#" class="btn btn-outline-secondary">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </section>
@endsection
