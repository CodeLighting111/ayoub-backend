<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\StatisticsService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StatisticsController extends Controller
{
    public function __construct(private readonly StatisticsService $statisticsService) {}

    public function index(Request $request): View
    {
        ['month_from' => $monthFrom, 'month_to' => $monthTo, 'year' => $year] = $this->statisticsService->resolvePeriod(
            is_numeric($request->query('month_from')) ? (int) $request->query('month_from') : null,
            is_numeric($request->query('month_to')) ? (int) $request->query('month_to') : null,
            is_numeric($request->query('year')) ? (int) $request->query('year') : null,
        );

        $stats = $this->statisticsService->forPeriod($monthFrom, $monthTo, $year);

        return view('dashboard.statistics.index', [
            'activeMenu' => 'statistics',
            'monthFrom' => $monthFrom,
            'monthTo' => $monthTo,
            'year' => $year,
            'arabicMonths' => $this->statisticsService->arabicMonths(),
            'periodLabel' => $this->statisticsService->periodLabel($monthFrom, $monthTo, $year),
            'stats' => $stats,
        ]);
    }
}
