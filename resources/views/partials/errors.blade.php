@if ($errors->any())
    <div class="mb-4 rounded-lg border border-red-300 bg-red-50 p-3 text-sm text-red-800">
        <p class="font-semibold">Periksa kembali input berikut:</p>
        <ul class="mt-1 list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
