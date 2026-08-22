<x-app-layout>
    <!-- Hero + recherche -->
    <div class="bg-indigo-700 text-white">
        <div class="max-w-7xl mx-auto px-4 py-16 text-center">
            <h1 class="text-4xl font-bold mb-4">Paradise Hôtel</h1>
            <p class="text-lg mb-8">Trouvez la chambre parfaite pour votre séjour</p>

            <form action="{{ route('rooms.index') }}" method="GET" class="max-w-2xl mx-auto flex gap-2">
                <input
                    type="text"
                    name="search"
                    placeholder="Rechercher une chambre..."
                    class="flex-1 rounded-lg px-4 py-3 text-gray-900"
                >
                <button type="submit" class="bg-orange-500 hover:bg-orange-600 px-6 py-3 rounded-lg font-semibold">
                    Rechercher
                </button>
            </form>
        </div>
    </div>

    <!-- Catégories -->
    <div class="max-w-7xl mx-auto px-4 py-12">
        <h2 class="text-2xl font-bold mb-6">Catégories de chambres</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach ($categories as $category)
                <a
                    href="{{ route('rooms.index', ['category' => $category->id]) }}"
                    class="bg-white border rounded-lg p-6 text-center hover:shadow-lg transition"
                >
                    <span class="font-semibold text-gray-800">{{ $category->name }}</span>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Chambres populaires -->
    @if ($popularRooms->count())
    <div class="max-w-7xl mx-auto px-4 py-12">
        <h2 class="text-2xl font-bold mb-6">Chambres populaires</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($popularRooms as $room)
                @include('partials.room-card', ['room' => $room])
            @endforeach
        </div>
    </div>
    @endif

    <!-- Chambres disponibles -->
    <div class="max-w-7xl mx-auto px-4 py-12">
        <h2 class="text-2xl font-bold mb-6">Chambres disponibles</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($availableRooms as $room)
                @include('partials.room-card', ['room' => $room])
            @endforeach
        </div>
    </div>
</x-app-layout>