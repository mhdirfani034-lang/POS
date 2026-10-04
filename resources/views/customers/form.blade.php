@extends('layouts.app')

@section('title', $customer->exists ? 'Ubah pelanggan' : 'Tambah pelanggan')
@section('heading', $customer->exists ? 'Ubah pelanggan' : 'Tambah pelanggan')

@section('content')
    <div class="max-w-3xl">
        <a href="{{ route('pelanggan.index') }}" class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">← Kembali ke pelanggan</a>
        <form method="POST" action="{{ $customer->exists ? route('pelanggan.update', $customer) : route('pelanggan.store') }}" class="mt-5 space-y-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
            @csrf
            @if ($customer->exists) @method('PUT') @endif
            <div class="grid gap-5 sm:grid-cols-2">
                <div class="sm:col-span-2"><label for="name" class="field-label">Nama lengkap *</label><input id="name" name="name" required maxlength="160" value="{{ old('name', $customer->name) }}" class="field-input" placeholder="Nama pelanggan"></div>
                <div><label for="phone" class="field-label">Nomor telepon</label><input id="phone" name="phone" type="tel" maxlength="32" value="{{ old('phone', $customer->phone) }}" class="field-input" placeholder="08xxxxxxxxxx"></div>
                <div><label for="email" class="field-label">Email</label><input id="email" name="email" type="email" maxlength="255" value="{{ old('email', $customer->email) }}" class="field-input" placeholder="nama@email.com"></div>
                <div class="sm:col-span-2"><label for="address" class="field-label">Alamat</label><textarea id="address" name="address" rows="3" maxlength="2000" class="field-input" placeholder="Alamat lengkap (opsional)">{{ old('address', $customer->address) }}</textarea></div>
                <div class="sm:col-span-2"><label for="notes" class="field-label">Catatan</label><textarea id="notes" name="notes" rows="2" maxlength="2000" class="field-input" placeholder="Preferensi atau catatan lain (opsional)">{{ old('notes', $customer->notes) }}</textarea></div>
            </div>
            <div class="flex justify-end gap-3 border-t border-slate-100 pt-5"><a href="{{ route('pelanggan.index') }}" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold hover:bg-slate-50">Batal</a><button class="rounded-xl bg-emerald-500 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-600">Simpan pelanggan</button></div>
        </form>
    </div>
@endsection
