<?php

namespace App\Http\Controllers;

use App\Charts\ExchangeRateChart;

class ChartController extends Controller
{
    /**
     * Show chart.
     *
     */
    public function view()
    {
        $chart = new ExchangeRateChart();
        $chart->labels(['One', 'Two', 'Three']);
        $chart->dataset('test', 'line', [1, 2, 3, 4]);
        return view('chart', compact('chart'));
    }
}
