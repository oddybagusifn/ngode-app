@extends('layouts.admin')

@section('page-title', 'Perbarui Produk')

@section('admin-contents')
    <div class="header d-flex align-items-center gap-5 mb-3">
        <div class="d-flex">
            <img src="/img/stockProduct.svg" alt="">
            <div class="d-flex flex-column ms-3">
                <p class="m-0 text-secondary">Pastikan Anda benar-benar yakin sebelum melanjutkan.</p>
                <p class="m-0 pt-1 fs-5 fw-medium">Perbarui Produk</p>
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

        <form method="POST" action="{{ route('admin.product.update', $product->id) }}" enctype="multipart/form-data"
            class="rounded p-4" style="background-color: #fff; border-color: #F5CCA0;">
            @csrf
            @method('PUT')
            <hr style="border-top: 2px solid #F5CCA0;">

            <div class="mb-4">
                <label for="productName" class="form-label fs-5">Nama Produk</label>
                <input type="text" class="form-control p-2 rounded-3" name="name" id="productName"
                    placeholder="Nama Produk" value="{{ old('name', $product->name) }}">
            </div>

            <div class="mb-4">
                <label class="form-label fs-5">Kategori Produk</label>
                <div class="row">
                    @foreach ($categories->chunk(ceil($categories->count() / 2)) as $chunk)
                        <div class="col-md-6">
                            @foreach ($chunk as $category)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" id="category_{{ $category->id }}"
                                        name="categories[]" value="{{ $category->id }}"
                                        {{ $product->category_id == $category->id ? 'checked' : '' }}>
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
                    placeholder="Jumlah Stock" value="{{ old('stock', $product->stock) }}">
            </div>

            <div class="mb-4">
                <label for="price" class="form-label fs-5">Harga Satuan</label>
                <input type="text" class="form-control p-2 rounded-3" name="price" id="price"
                    placeholder="Harga Satuan" value="{{ old('price', $product->price) }}">
            </div>

            <div class="mb-4">
                <label for="description" class="form-label fs-5">Deskripsi Produk</label>
                <textarea class="form-control p-2 rounded-3" name="description" id="description" rows="4"
                    placeholder="Deskripsi Produk">{{ old('description', $product->description) }}</textarea>
            </div>

            <!-- ✅ Upload Gambar Utama -->
            <div class="mb-4">
                <label for="image" class="form-label fs-5">Gambar Utama Produk</label>
                <input type="file" class="form-control p-2 rounded-3" name="image" id="image" accept="image/*">
                @if ($product->image)
                    <div class="mt-2">
                        <img src="{{ asset($product->image) }}" alt="Gambar Utama Saat Ini"
                            class="img-fluid rounded" style="max-height: 150px;">
                    </div>
                @endif
            </div>

            <!-- ✅ Upload Thumbnail 1 -->
            <div class="mb-4">
                <label for="thumbnail_1" class="form-label fs-5">Thumbnail Produk 1</label>
                <input type="file" class="form-control p-2 rounded-3" name="thumbnails[]" id="thumbnail_1"
                    accept="image/*">
            </div>

            <!-- ✅ Upload Thumbnail 2 -->
            <div class="mb-4">
                <label for="thumbnail_2" class="form-label fs-5">Thumbnail Produk 2</label>
                <input type="file" class="form-control p-2 rounded-3" name="thumbnails[]" id="thumbnail_2"
                    accept="image/*">
            </div>

            @if ($product->images && $product->images->count())
                <div class="mb-4">
                    <label class="form-label fs-6">Thumbnail Saat Ini:</label>
                    <div class="d-flex gap-3">
                        @foreach ($product->images as $image)
                            <img src="{{ asset($image->image_path) }}" class="rounded border"
                                style="width: 120px; height: auto;">
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="d-flex justify-content-evenly mt-5 border gap-2">
                <a href="/admin" class="btn border px-4 py-2 w-100 rounded-3">Kembali ke Beranda</a>
                <button type="submit" class="btn text-white px-4 py-2 w-100 rounded-3"
                    style="background-color: #994D1C;">Perbarui Data Sekarang</button>
            </div>
        </form>
    </div>
@endsection
