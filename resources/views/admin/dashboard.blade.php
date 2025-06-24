@section('page-title', 'Beranda')

@section('is-dashboard') @endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const perPageSelectLinks = document.querySelectorAll('.dropdown-menu a');
            const tableContainer = document.getElementById('productTableContainer');
            const dateFilter = document.getElementById('dateInput');

            function enablePaginationLinks() {
                const pagination = document.getElementById('pagination');

                // Hapus event listener sebelumnya (opsional jika pakai delegation)
                pagination?.removeEventListener('click', handlePaginationClick);

                function handlePaginationClick(e) {
                    const link = e.target.closest('a');

                    if (link && pagination.contains(link)) {
                        e.preventDefault();
                        const url = new URL(link.href);
                        const page = url.searchParams.get("page");

                        const search = searchInput?.value || '';
                        const date = dateFilter?.value || '';
                        const perPage = '{{ $perPage }}';

                        const queryString = new URLSearchParams({
                            query: search,
                            per_page: perPage,
                            date: date,
                            page: page
                        }).toString();

                        fetch(`/admin/products/filter?${queryString}`)
                            .then(response => response.text())
                            .then(html => {
                                tableContainer.innerHTML = html;
                                // Tidak perlu panggil ulang enablePaginationLinks karena pakai delegation
                            });
                    }
                }

                pagination?.addEventListener('click', handlePaginationClick);
            }

            function fetchData(params = {}) {
                const search = searchInput?.value || '';
                const date = dateFilter?.value || '';
                const perPage = params.perPage || '{{ $perPage }}';

                const queryString = new URLSearchParams({
                    query: search,
                    per_page: perPage,
                    date: date,
                }).toString();

                // Ubah URL di browser agar parameter tetap tersimpan
                const newUrl = `${window.location.pathname}?${queryString}`;
                window.history.replaceState({}, '', newUrl);

                fetch(`/admin/products/filter?${queryString}`)
                    .then(response => response.text())
                    .then(html => {
                        tableContainer.innerHTML = html;
                        enablePaginationLinks();
                    });
            }

            if (searchInput) {
                searchInput.addEventListener('input', () => fetchData());
            }

            if (dateFilter) {
                dateFilter.addEventListener('change', () => fetchData());
            }

            perPageSelectLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = new URL(this.href);
                    const perPage = url.searchParams.get("per_page");
                    fetchData({
                        perPage
                    });
                });
            });

            enablePaginationLinks(); // aktifkan untuk paginasi yang sudah muncul saat pertama kali
        });
    </script>
@endpush

@extends('layouts.admin')

@section('page-title', 'Beranda')

@section('admin-contents')
    <div class="header d-flex align-items-center gap-5">
        <div class="stock-header d-flex">
            <img src="img/stockProduct.svg" alt="">
            <div class="d-flex flex-column ms-3">
                <p class="m-0 text-secondary">Daftar Produk</p>
                <p class="m-0 pt-1 fs-5 fw-medium">Stock Produk</p>
            </div>
        </div>

        <div class="ms-5 d-flex align-items-center gap-5">
            <div class="data-search border p-0 m-0 d-flex align-items-center rounded px-2" style="height: 40px;">
                <img class="img-fluid me-2" style="width: 20px" src="/img/search-normal.svg" alt="Icon Pencarian">
                <input type="text" id="searchInput" class="form-control border-0 p-0 text-secondary"
                    placeholder="Pencarian" style="box-shadow: none;">
            </div>

            <div class="data-sort">
                <div class="dropdown">
                    <button class="btn border d-flex align-items-center justify-content-between px-3 py-2 rounded-3"
                        type="button" id="dropdownDataPerHalaman" data-bs-toggle="dropdown" aria-expanded="false"
                        style="width: 230px;">
                        <div class="d-flex align-items-center gap-2">
                            <img src="/img/candle-2.svg" alt="Filter" style="width: 20px;">
                            <span class="text-muted text-secondary">
                                {{ $perPage == 'all' ? 'Semua Data' : $perPage . ' Data per halaman' }}
                            </span>
                        </div>
                        <img src="/img/arrow-down.svg" alt="Filter" style="width: 20px;">
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownDataPerHalaman">
                        @php $options = [5, 10, 20, 'all']; @endphp
                        @foreach ($options as $option)
                            <li>
                                @php
                                    $currentPerPage = request()->get('per_page', 5);
                                @endphp
                                <a class="dropdown-item {{ $currentPerPage == $option ? 'active' : '' }}"
                                    href="{{ request()->fullUrlWithQuery(['per_page' => $option, 'page' => 1]) }}">

                                    {{ $option === 'all' ? 'Semua' : "$option Data per halaman" }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="data-date">
                <div class="position-relative" style="max-width: 240px;">
                    <input type="date" id="dateInput" class="form-control ps-5 pe-3 py-2 text-secondary"
                        style="font-size: 1rem;">
                    <img src="/img/calendar-search.svg" alt="Calendar"
                        style="width: 20px; position: absolute; top: 50%; left: 14px; transform: translateY(-50%); pointer-events: none;">
                </div>
            </div>
        </div>
    </div>

    <div class="data-table mt-5">
        <div class="table-responsive container-fluid px-0">
            <table class="table w-100 align-middle table-borderless"
                style="border-collapse: separate; border-spacing: 0 10px;">
                <thead>
                    <tr class="py-3">
                        <th class="py-3">ID. Barang</th>
                        <th class="py-3">Nama Produk</th>
                        <th class="py-3">Kategori Produk</th>
                        <th class="py-3">Jumlah Stock</th>
                        <th class="py-3">Harga Satuan</th>
                        <th class="py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody id="productTableContainer">
                    @include('admin.partials.product-table', ['products' => $products])
                </tbody>
            </table>

            {{-- Paginasi --}}
            <div class="d-flex justify-content-center mt-4" id="pagination">
                <ul class="pagination gap-2">
                    @if ($products->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            <nav>
                                <ul class="pagination gap-4">

                                    {{-- Previous Page Link --}}
                                    @if ($products->onFirstPage())
                                        <li class="page-item disabled">
                                            <span class="page-link rounded-3 border text-dark"
                                                style="border: 1px solid #F5CCA0;">&lt;</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link rounded-3 border text-dark"
                                                style="border: 1px solid #F5CCA0;"
                                                href="{{ $products->appends(request()->query())->previousPageUrl() }}">&lt;</a>
                                        </li>
                                    @endif

                                    {{-- First Page --}}
                                    <li class="page-item {{ $products->currentPage() == 1 ? 'active' : '' }}">
                                        <a class="page-link rounded-3 {{ $products->currentPage() == 1 ? 'text-white' : 'text-dark' }}"
                                            style="{{ $products->currentPage() == 1 ? 'background-color: #994D1C; border: none;' : 'border: 1px solid #F5CCA0;' }}"
                                            href="{{ $products->appends(request()->query())->url(1) }}">
                                            1
                                        </a>
                                    </li>

                                    {{-- Dots before current range --}}
                                    @if ($products->currentPage() > 3)
                                        <li class="page-item disabled">
                                            <span class="page-link bg-transparent border-0 text-dark">...</span>
                                        </li>
                                    @endif

                                    {{-- Pages around current page --}}
                                    @for ($i = max(2, $products->currentPage() - 1); $i <= min($products->lastPage() - 1, $products->currentPage() + 1); $i++)
                                        <li class="page-item {{ $products->currentPage() == $i ? 'active' : '' }}">
                                            <a class="page-link rounded-3 {{ $products->currentPage() == $i ? 'text-white' : 'text-dark' }}"
                                                style="{{ $products->currentPage() == $i ? 'background-color: #994D1C; border: none;' : 'border: 1px solid #F5CCA0;' }}"
                                                href="{{ $products->appends(request()->query())->url($i) }}">
                                                {{ $i }}
                                            </a>
                                        </li>
                                    @endfor

                                    {{-- Dots after current range --}}
                                    @if ($products->currentPage() < $products->lastPage() - 2)
                                        <li class="page-item disabled">
                                            <span class="page-link bg-transparent border-0 text-dark">...</span>
                                        </li>
                                    @endif

                                    {{-- Last Page --}}
                                    @if ($products->lastPage() > 1)
                                        <li
                                            class="page-item {{ $products->currentPage() == $products->lastPage() ? 'active' : '' }}">
                                            <a class="page-link rounded-3 {{ $products->currentPage() == $products->lastPage() ? 'text-white' : 'text-dark' }}"
                                                style="{{ $products->currentPage() == $products->lastPage() ? 'background-color: #994D1C; border: none;' : 'border: 1px solid #F5CCA0;' }}"
                                                href="{{ $products->appends(request()->query())->url($products->lastPage()) }}">
                                                {{ $products->lastPage() }}
                                            </a>
                                        </li>
                                    @endif

                                    {{-- Next Page --}}
                                    @if ($products->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link rounded-3 border text-dark"
                                                style="border: 1px solid #F5CCA0;"
                                                href="{{ $products->appends(request()->query())->nextPageUrl() }}">&gt;</a>
                                        </li>
                                    @else
                                        <li class="page-item disabled">
                                            <span class="page-link rounded-3 border text-dark"
                                                style="border: 1px solid #F5CCA0;">&gt;</span>
                                        </li>
                                    @endif

                                </ul>
                            </nav>
                        </div>
                    @endif
                </ul>
            </div>
        </div>
    </div>

    @endsection
