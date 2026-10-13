<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\StatisticsService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request, StatisticsService $stats)
    {
        $request->validate([
            'period' => 'nullable|in:today,week,month,custom',
            'from' => 'required_if:period,custom|nullable|date',
            'to' => 'required_if:period,custom|nullable|date|after_or_equal:from',
        ]);

        [$from, $to] = $stats->range($request->all());

        return view('admin.dashboard', [
            'stats' => $stats->dashboard($from, $to),
            'from' => $from, 'to' => $to,
            'period' => $request->input('period', 'month'),
        ]);
    }
}