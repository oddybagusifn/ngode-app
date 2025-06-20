@extends('layouts.user')



@section('user-content')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const provinsiSelect = document.getElementById('provinsi');
            const kabupatenSelect = document.getElementById('kabupaten');
            const kecamatanSelect = document.getElementById('kecamatan');
            const kelurahanSelect = document.getElementById('kelurahan');

            // Load kabupaten saat provinsi dipilih
            provinsiSelect.addEventListener('change', function() {
                const provId = this.value;
                fetch(`/checkout/kabupaten?provinsi_id=${provId}`)
                    .then(res => res.json())
                    .then(data => {
                        kabupatenSelect.innerHTML =
                            '<option selected disabled>Pilih Kabupaten/Kota</option>';
                        data.forEach(city => {
                            kabupatenSelect.innerHTML +=
                                `<option value="${city.id}">${city.name}</option>`;
                        });
                        kecamatanSelect.innerHTML =
                            '<option selected disabled>Pilih Kecamatan</option>';
                        kelurahanSelect.innerHTML =
                            '<option selected disabled>Pilih Kelurahan</option>';
                    })
                    .catch(err => console.error('Gagal memuat kabupaten:', err));
            });

            // Load kecamatan saat kabupaten dipilih
            kabupatenSelect.addEventListener('change', function() {
                const kabId = this.value;
                fetch(`/checkout/kecamatan?kabupaten_id=${kabId}`)
                    .then(res => res.json())
                    .then(data => {
                        kecamatanSelect.innerHTML =
                            '<option selected disabled>Pilih Kecamatan</option>';
                        data.forEach(kec => {
                            kecamatanSelect.innerHTML +=
                                `<option value="${kec.id}">${kec.name}</option>`;
                        });
                        kelurahanSelect.innerHTML =
                            '<option selected disabled>Pilih Kelurahan</option>';
                    })
                    .catch(err => console.error('Gagal memuat kecamatan:', err));
            });

            // Load kelurahan saat kecamatan dipilih
            kecamatanSelect.addEventListener('change', function() {
                const kecId = this.value;
                fetch(`/checkout/kelurahan?kecamatan_id=${kecId}`)
                    .then(res => res.json())
                    .then(data => {
                        kelurahanSelect.innerHTML =
                            '<option selected disabled>Pilih Kelurahan</option>';
                        data.forEach(kel => {
                            kelurahanSelect.innerHTML +=
                                `<option value="${kel.id}">${kel.name}</option>`;
                        });
                    })
                    .catch(err => console.error('Gagal memuat kelurahan:', err));
            });

            // Toggle alamat dan kurir section
            const antarRadio = document.getElementById("antar");
            const tokoRadio = document.getElementById("toko");
            const alamatSection = document.getElementById("alamatLengkapSection");
            const kurirSection = document.getElementById("courierOptions");

            function toggleSections() {
                const isAntar = antarRadio.checked;
                alamatSection.style.display = isAntar ? "block" : "none";
                kurirSection.style.display = isAntar ? "block" : "none";
            }

            if (antarRadio && tokoRadio) {
                antarRadio.addEventListener("change", toggleSections);
                tokoRadio.addEventListener("change", toggleSections);
                toggleSections(); // Inisialisasi saat load
            }
        });
    </script>


    <div class="container-fluid py-5">
        <form method="POST" action="{{ route('checkout.store') }}">
            @csrf
            <div class="row">
                <!-- Informasi Checkout -->
                <div class="col-lg-9">
                    <!-- Informasi Alamat Pengantaran -->
                    <div class="border rounded-4 p-4 mb-4 shadow-sm">
                        <div class="d-flex justify-content-between mb-3">
                            <h6 class="fw-medium fs-3 ">Informasi Alamat Pengantaran</h6>
                            <span class="text-secondary">Langkah 1 dari 3</span>
                        </div>
                        <p class="text-muted small">Mohon lengkapi informasi pengiriman dengan akurat dan sesuai</p>
                        <div class="row gy-5 mt-2">
                            <div class="col-md-6">
                                <input name="person_name" type="text" class="form-control" placeholder="Nama Lengkap">
                            </div>
                            <div class="col-md-6">
                                <input name="phone" type="text" class="form-control" placeholder="Nomor Telepon">
                            </div>

                            <div class="mt-4" id="alamatLengkapSection">
                                <div class="row gy-5">
                                    <div class="col-md-6">
                                        <input name="address" type="text" class="form-control"
                                            placeholder="Alamat Lengkap">
                                    </div>
                                    <div class="col-md-6">
                                        <input name="postal_code" type="text" class="form-control"
                                            placeholder="Kode Pos">
                                    </div>
                                </div>

                                <div class="row gy-3 mt-3">
                                    <div class="col-md-6">
                                        <select class="form-select" name="province" id="provinsi">
                                            <option selected disabled>Pilih Provinsi</option>
                                            @foreach ($provinces as $province)
                                                <option value="{{ $province['id'] }}">{{ $province['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <select id="kabupaten" name="city" class="form-select">
                                            <option selected disabled>Pilih Kabupaten/Kota</option>
                                        </select>

                                    </div>
                                    <div class="col-md-6">
                                        <select id="kecamatan" name="subdistrict" class="form-select mt-3" name="kecamatan">
                                            <option selected disabled>Pilih Kecamatan</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <select id="kelurahan" class="form-select mt-3" name="village">
                                            <option selected disabled>Pilih Kelurahan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 mt-4">
                                    <input type="text" class="form-control"
                                        placeholder="Detail Lainnya (Cth: Blok / Unit No. / Patokan)">
                                </div>
                            </div>


                        </div>
                    </div>

                    <!-- Opsi Pengiriman -->
                    <div class="border rounded-4 p-4 mb-4 shadow-sm">
                        <div class="d-flex justify-content-between mb-3">
                            <h6 class="fw-bold">Opsi Pengiriman</h6>
                            <span class="text-muted">Langkah 2 dari 3</span>
                        </div>
                        <p class="text-muted small">Mohon pilih opsi layanan pengiriman Anda</p>
                        <div class="mt-3" id="courierOptions" style="display: none;">
                            <select class="form-select" id="courier" name="courier">
                                <option selected disabled>Pilih Kurir</option>
                                @foreach ($couriers as $courier)
                                    <option value="{{ $courier['code'] }}">{{ $courier['description'] }}</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <div class="form-check border p-3 rounded">
                                    <input class="form-check-input" type="radio" name="pengiriman" id="antar"
                                        value="antar" checked>
                                    <label class="form-check-label" for="antar">
                                        Antar ke Alamat<br><small class="text-muted">Mulai dari Rp0</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check border p-3 rounded">
                                    <input class="form-check-input" type="radio" name="pengiriman" id="toko"
                                        value="toko">
                                    <label class="form-check-label" for="toko">
                                        Ambil di Toko<br><small class="text-muted">Gratis</small>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Opsi Pembayaran -->
                    <div class="border rounded-4 p-4 mb-4 shadow-sm">
                        <div class="d-flex justify-content-between mb-3">
                            <h6 class="fw-bold">Opsi Pembayaran</h6>
                            <span class="text-muted">Langkah 3 dari 3</span>
                        </div>
                        <p class="text-muted small">Mohon pilih opsi pembayaran Anda dan pastikan data yang dimasukkan telah
                            benar</p>
                        <div class="row g-3 mt-2">
                            <div class="col-md-4">
                                <div class="form-check border p-3 rounded">
                                    <input class="form-check-input" type="radio" name="pembayaran" id="cod">
                                    <label class="form-check-label" for="cod">Bayar di Tempat</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check border p-3 rounded">
                                    <input class="form-check-input" type="radio" name="pembayaran" id="kartu">
                                    <label class="form-check-label" for="kartu">Kartu Kredit/Debit</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check border p-3 rounded">
                                    <input class="form-check-input" type="radio" name="pembayaran" id="qris">
                                    <label class="form-check-label" for="qris">QRIS</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ringkasan Produk -->
                <div class="col-lg-3">
                    <div class=" rounded-4 p-4 d-flex flex-column gap-2" style="border: 2px solid #994D1C">
                        <h6 class="fw-bold">Ringkasan Produk</h6>
                        <p class="text-muted small">Mohon pastikan produk yang Anda pilih sesuai dengan keinginan Anda</p>
                        <hr>

                        @foreach ($cartItems as $item)
                            <input type="hidden" name="products[{{ $loop->index }}][product_id]"
                                value="{{ $item->product->id }}">
                            <input type="hidden" name="products[{{ $loop->index }}][name]"
                                value="{{ $item->product->name }}">
                            <input type="hidden" name="products[{{ $loop->index }}][price]"
                                value="{{ $item->price }}">
                            <input type="hidden" name="products[{{ $loop->index }}][quantity]"
                                value="{{ $item->quantity }}">
                            <input type="hidden" name="products[{{ $loop->index }}][size]"
                                value="{{ $item->size }}">
                                <input type="hidden" name="total_price" value="{{ $totalHarga }}">
                        @endforeach

                        @php $total = 0; @endphp
                        @foreach ($cartItems as $item)
                            <div class="d-flex mb-3">
                                <img src="{{ asset($item->image) }}" alt="{{ $item->product->name }}"
                                    class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                <div>
                                    <div class="fw-semibold">{{ $item->product->name }}</div>
                                    <small class="text-muted">{{ $item->quantity }}x - {{ $item->size }}</small>
                                </div>
                            </div>
                            @php $total += $item->price * $item->quantity; @endphp
                        @endforeach

                        <hr>
                        <div class="d-flex justify-content-between">
                            <span class="text-secondary">Harga Produk</span>
                            <span>Rp{{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-secondary">Biaya Kemasan</span>
                            <span>Rp20.000</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-secondary">Biaya Pengiriman</span>
                            <span>Rp25.000</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-secondary">Biaya Layanan</span>
                            <span>Rp5.000</span>
                        </div>
                        <div class="d-flex justify-content-between text-success">
                            <span class="text-secondary"><i class="fa fa-check-circle" style="color: #994D1C"></i>
                                Diskon</span>
                            <span>- Rp20.000</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold">
                            <span>Total Harga</span>
                            <span>Rp{{ number_format($totalHarga, 0, ',', '.') }}</span>
                        </div>
                        <button type="submit" class="btn btn-secondary w-100 mt-3 rounded-pill">Beli Sekarang</button>
                    </div>
                </div>

            </div>
        </form>
    </div>
@endsection
