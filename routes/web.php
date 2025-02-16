<?php

use App\Http\Controllers\ContactsController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('contacts', ContactsController::class);
Route::get('get-contacts', [ContactsController::class, 'getContacts'])->name('contacts.get');
Route::post('/contacts/import-xml', [ContactsController::class, 'importXML'])->name('contacts.import.xml');

Route::get('/download-sample-xml', function () {
    $filePath = public_path('contacts.xml');
    return response()->download($filePath);

    if (file_exists($filePath)) {
        return back()->with('error', 'You have already uploaded a contact XML file.');
    }

    $request->file('xmlFile')->move(public_path(), 'contacts.xml');

})->name('download-sample-xml');

require __DIR__.'/auth.php';
