<?php

namespace App\Console\Commands;

use App\Models\Link;
use App\Services\LinkStatusChecker;
use Illuminate\Console\Command;

class CheckLinkStatusesCommand extends Command
{
    protected $signature = 'links:check-status
        {--id=* : Periksa hanya id_link tertentu}';

    protected $description = 'Periksa otomatis apakah website pada t_link sedang aktif atau bermasalah.';

    public function handle(LinkStatusChecker $checker): int
    {
        $query = Link::query()->orderBy('id_link');
        $selectedIds = collect((array) $this->option('id'))
            ->filter(fn ($value) => $value !== null && $value !== '')
            ->map(fn ($value) => (int) $value)
            ->values();

        if ($selectedIds->isNotEmpty()) {
            $query->whereIn('id_link', $selectedIds);
        }

        $links = $query->get();

        if ($links->isEmpty()) {
            $this->warn('Tidak ada link yang bisa diperiksa.');

            return self::SUCCESS;
        }

        $summary = [
            'aktif' => 0,
            'bermasalah' => 0,
        ];

        $this->withProgressBar($links, function (Link $link) use ($checker, &$summary) {
            $result = $checker->check($link);

            $link->fill($result);
            $link->save();

            $summary[$result['status']] = ($summary[$result['status']] ?? 0) + 1;
        });

        $this->newLine(2);
        $this->info('Pemeriksaan status link selesai.');
        $this->line(sprintf('Aktif: %d', $summary['aktif']));
        $this->line(sprintf('Bermasalah: %d', $summary['bermasalah']));

        return self::SUCCESS;
    }
}
