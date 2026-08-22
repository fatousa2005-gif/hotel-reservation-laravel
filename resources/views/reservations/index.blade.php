<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">Mes réservations</h1>

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg p-4 mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg p-4 mb-6">
                {{ $errors->first() }}
            </div>
        @endif

        @if ($reservations->isEmpty())
            <p class="text-gray-500">Vous n'avez aucune réservation pour le moment.</p>
        @else
            <div class="space-y-4">
                @foreach ($reservations as $reservation)
                    <div class="bg-white border rounded-lg p-5 flex justify-between items-center">
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ $reservation->room->name }}</h3>
                            <p class="text-sm text-gray-500">
                                Du {{ \Carbon\Carbon::parse($reservation->check_in)->format('d/m/Y') }}
                                au {{ \Carbon\Carbon::parse($reservation->check_out)->format('d/m/Y') }}
                                ({{ $reservation->nights }} nuit{{ $reservation->nights > 1 ? 's' : '' }})
                            </p>
                            <p class="text-indigo-600 font-bold mt-1">
                                {{ number_format($reservation->total_price, 0, ',', ' ') }} FCFA
                            </p>
                        </div>

                        <div class="text-right">
                            @php
                                $statusLabels = [
                                    'en_attente' => ['En attente', 'bg-yellow-100 text-yellow-700'],
                                    'confirmee' => ['Confirmée', 'bg-green-100 text-green-700'],
                                    'refusee' => ['Refusée', 'bg-red-100 text-red-700'],
                                    'annulee' => ['Annulée', 'bg-gray-100 text-gray-600'],
                                    'terminee' => ['Terminée', 'bg-blue-100 text-blue-700'],
                                ];
                                [$label, $classes] = $statusLabels[$reservation->status];
                            @endphp
                            <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold {{ $classes }}">
                                {{ $label }}
                            </span>

                            @if (in_array($reservation->status, ['en_attente', 'confirmee']) && \Carbon\Carbon::parse($reservation->check_in)->isFuture())
                                <form action="{{ route('reservations.cancel', $reservation) }}" method="POST" class="mt-2">
                                    @csrf
                                    <button
                                        type="submit"
                                        onclick="return confirm('Annuler cette réservation ?')"
                                        class="text-sm text-red-600 hover:underline"
                                    >
                                        Annuler
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>