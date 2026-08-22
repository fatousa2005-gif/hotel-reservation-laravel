<div class="border rounded-lg overflow-hidden shadow hover:shadow-lg transition bg-white">
    @if ($room->image)
        <img src="{{ Storage::url($room->image) }}" alt="{{ $room->name }}" class="w-full h-40 object-cover">
    @else
        <div class="w-full h-40 bg-gray-200 flex items-center justify-center text-gray-400">
            Pas d'image
        </div>
    @endif
    <div class="p-4">
        <h3 class="font-semibold text-gray-900">{{ $room->name }}</h3>
        <p class="text-sm text-gray-500">{{ $room->category->name }}</p>
        <p class="text-indigo-600 font-bold mt-2">{{ number_format($room->price, 0, ',', ' ') }} FCFA / nuit</p>
        <p class="text-sm text-gray-500">{{ $room->capacity }} personnes max</p>
    </div>
</div>