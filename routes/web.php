<?php

use App\Http\Controllers\CardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Dashboard');
//         // 'canLogin' => Route::has('login'),
//         // 'canRegister' => Route::has('register'),
//         // 'laravelVersion' => Application::VERSION,
//         // 'phpVersion' => PHP_VERSION,
//     ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Dashboard');
    });
    Route::get('/project/{id}', function ($id) {
        return Inertia::render('Project',[
            'project_id' => $id
        ]);
    })->name('project');

    Route::get('/report', function () {
        return Inertia::render('Report');
    })->name('report');

    Route::group(['prefix' => 'projects'], function () {
        Route::get('/', [ProjectController::class, 'index'])->name('project.index');
        Route::get('/{id}', [ProjectController::class, 'get_by_id'])->name('project.get_by_id');
        Route::post('create', [ProjectController::class, 'create'])->name('project.create');
        Route::patch('update/{id}', [ProjectController::class, 'update'])->name('project.update');
        Route::delete('destroy/{id}', [ProjectController::class, 'destroy'])->name('project.destroy');
    });

    Route::group(['prefix' => 'tasks'], function () {
        Route::post('create', [TaskController::class, 'create'])->name('task.create');
        Route::patch('completed/{id}', [TaskController::class, 'completed'])->name('task.completed');
        Route::delete('destroy/{id}', [TaskController::class, 'destroy'])->name('task.destroy');
        Route::post('time/{id}', [TaskController::class, 'time'])->name('task.time');
    });

    Route::group(['prefix' => 'cards'], function () {
        Route::post('create', [CardController::class, 'create'])->name('card.create');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
