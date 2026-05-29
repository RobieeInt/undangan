<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user         = auth()->user();
        $invitations  = DB::table('invitations')
            ->where('user_id', $user->id)
            ->join('templates', 'invitations.template_id', '=', 'templates.id')
            ->leftJoin('packages', 'invitations.package_id', '=', 'packages.id')
            ->select(
                'invitations.*',
                'templates.name as template_name',
                'templates.slug as template_slug',
                'templates.thumbnail as template_thumbnail',
                'packages.name as package_name'
            )
            ->orderByDesc('invitations.created_at')
            ->paginate(9);

        return view('dashboard.index', compact('invitations'));
    }
}
