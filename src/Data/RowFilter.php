<?php

namespace Cvar1984\Math\Statistic\Data;

final class RowFilter implements \IteratorAggregate
{
    public function __construct(
        private iterable $source,
        private \Closure $predicate
    ) {}

    public function getIterator(): \Traversable
    {
        foreach ($this->source as $row) {
            if (($this->predicate)($row)) {
                yield $row;
            }
        }
    }
}
