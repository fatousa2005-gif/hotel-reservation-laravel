<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoomResource\Pages;
use App\Filament\Resources\RoomResource\RelationManagers;
use App\Models\Room;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RoomResource extends Resource
{
    protected static ?string $model = Room::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
        Forms\Components\Select::make('room_category_id')
             ->label('Categorie')
             ->relationship('category','name')
             ->required()
             ->searchable(),
        Forms\Components\TextInput::make('number')
             ->label('Numero')
             ->required()
             ->unique(ignoreRecord:true)
             ->maxLength(255),
        Forms\Components\TextInput::make('price')
             ->label('prix / nuit')
             ->required()
             ->numeric()
             ->prefix('FCFA'),
        Forms\Components\TextInput::make('capacity')
              ->label('capacite(personnes)')
              ->required()
              ->numeric(),
        Forms\Components\Select::make('status')
           ->label('statut')
           ->options([
               'disponible' => 'disponible',
               'occupee' => 'occupee',
           ])
            ->required()
            ->default('disponible'),
        Forms\Components\FileUpload::make('image')
            ->label('image principale')
            ->image()
            ->directory('rooms')
            ->columnSpanFull(),
            Forms\Components\Repeater::make('images')
            ->relationship('images')
            ->label('Galerie d\'images')
            ->schema([
        Forms\Components\FileUpload::make('image')
            ->label('Image')
            ->image()
            ->directory('rooms/gallery')
            ->required(),
          ])
           ->collapsible()
           ->columnSpanFull(),
        Forms\Components\Textarea::make('description')
            ->label('Description')
            ->rows(4)
            ->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
              ->columns([
            Tables\Columns\ImageColumn::make('image')
                ->label('Image')
                ->square(),
            Tables\Columns\TextColumn::make('number')
                ->label('Numéro')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('name')
                ->label('Nom')
                ->searchable(),
            Tables\Columns\TextColumn::make('category.name')
                ->label('Catégorie')
                ->badge()
                ->sortable(),
            Tables\Columns\TextColumn::make('price')
                ->label('Prix / nuit')
                ->money('XOF')
                ->sortable(),
            Tables\Columns\TextColumn::make('capacity')
                ->label('Capacité')
                ->sortable(),
            Tables\Columns\TextColumn::make('status')
                ->label('Statut')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'disponible' => 'success',
                    'occupee' => 'danger',
                }),
                
            ])
            ->filters([
                  Tables\Filters\SelectFilter::make('room_category_id')
                ->label('Catégorie')
                ->relationship('category', 'name'),
            Tables\Filters\SelectFilter::make('status')
                ->label('Statut')
                ->options([
                    'disponible' => 'Disponible',
                    'occupee' => 'Occupée',
                ]),
        ])
            
            ->actions([
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
            'index' => Pages\ListRooms::route('/'),
            'create' => Pages\CreateRoom::route('/create'),
            'edit' => Pages\EditRoom::route('/{record}/edit'),
        ];
    }
}
