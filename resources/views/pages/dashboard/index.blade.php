@extends('layouts.template')

@section('content')
    @include('layouts.breadcrumb')
    <section class="section dashboard">
        <div class="row">


            <div class="row">
                <div class="col-xxl-4 col-md-4">
                    <div class="card info-card sales-card">
                        <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <li class="dropdown-header text-start">
                                    <h6>Download</h6>
                                </li>

                                <li><a class="dropdown-item" href="">Export
                                        Exel</a></li>
                            </ul>
                        </div>

                        <div class="card-body">
                            <h5 class="card-title">Total Transaksi Material </h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="fa-solid fa-location-dot"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>4</h6>
                                    <span class="text-muted small pt-2 ps-1">transaksi</span>
                                </div>
                            </div>

                        </div>

                    </div>
                </div><!-- End Sales Card -->
                <div class="col-xxl-4 col-md-4">
                    <div class="card info-card sales-card">

                        <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <li class="dropdown-header text-start">
                                    <h6>Download</h6>
                                </li>

                                <li><a class="dropdown-item" href="">Export Exel</a></li>
                            </ul>
                        </div>

                        <div class="card-body">
                            <h5 class="card-title">Pengeluaran Material </h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="fa-solid fa-handshake"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>7</h6>
                                    <span class="text-muted small pt-2 ps-1">transaksi</span>

                                </div>
                            </div>
                        </div>

                    </div>
                </div><!-- End Sales Card -->
                <div class="col-xxl-4 col-md-4">
                    <div class="card info-card sales-card">

                        <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <li class="dropdown-header text-start">
                                    <h6>Download</h6>
                                </li>

                                <li><a class="dropdown-item" href="">Export
                                        Exel</a></li>
                            </ul>
                        </div>

                        <div class="card-body">
                            <h5 class="card-title">Pengeluaran Material</h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="fa-solid fa-briefcase"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>4</h6>
                                    <span class="text-muted small pt-2 ps-1">transaksi</span>

                                </div>
                            </div>
                        </div>

                    </div>
                </div><!-- End Sales Card -->
                <div class="col-xxl-4 col-md-4">
                    <div class="card info-card sales-card">

                        <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <li class="dropdown-header text-start">
                                    <h6>Download</h6>
                                </li>

                                <li><a class="dropdown-item" href="">Export Exel</a></li>
                            </ul>
                        </div>

                        <div class="card-body">
                            <h5 class="card-title">Disetujui </h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="fa-solid fa-trophy"></i>
                                </div>
                                <div class="ps-3">
                                    <h6></h6>
                                    <span class="text-muted small pt-2 ps-1">transaksi</span>

                                </div>
                            </div>
                        </div>

                    </div>
                </div><!-- End Sales Card -->
                <div class="col-xxl-4 col-md-4">
                    <div class="card info-card sales-card">

                        <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <li class="dropdown-header text-start">
                                    <h6>Download</h6>
                                </li>

                                <li><a class="dropdown-item" href="">Export Exel</a>
                                </li>
                            </ul>
                        </div>

                        <div class="card-body">
                            <h5 class="card-title">Dikembalikan </h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="fa-solid fa-trophy"></i>

                                </div>
                                <div class="ps-3">
                                    <h6></h6>
                                    <span class="text-muted small pt-2 ps-1">transaksi</span>

                                </div>
                            </div>
                        </div>

                    </div>
                </div><!-- End Sales Card -->
                <div class="col-xxl-4 col-md-4">
                    <div class="card info-card sales-card">

                        <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <li class="dropdown-header text-start">
                                    <h6>Download</h6>
                                </li>

                                <li><a class="dropdown-item" href="">Export Exel</a></li>
                            </ul>
                        </div>

                        <div class="card-body">
                            <h5 class="card-title">Belum Diverifikasi </h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="fa-solid fa-medal"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>0</h6>
                                    <span class="text-muted small pt-2 ps-1">transaksi</span>

                                </div>
                            </div>
                        </div>

                    </div>
                </div><!-- End Sales Card -->

            </div>
            <div class="card-body">
                <h5 class="card-title">Reports <span>/Today</span></h5>
                <!-- Line Chart -->
                <div id="reportsChart"></div>
                <!-- End Line Chart -->
            </div>
        </div>
    </section>
    {{-- <script>
        document.addEventListener("DOMContentLoaded", () => {
            const labels = @json($labelsMingguan);
            const series = @json($seriesChart);

            new ApexCharts(document.querySelector("#reportsChart"), {
                chart: {
                    type: 'area',
                    height: 350,
                    toolbar: {
                        show: true,
                        tools: {
                            download: false
                        }
                    }
                },
                series: series,
                xaxis: {
                    categories: labels,
                    labels: {
                        rotate: -45,
                        style: {
                            fontSize: '12px'
                        }
                    }
                },
                markers: {
                    size: 6,
                    colors: ['#4154f1', '#2eca6a', '#ff771d', '#e91e63', '#00bcd4', '#ffc107'],
                    strokeColors: '#fff',
                    strokeWidth: 2,
                    hover: {
                        size: 8
                    }
                },
                colors: ['#4154f1', '#2eca6a', '#ff771d', '#e91e63', '#00bcd4', '#ffc107'],
                stroke: {
                    curve: 'smooth',
                    width: 2
                },
                dataLabels: {
                    enabled: false
                },
                fill: {
                    type: "gradient",
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.4,
                        opacityTo: 0.1,
                        stops: [0, 90, 100]
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val + " pendaftar";
                        }
                    }
                }
            }).render();
        });
    </script> --}}
@endsection
