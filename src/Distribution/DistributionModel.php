<?php

namespace Cvar1984\Math\Statistic\Distribution;

interface DistributionModel
{
    public function pdf(float $x): float;
}
