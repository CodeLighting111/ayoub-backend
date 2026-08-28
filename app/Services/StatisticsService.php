<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Complaint;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Collection;

class StatisticsService
{
    public function arabicMonths(): array
    {
        return [
            1 => 'يناير',
            2 => 'فبراير',
            3 => 'مارس',
            4 => 'أبريل',
            5 => 'مايو',
            6 => 'يونيو',
            7 => 'يوليو',
            8 => 'أغسطس',
            9 => 'سبتمبر',
            10 => 'أكتوبر',
            11 => 'نوفمبر',
            12 => 'ديسمبر',
        ];
    }

    public function resolvePeriod(?int $monthFrom, ?int $monthTo, ?int $year): array
    {
        $year = ($year >= 2000 && $year <= 2100) ? $year : (int) now()->year;
        $currentMonth = (int) now()->month;

        $monthFrom = ($monthFrom >= 1 && $monthFrom <= 12) ? $monthFrom : $currentMonth;
        $monthTo = ($monthTo >= 1 && $monthTo <= 12) ? $monthTo : $currentMonth;

        if ($monthFrom > $monthTo) {
            [$monthFrom, $monthTo] = [$monthTo, $monthFrom];
        }

        return [
            'month_from' => $monthFrom,
            'month_to' => $monthTo,
            'year' => $year,
        ];
    }

    public function periodLabel(int $monthFrom, int $monthTo, int $year): string
    {
        $months = $this->arabicMonths();

        if ($monthFrom === $monthTo) {
            return $months[$monthFrom].' '.$year;
        }

        return 'من '.$months[$monthFrom].' إلى '.$months[$monthTo].' '.$year;
    }

    public function forPeriod(int $monthFrom, int $monthTo, int $year): array
    {
        $ordersQuery = $this->ordersInPeriod($monthFrom, $monthTo, $year);
        $activeOrdersQuery = (clone $ordersQuery)->where('status', '!=', 'cancelled');

        $ordersTotal = (clone $ordersQuery)->count();
        $revenue = (float) (clone $activeOrdersQuery)->sum('total');
        $deliveredCount = (clone $ordersQuery)->where('status', 'delivered')->count();
        $cancelledCount = (clone $ordersQuery)->where('status', 'cancelled')->count();
        $activeOrdersCount = (clone $activeOrdersQuery)->count();
        $averageOrderValue = $activeOrdersCount > 0
            ? round($revenue / $activeOrdersCount, 2)
            : 0.0;

        $ordersByStatus = [];
        foreach (Order::STATUSES as $status) {
            $ordersByStatus[$status] = (clone $ordersQuery)->where('status', $status)->count();
        }

        $complaintsQuery = $this->applyMonthRange(
            Complaint::query(),
            $year,
            $monthFrom,
            $monthTo,
        );

        $complaintsTotal = (clone $complaintsQuery)->count();
        $complaintsByStatus = [];
        foreach (Complaint::STATUSES as $status) {
            $complaintsByStatus[$status] = (clone $complaintsQuery)->where('status', $status)->count();
        }

        $newClientsCount = $this->applyMonthRange(
            Client::query(),
            $year,
            $monthFrom,
            $monthTo,
        )->count();

        return [
            'orders_total' => $ordersTotal,
            'revenue' => $revenue,
            'delivered_count' => $deliveredCount,
            'cancelled_count' => $cancelledCount,
            'average_order_value' => $averageOrderValue,
            'orders_by_status' => $ordersByStatus,
            'complaints_total' => $complaintsTotal,
            'complaints_by_status' => $complaintsByStatus,
            'new_clients_count' => $newClientsCount,
            'top_clients' => $this->topClients($monthFrom, $monthTo, $year),
            'top_products' => $this->topProducts($monthFrom, $monthTo, $year),
            'payment_methods' => $this->paymentMethodBreakdown($monthFrom, $monthTo, $year),
        ];
    }

    private function ordersInPeriod(int $monthFrom, int $monthTo, int $year): Builder
    {
        return $this->applyMonthRange(Order::query(), $year, $monthFrom, $monthTo);
    }

    /**
     * @param  Builder|QueryBuilder  $query
     * @return Builder|QueryBuilder
     */
    private function applyMonthRange(Builder|QueryBuilder $query, int $year, int $monthFrom, int $monthTo, string $column = 'created_at')
    {
        return $query
            ->whereYear($column, $year)
            ->whereRaw('MONTH('.$column.') BETWEEN ? AND ?', [$monthFrom, $monthTo]);
    }

    private function topClients(int $monthFrom, int $monthTo, int $year): Collection
    {
        return $this->applyMonthRange(Order::query(), $year, $monthFrom, $monthTo)
            ->select('client_id')
            ->selectRaw('MAX(client_name) as client_name')
            ->selectRaw('COUNT(*) as orders_count')
            ->selectRaw('SUM(CASE WHEN status != ? THEN total ELSE 0 END) as revenue', ['cancelled'])
            ->groupBy('client_id')
            ->orderByDesc('revenue')
            ->orderByDesc('orders_count')
            ->limit(5)
            ->get();
    }

    private function topProducts(int $monthFrom, int $monthTo, int $year): Collection
    {
        return OrderItem::query()
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.status', '!=', 'cancelled')
            ->whereYear('orders.created_at', $year)
            ->whereRaw('MONTH(orders.created_at) BETWEEN ? AND ?', [$monthFrom, $monthTo])
            ->select('order_items.product_name')
            ->selectRaw('SUM(order_items.quantity) as total_quantity')
            ->selectRaw('SUM(order_items.line_total) as total_revenue')
            ->groupBy('order_items.product_name')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();
    }

    private function paymentMethodBreakdown(int $monthFrom, int $monthTo, int $year): array
    {
        $rows = $this->applyMonthRange(Order::query(), $year, $monthFrom, $monthTo)
            ->select('payment_method')
            ->selectRaw('COUNT(*) as orders_count')
            ->selectRaw('SUM(CASE WHEN status != ? THEN total ELSE 0 END) as revenue', ['cancelled'])
            ->groupBy('payment_method')
            ->orderByDesc('orders_count')
            ->get();

        $labels = [
            'cash' => 'كاش',
            'wallet' => 'محفظة إلكترونية',
            'bank_transfer' => 'تحويل بنكي',
        ];

        return $rows->map(function ($row) use ($labels) {
            return [
                'key' => $row->payment_method,
                'label' => $labels[$row->payment_method] ?? $row->payment_method,
                'orders_count' => (int) $row->orders_count,
                'revenue' => (float) $row->revenue,
            ];
        })->all();
    }
}
