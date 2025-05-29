<?php
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\GoogleAnalyticsController;
    use App\Http\Controllers\PropertyMetadataController;
    use App\Http\Controllers\PageSpeedController;
    use App\Http\Controllers\ExportController;
    use App\Http\Controllers\Admin\UserManagementController;
    use App\Http\Controllers\Auth\RegisteredUserController;
    use App\Services\GoogleService;

    Route::get('/', function () {
        return view('auth.login');
    });

    Route::get('/register', [RegisteredUserController::class, 'create'])->middleware('guest')->name('register');

    Route::middleware(['auth'])->group(function () {
        Route::get('/services', [GoogleAnalyticsController::class, 'fetchAccountsWithProperties'])->name('services');
    });
    
    Route::middleware(['auth'])->group(function () {
        Route::post('/update-property-meta', [PropertyMetadataController::class, 'update'])->name('update.property.meta');
        Route::post('/pagespeed-scan', [PageSpeedController::class, 'scan'])->name('pagespeed.scan');
    });
    
    Route::get('/export/csv', [ExportController::class, 'exportCSV'])->name('export.csv');
    Route::get('/export/pdf', [ExportController::class, 'exportPDF'])->name('export.pdf');

    Route::middleware(['auth'])->group(function () {
        Route::get('/users', [UserManagementController::class, 'index'])->middleware('admin')->name('admin.users');
        Route::post('/users/{user}/role', [UserManagementController::class, 'updateRole'])->middleware('admin')->name('admin.users.updateRole');
        Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->middleware('admin')->name('admin.users.delete');
    });

    Route::post('/users', [UserManagementController::class, 'store'])->name('admin.users.create');


    // JSON data return
    Route::get('/service-accounts', function (GoogleService $googleService) {
        try {
            $accounts = $googleService->getAnalyticsAccounts();
            return response()->json($accounts);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    });

    Route::get('/service-accounts/{accountId}', [GoogleAnalyticsController::class, 'fetchWebProperties']);

    require __DIR__.'/auth.php';


    Route::get('/service-accounts/{accountId}', [GoogleAnalyticsController::class, 'fetchWebProperties']);

    require __DIR__.'/auth.php';
