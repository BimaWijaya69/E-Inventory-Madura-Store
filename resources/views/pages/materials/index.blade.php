@extends('layouts.template')

@section('content')
    @include('layouts.breadcrumb')
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Datatables</h5>
                        <p>Add lightweight datatables to your project with using the <a
                                href="https://github.com/fiduswriter/Simple-DataTables" target="_blank">Simple
                                DataTables</a> library. Just add <code>.datatable</code> class name to any table you
                            wish to conver to a datatable. Check for <a
                                href="https://fiduswriter.github.io/simple-datatables/demos/" target="_blank">more
                                examples</a>.</p>

                        <!-- Table with stripped rows -->
                        <table class="table datatable">
                            <thead>
                                <tr>
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
                                <tr>
                                    <td>Unity Pugh</td>
                                    <td>9958</td>
                                    <td>Curicó</td>
                                    <td>2005/02/11</td>
                                    <td>37%</td>
                                    <td>2005/02/11</td>
                                    <td>37%</td>
                                </tr>
                            </tbody>
                        </table>
                        <!-- End Table with stripped rows -->

                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
