<?php

namespace App\Livewire\Invitation;

use App\Models\Invitation;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class GuestWishes extends Component
{
    public Invitation $invitation;
    public int $page = 1;
    public int $perPage = 10;
    public array $wishes = [];
    public int $total = 0;
    public bool $hasMore = false;

    public function mount(Invitation $invitation)
    {
        $this->invitation = $invitation;
        $this->loadWishes();
    }

    public function loadWishes(): void
    {
        $query = DB::table('invitation_rsvps')
            ->where('invitation_id', $this->invitation->id)
            ->whereNotNull('message')
            ->where('message', '!=', '');

        $this->total = $query->count();

        $results = $query->orderByDesc('created_at')
            ->limit($this->page * $this->perPage)
            ->get(['name', 'attendance', 'message', 'created_at']);

        $this->wishes  = $results->toArray();
        $this->hasMore = $this->total > count($this->wishes);
    }

    public function loadMore(): void
    {
        $this->page++;
        $this->loadWishes();
    }

    // Listen for new RSVP submission
    #[On('rsvp-submitted')]
    public function refreshWishes(): void
    {
        $this->page = 1;
        $this->loadWishes();
    }

    public function render()
    {
        return view('livewire.invitation.guest-wishes');
    }
}
