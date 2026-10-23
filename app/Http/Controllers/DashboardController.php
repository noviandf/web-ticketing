<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // Kita ubah nama fungsinya dari dashboard() menjadi index() 
    // karena ini adalah fungsi utama (dan satu-satunya) di controller ini.
    public function index(Request $request)
    {
        $user = Auth::user();
        $baseQuery = Ticket::query();

        // Filter Bulan
        if ($request->filled('filter_month')) {
            $baseQuery->where('created_at', 'like', $request->filter_month . '%');
        }

        // Filter User Biasa
        if ($user->role != 'admin') {
            $baseQuery->where('user_id', $user->id);
        }

        // --- A. DATA STATISTIK (ADMIN/MANAGEMENT) ---
        if ($user->role == 'admin') {
            $totalTickets      = (clone $baseQuery)->count();
            $openTickets       = (clone $baseQuery)->where('status', 'open')->count();
            $inProgressTickets = (clone $baseQuery)->where('status', 'in_progress')->count();
            $pendingTickets    = (clone $baseQuery)->where('status', 'pending')->count();
            $resolvedTickets   = (clone $baseQuery)->where('status', 'resolved')->count();
            $closedTickets     = (clone $baseQuery)->where('status', 'closed')->count();
            $cancelledTickets  = (clone $baseQuery)->where('status', 'cancelled')->count();

            $chartType = $request->get('chart_type', 'type');

            if ($chartType == 'category') {
                // Data Grafik (Berdasarkan Category Besar)
                $ticketIds = (clone $baseQuery)->pluck('id');
                $categoriesChart = Category::withCount(['tickets' => function ($q) use ($ticketIds) {
                    $q->whereIn('id', $ticketIds);
                }])->where('name', '!=', 'User Access (Login/Email)')->get();

                $chartLabels = $categoriesChart->pluck('name');
                $chartValues = $categoriesChart->pluck('tickets_count');
            } elseif ($chartType == 'priority') {
                // Data Grafik (Berdasarkan Prioritas)
                $categoriesChart = (clone $baseQuery)
                    ->selectRaw('priority as name, count(*) as tickets_count')
                    ->groupBy('priority')
                    ->get();

                $chartLabels = $categoriesChart->pluck('name')->map(fn($val) => strtoupper($val));
                $chartValues = $categoriesChart->pluck('tickets_count');
            } elseif ($chartType == 'lingkup') {
                // Data Grafik (Berdasarkan Lingkup Internal/Eksternal)
                $eksternalCount = (clone $baseQuery)->where('scope', 'eksternal')->count();
                
                $totalCount = (clone $baseQuery)->count();
                $internalCount = $totalCount - $eksternalCount;

                $chartLabels = collect(['INTERNAL', 'EKSTERNAL']);
                $chartValues = collect([$internalCount, $eksternalCount]);
            } else {
                // Data Grafik (Berdasarkan item_type)
                $categoriesChart = (clone $baseQuery)
                    ->whereNotNull('item_type')
                    ->selectRaw('item_type as name, count(*) as tickets_count')
                    ->groupBy('item_type')
                    ->orderByDesc('tickets_count')
                    ->limit(7)
                    ->get();

                $chartLabels = $categoriesChart->pluck('name');
                $chartValues = $categoriesChart->pluck('tickets_count');
            }

            // Kalkulasi SLA
            $resolvedOrClosedTickets = (clone $baseQuery)->whereIn('status', ['resolved', 'closed'])->with('category')->get();
            $totalMinutesOverall = 0;
            $totalMinutesInternal = 0;
            $totalMinutesExternal = 0;
            
            $countOverall = $resolvedOrClosedTickets->count();
            $countInternal = 0;
            $countExternal = 0;

            foreach ($resolvedOrClosedTickets as $t) {
                $end_time = $t->resolved_at ?? $t->updated_at;
                $minutes = $t->created_at->diffInMinutes($end_time);
                
                $totalMinutesOverall += $minutes;
                $isExternal = $t->scope === 'eksternal';
                
                if ($isExternal) {
                    $totalMinutesExternal += $minutes;
                    $countExternal++;
                } else {
                    $totalMinutesInternal += $minutes;
                    $countInternal++;
                }
            }

            $formatSla = function($minutes) {
                if ($minutes == 0) return "-";
                $h = floor($minutes / 60);
                $m = $minutes % 60;
                return "{$h} Jam {$m} Menit";
            };

            $slaAverage = $countOverall > 0 ? $formatSla(round($totalMinutesOverall / $countOverall)) : "-";
            $slaInternal = $countInternal > 0 ? $formatSla(round($totalMinutesInternal / $countInternal)) : "-";
            $slaExternal = $countExternal > 0 ? $formatSla(round($totalMinutesExternal / $countExternal)) : "-";
        } else {
            // User biasa set 0
            $totalTickets = $openTickets = $inProgressTickets = $pendingTickets = 0;
            $resolvedTickets = $closedTickets = $cancelledTickets = 0;
            $chartLabels = [];
            $chartValues = [];
            $slaAverage = "-";
            $slaInternal = "-";
            $slaExternal = "-";
        }

        // --- B. DATA TABEL ---
        $tableQuery = (clone $baseQuery)->with(['user', 'category']);

        if ($request->filled('search')) {
            $search = $request->search;
            $tableQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $tableQuery->where('status', $request->status);
        }

        $tickets = $tableQuery->latest()->paginate(10)->withQueryString();

        return view('dashboard', compact(
            'tickets',
            'totalTickets',
            'openTickets',
            'inProgressTickets',
            'pendingTickets',
            'resolvedTickets',
            'closedTickets',
            'cancelledTickets',
            'chartLabels',
            'chartValues',
            'slaAverage',
            'slaInternal',
            'slaExternal'
        ));
    }
}
