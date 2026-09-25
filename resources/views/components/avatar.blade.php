@props(['user', 'size' => 'w-10 h-10'])

@if ($user->foto_profil)
    <img src="{{ asset('storage/'.$user->foto_profil) }}" alt="{{ $user->name }}" class="{{ $size }} rounded-full object-cover shrink-0">
@else
    <div class="{{ $size }} rounded-full bg-slate-800 text-white flex items-center justify-center text-sm font-semibold shrink-0">
        {{ strtoupper(substr($user->name, 0, 1)) }}
    </div>
@endif