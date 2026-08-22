<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 py-8">
        <a href="{{ route('rooms.show', $room) }}" class="text-indigo-600 hover:underline text-sm">&larr; Retour à la chambre</a>

        <h1 class="text-2xl font-bold mt-4 mb-6">Réserver : {{ $room->name }}</h1>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg p-4 mb-6">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('reservations.store', $room) }}" method="POST" class="bg-white border rounded-lg p-6 space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date d'arrivée</label>
                <input
                    type="date"
                    name="check_in"
                    id="check_in"
                    value="{{ old('check_in') }}"
                    min="{{ date('Y-m-d') }}"
                    class="w-full rounded border-gray-300"
                    required
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date de départ</label>
                <input
                    type="date"
                    name="check_out"
                    id="check_out"
                    value="{{ old('check_out') }}"
                    class="w-full rounded border-gray-300"
                    required
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre de personnes</label>
                <input
                    type="number"
                    name="capacity"
                    value="{{ old('capacity', 1) }}"
                    min="1"
                    max="{{ $room->capacity }}"
                    class="w-full rounded border-gray-300"
                    required
                >
                <p class="text-xs text-gray-500 mt-1">Maximum {{ $room->capacity }} personne(s) pour cette chambre.</p>
            </div>

            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex justify-between text-sm text-gray-600 mb-1">
                    <span>Nombre de nuits</span>
                    <span id="nights-display">0</span>
                </div>
                <div class="flex justify-between font-bold text-lg text-gray-900">
                    <span>Prix total</span>
                    <span id="price-display">0 FCFA</span>
                </div>
            </div>

            <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 rounded-lg">
                Confirmer la réservation
            </button>
        </form>
    </div>

    <script>
        const checkInInput = document.getElementById('check_in');
        const checkOutInput = document.getElementById('check_out');
        const nightsDisplay = document.getElementById('nights-display');
        const priceDisplay = document.getElementById('price-display');
        const pricePerNight = {{ $room->price }};

        function updateCalculation() {
            const checkIn = new Date(checkInInput.value);
            const checkOut = new Date(checkOutInput.value);

            if (checkInInput.value && checkOutInput.value && checkOut > checkIn) {
                const diffTime = checkOut - checkIn;
                const nights = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                const total = nights * pricePerNight;

                nightsDisplay.textContent = nights;
                priceDisplay.textContent = total.toLocaleString('fr-FR') + ' FCFA';

                checkOutInput.min = checkInInput.value;
            } else {
                nightsDisplay.textContent = '0';
                priceDisplay.textContent = '0 FCFA';
            }
        }

        checkInInput.addEventListener('change', updateCalculation);
        checkOutInput.addEventListener('change', updateCalculation);
    </script>
</x-app-layout>