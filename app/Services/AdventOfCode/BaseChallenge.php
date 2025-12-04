<?php

namespace App\Services\AdventOfCode;

use Illuminate\Support\Facades\Storage;

abstract class BaseChallenge
{
    public function getInput(int $day, int $year): string
    {
        return Storage::get("input/{$year}/day-{$day}.txt");
    }

    public function getSampleInput(int $day, int $year): string
    {
        return Storage::get("sample/{$year}/day-{$day}.txt");
    }

    public function getLines(string $input): array
    {
        $linesArray = array_map(fn($line) => str_replace("\r", '', $line), explode("\n", $input));
        return array_filter($linesArray, fn($line) => $line !== "");
    }

    public function getCsv(string $input): array
    {
        return str_getcsv($input);
    }

    public function parseGrid(string $input): array
    {
        $lines = $this->getLines($input);
        $grid = [];
        foreach ($lines as $line) {
            $grid[] = str_split($line);
        }
        return $grid;
    }

    public function getNeighbors(array $grid, int $row, int $col): array
    {
        $neighbors = [];
        for ($i = $row - 1; $i <= $row + 1; $i++) {
            for ($j = $col - 1; $j <= $col + 1; $j++) {
                if (
                    $i >= 0 && $i < count($grid) &&
                    $j >= 0 && $j < count($grid[$i]) &&
                    !($i === $row && $j === $col)
                ) {
                    $neighbors[] = $grid[$i][$j];
                }
            }
        }
        return $neighbors;
    }

}
