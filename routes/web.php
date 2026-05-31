<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Models\FavoriteBook;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Autenticação:
Route::get('/login', function () { return view('login'); })->name('login');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);

// Protegidas por login:
Route::middleware(['web'])->group(function () {
    Route::get('/', function () { return redirect('/search-books'); });
    
    // Busca:
    Route::get('/search-books', [BookController::class, 'search']);
    
    // Favoritar:
    Route::post('/books/favorite', [BookController::class, 'favoritar']);

    // Remover dos favoritos:
    Route::delete('/books/favorite/{id}', [BookController::class, 'removerFavorito']);
    
    // Meus Favoritos:
    Route::get('/books/my-favorites', function () {
        if (!Auth::check()) return redirect('/login');
        $favorites = FavoriteBook::where('user_id', Auth::id())->get();
        return view('favorites', compact('favorites'));
    });
});
