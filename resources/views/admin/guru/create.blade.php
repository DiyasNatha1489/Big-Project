<x-admin-layout title="Tambah Guru">
    <div class="max-w-4xl mx-auto">

    <div class="max-w-4xl mx-auto space-y-4">
        <form action="{{ route('admin.guru.store') }}" method="POST" class="bg-white p-6 shadow rounded space-y-4">
            @csrf
            <div>
                <x-input-label for="name" value="Nama" />
                <x-text-input id="name" name="name" class="block w-full mt-1" :value="old('name')" required />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="email" value="Email" />
                <x-text-input id="email" name="email" type="email" class="block w-full mt-1" :value="old('email')" required />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
            <x-primary-button>Simpan</x-primary-button>
        </form>
    </div>
</x-admin-layout>