@extends('layouts.app')

@section('title', 'Produk')
@section('heading', 'Katalog produk')

@section('content')
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div><p class="text-sm text-slate-500">Kelola katalog, harga, dan ketersediaan stok.</p><p class="mt-2 text-xs font-medium text-slate-400">{{ $products->total() }} produk terdaftar</p></div>
        <a href="{{ route('produk.create') }}" class="inline-flex justify-center rounded-xl bg-emerald-500 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-600">＋ Tambah produk</a>
    </div>
    <form method="GET" class="mt-6 flex max-w-xl gap-2">
        <label for="q" class="sr-only">Cari produk</label>
        <input id="q" name="q" value="{{ $search }}" placeholder="Cari nama, SKU, atau kategori…" class="min-w-0 flex-1 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
        <button class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold hover:bg-slate-50">Cari</button>
    </form>
    <div class="mt-5 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[720px] text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500"><tr><th class="px-5 py-4 font-semibold">Produk</th><th class="px-5 py-4 font-semibold">SKU</th><th class="px-5 py-4 font-semibold">Kategori</th><th class="px-5 py-4 font-semibold">Harga</th><th class="px-5 py-4 font-semibold">Stok</th><th class="px-5 py-4 text-right font-semibold">Aksi</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($products as $product)
                        <tr class="hover:bg-slate-50/70">
                            <td class="px-5 py-4"><div class="font-semibold text-slate-900">{{ $product->name }}</div><span @class(['mt-1 inline-flex rounded-full px-2 py-0.5 text-[10px] font-bold', 'bg-emerald-50 text-emerald-700' => $product->is_active, 'bg-slate-100 text-slate-500' => ! $product->is_active])>{{ $product->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                            <td class="px-5 py-4 font-mono text-xs text-slate-500">{{ $product->sku }}</td><td class="px-5 py-4 text-slate-500">{{ $product->category ?: '—' }}</td>
                            <td class="px-5 py-4 font-semibold text-slate-800">Rp {{ number_format($product->priceInCents() / 100, 2, ',', '.') }}</td>
                            <td class="px-5 py-4"><span @class(['font-semibold', 'text-amber-700' => $product->stock <= 5, 'text-slate-700' => $product->stock > 5])>{{ $product->stock }}</span></td>
                            <td class="px-5 py-4"><div class="flex justify-end gap-2"><a href="{{ route('produk.edit', $product) }}" class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold hover:bg-slate-50">Ubah</a><form method="POST" action="{{ route('produk.destroy', $product) }}" onsubmit="return confirm('Hapus produk {{ $product->name }}?')">@csrf @method('DELETE')<button class="rounded-lg border border-rose-200 px-3 py-1.5 text-xs font-semibold text-rose-700 hover:bg-rose-50">Hapus</button></form></div></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-14 text-center"><p class="font-semibold text-slate-700">{{ $search ? 'Produk tidak ditemukan' : 'Katalog masih kosong' }}</p><p class="mt-1 text-sm text-slate-500">Tambahkan produk untuk mulai mencatat penjualan.</p></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($products->hasPages())<div class="border-t border-slate-100 px-5 py-4">{{ $products->links() }}</div>@endif
    </div>
@endsection
