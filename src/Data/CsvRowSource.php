<?php

namespace Cvar1984\Math\Statistic\Data;

final class CsvRowSource implements \IteratorAggregate
{
    public function __construct(
        private string $file,
        private string $delimiter = ','
    ) {}

    public function getIterator(): \Traversable
    {
        $fh = fopen($this->file, 'r');
        $header = fgetcsv($fh, 0, $this->delimiter);

        while (($row = fgetcsv($fh, 0, $this->delimiter)) !== false) {
            yield array_combine($header, $row);
        }

        fclose($fh);
    }
}
