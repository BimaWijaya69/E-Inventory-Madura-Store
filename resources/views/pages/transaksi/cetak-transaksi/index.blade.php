<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Transaksi - {{ $transaksi->kode_transaksi }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('style/style.css') }}">
</head>

<body>
    <div class="no-print print-button">
        <button onclick="window.print()" class="btn btn-primary btn-lg shadow">
            <i class="bi bi-printer"></i> Cetak
        </button>
        <button onclick="window.close()" class="btn btn-secondary btn-lg shadow ms-2">
            <i class="bi bi-x-circle"></i> Tutup
        </button>
    </div>

    <div class="print-container">
        <div class="header-laporan">
            <div class="d-flex align-items-center justify-content-center mb-3">
                <img src="{{ asset('images/Logo_PLN.png') }}" alt="Logo PLN" style="height: 80px; margin-right: 20px;">
                <div class="text-center">
                    <h2 class="mb-0">LAPORAN TRANSAKSI MATERIAL</h2>
                    <p class="mb-0 mt-2" style="font-size: 14px; font-weight: normal;">PT PLN (Persero)</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <table class="info-table">
                    <tr>
                        <td class="label">Kode Transaksi</td>
                        <td class="separator">:</td>
                        <td class="value">{{ $transaksi->kode_transaksi }}</td>
                    </tr>
                    <tr>
                        <td class="label">Tanggal</td>
                        <td class="separator">:</td>
                        <td class="value">{{ $transaksi->tanggal }}</td>
                    </tr>
                    <tr>
                        <td class="label">Nama Pihak</td>
                        <td class="separator">:</td>
                        <td class="value">{{ $transaksi->nama_pihak_transaksi ?? '-' }}
                            @if (!empty($transaksi->nomor_pelanggan))
                                ({{ $transaksi->nomor_pelanggan }})
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-6">
                <table class="info-table">
                    <tr>
                        <td class="label">Jenis</td>
                        <td class="separator">:</td>
                        <td class="value">{{ $transaksi->jenis == 0 ? 'PENERIMAAN' : 'PENGELUARAN' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Keperluan</td>
                        <td class="separator">:</td>
                        <td class="value">{{ $transaksi->keperluan ?? '-' }}</td>
                    </tr>

                    <tr>
                        <td class="label">Dibuat oleh</td>
                        <td class="separator">:</td>
                        <td class="value">{{ $transaksi->pembuat->name ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="section-title">DAFTAR MATERIAL</div>
        <table class="material-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Nama Material</th>
                    <th width="15%">Jumlah</th>
                    <th width="15%">Satuan</th>
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
                        <td colspan="4" class="text-center">Tidak ada data material</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="section-title">DOKUMENTASI</div>
        <div class="documentation-section">
            @php
                use Illuminate\Support\Facades\Storage;
            @endphp

            @if ($transaksi->jenis == 0)
                <div class="row">
                    <div class="col-12">
                        <div class="doc-item">
                            <div class="doc-label">Bukti Penerimaan</div>
                            @if ($transaksi->foto_bukti && Storage::disk('public')->exists($transaksi->foto_bukti))
                                <img src="{{ asset('storage/' . $transaksi->foto_bukti) }}" alt="Bukti Penerimaan">
                            @else
                                <p class="text-muted">Tidak ada dokumentasi</p>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                {{-- PENGELUARAN --}}
                <div class="row">
                    <div class="col-4">
                        <div class="doc-item">
                            <div class="doc-label">Foto SR Sebelum</div>
                            @if ($transaksi->foto_sr_sebelum && Storage::disk('public')->exists($transaksi->foto_sr_sebelum))
                                <img src="{{ asset('storage/' . $transaksi->foto_sr_sebelum) }}" alt="SR Sebelum">
                            @else
                                <p class="text-muted">-</p>
                            @endif
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="doc-item">
                            <div class="doc-label">Foto SR Sesudah</div>
                            @if ($transaksi->foto_sr_sesudah && Storage::disk('public')->exists($transaksi->foto_sr_sesudah))
                                <img src="{{ asset('storage/' . $transaksi->foto_sr_sesudah) }}" alt="SR Sesudah">
                            @else
                                <p class="text-muted">-</p>
                            @endif
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="doc-item">
                            <div class="doc-label">Bukti Pengeluaran</div>
                            @if ($transaksi->foto_bukti && Storage::disk('public')->exists($transaksi->foto_bukti))
                                <img src="{{ asset('storage/' . $transaksi->foto_bukti) }}" alt="Bukti Pengeluaran">
                            @else
                                <p class="text-muted">-</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- TANDA TANGAN --}}
        <div class="signature-section">
            <div class="row">
                <div class="col-6">
                    <div class="signature-box">
                        <div>Mengetahui,</div>
                        <div class="signature-line">
                            @if ($transaksi->verifikasi_transaksi && $transaksi->verifikasi_transaksi->status == '1')
                                {{ $transaksi->verifikasi_transaksi->penverifikasi->name ?? 'Verifikator' }}
                            @else
                                (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="signature-box">
                        <div>Dibuat oleh,</div>
                        <div class="signature-line">
                            {{ $transaksi->pembuat->name ?? 'Pembuat' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>

</html>
