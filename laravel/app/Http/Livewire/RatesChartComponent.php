<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Charts\ExchangeRateChart;

class RatesChartComponent extends Component
{
    protected ExchangeRateChart $chart;

    /**
     * Renders the view component.
     */
    public function render()
    {
        $this->chart = new ExchangeRateChart();
        $this->chart->labels(['A', 'B', 'C', 'D']);
        $this->chart->dataset('test', 'line', [1, 2, 3, rand(0, 5)]);
        return view('livewire.rates-chart-component', [
            'chart' => $this->chart
        ]);
        $this->emit(
            'chartUpdate',
            $this->chart->id,
            $this->chart->labels(),
            $this->chart->datasets()
        );
    }
}
