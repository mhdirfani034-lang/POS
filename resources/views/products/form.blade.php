@extends('layouts.app')

@section('title', $product->exists ? 'Ubah produk' : 'Tambah produk')
@section('heading', $product->exists ? 'Ubah produk' : 'Tambah produk')

@section('content')
    <div class="max-w-3xl">
        <a href="{{ route('produk.index') }}" class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">← Kembali ke produk</a>
        <form method="POST" action="{{ $product->exists ? route('produk.update', $product) : route('produk.store') }}" class="mt-5 space-y-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
            @csrf
            @if ($product->exists) @method('PUT') @endif
            <div class="grid gap-5 sm:grid-cols-2">
                <div><label for="name" class="field-label">Nama produk *</label><input id="name" name="name" required maxlength="160" value="{{ old('name', $product->name) }}" class="field-input" placeholder="Contoh: Kopi susu"></div>
                <div><label for="sku" class="field-label">SKU *</label><input id="sku" name="sku" required maxlength="64" value="{{ old('sku', $product->sku) }}" class="field-input" placeholder="Contoh: KOP-001"></div>
                <div><label for="category" class="field-label">Kategori</label><input id="category" name="category" maxlength="100" value="{{ old('category', $product->category) }}" class="field-input" placeholder="Contoh: Minuman"></div>
                <div><label for="price" class="field-label">Harga (Rp) *</label><input id="price" name="price" type="number" required min="0" max="9999999999.99" step="0.01" value="{{ old('price', $product->price) }}" class="field-input" placeholder="18000"></div>
                <div><label for="stock" class="field-label">Stok awal *</label><input id="stock" name="stock" type="number" required min="0" step="1" value="{{ old('stock', $product->stock ?? 0) }}" class="field-input"></div>
                <div class="flex items-center gap-3 self-end rounded-xl bg-slate-50 p-3"><input id="is_active" name="is_active" type="checkbox" value="1" @checked(old('is_active', $product->exists ? $product->is_active : true)) class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"><label for="is_active" class="text-sm font-medium text-slate-700">Produk aktif dan dapat dijual</label></div>
                <div class="sm:col-span-2"><label for="description" class="field-label">Deskripsi</label><textarea id="description" name="description" rows="3" maxlength="2000" class="field-input" placeholder="Informasi singkat produk (opsional)">{{ old('description', $product->description) }}</textarea></div>
            </div>
            <div class="flex justify-end gap-3 border-t border-slate-100 pt-5"><a href="{{ route('produk.index') }}" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold hover:bg-slate-50">Batal</a><button class="rounded-xl bg-emerald-500 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-600">Simpan produk</button></div>
        </form>
    </div>
@endsection
