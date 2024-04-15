<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReorderResource\Pages;
use App\Filament\Resources\ReorderResource\RelationManagers;
use App\Models\Product;
use App\Models\Reorder;
use App\Providers\ReorderStatus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReorderResource extends Resource
{
    protected static ?string $model = Reorder::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->latest();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('product_id')
                    ->numeric(),
                Forms\Components\TextInput::make('status')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
//                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('Dispatch')
                    ->form([
                        Forms\Components\Section::make()->schema([
                            Forms\Components\TextInput::make('quantity')
                                ->required()
                                ->numeric()
                        ])
                    ])
                    ->action(function (Reorder $reorder, array $data)
                    {
                        $reorder->update([
                            'status'=> ReorderStatus::Dispatched
                        ]);
                        $reorder->product->increment('quantity', $data['quantity']);
                        Notification::make()->title('Product Dispatched Successfully');
                    })->hidden(function (Reorder $reorder){
                        return $reorder ->status != ReorderStatus::Pending->value;
                    })
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListReorders::route('/'),
            'create' => Pages\CreateReorder::route('/create'),
            'edit' => Pages\EditReorder::route('/{record}/edit'),
        ];
    }
}
