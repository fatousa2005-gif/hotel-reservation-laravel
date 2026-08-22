<x-app-layout>
    <div class="max-w-5xl mx-auto px-4 py-8">
        <a href="{{ route('rooms.index') }}" class="text-indigo-600 hover:underline text-sm">&larr; Retour aux chambres</a>

        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Image -->
            <div>
                @if ($room->image)
                    <img src="{{ Storage::url($room->image) }}" alt="{{ $room->name }}" class="w-full h-80 object-cover rounded-lg">
                @else
                    <div class="w-full h-80 bg-gray-200 flex items-center justify-center text-gray-400 rounded-lg">
                        Pas d'image
                    </div>
                @endif
            </div>

            <!-- Infos -->
            <div>
                <span class="inline-block bg-indigo-100 text-indigo-700 text-sm font-semibold px-3 py-1 rounded-full mb-3">
                    {{ $room->category->name }}
                </span>

                <h1 class="text-3xl font-bold text-gray-900">{{ $room->name }}</h1>
                <p class="text-gray-500 mt-1">Chambre n°{{ $room->number }}</p>

                <p class="text-2xl font-bold text-indigo-600 mt-4">
                    {{ number_format($room->price, 0, ',', ' ') }} FCFA <span class="text-base font-normal text-gray-500">/ nuit</span>
                </p>

                <p class="text-gray-600 mt-2">Capacité : {{ $room->capacity }} personne(s) maximum</p>

                <p class="mt-4 text-gray-700 leading-relaxed">
                    {{ $room->description ?? 'Aucune description disponible.' }}
                </p>

                <div class="mt-6">
                    @if ($room->status === 'disponible')
                        <span class="inline-flex items-center gap-2 text-green-700 font-semibold mb-4">
                            <span class="w-2 h-2 bg-green-500 rounded-full"></span> Disponible
                        </span>
                    @else
                        <span class="inline-flex items-center gap-2 text-red-700 font-semibold mb-4">
                            <span class="w-2 h-2 bg-red-500 rounded-full"></span> Occupée
                        </span>
                    @endif

                    @auth
                        @if ($room->status === 'disponible')
                            <a
                                href="{{ route('reservations.create', $room) }}"
                                class="block text-center bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 rounded-lg"
                            >
                                Réserver cette chambre
                            </a>
                        @endif
                    @else
                        <a
                            href="{{ route('login') }}"
                            class="block text-center bg-gray-800 hover:bg-gray-900 text-white font-semibold py-3 rounded-lg"
                        >
                            Connectez-vous pour réserver
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</x-app-layout>