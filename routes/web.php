<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DailyQuestionController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\MoodController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\ConsultantController;
use App\Http\Controllers\CheckinController;

Route::get('/', function () {
    return view('welcome');
});

// User dashboard
Route::get('/dashboard', [QuoteController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/dashboard', [QuoteController::class, 'dashboard'])->name('dashboard');

Route::middleware(['auth', 'role:consultant'])->group(function () {
    Route::get('/consultant/dashboard', [ConsultantController::class, 'dashboard'])
         ->name('consultant.dashboard');
});

Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Daily questions
    Route::post('/daily-questions', [DailyQuestionController::class, 'store'])
        ->name('daily.questions.store');

    // Journal
    Route::get('/journal', [JournalController::class, 'index'])->name('journal.index');
    Route::get('/journal/create', [JournalController::class, 'create'])->name('journal.create');
    Route::post('/journal/store', [JournalController::class, 'store'])->name('journal.store');
    Route::get('/journal/{id}/edit', [JournalController::class, 'edit'])->name('journal.edit');
    Route::put('/journal/{id}', [JournalController::class, 'update'])->name('journal.update');
    Route::post('/journal/{id}/archive', [JournalController::class, 'archiveEntry'])->name('journal.archive');
    Route::post('/journal/{id}/restore', [JournalController::class, 'restore'])->name('journal.restore');
    Route::post('/journal/{id}/share', [JournalController::class, 'share'])->name('journal.share');
    Route::get('/journal/archived', [JournalController::class, 'showArchived'])->name('journal.archived');
    Route::get('/consultant/shared-journals', [ConsultantController::class, 'sharedJournals'])
        ->name('consultant.shared-journals');
    Route::post('/journal/{entry}/archive', [JournalController::class, 'archive'])
        ->name('journal.archiveEntry');

    // Quotes
    Route::get('/quotes', [QuoteController::class, 'index'])->name('quotes.index');
    Route::post('/quotes', [QuoteController::class, 'store'])->name('quotes.store');
    Route::post('/quotes/toggle', [QuoteController::class, 'toggle'])->name('quotes.toggle');
    Route::post('/quotes/pin/{quote}', [QuoteController::class, 'pin'])->name('quotes.pin');
    Route::post('/journal/redirect', [QuoteController::class, 'redirectToJournal'])
     ->name('journal.redirect');
    Route::post('/quotes/{quote}/pin', [QuoteController::class, 'pin'])->name('quotes.pin');

    // Mood Tracking
    Route::post('/mood', [MoodController::class, 'store'])->name('mood.store');

    // Resources
    Route::resource('resources', ResourceController::class);

    // Chat
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat', [ChatController::class, 'store'])->name('chat.store');
    Route::get('/consultant/chat/{user}', [ConsultantController::class, 'chat'])
        ->name('consultant.chat');

    // Consultant routes
    Route::middleware(['auth'])->group(function () {;
    Route::get('/consultant/dashboard', [ConsultantController::class, 'dashboard'])->name('consultant.dashboard');
    Route::get('/consultant/clients', [ConsultantController::class, 'clients'])->name('consultant.clients');
    Route::get('/consultant/messages/{user?}', [ConsultantController::class, 'chat'])->name('consultant.messages');
    Route::post('/consultant/chat/{user}', [ConsultantController::class, 'reply'])->name('consultant.chat.reply');
    Route::get('/consultant/search', [ConsultantController::class, 'search'])->name('consultant.search');
    Route::get('/consultant/notifications', [ConsultantController::class, 'notifications'])->name('consultant.notifications');
});

    // Authentication routes
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

    Route::get('/checkin/start', [CheckinController::class, 'start'])->name('checkin.start');

require __DIR__.'/auth.php';



