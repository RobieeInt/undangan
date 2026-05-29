<?php

namespace App\Http\Controllers;

use App\Services\InvitationResolverService;
use Illuminate\Http\Request;

class InvitationPublicController extends Controller
{
    public function __construct(private InvitationResolverService $resolver) {}

    public function __invoke(Request $request, string $slug)
    {
        $invitation = $this->resolver->resolve($request, $slug);

        if (!$invitation) {
            return $this->notFound($slug);
        }

        $guest    = $this->resolver->resolveGuest($request, $invitation);
        $tplData  = $this->resolver->loadTemplate($invitation);
        $viewName = $tplData['view'];

        // Load all related data via Query Builder (efficient, no N+1)
        $events   = \Illuminate\Support\Facades\DB::table('invitation_events')
            ->where('invitation_id', $invitation->id)
            ->orderBy('sort_order')
            ->get();

        $galleries = \Illuminate\Support\Facades\DB::table('invitation_galleries')
            ->where('invitation_id', $invitation->id)
            ->orderBy('sort_order')
            ->get();

        $gifts = \Illuminate\Support\Facades\DB::table('invitation_gifts')
            ->where('invitation_id', $invitation->id)
            ->orderBy('sort_order')
            ->get();

        $recentWishes = \Illuminate\Support\Facades\DB::table('invitation_rsvps')
            ->where('invitation_id', $invitation->id)
            ->whereNotNull('message')
            ->where('message', '!=', '')
            ->orderByDesc('created_at')
            ->limit(20)
            ->get();

        if (!view()->exists($viewName)) {
            $viewName = 'templates.minimalist-modern.index';
        }

        return view($viewName, array_merge($tplData, compact(
            'invitation', 'guest', 'events', 'galleries', 'gifts', 'recentWishes'
        )));
    }

    /**
     * Editor preview — bypass published/active check, owner only.
     */
    public function preview(\Illuminate\Http\Request $request, \App\Models\Invitation $invitation)
    {
        // Only the owner (or admin) can preview unpublished
        if ($invitation->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $guest    = null;
        $tplData  = $this->resolver->loadTemplate($invitation);
        $viewName = $tplData['view'];

        $events = \Illuminate\Support\Facades\DB::table('invitation_events')
            ->where('invitation_id', $invitation->id)
            ->orderBy('sort_order')->get();

        $galleries = \Illuminate\Support\Facades\DB::table('invitation_galleries')
            ->where('invitation_id', $invitation->id)
            ->orderBy('sort_order')->get();

        $gifts = \Illuminate\Support\Facades\DB::table('invitation_gifts')
            ->where('invitation_id', $invitation->id)
            ->orderBy('sort_order')->get();

        $recentWishes = \Illuminate\Support\Facades\DB::table('invitation_rsvps')
            ->where('invitation_id', $invitation->id)
            ->whereNotNull('message')->where('message', '!=', '')
            ->orderByDesc('created_at')->limit(20)->get();

        if (!view()->exists($viewName)) {
            $viewName = 'templates.minimalist-modern.index';
        }

        return view($viewName, array_merge($tplData, compact(
            'invitation', 'guest', 'events', 'galleries', 'gifts', 'recentWishes'
        )));
    }

    private function notFound(string $slug)
    {
        return response()->view('errors.invitation-not-found', ['slug' => $slug], 404);
    }
}
