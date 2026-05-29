<?php

namespace App\Livewire\Editor;

use App\Models\Invitation;
use App\Models\InvitationEvent;
use App\Models\InvitationGallery;
use App\Models\InvitationGift;
use App\Models\Package;
use App\Services\StorageService;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;

class InvitationEditor extends Component
{
    use WithFileUploads;

    public Invitation $invitation;
    public string $activeTab = 'informasi';
    public string $autoSaveStatus = '';
    public bool $showPreview = false;
    public bool $isSaving = false;

    // ── Informasi Tab ───────────────────────────────────────────────────────
    public string $groom_name = '';
    public string $bride_name = '';
    public string $groom_full_name = '';
    public string $bride_full_name = '';
    public string $groom_father = '';
    public string $groom_mother = '';
    public string $bride_father = '';
    public string $bride_mother = '';
    public string $opening_quote = '';
    public string $opening_quote_source = '';
    public string $story = '';
    public $groom_photo = null;
    public $bride_photo = null;
    public $cover_photo = null;

    // ── Event Tab ────────────────────────────────────────────────────────────
    public array $events = [];
    public array $newEvent = [
        'name' => '', 'date' => '', 'time_start' => '', 'time_end' => '',
        'venue' => '', 'venue_address' => '', 'venue_maps_url' => '', 'description' => '',
    ];

    // ── Gallery Tab ──────────────────────────────────────────────────────────
    public array $galleries = [];
    public $galleryUpload = null;
    public string $galleryCaption = '';

    // ── Theme Tab ────────────────────────────────────────────────────────────
    public string $selectedTemplate = '';
    public array $themeOverrides = [];

    // ── Music Tab ────────────────────────────────────────────────────────────
    public string $music_url = '';
    public string $music_name = '';
    public bool $music_autoplay = false;
    public string $youtubeEmbedStatus = ''; // '', 'ok', 'blocked', 'error'

    // ── Gift Tab ─────────────────────────────────────────────────────────────
    public array $gifts = [];
    public array $newGift = [
        'type' => 'bank', 'bank_name' => '', 'account_number' => '',
        'account_name' => '', 'label' => '',
    ];
    public $qrisImage = null;

    // ── RSVP Tab ─────────────────────────────────────────────────────────────
    public string $rsvp_deadline = '';
    public bool $is_open = true;

    public function mount(Invitation $invitation)
    {
        $this->authorize('update', $invitation);

        // Gate: undangan harus aktif (sudah bayar) sebelum bisa diedit
        if (!$invitation->is_active) {
            $this->redirect(route('payment.select-package', $invitation->id));
            return;
        }

        $this->invitation = $invitation;
        $this->fillFromModel();
    }

    private function fillFromModel(): void
    {
        $inv = $this->invitation;

        $this->groom_name          = $inv->groom_name ?? '';
        $this->bride_name          = $inv->bride_name ?? '';
        $this->groom_full_name     = $inv->groom_full_name ?? '';
        $this->bride_full_name     = $inv->bride_full_name ?? '';
        $this->groom_father        = $inv->groom_father ?? '';
        $this->groom_mother        = $inv->groom_mother ?? '';
        $this->bride_father        = $inv->bride_father ?? '';
        $this->bride_mother        = $inv->bride_mother ?? '';
        $this->opening_quote       = $inv->opening_quote ?? '';
        $this->opening_quote_source= $inv->opening_quote_source ?? '';
        $this->story               = $inv->story ?? '';
        $this->music_url           = $inv->music_url ?? '';
        $this->music_name          = $inv->music_name ?? '';
        $this->music_autoplay      = (bool) $inv->music_autoplay;
        $this->rsvp_deadline       = $inv->rsvp_deadline ? $inv->rsvp_deadline->format('Y-m-d') : '';
        $this->is_open             = (bool) $inv->is_open;
        $this->selectedTemplate    = $inv->template->slug ?? '';
        $this->themeOverrides      = $inv->theme ?? [];

        $this->loadEvents();
        $this->loadGalleries();
        $this->loadGifts();
    }

    private function loadEvents(): void
    {
        $this->events = DB::table('invitation_events')
            ->where('invitation_id', $this->invitation->id)
            ->orderBy('sort_order')
            ->get()
            ->map(fn($e) => (array) $e)
            ->toArray();
    }

    private function loadGalleries(): void
    {
        $this->galleries = DB::table('invitation_galleries')
            ->where('invitation_id', $this->invitation->id)
            ->orderBy('sort_order')
            ->get()
            ->map(fn($g) => (array) $g)
            ->toArray();
    }

    private function loadGifts(): void
    {
        $this->gifts = DB::table('invitation_gifts')
            ->where('invitation_id', $this->invitation->id)
            ->orderBy('sort_order')
            ->get()
            ->map(fn($g) => (array) $g)
            ->toArray();
    }

    // ── Auto-Save ────────────────────────────────────────────────────────────
    public function autoSave(): void
    {
        $this->saveInformation(silent: true);
    }

    public function saveInformation(bool $silent = false): void
    {
        $this->validate([
            'groom_name' => 'required|string|max:100',
            'bride_name' => 'required|string|max:100',
            'groom_full_name'  => 'nullable|string|max:150',
            'bride_full_name'  => 'nullable|string|max:150',
            'opening_quote'    => 'nullable|string|max:500',
            'story'            => 'nullable|string|max:3000',
        ]);

        $this->invitation->update([
            'groom_name'          => $this->groom_name,
            'bride_name'          => $this->bride_name,
            'groom_full_name'     => $this->groom_full_name,
            'bride_full_name'     => $this->bride_full_name,
            'groom_father'        => $this->groom_father,
            'groom_mother'        => $this->groom_mother,
            'bride_father'        => $this->bride_father,
            'bride_mother'        => $this->bride_mother,
            'opening_quote'       => $this->opening_quote,
            'opening_quote_source'=> $this->opening_quote_source,
            'story'               => $this->story,
        ]);

        if (!$silent) {
            $this->autoSaveStatus = 'Tersimpan';
            $this->dispatch('saved');
            $this->dispatch('toast', '✓ Informasi berhasil disimpan', 'success');
        }
    }

    public function saveMusic(): void
    {
        $this->invitation->update([
            'music_url'      => $this->music_url,
            'music_name'     => $this->music_name,
            'music_autoplay' => $this->music_autoplay,
        ]);
        $this->autoSaveStatus     = 'Tersimpan';
        $this->youtubeEmbedStatus = 'checking'; // triggers client-side YT.Player test
        $this->dispatch('saved');
        $this->dispatch('toast', '✓ Musik disimpan — sedang memverifikasi...', 'success');
    }

    public function updatedMusicUrl(): void
    {
        $this->youtubeEmbedStatus = ''; // reset badge on URL change
    }

    /** Called back from Alpine after real YT.Player embed test completes */
    public function setYoutubeEmbedStatus(string $status): void
    {
        $this->youtubeEmbedStatus = $status;
        if ($status === 'blocked') {
            $this->dispatch('toast', '⚠️ Video ini melarang embed! Tamu tidak bisa mendengar musik. Ganti video.', 'error');
        } elseif ($status === 'ok') {
            $this->dispatch('toast', '✓ Musik siap diputar di undangan', 'success');
        }
    }

    public function saveRsvpSettings(): void
    {
        $this->invitation->update([
            'rsvp_deadline' => $this->rsvp_deadline ?: null,
            'is_open'       => $this->is_open,
        ]);
        $this->autoSaveStatus = 'Tersimpan';
        $this->dispatch('toast', '✓ Pengaturan RSVP disimpan', 'success');
    }

    // ── Photo Upload ─────────────────────────────────────────────────────────
    public function uploadGroomPhoto(): void
    {
        $this->validate(['groom_photo' => 'required|image|max:2048|mimes:jpg,jpeg,png,webp']);
        $storage = app(StorageService::class);
        $path = $storage->storeImage($this->groom_photo, 'invitations/' . $this->invitation->id . '/photos');
        if ($this->invitation->groom_photo) $storage->deleteImage($this->invitation->groom_photo);
        $this->invitation->update(['groom_photo' => $path]);
        $this->groom_photo = null;
        $this->autoSaveStatus = 'Foto pengantin pria tersimpan';
        $this->dispatch('toast', '✓ Foto pengantin pria disimpan', 'success');
    }

    public function uploadBridePhoto(): void
    {
        $this->validate(['bride_photo' => 'required|image|max:2048|mimes:jpg,jpeg,png,webp']);
        $storage = app(StorageService::class);
        $path = $storage->storeImage($this->bride_photo, 'invitations/' . $this->invitation->id . '/photos');
        if ($this->invitation->bride_photo) $storage->deleteImage($this->invitation->bride_photo);
        $this->invitation->update(['bride_photo' => $path]);
        $this->bride_photo = null;
        $this->autoSaveStatus = 'Foto pengantin wanita tersimpan';
        $this->dispatch('toast', '✓ Foto pengantin wanita disimpan', 'success');
    }

    public function uploadCoverPhoto(): void
    {
        $this->validate(['cover_photo' => 'required|image|max:2048|mimes:jpg,jpeg,png,webp']);
        $storage = app(StorageService::class);
        $path = $storage->storeImage($this->cover_photo, 'invitations/' . $this->invitation->id . '/photos');
        if ($this->invitation->cover_photo) $storage->deleteImage($this->invitation->cover_photo);
        $this->invitation->update(['cover_photo' => $path]);
        $this->cover_photo = null;
        $this->autoSaveStatus = 'Foto cover tersimpan';
        $this->dispatch('toast', '✓ Foto cover disimpan', 'success');
    }

    // ── Events CRUD ──────────────────────────────────────────────────────────
    public function addEvent(): void
    {
        $this->validate([
            'newEvent.name'       => 'required|string|max:100',
            'newEvent.date'       => 'required|date',
            'newEvent.time_start' => 'required',
            'newEvent.venue'      => 'required|string|max:200',
        ]);

        DB::table('invitation_events')->insert([
            'invitation_id'  => $this->invitation->id,
            'name'           => $this->newEvent['name'],
            'date'           => $this->newEvent['date'],
            'time_start'     => $this->newEvent['time_start'],
            'time_end'       => $this->newEvent['time_end'] ?: null,
            'venue'          => $this->newEvent['venue'],
            'venue_address'  => $this->newEvent['venue_address'] ?: null,
            'venue_maps_url' => $this->newEvent['venue_maps_url'] ?: null,
            'description'    => $this->newEvent['description'] ?: null,
            'sort_order'     => count($this->events),
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        $this->newEvent = ['name'=>'','date'=>'','time_start'=>'','time_end'=>'','venue'=>'','venue_address'=>'','venue_maps_url'=>'','description'=>''];
        $this->loadEvents();
        $this->autoSaveStatus = 'Event ditambahkan';
        $this->dispatch('toast', '✓ Acara berhasil ditambahkan', 'success');
    }

    public function deleteEvent(int $id): void
    {
        DB::table('invitation_events')
            ->where('id', $id)
            ->where('invitation_id', $this->invitation->id) // ownership check
            ->delete();
        $this->loadEvents();
        $this->autoSaveStatus = 'Event dihapus';
        $this->dispatch('toast', 'Acara dihapus', 'warning');
    }

    // ── Gallery CRUD ─────────────────────────────────────────────────────────
    public function addGallery(): void
    {
        $package = $this->invitation->package;
        $maxGallery = $package?->max_gallery ?? 10;

        if (count($this->galleries) >= $maxGallery) {
            $this->addError('galleryUpload', "Maksimal {$maxGallery} foto di paket ini.");
            return;
        }

        $this->validate(['galleryUpload' => 'required|image|max:2048|mimes:jpg,jpeg,png,webp']);

        $storage = app(StorageService::class);
        $path = $storage->storeImage($this->galleryUpload, 'invitations/' . $this->invitation->id . '/gallery');

        DB::table('invitation_galleries')->insert([
            'invitation_id' => $this->invitation->id,
            'image'         => $path,
            'caption'       => $this->galleryCaption ?: null,
            'sort_order'    => count($this->galleries),
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        $this->galleryUpload = null;
        $this->galleryCaption = '';
        $this->loadGalleries();
        $this->autoSaveStatus = 'Foto ditambahkan';
        $this->dispatch('toast', '✓ Foto galeri ditambahkan', 'success');
    }

    public function deleteGallery(int $id): void
    {
        $photo = DB::table('invitation_galleries')
            ->where('id', $id)
            ->where('invitation_id', $this->invitation->id)
            ->first();

        if ($photo) {
            app(StorageService::class)->deleteImage($photo->image);
            DB::table('invitation_galleries')->where('id', $id)->delete();
        }

        $this->loadGalleries();
        $this->autoSaveStatus = 'Foto dihapus';
        $this->dispatch('toast', 'Foto dihapus', 'warning');
    }

    // ── Gift CRUD ─────────────────────────────────────────────────────────────
    public function addGift(): void
    {
        $this->validate([
            'newGift.type'           => 'required|in:bank,qris,ewallet',
            'newGift.label'          => 'nullable|string|max:100',
            'newGift.bank_name'      => 'required_if:newGift.type,bank|nullable|string|max:100',
            'newGift.account_number' => 'required_if:newGift.type,bank|nullable|string|max:50',
            'newGift.account_name'   => 'required_if:newGift.type,bank|nullable|string|max:100',
        ]);

        $data = [
            'invitation_id'  => $this->invitation->id,
            'type'           => $this->newGift['type'],
            'label'          => $this->newGift['label'] ?: null,
            'bank_name'      => $this->newGift['bank_name'] ?: null,
            'account_number' => $this->newGift['account_number'] ?: null,
            'account_name'   => $this->newGift['account_name'] ?: null,
            'sort_order'     => count($this->gifts),
            'created_at'     => now(),
            'updated_at'     => now(),
        ];

        if ($this->newGift['type'] === 'qris' && $this->qrisImage) {
            $this->validate(['qrisImage' => 'required|image|max:2048|mimes:jpg,jpeg,png,webp']);
            $storage = app(StorageService::class);
            $data['qris_image'] = $storage->storeImage($this->qrisImage, 'invitations/' . $this->invitation->id . '/qris');
            $this->qrisImage = null;
        }

        DB::table('invitation_gifts')->insert($data);
        $this->newGift = ['type' => 'bank','bank_name' => '','account_number' => '','account_name' => '','label' => ''];
        $this->loadGifts();
        $this->autoSaveStatus = 'Rekening/QRIS ditambahkan';
        $this->dispatch('toast', '✓ Rekening/QRIS berhasil ditambahkan', 'success');
        $this->dispatch('saved');
    }

    public function deleteGift(int $id): void
    {
        $gift = DB::table('invitation_gifts')
            ->where('id', $id)
            ->where('invitation_id', $this->invitation->id)
            ->first();

        if ($gift?->qris_image) {
            app(StorageService::class)->deleteImage($gift->qris_image);
        }

        DB::table('invitation_gifts')
            ->where('id', $id)
            ->where('invitation_id', $this->invitation->id)
            ->delete();

        $this->loadGifts();
    }

    // ── Publish ──────────────────────────────────────────────────────────────
    public function publish(): void
    {
        if (!$this->invitation->is_active) {
            session()->flash('error', 'Undangan belum aktif. Silakan lakukan pembayaran terlebih dahulu.');
            return;
        }

        $this->invitation->update(['is_published' => true]);
        $this->autoSaveStatus = 'Undangan dipublikasikan!';
        $this->dispatch('published');
    }

    public function unpublish(): void
    {
        $this->invitation->update(['is_published' => false]);
        $this->autoSaveStatus = 'Undangan disembunyikan';
    }

    #[Computed]
    public function hasPackage(): bool
    {
        return $this->invitation->is_active && $this->invitation->package_id;
    }

    #[Computed]
    public function packageLimits(): array
    {
        $package = $this->invitation->package;
        return [
            'max_gallery'       => $package?->max_gallery ?? 10,
            'max_guests'        => $package?->max_guests ?? 100,
            'has_analytics'     => $package?->has_analytics ?? false,
            'has_qr_checkin'    => $package?->has_qr_checkin ?? false,
            'has_rsvp_export'   => $package?->has_rsvp_export ?? false,
            'has_watermark'     => $package?->has_watermark ?? true,
        ];
    }

    public function render()
    {
        $this->invitation->refresh();
        return view('livewire.editor.invitation-editor')
            ->layout('layouts.editor')
            ->layoutData([
                'invitation'      => $this->invitation,
                'autoSaveStatus'  => $this->autoSaveStatus,
            ]);
    }
}
