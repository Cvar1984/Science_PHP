<?php

namespace Cvar1984\Math\Statistic\Data;

final class ScalarFieldSource implements \IteratorAggregate
{
    public function __construct(
        private iterable $rows,
        private string $field,
        private ?\Closure $transform = null
    ) {}

    public function getIterator(): \Traversable
    {
        foreach ($this->rows as $row) {
            if (!isset($row[$this->field])) {
                continue;
            }

            $value = (float)$row[$this->field];

            yield $this->transform
                ? ($this->transform)($value)
                : $value;
        }
    }
}
