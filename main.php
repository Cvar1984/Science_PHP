<?php

require __DIR__ . '/vendor/autoload.php';

use Cvar1984\Math\Statistic\Data\CsvRowSource;
use Cvar1984\Math\Statistic\Data\ScalarFieldSource;
use Cvar1984\Math\Statistic\Data\RowFilter;
use Cvar1984\Math\Statistic\Statistics\Statistics;
use Cvar1984\Math\Statistic\Distribution\NormalDistribution;
use Cvar1984\Math\Statistic\Plot\GnuPlotBellCurvePlotter;

$rows = new CsvRowSource(
    file: 'star_dataset.csv', //https://www.kaggle.com/datasets/waqi786/stars-dataset
);

/**
 * Spread of all stellar temperatures
 */
$temp = new ScalarFieldSource(
    rows: $rows,
    field: 'Temperature (K)'
);

/**
 * Spread of A-type stars only
 */
$filtered = new RowFilter(
    source: $rows,
    predicate: fn($r) => str_starts_with($r['Spectral Class'], 'A')
);
$tempA = new ScalarFieldSource(
    rows: $filtered,
    field: 'Temperature (K)'
);

$stats = new Statistics();
$stats->ingest($tempA);

echo "Mean temperature: {$stats->mean()} K\n";
echo "Std dev: {$stats->stdDev()} K\n";

$gaussian = new NormalDistribution(
    $stats->mean(),
    $stats->stdDev()
);

$plotter = new GnuPlotBellCurvePlotter(
    filename: 'temperature_spread.png',
    bins: 60
);

$plotter->plot($tempA, $gaussian, 2000, 40000);
