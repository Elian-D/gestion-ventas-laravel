
<?php

use App\Http\Controllers\Sales\InvoiceController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    
    // Exportación de historial
    Route::get('invoice/export', [InvoiceController::class, 'export'])
        ->middleware('permission:export invoices')
        ->name('invoices.export');

    Route::get('invoices/{invoice}/preview', [InvoiceController::class, 'preview'])
    ->name('invoices.preview')
    ->middleware(['auth', 'permission:view invoices']);

    // Listado principal con AJAX
    Route::get('invoice/', [InvoiceController::class, 'index'])
        ->middleware('permission:view invoices')
        ->name('invoices.index');

    // Visualización de detalle (El documento legal)
    Route::get('invoice/{invoice}', [InvoiceController::class, 'show'])
        ->middleware('permission:view invoices')
        ->name('invoices.show');

    // Impresión (Generación de PDF/Ticket)
    Route::get('invoice/{invoice}/print', [InvoiceController::class, 'print'])
        ->middleware('permission:print invoices')
        ->name('invoices.print');
});