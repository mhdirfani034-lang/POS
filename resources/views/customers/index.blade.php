@extends('layouts.app')

@section('title', 'Pelanggan')
@section('heading', 'Pelanggan')

@section('content')
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div><p class="text-sm text-slate-500">Simpan informasi pelanggan dan temukan kembali dengan cepat.</p><p class="mt-2 text-xs font-medium text-slate-400">{{ $customers->total() }} pelanggan terdaftar</p></div>
        <a href="{{ route('pelanggan.create') }}" class="inline-flex justify-center rounded-xl bg-emerald-500 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-600">＋ Tambah pelanggan</a>
    </div>
    <form method="GET" class="mt-6 flex max-w-xl gap-2"><label for="q" class="sr-only">Cari pelanggan</label><input id="q" name="q" value="{{ $search }}" placeholder="Cari nama, email, atau nomor telepon…" class="min-w-0 flex-1 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100"><button class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold hover:bg-slate-50">Cari</button></form>
    <div class="mt-5 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto"><table class="w-full min-w-[720px] text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500"><tr><th class="px-5 py-4 font-semibold">Pelanggan</th><th class="px-5 py-4 font-semibold">Kontak</th><th class="px-5 py-4 font-semibold">Alamat</th><th class="px-5 py-4 font-semibold">Transaksi</th><th class="px-5 py-4 text-right font-semibold">Aksi</th></tr></thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($customers as $customer)
                    <tr class="hover:bg-slate-50/70"><td class="px-5 py-4"><div class="font-semibold text-slate-900">{{ $customer->name }}</div><div class="mt-1 text-xs text-slate-400">Pelanggan sejak {{ $customer->created_at->format('d M Y') }}</div></td><td class="px-5 py-4"><div class="text-slate-700">{{ $customer->phone ?: '—' }}</div><div class="mt-1 text-xs text-slate-400">{{ $customer->email ?: '' }}</div></td><td class="max-w-xs truncate px-5 py-4 text-slate-500">{{ $customer->address ?: '—' }}</td><td class="px-5 py-4"><span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold">{{ $customer->orders_count }}</span></td><td class="px-5 py-4"><div class="flex justify-end gap-2"><a href="{{ route('pelanggan.edit', $customer) }}" class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold hover:bg-slate-50">Ubah</a><form method="POST" action="{{ route('pelanggan.destroy', $customer) }}" onsubmit="return confirm('Hapus pelanggan {{ $customer->name }}?')">@csrf @method('DELETE')<button class="rounded-lg border border-rose-200 px-3 py-1.5 text-xs font-semibold text-rose-700 hover:bg-rose-50">Hapus</button></form></div></td></tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-14 text-center"><p class="font-semibold text-slate-700">{{ $search ? 'Pelanggan tidak ditemukan' : 'Belum ada pelanggan' }}</p><p class="mt-1 text-sm text-slate-500">Tambahkan kontak pelanggan untuk mencatat hubungan penjualan.</p></td></tr>
                @endforelse
            </tbody>
        </table></div>
        @if ($customers->hasPages())<div class="border-t border-slate-100 px-5 py-4">{{ $customers->links() }}</div>@endif
    </div>
@endsection
