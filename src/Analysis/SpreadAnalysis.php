<?php

namespace Cvar1984\Math\Statistic\Analysis;

use Cvar1984\Math\Statistic\Data\DataSource;
use Cvar1984\Math\Statistic\Statistics\MeanVector;
use Cvar1984\Math\Statistic\Statistics\CovarianceMatrix;

final class SpreadAnalysis
{
    public function analyze(DataSource $source): array
    {
        $mean = new MeanVector();
        $cov = new CovarianceMatrix();

        foreach ($source as $v) {
            $mean->ingest($v);
            $cov->ingest($v);
        }

        return [
            'mean' => $mean->value(),
            'covariance' => $cov->matrix()
        ];
    }
}
