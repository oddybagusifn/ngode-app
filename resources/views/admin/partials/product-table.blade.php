@if ($products->count() > 0)
    @foreach ($products as $product)
        <tr style="background-color: #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <td class="text-muted">{{ $product->product_code }}</td>
            <td>{{ $product->name }}</td>
            <td class="text-muted">{{ $product->category->name ?? '-' }}</td>
            <td class="fw-bold">{{ $product->stock }}</td>
            <td>Rp{{ number_format($product->price, 0, ',', '.') }}</td>
            <td>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.product.edit', $product->id) }}" class="btn btn-sm px-2"
                        style="border: 1px solid #F5CCA0; border-radius: 8px">Perbarui</a>
                    <form action="{{ route('admin.product.destroy', $product->id) }}" method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn text-white btn-sm px-3"
                            style="background-color: #994D1C; border-radius: 8px">Hapus</button>
                    </form>
                </div>
            </td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="6" class="text-center text-muted py-4">
            <em>Tidak ada data produk ditemukan.</em>
        </td>
    </tr>
@endif
