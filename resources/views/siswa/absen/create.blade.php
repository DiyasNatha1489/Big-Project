<x-siswa-layout title="Absen">
    <div class="max-w-md mx-auto space-y-6">

        @if (session('success'))
            <div class="p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="p-4 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
        @endif

        <div class="bg-white p-6 shadow rounded">

            <p class="mb-1"><strong>Kelas:</strong> {{ $kelas->nama }}</p>
            <p class="mb-4"><strong>Jendela absen:</strong> {{ \Carbon\Carbon::parse($sesi->buka_pada)->format('H:i') }} - {{ \Carbon\Carbon::parse($sesi->tutup_pada)->format('H:i') }}</p>

            @if ($sudahAbsen)
                <div class="p-4 bg-blue-100 text-blue-800 rounded">
                    Kamu sudah tercatat untuk hari ini. Terima kasih!
                </div>
            @else
                <div x-data="{ tab: 'absen' }">

                    <div class="flex gap-2 mb-4">
                        <button type="button" @click="tab = 'absen'"
                            :class="tab === 'absen' ? 'bg-slate-800 text-white' : 'bg-gray-100 text-slate-600'"
                            class="flex-1 px-4 py-2 rounded-lg text-sm font-medium">
                            Absen (Kamera)
                        </button>
                        <button type="button" @click="tab = 'izin'"
                            :class="tab === 'izin' ? 'bg-slate-800 text-white' : 'bg-gray-100 text-slate-600'"
                            class="flex-1 px-4 py-2 rounded-lg text-sm font-medium">
                            Upload Izin
                        </button>
                    </div>

                    {{-- TAB ABSEN: kamera live --}}
                    <div x-show="tab === 'absen'">
                        @if (!$jendelaAktif)
                            <div class="p-4 bg-yellow-100 text-yellow-800 rounded text-sm">
                                Jendela absen hari ini belum dibuka atau sudah tutup. Kamu masih bisa mengirim izin lewat tab sebelah.
                            </div>
                        @else
                            <form id="form-absen" action="{{ route('siswa.absen.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                @csrf

                                <div>
                                    <video id="video-preview" autoplay playsinline class="w-full rounded-lg bg-black aspect-video"></video>
                                    <canvas id="canvas-foto" class="hidden"></canvas>
                                    <img id="preview-foto" class="hidden w-full rounded-lg">
                                </div>

                                <div class="flex gap-2">
                                    <button type="button" id="btn-ambil-foto" class="flex-1 px-4 py-2 bg-slate-800 text-white text-sm rounded-lg">
                                        Ambil Foto
                                    </button>
                                    <button type="button" id="btn-ulangi-foto" class="hidden flex-1 px-4 py-2 bg-gray-200 text-slate-700 text-sm rounded-lg">
                                        Ulangi
                                    </button>
                                </div>

                                <input type="file" name="foto" id="input-foto" class="hidden" accept="image/*" required>
                                <x-input-error :messages="$errors->get('foto')" class="mt-2" />

                                <input type="hidden" name="latitude" id="latitude">
                                <input type="hidden" name="longitude" id="longitude">
                                <div id="status-lokasi" class="text-sm text-gray-600">Mengambil lokasi kamu, mohon tunggu...</div>

                                <x-primary-button id="btn-absen" type="submit" disabled class="opacity-50 cursor-not-allowed w-full justify-center">
                                    Absen Sekarang
                                </x-primary-button>
                            </form>
                        @endif
                    </div>

                    {{-- TAB IZIN: upload file biasa --}}
                    <div x-show="tab === 'izin'" x-cloak>
                        <form action="{{ route('siswa.absen.izin') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div>
                                <x-input-label for="foto_izin" value="Unggah Surat Izin / Bukti Sakit" />
                                <input id="foto_izin" name="foto" type="file" accept="image/*" required
                                    class="block w-full mt-1 border-gray-300 rounded">
                                <x-input-error :messages="$errors->get('foto')" class="mt-2" />
                            </div>
                            <x-primary-button class="w-full justify-center">Kirim Izin</x-primary-button>
                        </form>
                    </div>

                </div>
            @endif

        </div>
    </div>

    @if (!$sudahAbsen && $jendelaAktif)
    <script>
        (function () {
            const video = document.getElementById('video-preview');
            const canvas = document.getElementById('canvas-foto');
            const preview = document.getElementById('preview-foto');
            const inputFoto = document.getElementById('input-foto');
            const btnAmbil = document.getElementById('btn-ambil-foto');
            const btnUlangi = document.getElementById('btn-ulangi-foto');

            navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } })
                .then(function (stream) { video.srcObject = stream; })
                .catch(function () {
                    video.insertAdjacentHTML('afterend', '<p class="text-sm text-red-600 mt-2">Tidak bisa mengakses kamera. Pastikan izin kamera diaktifkan di browser.</p>');
                });

            btnAmbil.addEventListener('click', function () {
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                canvas.getContext('2d').drawImage(video, 0, 0);

                canvas.toBlob(function (blob) {
                    const file = new File([blob], 'absen.jpg', { type: 'image/jpeg' });
                    const dt = new DataTransfer();
                    dt.items.add(file);
                    inputFoto.files = dt.files;

                    preview.src = canvas.toDataURL('image/jpeg');
                    preview.classList.remove('hidden');
                    video.classList.add('hidden');
                    btnAmbil.classList.add('hidden');
                    btnUlangi.classList.remove('hidden');
                }, 'image/jpeg', 0.9);
            });

            btnUlangi.addEventListener('click', function () {
                preview.classList.add('hidden');
                video.classList.remove('hidden');
                btnUlangi.classList.add('hidden');
                btnAmbil.classList.remove('hidden');
                inputFoto.value = '';
            });

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
                    function () {
                        statusEl.textContent = 'Gagal mengambil lokasi. Pastikan kamu mengizinkan akses lokasi, lalu refresh halaman ini.';
                        statusEl.classList.replace('text-gray-600', 'text-red-600');
                    },
                    { enableHighAccuracy: true, timeout: 10000 }
                );
            }
        })();
    </script>
    @endif
</x-siswa-layout>