<?php

namespace App\Livewire\Dashboard;

use App\Models\Invitation;
use App\Models\Template;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;

class InvitationList extends Component
{
    public bool $showCreateModal  = false;
    public string $groomName      = '';
    public string $brideName      = '';
    public int $selectedTemplate  = 0;
    public ?int $newInvitationId  = null;

    // ID undangan yang akan dihapus (null = modal tutup)
    public ?int $confirmDeleteId  = null;

    // Batas maksimal undangan belum aktif per user
    const MAX_INACTIVE = 2;

    public function openCreateModal(): void
    {
        $this->showCreateModal = true;
    }

    public function closeModal(): void
    {
        $this->showCreateModal = false;
        $this->resetValidation();
        $this->reset(['groomName', 'brideName', 'selectedTemplate']);
    }

    public function createInvitation(): void
    {
        $this->validate([
            'groomName'        => 'required|string|max:100',
            'brideName'        => 'required|string|max:100',
            'selectedTemplate' => 'required|integer|min:1|exists:templates,id',
        ]);

        // Cek batas undangan belum aktif (admin bebas, user biasa max 2)
        if (!auth()->user()->isAdmin()) {
            $inactiveCount = Invitation::where('user_id', auth()->id())
                ->where('is_active', false)
                ->count();

            if ($inactiveCount >= self::MAX_INACTIVE) {
                $this->addError('limit', 'Kamu sudah memiliki ' . self::MAX_INACTIVE . ' undangan yang belum diaktifkan. Aktifkan atau hapus salah satunya terlebih dahulu.');
                return;
            }
        }

        $template = Template::findOrFail($this->selectedTemplate);

        // Generate unique slug
        $baseSlug = Str::slug($this->groomName . '-' . $this->brideName);
        $slug     = $baseSlug;
        $i        = 1;
        while (DB::table('invitations')->where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $i++;
        }

        $invitation = Invitation::create([
            'user_id'     => auth()->id(),
            'template_id' => $template->id,
            'slug'        => $slug,
            'groom_name'  => $this->groomName,
            'bride_name'  => $this->brideName,
        ]);

        DB::table('templates')->where('id', $template->id)->increment('usage_count');

        $this->showCreateModal = false;
        $this->reset(['groomName', 'brideName', 'selectedTemplate']);

        // Langsung ke halaman pilih paket — editor hanya bisa diakses setelah bayar
        $this->redirect(route('payment.select-package', $invitation->id));
    }

    // ── Hapus undangan ──────────────────────────────────────

    public function confirmDelete(int $id): void
    {
        $invitation = Invitation::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Hanya boleh hapus yang belum aktif
        if ($invitation->is_active) {
            return;
        }

        $this->confirmDeleteId = $id;
    }

    public function cancelDelete(): void
    {
        $this->confirmDeleteId = null;
    }

    public function deleteInvitation(): void
    {
        if (!$this->confirmDeleteId) return;

        $invitation = Invitation::where('id', $this->confirmDeleteId)
            ->where('user_id', auth()->id())
            ->where('is_active', false) // double-check: hanya yang belum aktif
            ->firstOrFail();

        // Hapus semua file yang diupload (foto, galeri, dll)
        Storage::disk('public')->deleteDirectory('invitations/' . $invitation->id);

        // Hapus record (cascade delete handles children: events, gallery, guests, rsvps, dll)
        $invitation->delete();

        $this->confirmDeleteId = null;
    }

    public function render()
    {
        $userId = auth()->id();

        $invitations = DB::table('invitations')
            ->where('invitations.user_id', $userId)
            ->join('templates', 'invitations.template_id', '=', 'templates.id')
            ->leftJoin('packages', 'invitations.package_id', '=', 'packages.id')
            ->select(
                'invitations.id', 'invitations.slug',
                'invitations.groom_name', 'invitations.bride_name',
                'invitations.is_active', 'invitations.is_published',
                'invitations.expires_at', 'invitations.view_count',
                'invitations.created_at',
                'templates.name as template_name', 'templates.slug as template_slug',
                'templates.thumbnail as template_thumbnail',
                'packages.name as package_name'
            )
            ->orderByDesc('invitations.created_at')
            ->get();

        $inactiveCount = $invitations->where('is_active', false)->count();
        $atLimit       = !auth()->user()->isAdmin() && $inactiveCount >= self::MAX_INACTIVE;

        $templates = Template::active()->ordered()->get();

        return view('livewire.dashboard.invitation-list', compact('invitations', 'templates', 'inactiveCount', 'atLimit'));
    }
}
