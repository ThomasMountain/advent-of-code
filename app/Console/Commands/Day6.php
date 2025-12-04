<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class Day6 extends Command
{
    const USES_SAMPLE = true;

    protected $signature = 'app:day6';

    protected $description = 'Command description';

    protected ?array $grid;

    const DIRECTIONS = [
        '^' => [
            'y' => -1,
            'x' => 0,
        ],
        'v' => [
            'y' => 1,
            'x' => 0,
        ],
        '<' => [
            'y' => 0,
            'x' => -1,
        ],
        '>' => [
            'y' => 0,
            'x' => 1,
        ],
    ];

    public function handle()
    {
        $this->grid = $this->loadGridFromFile(
            self::USES_SAMPLE ? 'input/day-6-sample.txt' : 'input/day-6.txt',
        );

        $this->info('Step 1: '.$this->step1());
        $this->info('Step 2: '.$this->step2());
    }

    public function step1(): int
    {
        $directions = array_keys(self::DIRECTIONS);

        foreach ($this->grid as $key => $row) {
            foreach ($row as $subKey => $column) {
                if (in_array($this->grid[$key][$subKey], $directions)) {
                    dd($key, $subKey, $column);
                }
            }
        }

        return 0;
    }

    public function step2(): int
    {
        return 0;
    }

    private function loadGridFromFile($filePath): array
    {
        $fileContent = Storage::get($filePath);
        $lines = explode("\n", trim($fileContent));

        return array_map(fn ($line) => str_split($line), $lines);
    }
}
