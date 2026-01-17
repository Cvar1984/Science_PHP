<?php

namespace Cvar1984\Math\Statistic\Statistics;

final class Statistics
{
    private int $n = 0;
    private float $mean = 0.0;
    private float $M2 = 0.0;

    public function ingest(iterable $data): void
    {
        foreach ($data as $x) {
            $x = (float)$x;
            $this->n++;

            $delta = $x - $this->mean;
            $this->mean += $delta / $this->n;
            $this->M2 += $delta * ($x - $this->mean);
        }
    }

    public function mean(): float
    {
        return $this->mean;
    }

    public function variance(): float
    {
        return $this->n > 1 ? $this->M2 / ($this->n - 1) : 0.0;
    }

    public function stdDev(): float
    {
        return sqrt($this->variance());
    }
}
