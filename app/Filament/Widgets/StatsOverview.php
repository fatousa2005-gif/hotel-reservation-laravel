<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
              Stat::make('Chambres', \App\Models\Room::count())
            ->description('Nombre total de chambres')
            ->color('primary'),
        Stat::make('Disponibles', \App\Models\Room::where('status', 'disponible')->count())
            ->description('Chambres disponibles')
            ->color('success'),
        Stat::make('Occupées', \App\Models\Room::where('status', 'occupee')->count())
            ->description('Chambres occupées')
            ->color('danger'),
        Stat::make('Réservations du jour', \App\Models\Reservation::whereDate('created_at', today())->count())
            ->description('Créées aujourd\'hui')
            ->color('warning'),
        Stat::make('Chiffre d\'affaires', number_format(\App\Models\Reservation::where('status', '!=', 'annulee')->sum('total_price'), 0, ',', ' ') . ' FCFA')
            ->description('Total (hors annulées)')
            ->color('success'),
        Stat::make('Nouveaux clients', \App\Models\User::whereDate('created_at', '>=', now()->subDays(30))->count())
            ->description('Ce mois-ci')
            ->color('info'),
    ];
}
        
    
}
