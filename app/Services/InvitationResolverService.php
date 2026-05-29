<?php

namespace App\Services;

use App\Models\Invitation;
use App\Models\InvitationGuest;
use App\Models\VisitorLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvitationResolverService
{
    /**
     * Resolve an invitation from a request.
     * Handles both slug-based and custom-domain-based resolution.
     */
    public function resolve(Request $request, string $slug): ?Invitation
    {
        $host = $request->getHost();

        // 1. Try custom domain resolution first
        $invitation = $this->resolveByCustomDomain($host);

        // 2. Fall back to slug resolution
        if (!$invitation) {
            $invitation = $this->resolveBySlug($slug);
        }

        if (!$invitation) return null;

        // 3. Validate the invitation
        if (!$this->validate($invitation)) return null;

        // 4. Log visitor (async-safe, no exception if it fails)
        $this->logVisitor($request, $invitation, $slug);

        // 5. Increment view counter efficiently
        DB::table('invitations')
            ->where('id', $invitation->id)
            ->increment('view_count');

        return $invitation;
    }

    private function resolveByCustomDomain(string $host): ?Invitation
    {
        // Skip if it's the main app domain
        $appHost = parse_url(config('app.url'), PHP_URL_HOST);
        if ($host === $appHost || str_ends_with($host, '.' . $appHost)) {
            return null;
        }

        return DB::table('invitations')
            ->where('custom_domain', $host)
            ->where('is_active', true)
            ->where('is_published', true)
            ->first() ? Invitation::where('custom_domain', $host)->first() : null;
    }

    private function resolveBySlug(string $slug): ?Invitation
    {
        return Invitation::where('slug', $slug)->first();
    }

    public function validate(Invitation $invitation): bool
    {
        // Must be active and published
        if (!$invitation->is_active || !$invitation->is_published) {
            return false;
        }

        // Must not be expired
        if ($invitation->isExpired()) {
            return false;
        }

        return true;
    }

    public function resolveGuest(Request $request, Invitation $invitation): ?InvitationGuest
    {
        $guestSlug = $request->query('tamu');
        if (!$guestSlug) return null;

        return InvitationGuest::where('invitation_id', $invitation->id)
            ->where('slug', $guestSlug)
            ->first();
    }

    public function loadTemplate(Invitation $invitation): array
    {
        $template   = $invitation->template;
        $package    = $invitation->package;

        return [
            'view'          => 'templates.' . $template->slug . '.index',
            'template'      => $template,
            'package'       => $package,
            'show_watermark'=> $package?->has_watermark ?? true,
            'config'        => $template->config ?? [],
        ];
    }

    private function logVisitor(Request $request, Invitation $invitation, string $slug): void
    {
        try {
            $ua          = $request->userAgent() ?? '';
            $deviceType  = $this->detectDevice($ua);
            $guestSlug   = $request->query('tamu');

            DB::table('visitor_logs')->insert([
                'invitation_id' => $invitation->id,
                'ip_address'    => $request->ip(),
                'user_agent'    => substr($ua, 0, 500),
                'device_type'   => $deviceType,
                'referrer'      => substr($request->headers->get('referer', ''), 0, 255),
                'guest_slug'    => $guestSlug,
                'visited_at'    => now(),
            ]);
        } catch (\Throwable $e) {
            // Silently fail — analytics should never break the invitation page
        }
    }

    private function detectDevice(string $ua): string
    {
        $ua = strtolower($ua);
        if (str_contains($ua, 'mobile') || str_contains($ua, 'android') || str_contains($ua, 'iphone')) {
            return 'mobile';
        }
        if (str_contains($ua, 'tablet') || str_contains($ua, 'ipad')) {
            return 'tablet';
        }
        return 'desktop';
    }
}
