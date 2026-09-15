<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Absen</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
            @endif

            <div class="bg-white p-6 shadow rounded">

                <p class="mb-1"><strong>Kelas:</strong> {{ $kelas->nama }}</p>
                <p class="mb-4"><strong>Jendela absen:</strong> {{ \Carbon\Carbon::parse($sesi->buka_pada)->format('H:i') }} - {{ \Carbon\Carbon::parse($sesi->tutup_pada)->format('H:i') }}</p>

                @if ($sudahAbsen)
                    <div class="p-4 bg-blue-100 text-blue-800 rounded">
                        Kamu sudah absen untuk hari ini. Terima kasih!
                    </div>
                @elseif (!$jendelaAktif)
                    <div class="p-4 bg-yellow-100 text-yellow-800 rounded">
                        Jendela absen hari ini belum dibuka atau sudah tutup.
                    </div>
                @else
                    <form id="form-absen" action="{{ route('siswa.absen.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf

                        <div>
                            <x-input-label for="foto" value="Ambil Foto" />
                            <input id="foto" name="foto" type="file" accept="image/*" capture="user" required
                                class="block w-full mt-1 border-gray-300 rounded" />
                            <x-input-error :messages="$errors->get('foto')" class="mt-2" />
                        </div>

                        <input type="hidden" name="latitude" id="latitude">
                        <input type="hidden" name="longitude" id="longitude">

                        <div id="status-lokasi" class="text-sm text-gray-600">
                            Mengambil lokasi kamu, mohon tunggu...
                        </div>

                        <x-primary-button id="btn-absen" type="submit" disabled class="opacity-50 cursor-not-allowed">
                            Absen Sekarang
                        </x-primary-button>
                    </form>
                @endif

            </div>
        </div>
    </div>

    @if (!$sudahAbsen && $jendelaAktif)
    <script>
        const statusEl = document.getElementById('status-lokasi');
        const btnEl = document.getElementById('btn-absen');
        const latEl = document.getElementById('latitude');
        const lonEl = document.getElementById('longitude');

        if (!navigator.geolocation) {
            statusEl.textContent = 'Browser kamu tidak mendukung akses lokasi. Absen tidak bisa dilanjutkan.';
        } else {
            navigator.geolocation.getCurrentPosition(
                function (position) {
                    latEl.value = position.coords.latitude;
                    lonEl.value = position.coords.longitude;
                    statusEl.textContent = 'Lokasi berhasil didapat. Kamu bisa absen sekarang.';
                    statusEl.classList.replace('text-gray-600', 'text-green-600');
                    btnEl.disabled = false;
                    btnEl.classList.remove('opacity-50', 'cursor-not-allowed');
                },
                function (error) {
                    statusEl.textContent = 'Gagal mengambil lokasi. Pastikan kamu mengizinkan akses lokasi di browser, lalu refresh halaman ini.';
                    statusEl.classList.replace('text-gray-600', 'text-red-600');
                },
                { enableHighAccuracy: true, timeout: 10000 }
            );
        }
    </script>
    @endif
</x-app-layout>