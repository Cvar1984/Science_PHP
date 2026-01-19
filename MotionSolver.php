<?php

require 'vendor/autoload.php';

use Gregwar\GnuPlot\GnuPlot;

class MotionSolver
{
    public float $x;
    public float $v;
    public float $m;

    public function __construct(float $x0, float $v0, float $mass)
    {
        $this->x = $x0;
        $this->v = $v0;
        $this->m = $mass;
    }

    // Semi-implicit Euler (symplectic)
    public function step(float $dt, float $force)
    {
        $a = $force / $this->m;
        $this->v += $a * $dt;
        $this->x += $this->v * $dt;
    }
}

// -----------------------------
// Parameters
// -----------------------------
$mass = 1.0;
$g    = 9.81;

$sim = new MotionSolver(100, 0, $mass);
$dt  = 0.0001;
$t   = 0;

// -----------------------------
// GnuPlot
// -----------------------------
$gp = new GnuPlot();

// Curve titles
$gp->setTitle(0, "Position (m)");
$gp->setTitle(1, "Velocity (m/s)");

// Initial point
$gp->push($t, $sim->x, 0);
$gp->push($t, $sim->v, 1);

// -----------------------------
// Simulation loop (until ground hit)
// -----------------------------
while (true) {
    $xPrev = $sim->x;
    $vPrev = $sim->v;
    $tPrev = $t;

    $sim->step($dt, -$mass * $g);
    $t += $dt;

    // Ground impact interpolation
    if ($sim->x <= 0.0) {
        $alpha = $xPrev / ($xPrev - $sim->x);
        $tHit  = $tPrev + $alpha * $dt;
        $vHit  = $vPrev - $g * ($alpha * $dt);

        $gp->push($tHit, 0.0, 0);
        $gp->push($tHit, $vHit, 1);
        break;
    }

    $gp->push($t, $sim->x, 0);
    $gp->push($t, $sim->v, 1);
}

// -----------------------------
// Graph configuration
// -----------------------------
$gp->setGraphTitle("Free Fall Until Ground Impact");
$gp->setXLabel("Time (s)");
$gp->setYLabel("Value");
$gp->setWidth(2000);
$gp->setHeight(800);

// Optional axis limits
// $gp->setXRange(0, $t);
// $gp->setYRange(-50, 110);

// Save PNG
$gp->writePng("motion.png");

echo "Saved graph to motion.png\n";
