@extends('layouts.template')

@section('content')
    @include('layouts.breadcrumb')

    <section class="section detail">
        <div class="row">
            <div class="col-lg-12">

                @if ($transaksi->verifikasi_transaksi)
                    @php
                        $status = $transaksi->verifikasi_transaksi->status;
                    @endphp
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-body">

                            @php
                                if ($status == 0) {
                                    $alertClass = 'alert-warning';
                                    $statusText = 'Menunggu';
                                } elseif ($status == 1) {
                                    $alertClass = 'alert-success';
                                    $statusText = 'Disetujui';
                                } else {
                                    $alertClass = 'alert-danger';
                                    $statusText = 'Dikembalikan';
                                }
                            @endphp

                            <div class="alert {{ $alertClass }} mt-3 mb-0">
                                <div class="row align-items-start">

                                    <div class="col-md-7 d-flex align-items-start">
                                        <div class="me-2 fs-5">
                                            @if ($status == 0)
                                                <i class="bi bi-info-circle-fill"></i>
                                            @elseif ($status == 1)
                                                <i class="bi bi-check-circle-fill"></i>
                                            @else
                                                <i class="bi bi-x-circle-fill"></i>
                                            @endif
                                        </div>

                                        <div class="flex-grow-1">
                                            <strong>Status Verifikasi: {{ $statusText }}</strong>
                                            @if ($status == 2 && !empty($transaksi->verifikasi_transaksi->alasan_pengembalian))
                                                <div class="small text-muted mt-1">
                                                    {{ $transaksi->verifikasi_transaksi->alasan_pengembalian }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-5 text-md-end mt-3 mt-md-0">
                                        @if (!empty($transaksi->verifikasi_transaksi) && !empty($transaksi->verifikasi_transaksi->penverifikasi))
                                            <div class="small text-muted">Diverifikasi oleh</div>
                                            <strong>
                                                {{ !empty($transaksi->verifikasi_transaksi->penverifikasi)
                                                    ? $transaksi->verifikasi_transaksi->penverifikasi->name
                                                    : 'User ID ' . $transaksi->verifikasi_transaksi->diverifikasi_oleh }}
                                            </strong>
                                        @else
                                            <div class="small text-muted">Dibuat oleh</div>
                                            <strong>
                                                {{ !empty($transaksi->pembuat) ? $transaksi->pembuat->name : 'User ID ' . $transaksi->dibuat_oleh }}
                                            </strong>
                                        @endif
                                    </div>

                                </div>
                            </div>


                        </div>
                    </div>
                @endif

                {{-- LAPORAN TRANSAKSI MATERIAL --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="text-center mb-4 pb-3 border-bottom">
                            <h4 class="fw-bold mb-1">LAPORAN TRANSAKSI MATERIAL</h4>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <table class="table table-borderless table-sm">
                                    <tr>
                                        <td width="40%" class="text-muted">Kode Transaksi</td>
                                        <td width="5%">:</td>
                                        <td><strong>{{ $transaksi->kode_transaksi }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Tanggal</td>
                                        <td>:</td>
                                        <td><strong>{{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d-m-Y') }}</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Nama Pihak</td>
                                        <td>:</td>
                                        <td><strong>{{ $transaksi->nama_pihak_transaksi ?? '-' }}
                                                @if (!empty($transaksi->nomor_pelanggan))
                                                    ({{ $transaksi->nomor_pelanggan }})
                                                @endif
                                            </strong></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless table-sm">
                                    <tr>
                                        <td width="40%" class="text-muted">Jenis</td>
                                        <td width="5%">:</td>
                                        <td><strong>{{ $transaksi->jenis == 0 ? 'PENERIMAAN' : 'PENGELUARAN' }}</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="40%" class="text-muted">Keperluan</td>
                                        <td width="5%">:</td>
                                        <td><strong>{{ $transaksi->keperluan ?? '-' }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Dibuat oleh</td>
                                        <td>:</td>
                                        <td><strong>{{ $transaksi->pembuat->name ?? '-' }}</strong></td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        {{-- DETAIL MATERIAL --}}
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3 pb-2 border-bottom">Daftar Material</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-center" width="5%">No</th>
                                            <th>Nama Material</th>
                                            <th class="text-center" width="15%">Jumlah</th>
                                            <th class="text-center" width="15%">Satuan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($transaksi->detail_transaksi as $i => $detail)
                                            <tr>
                                                <td class="text-center">{{ $i + 1 }}</td>
                                                <td>{{ $detail->material->nama_material ?? '-' }}</td>
                                                <td class="text-center">{{ $detail->jumlah }}</td>
                                                <td class="text-center">{{ $detail->material->satuan ?? '-' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-3">
                                                    <i class="bi bi-inbox"></i> Belum ada detail material
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- DOKUMENTASI --}}
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3 pb-2 border-bottom">Dokumentasi</h6>
                            <div class="row g-3">
                                @php
                                    use Illuminate\Support\Facades\Storage;
                                @endphp

                                @if ($transaksi->jenis == 0)
                                    {{-- PENERIMAAN --}}
                                    <div class="col-md-4">
                                        <div class="card h-100">
                                            <div class="card-body text-center mt-3">
                                                <label class="form-label fw-semibold d-block mb-3">Bukti Penerimaan</label>
                                                @if ($transaksi->foto_bukti && Storage::disk('public')->exists($transaksi->foto_bukti))
                                                    <img src="{{ asset('storage/' . $transaksi->foto_bukti) }}"
                                                        class="img-fluid rounded border" alt="Bukti Penerimaan"
                                                        style="max-height: 250px; object-fit: cover;">
                                                @else
                                                    <div class="text-muted py-5">
                                                        <i class="bi bi-image fs-1 d-block mb-2"></i>
                                                        <small>Tidak ada foto</small>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    {{-- PENGELUARAN --}}
                                    <div class="col-md-4">
                                        <div class="card h-100">
                                            <div class="card-body text-center mt-3">
                                                <label class="form-label fw-semibold d-block mb-3">Foto SR Sebelum</label>
                                                @if ($transaksi->foto_sr_sebelum && Storage::disk('public')->exists($transaksi->foto_sr_sebelum))
                                                    <img src="{{ asset('storage/' . $transaksi->foto_sr_sebelum) }}"
                                                        class="img-fluid rounded border" alt="SR Sebelum"
                                                        style="max-height: 250px; object-fit: cover;">
                                                @else
                                                    <div class="text-muted py-5">
                                                        <i class="bi bi-image fs-1 d-block mb-2"></i>
                                                        <small>Tidak ada foto</small>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="card h-100">
                                            <div class="card-body text-center">
                                                <label class="form-label fw-semibold d-block mb-3 mt-3">Foto SR
                                                    Sesudah</label>
                                                @if ($transaksi->foto_sr_sesudah && Storage::disk('public')->exists($transaksi->foto_sr_sesudah))
                                                    <img src="{{ asset('storage/' . $transaksi->foto_sr_sesudah) }}"
                                                        class="img-fluid rounded border" alt="SR Sesudah"
                                                        style="max-height: 250px; object-fit: cover;">
                                                @else
                                                    <div class="text-muted py-5">
                                                        <i class="bi bi-image fs-1 d-block mb-2"></i>
                                                        <small>Tidak ada foto</small>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="card h-100">
                                            <div class="card-body text-center">
                                                <label class="form-label fw-semibold d-block mb-3">Bukti Pengeluaran</label>
                                                @if ($transaksi->foto_bukti && Storage::disk('public')->exists($transaksi->foto_bukti))
                                                    <img src="{{ asset('storage/' . $transaksi->foto_bukti) }}"
                                                        class="img-fluid rounded border" alt="Bukti Pengeluaran"
                                                        style="max-height: 250px; object-fit: cover;">
                                                @else
                                                    <div class="text-muted py-5">
                                                        <i class="bi bi-image fs-1 d-block mb-2"></i>
                                                        <small>Tidak ada foto</small>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TOMBOL AKSI --}}
                <div class="d-flex justify-content-between mt-4 gap-2">
                    @php
                        $backRoute = $transaksi->jenis == 1 ? route('material-keluars') : route('material-masuks');
                    @endphp

                    <a href="{{ $backRoute }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>

                    @if ($transaksi->verifikasi_transaksi->status == 0 && $data->role == 1)
                        <div class="d-flex gap-2 justify-content-center">
                            <button type="button" class="btn btn-success btn-sm btn-confirm"
                                data-id="{{ $transaksi->id }}"><i class="bi bi-check-circle"></i> Setujui</button>
                            <button type="button" class="btn btn-danger btn-sm btn-decline"
                                data-id="{{ $transaksi->id }}"><i class="bi bi-x-circle"></i> Kembalikan</button>
                        </div>
                    @endif
                    @if ($transaksi->verifikasi_transaksi->status == 1)
                        <a href="{{ route('transaksi.print', $transaksi->id) }}" target="_blank"
                            class="btn btn-primary">
                            <i class="bi bi-printer"></i> Cetak Laporan
                        </a>
                    @endif
                </div>

            </div>
        </div>
    </section>

    {{-- PRINT STYLES --}}
    <style>
        @media print {

            .breadcrumb,
            .btn,
            .sidebar,
            .navbar,
            footer {
                display: none !important;
            }

            .card {
                border: 1px solid #000 !important;
                box-shadow: none !important;
                page-break-inside: avoid;
            }

            body {
                background: white !important;
            }

            .section.detail {
                padding: 0 !important;
            }

            /* Hide verification card on print if needed */
            .card:first-child {
                display: none;
            }
        }
    </style>
@endsection
