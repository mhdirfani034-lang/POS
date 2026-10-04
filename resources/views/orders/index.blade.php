@extends('layouts.app')

@section('title', 'Transaksi')
@section('heading', 'Riwayat transaksi')

@section('content')
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end"><div><p class="text-sm text-slate-500">Semua transaksi penjualan yang sudah tercatat.</p><p class="mt-2 text-xs font-medium text-slate-400">{{ $orders->total() }} transaksi</p></div><a href="{{ route('pos.create') }}" class="inline-flex justify-center rounded-xl bg-emerald-500 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-600">＋ Transaksi baru</a></div>
    <form method="GET" class="mt-6 flex max-w-xl gap-2"><label for="q" class="sr-only">Cari transaksi</label><input id="q" name="q" value="{{ $search }}" placeholder="Cari nomor invoice atau nama pelanggan…" class="min-w-0 flex-1 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100"><button class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold hover:bg-slate-50">Cari</button></form>
    <div class="mt-5 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"><div class="overflow-x-auto"><table class="w-full min-w-[700px] text-left text-sm">
        <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500"><tr><th class="px-5 py-4 font-semibold">Invoice</th><th class="px-5 py-4 font-semibold">Pelanggan</th><th class="px-5 py-4 font-semibold">Tanggal</th><th class="px-5 py-4 font-semibold">Pembayaran</th><th class="px-5 py-4 text-right font-semibold">Total</th><th class="px-5 py-4"></th></tr></thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($orders as $order)
                <tr class="hover:bg-slate-50/70"><td class="px-5 py-4 font-mono text-xs font-semibold text-slate-800">{{ $order->invoice_number }}</td><td class="px-5 py-4">{{ $order->customer_name ?: 'Pelanggan umum' }}</td><td class="px-5 py-4 text-slate-500">{{ $order->created_at->format('d M Y, H:i') }}</td><td class="px-5 py-4 capitalize text-slate-500">{{ $order->payment_method }}</td><td class="px-5 py-4 text-right font-bold">Rp {{ number_format($order->total / 100, 2, ',', '.') }}</td><td class="px-5 py-4 text-right"><a href="{{ route('orders.show', $order) }}" class="font-semibold text-emerald-700 hover:text-emerald-800">Detail →</a></td></tr>
            @empty
                <tr><td colspan="6" class="px-5 py-14 text-center"><p class="font-semibold text-slate-700">{{ $search ? 'Transaksi tidak ditemukan' : 'Belum ada transaksi' }}</p><a href="{{ route('pos.create') }}" class="mt-2 inline-block text-sm font-semibold text-emerald-700">Catat penjualan pertama →</a></td></tr>
            @endforelse
        </tbody>
    </table></div>@if ($orders->hasPages())<div class="border-t border-slate-100 px-5 py-4">{{ $orders->links() }}</div>@endif</div>
@endsection
