<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    public function getInvitationStats(int $invitationId): array
    {
        // Visitor stats
        $totalVisitors = DB::table('visitor_logs')
            ->where('invitation_id', $invitationId)
            ->count();

        $deviceBreakdown = DB::table('visitor_logs')
            ->where('invitation_id', $invitationId)
            ->selectRaw('device_type, COUNT(*) as count')
            ->groupBy('device_type')
            ->pluck('count', 'device_type')
            ->toArray();

        $visitorsByDay = DB::table('visitor_logs')
            ->where('invitation_id', $invitationId)
            ->where('visited_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(visited_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // RSVP stats
        $rsvpStats = DB::table('invitation_rsvps')
            ->where('invitation_id', $invitationId)
            ->selectRaw('attendance, COUNT(*) as count, SUM(guest_count) as total_guests')
            ->groupBy('attendance')
            ->get()
            ->keyBy('attendance');

        $totalRsvp      = DB::table('invitation_rsvps')->where('invitation_id', $invitationId)->count();
        $totalAttending = $rsvpStats->get('hadir')?->total_guests ?? 0;

        // Check-in stats
        $totalCheckins = DB::table('guest_checkins')
            ->where('invitation_id', $invitationId)
            ->count();

        // Conversion rate
        $conversionRate = $totalVisitors > 0
            ? round(($totalRsvp / $totalVisitors) * 100, 1)
            : 0;

        return [
            'total_visitors'    => $totalVisitors,
            'device_breakdown'  => $deviceBreakdown,
            'visitors_by_day'   => $visitorsByDay,
            'total_rsvp'        => $totalRsvp,
            'total_attending'   => $totalAttending,
            'total_checkins'    => $totalCheckins,
            'conversion_rate'   => $conversionRate,
            'rsvp_breakdown'    => $rsvpStats,
        ];
    }

    public function getAdminDashboardStats(): array
    {
        $totalUsers       = DB::table('users')->where('role', 'user')->count();
        $totalInvitations = DB::table('invitations')->where('is_active', true)->count();
        $totalRevenue     = DB::table('transactions')->where('status', 'paid')->sum('gross_amount');
        $monthlyRevenue   = DB::table('transactions')
            ->where('status', 'paid')
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('gross_amount');

        $revenueByMonth = DB::table('transactions')
            ->where('status', 'paid')
            ->where('paid_at', '>=', now()->subMonths(6))
            ->selectRaw("DATE_FORMAT(paid_at, '%Y-%m') as month, SUM(gross_amount) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $packagePopularity = DB::table('transactions')
            ->join('packages', 'transactions.package_id', '=', 'packages.id')
            ->where('transactions.status', 'paid')
            ->selectRaw('packages.name, COUNT(*) as count')
            ->groupBy('packages.name')
            ->get();

        $templatePopularity = DB::table('invitations')
            ->join('templates', 'invitations.template_id', '=', 'templates.id')
            ->selectRaw('templates.name, COUNT(*) as count')
            ->groupBy('templates.name')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        return compact(
            'totalUsers', 'totalInvitations', 'totalRevenue',
            'monthlyRevenue', 'revenueByMonth', 'packagePopularity', 'templatePopularity'
        );
    }
}
