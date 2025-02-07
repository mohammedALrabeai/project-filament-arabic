<?php

namespace App\Filament\Widgets;

use App\Models\IncomingCase;
use App\Models\TerminatedCase;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class StatsOverview extends BaseWidget
{
    /**
     * هنا نقوم بتجهيز البطاقات (Cards) التي ستظهر في الواجهة.
     */
    protected function getCards(): array
    {
        return [
            // بطاقة عدد القضايا الواردة
            Card::make('القضايا الواردة', IncomingCase::count())
                ->description('إجمالي القضايا الواردة'),

            // بطاقة عدد القضايا المنتهية
            Card::make('القضايا المنتهية', TerminatedCase::count())
                ->description('إجمالي القضايا المنتهية'),

            // بطاقة نسبة الانتهاء (إن كانت القضايا الواردة غير صفرية)
            Card::make('نسبة الانتهاء', $this->getCompletionPercentage().'%')
                ->description('نسبة القضايا المنتهية من القضايا الواردة'),
        ];
    }

    /**
     * دالة لحساب نسبة الانتهاء.
     */
    protected function getCompletionPercentage(): float
    {
        $incoming = IncomingCase::count();
        if ($incoming === 0) {
            return 0;
        }
        $terminated = TerminatedCase::count();

        return round(($terminated / $incoming) * 100, 2);
    }
}
