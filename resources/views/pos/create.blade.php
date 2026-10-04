@extends('layouts.app')

@section('title', 'Kasir')
@section('heading', 'Kasir')

@section('content')
    <div data-pos class="grid items-start gap-6 xl:grid-cols-[1fr_390px]">
        <section>
            <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                <div><p class="text-sm text-slate-500">Pilih produk untuk menambahkannya ke keranjang.</p><p class="mt-1 text-xs text-slate-400">{{ $products->count() }} produk siap dijual</p></div>
                <label class="relative block sm:w-72"><span class="sr-only">Cari produk</span><input data-product-search type="search" placeholder="Cari nama atau SKU…" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100"></label>
            </div>
            @if ($products->isEmpty())
                <div class="mt-6 rounded-2xl border border-dashed border-slate-300 bg-white px-5 py-16 text-center">
                    <p class="font-semibold text-slate-800">Belum ada produk siap dijual</p><p class="mt-2 text-sm text-slate-500">Tambahkan produk aktif dengan stok tersedia terlebih dahulu.</p><a href="{{ route('produk.create') }}" class="mt-4 inline-flex rounded-xl bg-emerald-500 px-4 py-2.5 text-sm font-semibold text-white">Tambah produk</a>
                </div>
            @else
                <div class="mt-5 grid gap-3 sm:grid-cols-2 2xl:grid-cols-3">
                    @foreach ($products as $product)
                        <article data-product-card data-search-text="{{ mb_strtolower($product->name.' '.$product->sku.' '.$product->category, 'UTF-8') }}" class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:border-emerald-200 hover:shadow-md">
                            <div class="flex items-start justify-between gap-2"><div class="min-w-0"><p class="truncate font-semibold text-slate-900">{{ $product->name }}</p><p class="mt-1 truncate font-mono text-[11px] text-slate-400">{{ $product->sku }}{{ $product->category ? ' · '.$product->category : '' }}</p></div><span class="shrink-0 rounded-lg bg-slate-100 px-2 py-1 text-[11px] font-semibold text-slate-500">Stok {{ $product->stock }}</span></div>
                            <div class="mt-5 flex items-center justify-between gap-3"><span class="text-sm font-bold text-emerald-700">Rp {{ number_format($product->priceInCents() / 100, 2, ',', '.') }}</span><button type="button" data-add-product data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}" data-product-price="{{ $product->priceInCents() }}" data-product-stock="{{ $product->stock }}" class="rounded-xl bg-slate-900 px-3.5 py-2 text-xs font-semibold text-white hover:bg-emerald-600">＋ Tambah</button></div>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>

        <form method="POST" action="{{ route('pos.store') }}" data-cart-form class="sticky top-5 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            @csrf
            <div class="flex items-center justify-between"><div><h2 class="font-bold text-slate-900">Keranjang</h2><p class="mt-1 text-xs text-slate-500"><span data-cart-count>0</span> item dipilih</p></div><span class="grid h-10 w-10 place-items-center rounded-xl bg-emerald-50 text-sm font-bold text-emerald-700">POS</span></div>
            <div class="mt-3 max-h-[360px] overflow-y-auto">
                <div data-cart-empty class="rounded-xl bg-slate-50 px-4 py-8 text-center text-sm text-slate-500">Keranjang masih kosong.<br>Pilih produk untuk memulai.</div>
                <div data-cart-rows></div>
            </div>
            <div data-cart-inputs></div>
            <div class="mt-4 space-y-4 border-t border-slate-100 pt-4">
                <div><label for="customer_id" class="field-label">Pelanggan</label><select id="customer_id" name="customer_id" class="field-input"><option value="">Pelanggan umum</option>@foreach ($customers as $customer)<option value="{{ $customer->id }}" @selected(old('customer_id') == $customer->id)>{{ $customer->name }}{{ $customer->phone ? ' · '.$customer->phone : '' }}</option>@endforeach</select></div>
                <div><label for="payment_method" class="field-label">Metode pembayaran</label><select id="payment_method" name="payment_method" required class="field-input">@foreach (['tunai' => 'Tunai', 'qris' => 'QRIS', 'transfer' => 'Transfer bank', 'kartu' => 'Kartu debit/kredit'] as $value => $label)<option value="{{ $value }}" @selected(old('payment_method', 'tunai') === $value)>{{ $label }}</option>@endforeach</select></div>
                <div class="flex items-center justify-between border-t border-slate-100 pt-4"><span class="text-sm font-semibold text-slate-600">Total pembayaran</span><span data-cart-total class="text-xl font-bold text-slate-900">Rp 0</span></div>
                <button data-submit-order type="submit" @disabled($products->isEmpty()) class="w-full rounded-xl bg-emerald-500 px-4 py-3.5 text-sm font-bold text-white transition hover:bg-emerald-600 disabled:cursor-not-allowed disabled:bg-slate-300">Simpan transaksi</button>
                <p class="text-center text-[11px] leading-4 text-slate-400">Harga dan stok diverifikasi kembali saat transaksi disimpan.</p>
            </div>
        </form>
    </div>
@endsection
