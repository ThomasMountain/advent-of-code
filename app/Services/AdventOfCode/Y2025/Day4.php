<?php

namespace App\Services\AdventOfCode\Y2025;

use App\Contracts\AdventChallenge;
use App\Services\AdventOfCode\BaseChallenge;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Day4 extends BaseChallenge implements AdventChallenge
{
    public function handleStep1(string $input): mixed
    {
        $total = 0;
        $grid = $this->parseGrid($input);

        foreach ($grid as $gridIndex => $line) {
            foreach ($line as $lineIndex => $item) {
                if($item === '.') {
                    continue;
                }
                $neighbours = $this->getNeighbors($grid, $gridIndex, $lineIndex);
                $filteredNeighbours = array_filter($neighbours, fn($n) => $n !== '.');
                if (count($filteredNeighbours) < 4) {
                    $total++;
                }
            }
        }

        return $total;
    }

    public function handleStep2(string $input): mixed
    {
        return "TODO: Implement step";
    }
}
