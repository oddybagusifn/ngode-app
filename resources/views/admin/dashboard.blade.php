@section('page-title', 'Beranda')

<x-admin-layouts>
    <div class="header d-flex  align-items-center gap-5">
        <div class="stock-header d-flex">
            <img src="img/stockProduct.svg" alt="">
            <div class=" d-flex  flex-column ms-3">
                <p class="m-0 text-secondary">Daftar Produk</p>
                <p class="m-0 pt-1 fs-5 fw-medium">Stock Produk</p>
            </div>
        </div>

        <div class="ms-5 d-flex align-items-center gap-5">
            <div class="data-search border p-0 m-0 d-flex align-items-center rounded px-2" style="height: 40px;">
                <img class="img-fluid me-2" style="width: 20px" src="img/search-normal.svg" alt="Icon Pencarian">
                <input type="text" class="form-control border-0 p-0 text-secondary" placeholder="Pencarian"
                    style="box-shadow: none;">
            </div>

            <div class="data-sort">
                <div class="dropdown">
                    <button class="btn border d-flex align-items-center justify-content-between px-3 py-2 rounded-3"
                        type="button" id="dropdownDataPerHalaman" data-bs-toggle="dropdown" aria-expanded="false"
                        style="width: 230px;">
                        <div class="d-flex align-items-center gap-2">
                            <img src="img/candle-2.svg" alt="Filter" style="width: 20px;">
                            <span class="text-muted text-secondary">5 Data per halaman</span>
                        </div>
                        <img src="img/arrow-down.svg" alt="Filter" style="width: 20px;">

                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownDataPerHalaman">
                        <li><a class="dropdown-item" href="#">5 Data per halaman</a></li>
                        <li><a class="dropdown-item" href="#">10 Data per halaman</a></li>
                        <li><a class="dropdown-item" href="#">20 Data per halaman</a></li>
                        <li><a class="dropdown-item" href="#">Semua</a></li>
                    </ul>
                </div>
            </div>

            <div class="data-date">
                <div class="d-flex align-items-center justify-content-start border rounded-3 ps-3 pe-5 py-2"
                    style=" max-width: 240px;">
                    <img src="img/calendar-search.svg" alt="Calendar" style="width: 20px; " class="me-2">
                    <input type="date" class="form-control border-0 p-0 text-secondary" placeholder="dd/mm/yyyy"
                        style="box-shadow: none; font-size: 1rem;" onfocus="this.showPicker && this.showPicker()">
                </div>
            </div>
        </div>
    </div>

    <div class="data-table mt-5">
        <div class="table-responsive container-fluid px-0">
            <!-- Table -->
            <table class="table w-100 align-middle table-borderless"
                style="border-collapse: separate; border-spacing: 0 10px;">
                <thead class="">
                    <tr class="py-3">
                        <th class="py-3">ID. Barang</th>
                        <th class="py-3">Nama Produk</th>
                        <th class="py-3">Kategori Produk</th>
                        <th class="py-3">Jumlah Stock</th>
                        <th class="py-3">Harga Satuan</th>
                        <th class="py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr style="background-color: #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                            <td class="text-muted">{{ $product->product_code }}</td>
                            <td>{{ $product->name }}</td>
                            <td class="text-muted">Kerajinan Tangan</td>
                            <td class="fw-bold">{{ $product->stock }}</td>
                            <td>Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm px-2"
                                        style="border: 1px solid #F5CCA0; border-radius: 8px">Perbarui</button>
                                    <button class="btn text-white btn-sm px-3"
                                        style="background-color: #994D1C; border-radius: 8px">Hapus</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                <nav>
                    <ul class="pagination gap-2">

                        <!-- Previous -->
                        <li class="page-item">
                            <a class="page-link rounded-3 border text-dark" style="border: 1px solid #F5CCA0;"
                                href="#">&lt;</a>
                        </li>

                        <!-- Pages -->
                        <li class="page-item">
                            <a class="page-link rounded-3 text-white" style="background-color: #994D1C; border: none;"
                                href="#">1</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link rounded-3 border text-dark" style="border: 1px solid #F5CCA0;"
                                href="#">2</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link rounded-3 border text-dark" style="border: 1px solid #F5CCA0;"
                                href="#">3</a>
                        </li>
                        <li class="page-item disabled">
                            <span class="page-link bg-transparent border-0 text-dark">...</span>
                        </li>
                        <li class="page-item">
                            <a class="page-link rounded-3 border text-dark" style="border: 1px solid #F5CCA0;"
                                href="#">8</a>
                        </li>

                        <!-- Next -->
                        <li class="page-item">
                            <a class="page-link rounded-3 border text-dark" style="border: 1px solid #F5CCA0;"
                                href="#">&gt;</a>
                        </li>

                    </ul>
                </nav>
            </div>

        </div>
    </div>
</x-admin-layouts>
