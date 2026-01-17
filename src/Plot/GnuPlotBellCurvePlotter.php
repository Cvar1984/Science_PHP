<?php

namespace Cvar1984\Math\Statistic\Plot;

use Gregwar\GnuPlot\GnuPlot;
use Cvar1984\Math\Statistic\Distribution\DistributionModel;

final class GnuPlotBellCurvePlotter
{
    private GnuPlot $gp;

    public function __construct(
        private string $filename,
        private int $bins = 50
    ) {
        $this->gp = new GnuPlot();
        $this->gp->setWidth(1200);
        $this->gp->setHeight(800);
        $this->gp->setGraphTitle('Gaussian Histogram');
        $this->gp->setXLabel('x');
        $this->gp->setYLabel('Density');
    }

    public function plot(
        iterable $data,
        ?DistributionModel $gaussian,
        float $xmin,
        float $xmax
    ): void {
        $binWidth = ($xmax - $xmin) / $this->bins;
        $hist = array_fill(0, $this->bins, 0);
        $n = 0;

        foreach ($data as $x) {
            $x = (float)$x;
            if ($x < $xmin || $x >= $xmax) {
                continue;
            }
            $i = (int)(($x - $xmin) / $binWidth);
            $hist[$i]++;
            $n++;
        }

        // Histogram curve (0)
        foreach ($hist as $i => $count) {
            $x = $xmin + ($i + 0.5) * $binWidth;
            $density = $count / ($n * $binWidth);
            $this->gp->push($x, $density, 0);
        }
        $this->gp->setTitle(0, 'Histogram');

        // Gaussian overlay (1)
        if ($gaussian !== null) {
            $step = $binWidth / 5;
            for ($x = $xmin; $x <= $xmax; $x += $step) {
                $this->gp->push($x, $gaussian->pdf($x), 1);
            }
            $this->gp->setTitle(1, 'Gaussian PDF');
        }

        $this->gp->setXRange($xmin, $xmax);
        $this->gp->writePng($this->filename);
    }
}
