<?php

use App\Http\Controllers\Accounting\JournalEntryController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    // Listado y visualización
    Route::get('journal_entries', [JournalEntryController::class, 'index'])
        ->middleware('permission:view journal entries')
        ->name('journal_entries.index');

    // Creación de nuevos asientos
    Route::get('journal_entries/create', [JournalEntryController::class, 'create'])
        ->middleware('permission:create journal entries')
        ->name('journal_entries.create');

    Route::post('journal_entries', [JournalEntryController::class, 'store'])
        ->middleware('permission:create journal entries')
        ->name('journal_entries.store');

    // Edición (Solo permitida en estado Borrador por el Service/Request)
    Route::get('journal_entries/{journal_entry}/edit', [JournalEntryController::class, 'edit'])
        ->middleware('permission:edit journal entries')
        ->name('journal_entries.edit');

    Route::put('journal_entries/{journal_entry}', [JournalEntryController::class, 'update'])
        ->middleware('permission:edit journal entries')
        ->name('journal_entries.update');

    // Acciones especiales de estado
    Route::patch('journal_entries/{journal_entry}/post', [JournalEntryController::class, 'post'])
        ->middleware('permission:post journal entries')
        ->name('journal_entries.post');

    Route::patch('journal_entries/{journal_entry}/cancel', [JournalEntryController::class, 'cancel'])
        ->middleware('permission:cancel journal entries')
        ->name('journal_entries.cancel');

    // Exportación
    Route::get('journal_entries/export', [JournalEntryController::class, 'export'])
        ->middleware('permission:view journal entries')
        ->name('journal_entries.export');
});