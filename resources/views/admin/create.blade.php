@extends('layouts.admin')

@section('page-title', 'Tambah Produk')

@section('admin-contents')
    <div class="header d-flex  align-items-center gap-5">
        <div class="d-flex">
            <img src="/img/stockProduct.svg" alt="">
            <div class=" d-flex  flex-column ms-3">
                <p class="m-0 text-secondary">Pastikan Anda benar-benar yakin sebelum melanjutkan.</p>
                <p class="m-0 pt-1 fs-5 fw-medium">Tambah Produk</p>
            </div>
        </div>
    </div>

    <div class="form-input">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.product.store') }}" enctype="multipart/form-data" class="rounded p-4"
            style="background-color: #fff; border-color: #F5CCA0;">
            @csrf
            <hr style="border-top: 2px solid #F5CCA0;">

            <div class="mb-4">
                <label for="productName" class="form-label fs-5">Nama Produk</label>
                <input type="text" class="form-control p-2 rounded-3" name="name" id="productName"
                    placeholder="Nama Produk">
            </div>

            <div class="mb-4">
                <label class="form-label fs-5">Kategori Produk</label>
                <div class="row">
                    @foreach ($chunks as $chunk)
                        <div class="col-md-6">
                            @foreach ($chunk as $category)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="category_{{ $category->id }}"
                                        name="categories[]" value="{{ $category->id }}">
                                    <label class="form-check-label" for="category_{{ $category->id }}">
                                        {{ $category->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mb-4">
                <label for="stock" class="form-label fs-5">Jumlah Stock</label>
                <input type="number" class="form-control p-2 rounded-3" name="stock" id="stock"
                    placeholder="Jumlah Stock">
            </div>

            <div class="mb-4">
                <label for="price" class="form-label fs-5">Harga Satuan</label>
                <input type="text" class="form-control p-2 rounded-3" name="price" id="price"
                    placeholder="Harga Satuan">
            </div>

            <!-- ✅ Tambahan Upload Gambar -->
            <div class="mb-4">
                <label for="image" class="form-label fs-5">Gambar Produk</label>
                <input type="file" class="form-control p-2 rounded-3" name="image" id="image" accept="image/*">
            </div>

            <!-- ✅ Upload Thumbnail 1 -->
            <div class="mb-4">
                <label for="thumbnail_1" class="form-label fs-5">Thumbnail Produk 1</label>
                <input type="file" class="form-control p-2 rounded-3" name="thumbnails[]" id="thumbnail_1"
                    accept="image/*" required>
            </div>

            <!-- ✅ Upload Thumbnail 2 -->
            <div class="mb-4">
                <label for="thumbnail_2" class="form-label fs-5">Thumbnail Produk 2</label>
                <input type="file" class="form-control p-2 rounded-3" name="thumbnails[]" id="thumbnail_2"
                    accept="image/*" required>
            </div>

            <small class="text-muted mt-1 d-block">*Kedua thumbnail wajib diisi untuk menampilkan galeri produk.</small>



            <!-- ✅ Tambahan Deskripsi Produk -->
            <div class="mb-4">
                <label for="description" class="form-label fs-5">Deskripsi Produk</label>
                <textarea class="form-control p-2 rounded-3" name="description" id="description" rows="4"
                    placeholder="Tulis deskripsi produk di sini..."></textarea>
            </div>

            <div class="d-flex justify-content-evenly mt-5 border gap-2">
                <a href="/admin" class="btn border px-4 py-2 w-100 rounded-3">Kembali ke Beranda</a>
                <button type="submit" class="btn text-white px-4 py-2 w-100 rounded-3"
                    style="background-color: #994D1C;">Tambah Data
                    Sekarang</button>
            </div>
        </form>
    </div>
@endsection
