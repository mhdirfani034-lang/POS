@extends('layouts.app')

@section('title', 'Detail transaksi')
@section('heading', 'Detail transaksi')

@section('content')
    <div class="mx-auto max-w-3xl">
        <div class="mb-5 flex items-center justify-between"><a href="{{ route('orders.index') }}" class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">← Riwayat transaksi</a><button type="button" onclick="window.print()" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50 print:hidden">Cetak struk</button></div>
        <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-9">
            <div class="flex flex-col justify-between gap-5 border-b border-dashed border-slate-200 pb-6 sm:flex-row sm:items-start"><div><span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">TRANSAKSI BERHASIL</span><h2 class="mt-4 text-2xl font-bold text-slate-900">Struk penjualan</h2><p class="mt-1 font-mono text-sm text-slate-500">{{ $order->invoice_number }}</p></div><div class="text-left sm:text-right"><p class="text-sm font-semibold text-slate-800">{{ $order->created_at->format('d M Y') }}</p><p class="mt-1 text-sm text-slate-500">{{ $order->created_at->format('H:i') }} WIB</p></div></div>
            <div class="grid gap-4 border-b border-dashed border-slate-200 py-5 sm:grid-cols-2"><div><p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Pelanggan</p><p class="mt-1 text-sm font-semibold text-slate-800">{{ $order->customer_name ?: 'Pelanggan umum' }}</p></div><div class="sm:text-right"><p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Pembayaran</p><p class="mt-1 text-sm font-semibold capitalize text-slate-800">{{ $order->payment_method }}</p></div></div>
            <div class="divide-y divide-slate-100">
                @foreach ($order->items as $item)
                    <div class="flex justify-between gap-4 py-4"><div><p class="text-sm font-semibold text-slate-800">{{ $item->product_name }}</p><p class="mt-1 text-xs text-slate-500">{{ $item->product_sku }} · {{ $item->quantity }} × Rp {{ number_format($item->unit_price / 100, 2, ',', '.') }}</p></div><span class="shrink-0 text-sm font-semibold text-slate-800">Rp {{ number_format($item->line_total / 100, 2, ',', '.') }}</span></div>
                @endforeach
            </div>
            <div class="mt-3 border-t border-slate-200 pt-5"><div class="flex justify-between text-sm text-slate-500"><span>Subtotal</span><span>Rp {{ number_format($order->subtotal / 100, 2, ',', '.') }}</span></div><div class="mt-3 flex justify-between text-base font-bold text-slate-900"><span>Total</span><span>Rp {{ number_format($order->total / 100, 2, ',', '.') }}</span></div></div>
            <p class="mt-8 text-center text-xs text-slate-400">Terima kasih telah berbelanja.</p>
        </article>
    </div>
@endsection
