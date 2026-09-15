<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Guru</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('admin.guru.update', $guru) }}" method="POST" class="bg-white p-6 shadow rounded space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <x-input-label for="name" value="Nama" />
                    <x-text-input id="name" name="name" class="block w-full mt-1" :value="old('name', $guru->name)" required />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="email" value="Email" />
                    <x-text-input id="email" name="email" type="email" class="block w-full mt-1" :value="old('email', $guru->email)" required />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
                <x-primary-button>Simpan</x-primary-button>
            </form>
        </div>
    </div>
</x-app-layout>