<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ReportTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Laravel\Cashier\Subscription;

class ReportController extends Controller
{
    use ReportTrait;

    public function subscriptions()
    {
        $query = Subscription::query();

        return $this->prepareDataForBarChart($query, 'Subscriptions By Day');
    }

    public function subscribers()
    {
        $query = User::query();

        return $this->prepareDataForBarChart($query, 'Subscribers By Day');
    }

    private function prepareDataForBarChart($query, $label)
    {
        $fromDate = $this->getFromDate() ?: Carbon::now()->subDay(30);
        $query
            ->selectRaw('DATE(created_at) AS day, COUNT(created_at) AS count')
            ->groupBy('day');
        if ($fromDate) {
            $query->where('created_at', '>', $fromDate);
        }
        $records = $query->get()->keyBy('day');

        // Process for chartjs
        $days = [];
        $labels = [];
        $now = Carbon::now();
        while ($fromDate < $now) {
            $key = $fromDate->format('Y-m-d');
            $labels[] = $key;
            $fromDate = $fromDate->addDay();
            $days[] = isset($records[$key]) ? $records[$key]['count'] : 0;
        }

        return [
            'labels' => $labels,
            'datasets' => [[
                'label' => $label,
                'backgroundColor' => '#f87979',
                'data' => $days,
            ]]
        ];
    }
}