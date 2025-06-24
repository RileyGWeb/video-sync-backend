<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\VideoSyncStatsWidget;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';

    protected static string $view = 'filament.pages.dashboard';

    protected static ?string $title = 'Video Sync Dashboard';

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'dashboard';

    public function getHeading(): string|Htmlable
    {
        return 'Video Sync Dashboard';
    }

    public function getHeaderWidgets(): array
    {
        return [
            VideoSyncStatsWidget::class,
        ];
    }
}
