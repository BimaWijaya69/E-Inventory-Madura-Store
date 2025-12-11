@extends('layouts.template')

@section('content')
    @include('layouts.breadcrumb')
    <section class="section keluar">
        <div class="row">

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title d-flex justify-content-end">
                            <a href="{{ route('create-material-keluars') }}">
                                <button class="btn btn-small btn-outline-primary">Tambah Pengeluaran Material</button></a>
                        </h5>

                        <!-- Table with stripped rows -->
                        <div class="table-responsive">
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tanggal</th>
                                        <th>Nama Penerima</th>
                                        <th>Keperluan</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
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
