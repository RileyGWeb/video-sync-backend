<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VideoSessionResource\Pages;
use App\Filament\Resources\VideoSessionResource\RelationManagers;
use App\Models\VideoSession;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class VideoSessionResource extends Resource
{
    protected static ?string $model = VideoSession::class;

    protected static ?string $navigationIcon = 'heroicon-o-video-camera';

    protected static ?string $navigationGroup = 'Video Management';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('session_id')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\Textarea::make('description')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Platform Details')
                    ->schema([
                        Forms\Components\Select::make('platform')
                            ->options([
                                'twitch' => 'Twitch',
                                'youtube' => 'YouTube',
                            ])
                            ->required(),
                        
                        Forms\Components\TextInput::make('platform_video_id')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('streamer_name')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('streamer_id')
                            ->required()
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make('Session Status')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'paused' => 'Paused',
                                'ended' => 'Ended',
                                'error' => 'Error',
                            ])
                            ->required(),
                        
                        Forms\Components\TextInput::make('viewer_count')
                            ->numeric()
                            ->default(0),
                        
                        Forms\Components\TextInput::make('current_timestamp')
                            ->numeric()
                            ->step(0.001)
                            ->default(0)
                            ->suffix('seconds'),
                        
                        Forms\Components\Toggle::make('is_live')
                            ->default(true),
                    ])->columns(2),

                Forms\Components\Section::make('Timestamps')
                    ->schema([
                        Forms\Components\DateTimePicker::make('started_at')
                            ->required(),
                        
                        Forms\Components\DateTimePicker::make('ended_at'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('session_id')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                
                Tables\Columns\TextColumn::make('platform')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'twitch' => 'purple',
                        'youtube' => 'red',
                        default => 'gray',
                    }),
                
                Tables\Columns\TextColumn::make('streamer_name')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (VideoSession $record): string => $record->status_color),
                
                Tables\Columns\TextColumn::make('viewer_count')
                    ->numeric()
                    ->sortable(),
                
                Tables\Columns\IconColumn::make('is_live')
                    ->boolean(),
                
                Tables\Columns\TextColumn::make('duration')
                    ->label('Duration')
                    ->getStateUsing(fn (VideoSession $record): ?string => $record->duration),
                
                Tables\Columns\TextColumn::make('started_at')
                    ->dateTime()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('platform')
                    ->options([
                        'twitch' => 'Twitch',
                        'youtube' => 'YouTube',
                    ]),
                
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'paused' => 'Paused',
                        'ended' => 'Ended',
                        'error' => 'Error',
                    ]),
                
                Tables\Filters\Filter::make('is_live')
                    ->query(fn (Builder $query): Builder => $query->where('is_live', true))
                    ->label('Live Sessions'),
                
                Tables\Filters\Filter::make('started_today')
                    ->query(fn (Builder $query): Builder => $query->whereDate('started_at', today()))
                    ->label('Started Today'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('started_at', 'desc')
            ->poll('30s'); // Auto-refresh every 30 seconds for live data
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\SyncEventsRelationManager::class,
            RelationManagers\BitsTransactionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVideoSessions::route('/'),
            'create' => Pages\CreateVideoSession::route('/create'),
            'edit' => Pages\EditVideoSession::route('/{record}/edit'),
        ];
    }
}
