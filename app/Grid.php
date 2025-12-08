<?php

namespace App;

class Grid
{
    private array $grid;   // 2D array of characters
    private int $width;
    private int $height;

    public function __construct(array $grid)
    {
        $this->grid = $grid;
        $this->height = count($grid);
        $this->width  = $this->height > 0 ? count($grid[0]) : 0;
    }

    public static function fromInput(string $input): self
    {
        $lines = array_filter(
            array_map(fn($line) => str_replace("\r", '', $line), explode("\n", $input)),
            fn($line) => $line !== ""
        );

        $grid = array_map(fn($line) => str_split($line), $lines);

        return new self($grid);
    }

    public function getWidth(): int { return $this->width; }
    public function getHeight(): int { return $this->height; }

    public function getRow(int $y): array
    {
        return $this->grid[$y] ?? [];
    }

    public function getColumn(int $x): array
    {
        return array_column($this->grid, $x);
    }

    public function getCell(int $x, int $y)
    {
        return $this->grid[$y][$x] ?? null;
    }

    public function flipHorizontal(): self
    {
        $new = array_map(fn($row) => array_reverse($row), $this->grid);
        return new self($new);
    }

    public function flipVertical(): self
    {
        $new = array_reverse($this->grid);
        return new self($new);
    }

    public function rotateCW(): self
    {
        $rotated = [];
        for ($x = 0; $x < $this->width; $x++) {
            $rotated[] = array_reverse(array_column($this->grid, $x));
        }
        return new self($rotated);
    }

    public function toString(): string
    {
        return implode("\n", array_map(fn($row) => implode('', $row), $this->grid));
    }

    public function inBounds(int $x, int $y): bool
    {
        return $x >= 0 && $x < $this->width &&
            $y >= 0 && $y < $this->height;
    }
}
