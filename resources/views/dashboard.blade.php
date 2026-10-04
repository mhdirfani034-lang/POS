@extends('layouts.app')

@section('title', 'Beranda')
@section('heading', 'Ringkasan')

@section('content')
    <section class="overflow-hidden rounded-3xl bg-gradient-to-br from-[#1d3150] via-[#203e5b] to-emerald-700 px-6 py-8 text-white shadow-sm sm:px-9 sm:py-10">
        <div class="max-w-2xl">
            <p class="text-sm font-semibold text-emerald-200">Ringkasan operasional</p>
            <h2 class="mt-2 text-3xl font-bold tracking-tight sm:text-4xl">Selamat datang di KasirKita</h2>
            <p class="mt-3 max-w-xl text-sm leading-6 text-slate-200">Catat penjualan, pantau produk, dan jaga hubungan baik dengan pelanggan dalam satu tempat.</p>
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('pos.create') }}" class="rounded-xl bg-emerald-400 px-5 py-3 text-sm font-bold text-slate-950 hover:bg-emerald-300">Mulai transaksi <span aria-hidden="true">→</span></a>
                <a href="{{ route('produk.create') }}" class="rounded-xl border border-white/25 px-5 py-3 text-sm font-semibold text-white hover:bg-white/10">Tambah produk</a>
            </div>
        </div>
    </section>

    <section class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4" aria-label="Statistik">
        @foreach ([
            ['Penjualan hari ini', 'Rp '.number_format($todayRevenue / 100, 2, ',', '.'), $todayOrders.' transaksi'],
            ['Total produk', number_format($productCount, 0, ',', '.'), 'produk terdaftar'],
            ['Pelanggan', number_format($customerCount, 0, ',', '.'), 'pelanggan terdaftar'],
            ['Transaksi hari ini', number_format($todayOrders, 0, ',', '.'), 'transaksi tercatat'],
        ] as [$label, $value, $note])
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">{{ $label }}</p>
                <p class="mt-3 text-2xl font-bold tracking-tight text-slate-900">{{ $value }}</p>
                <p class="mt-1 text-xs text-slate-400">{{ $note }}</p>
            </article>
        @endforeach
    </section>

    <div class="mt-7 grid gap-6 xl:grid-cols-[1.4fr_1fr]">
        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="flex items-center justify-between">
                <div><h2 class="font-bold text-slate-900">Transaksi terbaru</h2><p class="mt-1 text-sm text-slate-500">Aktivitas penjualan terkini</p></div>
                <a href="{{ route('orders.index') }}" class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">Lihat semua →</a>
            </div>
            <div class="mt-5 divide-y divide-slate-100">
                @forelse ($recentOrders as $order)
                    <a href="{{ route('orders.show', $order) }}" class="flex items-center justify-between gap-4 py-4 first:pt-0 last:pb-0">
                        <div class="min-w-0"><p class="truncate text-sm font-semibold text-slate-800">{{ $order->invoice_number }}</p><p class="mt-1 truncate text-xs text-slate-500">{{ $order->customer_name ?: 'Pelanggan umum' }} · {{ $order->created_at->format('d M, H:i') }}</p></div>
                        <span class="shrink-0 text-sm font-bold text-slate-900">Rp {{ number_format($order->total / 100, 2, ',', '.') }}</span>
                    </a>
                @empty
                    <div class="rounded-xl bg-slate-50 px-4 py-8 text-center text-sm text-slate-500">Belum ada transaksi. Penjualan pertama Anda dimulai dari menu kasir.</div>
                @endforelse
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="flex items-center justify-between">
                <div><h2 class="font-bold text-slate-900">Stok menipis</h2><p class="mt-1 text-sm text-slate-500">Produk dengan stok 5 atau kurang</p></div>
                <a href="{{ route('produk.index') }}" class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">Kelola →</a>
            </div>
            <div class="mt-5 space-y-3">
                @forelse ($lowStockProducts as $product)
                    <div class="flex items-center justify-between gap-4 rounded-xl bg-amber-50/80 px-4 py-3">
                        <div class="min-w-0"><p class="truncate text-sm font-semibold text-slate-800">{{ $product->name }}</p><p class="mt-1 text-xs text-slate-500">{{ $product->sku }}</p></div>
                        <span class="shrink-0 rounded-lg bg-amber-100 px-2.5 py-1 text-xs font-bold text-amber-800">{{ $product->stock }} tersisa</span>
                    </div>
                @empty
                    <div class="rounded-xl bg-emerald-50 px-4 py-8 text-center text-sm text-emerald-800">Semua produk aktif memiliki stok yang cukup.</div>
                @endforelse
            </div>
        </section>
    </div>
@endsection
