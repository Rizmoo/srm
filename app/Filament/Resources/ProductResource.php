<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Order;
use App\Models\Product;
use App\Services\ProductService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('quantity')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('reorder_stock')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('fulfilled_orders')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('unfulfilled_orders')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('reorder_stock')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fulfilled_orders')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('unfulfilled_orders')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('sell')
                    ->form([
                        Forms\Components\Section::make()->schema([
                            Forms\Components\TextInput::make('quantity')
                                ->required()
                                ->maxValue(function (Product $product){
                                    return $product->quantity;
                                })
                                ->numeric()
                        ])
                    ])
                    ->action(function (Product $product, array $data){
                    $service = new ProductService();
                    $order =  $service->order($product, $data['quantity']);
                    if ($order == 0)
                    {
                        Notification::make()
                            ->title('You have unprocessed Order on this product')
                            ->warning()
                            ->send();
                    }else{
                        Notification::make()
                            ->title('Order Created Successfully')
                            ->success()
                            ->send();
                    }
                })
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
