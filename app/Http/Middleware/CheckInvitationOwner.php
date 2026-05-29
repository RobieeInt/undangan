<?php

namespace App\Http\Middleware;

use App\Models\Invitation;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckInvitationOwner
{
    public function handle(Request $request, Closure $next): Response
    {
        $invitation = $request->route('invitation');

        // Support both model binding and ID
        if (is_numeric($invitation)) {
            $invitation = Invitation::findOrFail($invitation);
        }

        if (!$invitation || $invitation->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke undangan ini.');
        }

        return $next($request);
    }
}
