@extends('user.layouts.index')
@section('content')
<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-semibold">{{ $album->nama_album }}</h1>
    <a href="/editAlbum/{{ $album->id_album }}"
        class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-4 py-2">Edit Album</a>
</div>
<div class="grid grid-cols-4 gap-4">
    @foreach ($album->foto as $foto)
            <img src="{{ asset('storage/' . $foto->path_foto) }}" alt="{{ $foto->id_foto }}" class="h-full object-cover">
            @endforeach
        </div>

@endsection
