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

    public function step(float $dt, $force, float $t)
    {
        if (is_callable($force)) {
            $F = $force($t, $this->x, $this->v);
        } else {
            $F = $force;
        }

        // acceleration
        $a = $F / $this->m;

        // Euler integration
        $this->v += $a * $dt;
        $this->x += $this->v * $dt;
    }
}

// -----------------------------
// Simulation (gravity)
// -----------------------------
$mass = 1.0;
$g = 9.81;

$sim = new MotionSolver(100, 0, $mass);
$dt = 0.01;
$t  = 0;

// Create GnuPlot instance
$gp = new GnuPlot();

// Curve 0: position
$gp->setTitle(0, "Position (m)");

// Curve 1: velocity
$gp->setTitle(1, "Velocity (m/s)");

while ($t <= 5) {
    // Add points using the low-level API
    $gp->push($t, $sim->x, 0); // curve 0 -> x(t)
    $gp->push($t, $sim->v, 1); // curve 1 -> v(t)

    // Step simulation
    $sim->step($dt, -$mass * $g, $t);

    $t += $dt;
}

// Graph configuration
$gp->setGraphTitle("Motion Under Gravity");
$gp->setXLabel("Time (s)");
$gp->setYLabel("Value");
$gp->setWidth(800);
$gp->setHeight(600);

// Optional axis ranges
// $gp->setXRange(0, 5);
// $gp->setYRange(-60, 100);

// Save to PNG
$gp->writePng("motion.png");

echo "Saved graph to motion.png\n";
