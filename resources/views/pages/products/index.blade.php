@extends('layouts.main')

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1><b>Data Barang</b></h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item active">Barang</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <!-- Tambahkan SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Daftar Barang</h5>
                    <button class="btn btn-tambah ml-auto" data-bs-toggle="modal" data-bs-target="#addProductModal">Tambah
                        Barang</button>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-hover table-bordered align-middle">
                        <thead class="table-light text-center">
                            <tr>
                                <th>No</th>
                                <th>Kode Material</th>
                                <th>Nama Manterial</th>
                                <th>Foto Barang</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Kategori</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td>{{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}</td>
                                    <td>{{ $product->sku }}</td>
                                    <td>{{ $product->nama }}</td>
                                    <td>
                                        @if ($product->foto)
                                            <img src="{{ asset('storage/' . $product->foto) }}" alt="{{ $product->nama }}"
                                                style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <p>No Photo</p>
                                        @endif
                                    </td>

                                    <td>{{ number_format($product->harga, 0, ',', '.') }}</td>
                                    <td>{{ $product->stok }}</td>
                                    <td>{{ $product->category->nama }}</td>
                                    <td class="text-center">

                                        <div class="d-inline-flex mr-2">

                                            <!-- Tombol Edit -->
                                            <button class="btn btn-sm btn-dark mr-2" type="button"
                                                onclick="showEditModal({{ $product }})"><i
                                                    class="fas fa-edit"></i>Edit</button>

                                            <!-- Tombol Hapus -->
                                            <form action="/products/{{ $product->id }}" method="post" id="form-delete">
                                                @csrf
                                                @method('delete')
                                                <button class="btn btn-sm btn-dark" type="button"
                                                    onclick="deleteConfirmation(this)">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-center">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Produk -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Header Modal -->
                <div class="modal-header" style="background-color: #dbeb04; color: #000;">
                    <h5 class="modal-title" id="addProductModalLabel">Tambah Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Body Modal -->
                <div class="modal-body" style="max-height: 75vh; overflow-y: auto;">
                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <!-- Nama Barang -->
                        <div class="mb-3">
                            <label for="productName" class="form-label fw-bold">Nama Barang</label>
                            <input type="text" class="form-control" id="productName" name="nama"
                                placeholder="Masukkan nama barang" required>
                        </div>
                        <!-- Foto -->
                        <div class="mb-3">
                            <label for="productPhoto" class="form-label fw-bold">Foto</label>
                            <input type="file" class="form-control" id="productPhoto" name="foto" accept="image/*"
                                required>
                        </div>

                        <!-- Grid: SKU, Harga, Stok -->
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="productCode" class="form-label fw-bold">SKU/Kode Barang</label>
                                <input type="text" class="form-control" id="productCode" name="sku"
                                    value="{{ $kodeBarang ?? '' }}" readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="productPrice" class="form-label fw-bold">Harga</label>
                                <input type="number" class="form-control" id="productPrice" name="harga"
                                    placeholder="Masukkan harga modal" min="0" required>
                            </div>
                            <div class="col-md-4">
                                <label for="productStock" class="form-label fw-bold">Stok</label>
                                <input type="number" class="form-control" id="productStock" name="stok"
                                    placeholder="Masukkan stok barang" min="0" required>
                            </div>
                        </div>
                        <!-- Kategori -->
                        <div class="mb-3 mt-3">
                            <label for="category_id" class="form-label fw-bold">Kategori</label>
                            <select class="form-control" id="category_id" name="category_id" required>
                                <option value="" disabled selected>Pilih kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Button Actions -->
                        <div class="d-flex justify-content-end mr-2 mt-4">
                            <button type="button" class="btn btn-secondary mr-2" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn text-black"
                                style="background-color: #dbeb04;">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal edit Produk -->
    <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Header Modal -->
                <div class="modal-header" style="background-color: #dbeb04; color: #000;">
                    <h5 class="modal-title" id="editProductModalLabel">Tambah Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Body Modal -->
                <div class="modal-body" style="max-height: 75vh; overflow-y: auto;">
                    <form id="editProductForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <!-- Nama Barang -->
                        <div class="mb-3">
                            <label for="editProductName" class="form-label fw-bold">Nama Barang</label>
                            <input type="text" class="form-control" id="editProductName" name="nama"
                                placeholder="Masukkan nama barang" required>
                        </div>
                        <!-- Deskripsi -->
                        <div class="mb-3">
                            <label for="editProductPhoto" class="form-label fw-bold">Foto</label>
                            <input type="file" class="form-control" id="editProductPhoto" name="foto"
                                accept="image/*">
                            <img id="editProductPhotoPreview" src="" alt="Preview Foto"
                                style="display:none; margin-top:10px; width:100px;">
                        </div>
                        <!-- Grid: SKU, Harga, Stok -->
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="editProductCode" class="form-label fw-bold">SKU/Kode Barang</label>
                                <input type="text" class="form-control" id="editProductCode" name="sku"
                                    placeholder="Masukkan SKU" readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="editProductPrice" class="form-label fw-bold">Harga</label>
                                <input type="number" class="form-control" id="editProductPrice" name="harga"
                                    placeholder="Masukkan harga modal" min="0" required>
                            </div>
                            <div class="col-md-4">
                                <label for="editProductStock" class="form-label fw-bold">Stok</label>
                                <input type="number" class="form-control" id="editProductStock" name="stok"
                                    placeholder="Masukkan stok barang" min="0" required>
                            </div>
                        </div>
                        <!-- Kategori -->
                        <div class="mb-3 mt-3">
                            <label for="editProductCategory" class="form-label fw-bold">Kategori</label>
                            <select class="form-control" id="editProductCategory" name="category_id" required>
                                <option value="" disabled selected>Pilih kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Button Actions -->
                        <div class="d-flex justify-content-end mr-2 mt-4">
                            <button type="button" class="btn btn-secondary mr-2" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn text-black"
                                style="background-color: #dbeb04;">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>




    <script>
        // Fungsi showEditModal
        function showEditModal(product) {
            const editForm = document.getElementById('editProductForm');
            editForm.action = `/products/${product.id}`; // Ubah URL untuk update produk

            // Isi input form dengan data produk
            document.getElementById('editProductName').value = product.nama;
            document.getElementById('editProductCode').value = product.sku;
            document.getElementById('editProductPrice').value = product.harga;
            document.getElementById('editProductStock').value = product.stok;
            document.getElementById('editProductCategory').value = product.category_id;

            // Menampilkan foto yang ada di modal
            const editProductPhotoPreview = document.getElementById('editProductPhotoPreview');
            if (product.foto) {
                editProductPhotoPreview.src = `/storage/${product.foto}`;
                editProductPhotoPreview.style.display = 'block'; // Menampilkan gambar preview
            } else {
                editProductPhotoPreview.style.display = 'none'; // Sembunyikan jika tidak ada foto
            }

            // Menampilkan modal edit
            const editModal = new bootstrap.Modal(document.getElementById('editProductModal'));
            editModal.show();
        }


        // Fungsi deleteConfirmation
        async function deleteConfirmation(e) {
            console.log("bisa");
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Membuat form delete secara dinamis
                    const form = e.closest('form');
                    form.submit();
                }
            });
        }
    </script>
@endsection
