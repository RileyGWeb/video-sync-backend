<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BitsTransactionResource\Pages;
use App\Models\BitsTransaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BitsTransactionResource extends Resource
{
    protected static ?string $model = BitsTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationGroup = 'Video Management';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Transaction Details')
                    ->schema([
                        Forms\Components\Select::make('video_session_id')
                            ->relationship('videoSession', 'title')
                            ->required()
                            ->searchable()
                            ->preload(),
                        
                        Forms\Components\TextInput::make('transaction_id')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('bits_used')
                            ->numeric()
                            ->required()
                            ->suffix('bits'),
                        
                        Forms\Components\TextInput::make('total_bits_used')
                            ->numeric()
                            ->suffix('total bits'),
                    ])->columns(2),

                Forms\Components\Section::make('User Information')
                    ->schema([
                        Forms\Components\TextInput::make('user_id')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('user_name')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('user_login')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\Toggle::make('is_anonymous')
                            ->default(false),
                    ])->columns(2),

                Forms\Components\Section::make('Message & Context')
                    ->schema([
                        Forms\Components\Textarea::make('message')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                        
                        Forms\Components\KeyValue::make('context')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Product & Timing')
                    ->schema([
                        Forms\Components\TextInput::make('product_type')
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('product_sku')
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('video_timestamp')
                            ->numeric()
                            ->step(0.001)
                            ->suffix('seconds'),
                        
                        Forms\Components\DateTimePicker::make('twitch_timestamp')
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Debug Data')
                    ->schema([
                        Forms\Components\KeyValue::make('raw_data')
                            ->columnSpanFull(),
                    ])
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('videoSession.title')
                    ->searchable()
                    ->sortable()
                    ->limit(20),
                
                Tables\Columns\TextColumn::make('user_name')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('formatted_bits')
                    ->label('Bits')
                    ->getStateUsing(fn (BitsTransaction $record): string => $record->formatted_bits)
                    ->sortable('bits_used'),
                
                Tables\Columns\TextColumn::make('formatted_value')
                    ->label('Value')
                    ->getStateUsing(fn (BitsTransaction $record): string => $record->formatted_value)
                    ->sortable('bits_used'),
                
                Tables\Columns\TextColumn::make('message')
                    ->limit(30)
                    ->searchable()
                    ->placeholder('No message'),
                
                Tables\Columns\TextColumn::make('formatted_video_timestamp')
                    ->label('Video Time')
                    ->getStateUsing(fn (BitsTransaction $record): ?string => $record->formatted_video_timestamp)
                    ->placeholder('N/A'),
                
                Tables\Columns\IconColumn::make('is_anonymous')
                    ->boolean(),
                
                Tables\Columns\TextColumn::make('twitch_timestamp')
                    ->dateTime()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('video_session_id')
                    ->relationship('videoSession', 'title')
                    ->searchable()
                    ->preload(),
                
                Tables\Filters\Filter::make('high_value')
                    ->query(fn (Builder $query): Builder => $query->where('bits_used', '>=', 1000))
                    ->label('High Value (1000+ bits)'),
                
                Tables\Filters\Filter::make('recent')
                    ->query(fn (Builder $query): Builder => $query->where('twitch_timestamp', '>=', now()->subHour()))
                    ->label('Last Hour'),
                
                Tables\Filters\Filter::make('today')
                    ->query(fn (Builder $query): Builder => $query->whereDate('twitch_timestamp', today()))
                    ->label('Today'),
                
                Tables\Filters\Filter::make('anonymous')
                    ->query(fn (Builder $query): Builder => $query->where('is_anonymous', true))
                    ->label('Anonymous'),
                
                Tables\Filters\Filter::make('with_message')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('message'))
                    ->label('With Message'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('twitch_timestamp', 'desc')
            ->poll('30s'); // Auto-refresh for new transactions
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
            'index' => Pages\ListBitsTransactions::route('/'),
            'create' => Pages\CreateBitsTransaction::route('/create'),
            'edit' => Pages\EditBitsTransaction::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            // Can add BitsStatsWidget later
        ];
    }
}
