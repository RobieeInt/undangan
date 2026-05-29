<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CheckinController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvitationPublicController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Route;

// ─── Public Homepage ─────────────────────────────────────────────────────────
Route::get('/', function () {
    $templates = App\Models\Template::where('is_active', true)->orderBy('sort_order')->get();
    $packages  = App\Models\Package::where('is_active', true)->orderBy('sort_order')->get();
    return view('welcome', compact('templates', 'packages'));
})->name('home');

// ─── Auth Routes ─────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Email verification
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', fn() => view('auth.verify-email'))->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
        ->middleware('signed')->name('verification.verify');
    Route::post('/email/verification-notification', function(\Illuminate\Http\Request $req) {
        $req->user()->sendEmailVerificationNotification();
        return back()->with('status', 'verification-link-sent');
    })->middleware('throttle:6,1')->name('verification.send');
});

// ─── Dashboard ────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Editor — full-page Livewire component (uses layouts.editor via render()->layout())
    Route::get('/editor/{invitation}', \App\Livewire\Editor\InvitationEditor::class)->name('editor');

    // Editor preview — bypass is_published/is_active check, owner only
    Route::get('/preview/{invitation}', [App\Http\Controllers\InvitationPublicController::class, 'preview'])->name('invitation.preview');

    // Payment
    Route::get('/payment/{invitation}/package', [PaymentController::class, 'selectPackage'])->name('payment.select-package');
    Route::post('/payment/{invitation}/checkout', [PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::post('/payment/transaction/{transaction}/check-status', [PaymentController::class, 'checkStatus'])->name('payment.check-status');
    Route::get('/payment/finish',  [PaymentController::class, 'finish'])->name('payment.finish');
    Route::get('/payment/error',   [PaymentController::class, 'error'])->name('payment.error');
    Route::get('/payment/pending', [PaymentController::class, 'pending'])->name('payment.pending');

    // Check-in dashboard
    Route::get('/checkin/{invitation}', [CheckinController::class, 'dashboard'])->name('checkin.dashboard');
    Route::get('/checkin/verify/{token}', [CheckinController::class, 'verify'])->name('checkin.verify');
});

// ─── Admin Routes ─────────────────────────────────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', Admin\UserController::class)->only(['index']);
    Route::post('users/{user}/toggle-status', [Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::resource('transactions', Admin\TransactionController::class)->only(['index']);
    Route::post('transactions/{transaction}/update-status', [Admin\TransactionController::class, 'updateStatus'])->name('transactions.update-status');
    Route::resource('packages', Admin\PackageController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::post('packages/{package}/toggle-active', [Admin\PackageController::class, 'toggleActive'])->name('packages.toggle-active');
});

// ─── Midtrans Webhook (no CSRF) ───────────────────────────────────────────────
Route::post('/midtrans/webhook', [PaymentController::class, 'webhook'])
    ->name('midtrans.webhook')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

// ─── Template Preview (public, no auth) ──────────────────────────────────────
Route::get('/templates/{slug}/preview', App\Http\Controllers\TemplatePreviewController::class)
    ->name('template.preview');

// ─── Public Invitation (MUST be last) ────────────────────────────────────────
Route::get('/{slug}', InvitationPublicController::class)
    ->name('invitation.show')
    ->where('slug', '^(?!admin|dashboard|editor|payment|checkin|login|register|api|storage|midtrans)[a-zA-Z0-9_\-]+$');
