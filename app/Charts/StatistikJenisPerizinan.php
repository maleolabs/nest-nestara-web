<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class StatistikJenisPerizinan
{

    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build()
    {
        return $this->chart->pieChart()
            ->setTitle('Statistik Jenis Perizinan')
            ->setSubtitle('Season 2021.')
            ->addData([40, 50, 30])
            ->setLabels(['Player 7', 'Player 10', 'Player 9']);
    }

}