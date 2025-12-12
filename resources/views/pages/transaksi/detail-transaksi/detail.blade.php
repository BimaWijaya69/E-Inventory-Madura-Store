@extends('layouts.template')

@section('content')
    @include('layouts.breadcrumb')

    <section class="section detail">
        <div class="row">
            <div class="col-lg-12">


                <div class="card mb-4">
                    <div class="card-body d-flex justify-content-between align-items-start flex-wrap gap-3">
                        <div>
                            <h5 class="card-title mb-1">
                                Detail Transaksi
                                @if ($transaksi->jenis == 0)
                                    <span class="badge bg-success ms-2">Penerimaan</span>
                                @else
                                    <span class="badge bg-danger ms-2">Pengeluaran</span>
                                @endif
                            </h5>
                            <p class="text-muted mb-0 small">
                                Kode: <strong>{{ $transaksi->kode_transaksi }}</strong> ·
                                Tanggal: <strong>{{ $transaksi->tanggal }}</strong>
                            </p>
                        </div>

                        <div class="text-end">
                            <p class="mb-1">
                                <span class="text-muted small d-block">Nama Pihak</span>
                                <strong>{{ $transaksi->nama_pihak_transaksi ?? '-' }}</strong>
                            </p>
                            <p class="mb-1">
                                <span class="text-muted small d-block">Keperluan</span>
                                <strong>{{ $transaksi->keperluan ?? '-' }}</strong>
                            </p>
                            @if (!empty($transaksi->nomor_pelanggan))
                                <p class="mb-0">
                                    <span class="text-muted small d-block">Nomor Pelanggan</span>
                                    <strong>{{ $transaksi->nomor_pelanggan }}</strong>
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- MATERIAL YANG TERLIBAT --}}
                <div class="card mb-4">
                    <div class="card-body">
                        <h6 class="card-title mb-3">Material</h6>

                        <div class="table-responsive">
                            <table class="table table-bordered table-sm align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:5%">No</th>
                                        <th>Nama Material</th>
                                        <th style="width:15%">Jumlah</th>
                                        <th style="width:15%">Satuan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($transaksi->detail_transaksi as $i => $detail)
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td>{{ $detail->material->nama_material ?? '-' }}</td>
                                            <td>{{ $detail->jumlah }}</td>
                                            <td>{{ $detail->material->satuan ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">
                                                Belum ada detail material.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- DOKUMENTASI / FOTO --}}
                <div class="card mb-4">
                    <div class="card-body">
                        <h6 class="card-title mb-3">Dokumentasi</h6>

                        <div class="row g-3">
                            @php
                                use Illuminate\Support\Facades\Storage;
                            @endphp

                            @if ($transaksi->jenis == 0)
                                {{-- PENERIMAAN: hanya bukti penerimaan --}}
                                <div class="col-md-4">
                                    <label class="form-label small text-muted d-block mb-1">Bukti Penerimaan</label>
                                    @if ($transaksi->foto_bukti && Storage::disk('public')->exists($transaksi->foto_bukti))
                                        <img src="{{ asset('storage/' . $transaksi->foto_bukti) }}"
                                            class="img-fluid rounded border" alt="Bukti Penerimaan">
                                    @else
                                        <p class="text-muted small fst-italic">Tidak ada foto bukti.</p>
                                    @endif
                                </div>
                            @else
                                {{-- PENGELUARAN: SR sebelum, SR sesudah, dan bukti --}}
                                <div class="col-md-4">
                                    <label class="form-label small text-muted d-block mb-1">Foto SR Sebelum</label>
                                    @if ($transaksi->foto_sr_sebelum && Storage::disk('public')->exists($transaksi->foto_sr_sebelum))
                                        <img src="{{ asset('storage/' . $transaksi->foto_sr_sebelum) }}"
                                            class="img-fluid rounded border" alt="SR Sebelum">
                                    @else
                                        <p class="text-muted small fst-italic">Tidak ada foto.</p>
                                    @endif
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label small text-muted d-block mb-1">Foto SR Sesudah</label>
                                    @if ($transaksi->foto_sr_sesudah && Storage::disk('public')->exists($transaksi->foto_sr_sesudah))
                                        <img src="{{ asset('storage/' . $transaksi->foto_sr_sesudah) }}"
                                            class="img-fluid rounded border" alt="SR Sesudah">
                                    @else
                                        <p class="text-muted small fst-italic">Tidak ada foto.</p>
                                    @endif
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label small text-muted d-block mb-1">Bukti Pengeluaran</label>
                                    @if ($transaksi->foto_bukti && Storage::disk('public')->exists($transaksi->foto_bukti))
                                        <img src="{{ asset('storage/' . $transaksi->foto_bukti) }}"
                                            class="img-fluid rounded border" alt="Bukti Pengeluaran">
                                    @else
                                        <p class="text-muted small fst-italic">Tidak ada foto bukti.</p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- OPSIONAL: INFO VERIFIKASI --}}
                @if ($transaksi->verifikasi_transaksi)
                    <div class="card mb-4">
                        <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div>
                                <h6 class="card-title mb-1">Status Verifikasi</h6>
                                @php
                                    $status = $transaksi->verifikasi_transaksi->status;
                                @endphp
                                @if ($status == '1')
                                    <span class="badge bg-success">Disetujui</span>
                                @elseif($status == '2')
                                    <span class="badge bg-danger">Ditolak</span>
                                @else
                                    <span class="badge bg-secondary">Menunggu Verifikasi</span>
                                @endif
                            </div>
                            <div class="text-end small text-muted">
                                @if ($transaksi->verifikasi_transaksi->diverifikasi_oleh)
                                    Diverifikasi oleh:
                                    <strong>{{ $transaksi->verifikasi_transaksi->verifikator->name ?? 'User ID ' . $transaksi->verifikasi_transaksi->diverifikasi_oleh }}</strong>
                                    <br>
                                @endif
                                <span>Dibuat oleh:
                                    <strong>{{ $transaksi->dibuat_oleh->name ?? 'User ID ' . $transaksi->dibuat_oleh }}</strong>
                                </span>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- BUTTON BACK --}}
                <div class="d-flex justify-content-between">
                    @php
                        $backRoute = $transaksi->jenis == 1 ? route('material-keluars') : route('material-masuks');
                    @endphp

                    <a href="{{ $backRoute }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>

            </div>
        </div>
    </section>
@endsection
