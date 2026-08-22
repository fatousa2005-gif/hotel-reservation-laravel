<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">Toutes nos chambres</h1>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Filtres -->
            <div class="lg:col-span-1">
                <form action="{{ route('rooms.index') }}" method="GET" class="bg-white border rounded-lg p-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Nom de la chambre..."
                            class="w-full rounded border-gray-300"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catégorie</label>
                        <select name="category" class="w-full rounded border-gray-300">
                            <option value="">Toutes</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(request('category') == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Prix min (FCFA)</label>
                        <input type="number" name="min_price" value="{{ request('min_price') }}" class="w-full rounded border-gray-300">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Prix max (FCFA)</label>
                        <input type="number" name="max_price" value="{{ request('max_price') }}" class="w-full rounded border-gray-300">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Personnes (min)</label>
                        <input type="number" name="capacity" value="{{ request('capacity') }}" class="w-full rounded border-gray-300">
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg py-2 font-semibold">
                        Filtrer
                    </button>

                    @if (request()->anyFilled(['search', 'category', 'min_price', 'max_price', 'capacity']))
                        <a href="{{ route('rooms.index') }}" class="block text-center text-sm text-gray-500 hover:text-gray-700">
                            Réinitialiser
                        </a>
                    @endif
                </form>
            </div>

            <!-- Résultats -->
            <div class="lg:col-span-3">
                @if ($rooms->isEmpty())
                    <p class="text-gray-500">Aucune chambre ne correspond à votre recherche.</p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach ($rooms as $room)
                            <a href="{{ route('rooms.show', $room) }}">
                                @include('partials.room-card', ['room' => $room])
                            </a>
                        @endforeach
                    </div>

                    <div class="mt-8">
                        {{ $rooms->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>