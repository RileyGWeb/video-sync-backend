<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SyncEventResource\Pages;
use App\Models\SyncEvent;
use App\Models\VideoSession;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SyncEventResource extends Resource
{
    protected static ?string $model = SyncEvent::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationGroup = 'Video Management';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Event Details')
                    ->schema([
                        Forms\Components\Select::make('video_session_id')
                            ->relationship('videoSession', 'title')
                            ->required()
                            ->searchable()
                            ->preload(),
                        
                        Forms\Components\Select::make('event_type')
                            ->options([
                                'play' => 'Play',
                                'pause' => 'Pause',
                                'seek' => 'Seek',
                                'speed_change' => 'Speed Change',
                                'buffer' => 'Buffer',
                                'error' => 'Error',
                                'viewer_join' => 'Viewer Join',
                                'viewer_leave' => 'Viewer Leave',
                            ])
                            ->required(),
                        
                        Forms\Components\TextInput::make('timestamp')
                            ->numeric()
                            ->step(0.001)
                            ->required()
                            ->suffix('seconds'),
                        
                        Forms\Components\TextInput::make('playback_rate')
                            ->numeric()
                            ->step(0.01)
                            ->default(1.00)
                            ->suffix('x'),
                    ])->columns(2),

                Forms\Components\Section::make('User Information')
                    ->schema([
                        Forms\Components\TextInput::make('user_id')
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('user_name')
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('client_id')
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make('Technical Details')
                    ->schema([
                        Forms\Components\TextInput::make('ip_address')
                            ->ip(),
                        
                        Forms\Components\Textarea::make('user_agent')
                            ->maxLength(65535),
                        
                        Forms\Components\DateTimePicker::make('occurred_at')
                            ->required(),
                        
                        Forms\Components\KeyValue::make('event_data')
                            ->columnSpanFull(),
                    ])->columns(2),
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
                
                Tables\Columns\TextColumn::make('event_type')
                    ->badge()
                    ->color(fn (SyncEvent $record): string => $record->event_type_color),
                
                Tables\Columns\TextColumn::make('formatted_timestamp')
                    ->label('Video Time')
                    ->getStateUsing(fn (SyncEvent $record): string => $record->formatted_timestamp),
                
                Tables\Columns\TextColumn::make('playback_rate')
                    ->suffix('x')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('user_name')
                    ->searchable()
                    ->placeholder('Anonymous'),
                
                Tables\Columns\TextColumn::make('client_id')
                    ->searchable()
                    ->limit(10)
                    ->placeholder('N/A'),
                
                Tables\Columns\TextColumn::make('ip_address')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('occurred_at')
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
                
                Tables\Filters\SelectFilter::make('event_type')
                    ->options([
                        'play' => 'Play',
                        'pause' => 'Pause',
                        'seek' => 'Seek',
                        'speed_change' => 'Speed Change',
                        'buffer' => 'Buffer',
                        'error' => 'Error',
                        'viewer_join' => 'Viewer Join',
                        'viewer_leave' => 'Viewer Leave',
                    ]),
                
                Tables\Filters\Filter::make('recent')
                    ->query(fn (Builder $query): Builder => $query->where('occurred_at', '>=', now()->subHour()))
                    ->label('Last Hour'),
                
                Tables\Filters\Filter::make('errors_only')
                    ->query(fn (Builder $query): Builder => $query->where('event_type', 'error'))
                    ->label('Errors Only'),
                
                Tables\Filters\Filter::make('user_events')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('user_id'))
                    ->label('User Events Only'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('occurred_at', 'desc')
            ->poll('10s'); // More frequent refresh for events
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
            'index' => Pages\ListSyncEvents::route('/'),
            'create' => Pages\CreateSyncEvent::route('/create'),
            'edit' => Pages\EditSyncEvent::route('/{record}/edit'),
        ];
    }
}
