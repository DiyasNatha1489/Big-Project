@php
    $layout = match (auth()->user()->role) {
        'admin' => 'admin-layout',
        'wali_kelas' => 'wali-kelas-layout',
        'siswa' => 'siswa-layout',
        default => 'app-layout',
    };
@endphp

<x-dynamic-component :component="$layout" title="Profil Saya">
    <div class="max-w-2xl mx-auto space-y-6">

        <div class="p-4 sm:p-8 bg-white shadow-sm border border-gray-100 rounded-xl">
            <section class="max-w-xl">
                <header>
                    <h2 class="text-lg font-semibold text-slate-800">Foto Profil</h2>
                    <p class="mt-1 text-sm text-slate-500">Foto ini ditampilkan di sidebar.</p>
                </header>

                <div class="mt-4 flex items-center gap-4">
                    <x-avatar :user="auth()->user()" size="w-16 h-16" />

                    <form method="POST" action="{{ route('profile.foto.update') }}" enctype="multipart/form-data" class="flex-1">
                        @csrf
                        <input type="file" name="foto_profil" accept="image/*" required
                            class="block w-full text-sm text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-slate-800 file:text-white hover:file:bg-slate-700">
                        <x-input-error :messages="$errors->get('foto_profil')" class="mt-2" />

                        <button type="submit" class="mt-3 px-4 py-2 bg-amber-300 text-slate-900 text-sm font-medium rounded-lg hover:bg-amber-400">
                            Simpan Foto
                        </button>

                        @if (session('status') === 'foto-updated')
                            <p class="mt-2 text-sm text-green-600">Foto profil berhasil diperbarui.</p>
                        @endif
                    </form>
                </div>
            </section>
        </div>

        <div class="p-4 sm:p-8 bg-white shadow-sm border border-gray-100 rounded-xl">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="p-4 sm:p-8 bg-white shadow-sm border border-gray-100 rounded-xl">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="p-4 sm:p-8 bg-white shadow-sm border border-gray-100 rounded-xl">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>

        <div class="p-4 sm:p-8 bg-white shadow-sm border border-gray-100 rounded-xl">
            <section class="max-w-xl">
                <header>
                    <h2 class="text-lg font-semibold text-slate-800">Keluar Akun</h2>
                    <p class="mt-1 text-sm text-slate-500">Akhiri sesi login kamu di perangkat ini.</p>
                </header>

                <form method="POST" action="{{ route('logout') }}" class="mt-6">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700">
                        Keluar
                    </button>
                </form>
            </section>
        </div>

    </div>
</x-dynamic-component>