<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\QuinielaStatus;
use App\Models\Quiniela;
use Illuminate\Console\Command;

final class CloseExpiredQuinielas extends Command
{
    protected $signature = 'quiniela:close-expired';

    protected $description = 'Close quinielas that have passed their closing date';

    public function handle(): int
    {
        $count = Quiniela::query()
            ->open()
            ->where('closing_at', '<=', now())
            ->update(['status' => QuinielaStatus::Closed]);

        $this->info("Closed {$count} expired quiniela(s).");

        return self::SUCCESS;
    }
}
