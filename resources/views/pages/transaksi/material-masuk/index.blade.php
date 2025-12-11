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
                                        <th>Status</th>
                                        <th>Actions</th>
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
                                                <td>
                                                    <span class="badge {{ $map[$status]['class'] ?? 'text-bg-secondary' }}">
                                                        {{ $map[$status]['text'] ?? 'Unknown' }}
                                                    </span>
                                                </td>
                                                <td></td>
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
@endsection
