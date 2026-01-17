<?php

namespace Cvar1984\Math\Statistic\Distribution;

final class NormalDistribution implements DistributionModel
{
    private float $norm;

    public function __construct(
        private float $mean,
        private float $stdDev
    ) {
        $this->norm = 1.0 / ($this->stdDev * sqrt(2 * M_PI));
    }

    public function pdf(float $x): float
    {
        $z = ($x - $this->mean) / $this->stdDev;
        return $this->norm * exp(-0.5 * $z * $z);
    }
}
