<?php

namespace App\Filament\Widgets;

use App\Models\VideoSession;
use App\Models\SyncEvent;
use App\Models\BitsTransaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class VideoSyncStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Active Sessions', VideoSession::where('status', 'active')->count())
                ->description('Currently live video sessions')
                ->descriptionIcon('heroicon-m-video-camera')
                ->color('success'),

            Stat::make('Total Sessions Today', VideoSession::whereDate('started_at', today())->count())
                ->description('Sessions started today')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('info'),

            Stat::make('Recent Events', SyncEvent::where('occurred_at', '>=', now()->subHour())->count())
                ->description('Sync events in the last hour')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Bits Today', BitsTransaction::whereDate('twitch_timestamp', today())->sum('bits_used'))
                ->description('Total bits received today')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),
        ];
    }
}
