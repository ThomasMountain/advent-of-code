<?php

namespace App\Services\AdventOfCode;

use Illuminate\Support\Facades\Storage;

abstract class BaseChallenge
{
    public function getInput(int $day): string
    {
        return Storage::get("input/day-{$day}.txt");
    }

    public function getLines(string $input): array
    {
        return array_map(fn($line) => str_replace("\r", '', $line), explode("\n", $input));
    }
}
