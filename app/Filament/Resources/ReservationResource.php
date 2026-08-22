<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReservationResource\Pages;
use App\Filament\Resources\ReservationResource\RelationManagers;
use App\Models\Reservation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReservationResource extends Resource
{
    protected static ?string $model = Reservation::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                 Forms\Components\Select::make('user_id')
                ->label('Client')
                ->relationship('user', 'name')
                ->required()
                ->searchable(),
            Forms\Components\Select::make('room_id')
                ->label('Chambre')
                ->relationship('room', 'name')
                ->required()
                ->searchable()
                ->live(),
            Forms\Components\DatePicker::make('check_in')
                ->label('Date d\'arrivée')
                ->required()
                ->live()
                ->afterOrEqual(today())
                ->afterStateUpdated(function ($state, $get, $set) {
                    self::updateCalculations($get, $set);
                }),
            Forms\Components\DatePicker::make('check_out')
                ->label('Date de départ')
                ->required()
                ->live()
                ->afterOrEqual('check_in')
                ->afterStateUpdated(function ($state, $get, $set) {
                    self::updateCalculations($get, $set);
                }),
            Forms\Components\TextInput::make('nights')
                ->label('Nombre de nuits')
                ->numeric()
                ->disabled()
                ->dehydrated(),
            Forms\Components\TextInput::make('total_price')
                ->label('Prix total')
                ->numeric()
                ->prefix('FCFA')
                ->disabled()
                ->dehydrated(),
            Forms\Components\Select::make('status')
                ->label('Statut')
                ->options([
                    'en_attente' => 'En attente',
                    'confirmee' => 'Confirmée',
                    'refusee' => 'Refusée',
                    'annulee' => 'Annulée',
                    'terminee' => 'Terminée',
                ])
                ->required()
                ->default('en_attente'),
        ]);
}

protected static function updateCalculations($get, $set): void
{
    $checkIn = $get('check_in');
    $checkOut = $get('check_out');
    $roomId = $get('room_id');

    if ($checkIn && $checkOut && $roomId) {
        $nights = \Carbon\Carbon::parse($checkIn)->diffInDays(\Carbon\Carbon::parse($checkOut));
        $room = \App\Models\Room::find($roomId);

        if ($nights > 0 && $room) {
            $set('nights', $nights);
            $set('total_price', $nights * $room->price);
        }
    }
}
            
    

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                 Tables\Columns\TextColumn::make('user.name')
                ->label('Client')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('room.name')
                ->label('Chambre')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('check_in')
                ->label('Arrivée')
                ->date('d/m/Y')
                ->sortable(),
            Tables\Columns\TextColumn::make('check_out')
                ->label('Départ')
                ->date('d/m/Y')
                ->sortable(),
            Tables\Columns\TextColumn::make('nights')
                ->label('Nuits'),
            Tables\Columns\TextColumn::make('total_price')
                ->label('Prix total')
                ->money('XOF')
                ->sortable(),
            Tables\Columns\TextColumn::make('status')
                ->label('Statut')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'en_attente' => 'warning',
                    'confirmee' => 'success',
                    'refusee' => 'danger',
                    'annulee' => 'gray',
                    'terminee' => 'info',
                })
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'en_attente' => 'En attente',
                    'confirmee' => 'Confirmée',
                    'refusee' => 'Refusée',
                    'annulee' => 'Annulée',
                    'terminee' => 'Terminée',
                }),
        
            ])
            ->filters([
                 Tables\Filters\SelectFilter::make('status')
                ->label('Statut')
                ->options([
                    'en_attente' => 'En attente',
                    'confirmee' => 'Confirmée',
                    'refusee' => 'Refusée',
                    'annulee' => 'Annulée',
                    'terminee' => 'Terminée',
                ]),
        
            ])
            ->actions([
                 Tables\Actions\Action::make('confirmer')
                ->label('Confirmer')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn ($record) => $record->status === 'en_attente')
                ->requiresConfirmation()
                ->action(fn ($record) => $record->update(['status' => 'confirmee'])),
            Tables\Actions\Action::make('refuser')
                ->label('Refuser')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->visible(fn ($record) => $record->status === 'en_attente')
                ->requiresConfirmation()
                ->action(fn ($record) => $record->update(['status' => 'refusee'])),
            Tables\Actions\Action::make('annuler')
                ->label('Annuler')
                ->icon('heroicon-o-no-symbol')
                ->color('gray')
                ->visible(fn ($record) => in_array($record->status, ['en_attente', 'confirmee']))
                ->requiresConfirmation()
                ->action(fn ($record) => $record->update(['status' => 'annulee'])),
            Tables\Actions\Action::make('terminer')
                ->label('Terminer')
                ->icon('heroicon-o-flag')
                ->color('info')
                ->visible(fn ($record) => $record->status === 'confirmee')
                ->requiresConfirmation()
                ->action(fn ($record) => $record->update(['status' => 'terminee'])),
            Tables\Actions\EditAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ]);
}
        

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReservations::route('/'),
            'create' => Pages\CreateReservation::route('/create'),
            'edit' => Pages\EditReservation::route('/{record}/edit'),
        ];
    }
}
