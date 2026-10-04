<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Beranda') · KasirKita</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f5f7fb] text-slate-800 antialiased">
    <div class="min-h-screen lg:flex">
        <aside class="flex w-full flex-col bg-[#17243a] text-white lg:min-h-screen lg:w-64 lg:shrink-0">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-6 py-6">
                <span class="grid h-11 w-11 place-items-center rounded-2xl bg-emerald-400 text-lg font-black text-[#17243a]">K</span>
                <span><span class="block text-lg font-bold tracking-tight">KasirKita</span><span class="text-xs text-slate-400">POS & pelanggan</span></span>
            </a>
            <nav class="flex gap-1 overflow-x-auto px-3 pb-4 lg:flex-col" aria-label="Navigasi utama">
                @php($nav = [
                    ['dashboard', 'Beranda', 'dashboard'],
                    ['pos.create', 'Kasir', 'kasir'],
                    ['orders.index', 'Transaksi', 'transaksi'],
                    ['produk.index', 'Produk', 'produk.*'],
                    ['pelanggan.index', 'Pelanggan', 'pelanggan.*'],
                ])
                @foreach ($nav as [$route, $label, $active])
                    <a href="{{ route($route) }}" @class([
                        'flex shrink-0 items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium transition',
                        'bg-white/10 text-white' => request()->routeIs($active),
                        'text-slate-400 hover:bg-white/5 hover:text-white' => ! request()->routeIs($active),
                    ])>
                        <span class="grid h-6 w-6 place-items-center rounded-lg bg-white/10 text-[10px] font-bold">{{ mb_substr($label, 0, 1) }}</span>
                        {{ $label }}
                    </a>
                @endforeach
            </nav>
            <div class="mt-auto hidden px-6 py-5 text-xs leading-5 text-slate-500 lg:block">Sistem kasir sederhana untuk operasional harian.</div>
        </aside>
        <main class="min-w-0 flex-1">
            <header class="flex items-center justify-between border-b border-slate-200/80 bg-white px-5 py-4 sm:px-8">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[.16em] text-slate-400">KasirKita</p>
                    <h1 class="mt-1 text-lg font-bold text-slate-900">@yield('heading', 'Beranda')</h1>
                </div>
                <a href="{{ route('pos.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-600">
                    <span aria-hidden="true">＋</span> Transaksi baru
                </a>
            </header>
            <div class="mx-auto max-w-[1440px] p-5 sm:p-8">
                @if (session('success'))
                    <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800" role="status">{{ session('success') }}</div>
                @endif
                @if ($errors->any())
                    <div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800" role="alert">
                        <p class="font-semibold">Periksa kembali data yang dimasukkan.</p>
                        <ul class="mt-1 list-inside list-disc">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
