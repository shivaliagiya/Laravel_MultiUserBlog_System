<?php
use App\Http\Controllers\BlogPostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BlogController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return redirect()->route('blog.index');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 🔥 BlogPost Routes (Non-Resource CRUD)
Route::middleware('auth')->group(function () {
    Route::get('/blog', [BlogPostController::class, 'index'])->name('blog.index');
    Route::get('/blog/create', [BlogPostController::class, 'create'])->name('blog.create');
    Route::post('/blog/store', [BlogPostController::class, 'store'])->name('blog.store');
    Route::get('/blog/{id}', [BlogPostController::class, 'show'])->name('blog.show');
    Route::get('/blog/{id}/edit', [BlogPostController::class, 'edit'])->name('blog.edit');
    Route::put('/blog/{id}/update', [BlogPostController::class, 'update'])->name('blog.update');
    Route::post('/comments', [CommentController::class, 'store'])->middleware('auth')->name('comments.store');
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories/store', [CategoryController::class, 'store'])->name('categories.store');
    Route::delete('/blog/{id}/delete', [BlogPostController::class, 'destroy'])->name('blog.destroy');
    Route::get('/blog/{id}/approve', [BlogPostController::class, 'approve'])->name('blog.approve'); // ✅ Only Admin can approve

});

Route::prefix('api')->group(function () {
    Route::get('/blog-posts', [BlogController::class, 'index']);
    Route::get('/blog-posts/{id}', [BlogController::class, 'show']);
});
// 🔥 BlogPost Admin Routes (Only for Admin)
Route::middleware(['auth', 'admin'])->group(function () {

});

require __DIR__.'/auth.php';
