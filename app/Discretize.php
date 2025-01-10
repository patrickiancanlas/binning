<?php

namespace App;

class Discretize
{
    public function __construct(private Array $data, private string $filterType)
    {
        sort($this->data, SORT_NUMERIC);
    }

    public function run()
    {
        if ($this->filterType == 'equalWidth') {
            return $this->equalWidth();
        } elseif ($this->filterType == 'equalFrequency') {
            return $this->equalFrequency();
        }
    }

    private function equalWidth(): Array
    {
        $binWidth = (end($this->data) - $this->data[0]) / 3;

        return [
            'high' => array_filter($this->data, fn($n) => ($n >= $binWidth * 2 && $n <= end($this->data))),
            'medium' => array_filter($this->data, fn($n) => ($n >= $binWidth && $n < $binWidth * 2)),
            'low' => array_filter($this->data, fn($n) => ($n >= $this->data[0] && $n < $binWidth)),
        ];
    }

    private function equalFrequency(): Array
    {
        return array_combine(['high', 'medium', 'low'], array_reverse(array_chunk($this->data, 3)));
    }
}