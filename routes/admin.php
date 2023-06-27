<?php

use App\Http\Controllers\ACSController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PolytraumaController;
use App\Http\Controllers\UserController;
use App\Models\ACS;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Polytrauma;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'], function () {
    Route::get('/acs/list', [ACSController::class, 'index']);

    Route::get('/polytrauma/list', [PolytraumaController::class, 'index']);

    Route::get('/fullform-acs/{id}', function ($id) {
        $data = ACS::findOrFail($id);
        return view('dashboard.pages.full-table', compact('data'));
    })->name('full-table');
    Route::get('/fullform-polyt/{id}', function ($id) {
        $data = Polytrauma::findOrFail($id);
        return view('dashboard.pages.full-table-polyt', compact('data'));
    })->name('full-table-polyt');
    // Add the route for the create-page

    Route::group(['prefix' => 'acs'], function () {
        Route::get('create-page', function () {
            $branches = Branch::all(['id', 'name']);
            $departments = Department::all(['id', 'name']);
            return view('dashboard.pages.create-page', compact('branches', 'departments'));
        })->name('acs.create-page');

        Route::post('add', [ACSController::class, 'store'])->name('acs.add');
        Route::get('/edit-page/{id}', [ACSController::class, 'edit'])->name('edit-page');
        Route::put('/update-data/{id}', [ACSController::class, 'update'])->name('update-data');

        Route::delete('/delete/{id}', [ACSController::class, 'destroy'])->name('acs.delete');
    });

    Route::group(['prefix' => 'polytrauma'], function () {
        Route::get('polyt-create-page', function () {
            $branches = Branch::all(['id', 'name']);
            $departments = Department::all(['id', 'name']);
            return view('dashboard.pages.polyt-create-page', compact('branches', 'departments'));
        })->name('polytrauma.polyt-create-page');

        Route::post('add', [PolytraumaController::class, 'store'])->name('polytrauma.add');
        Route::get('/polyt-edit-page/{id}', [PolytraumaController::class, 'edit'])->name('polyt-edit-page');
        Route::put('/update-data/{id}', [PolytraumaController::class, 'update'])->name('polyt-update-data');

        Route::delete('/delete/{id}', [PolytraumaController::class, 'destroy'])->name('polytrauma.delete');
    });

    Route::group(['prefix' => 'users'], function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');

        Route::get('users-create-page', [UserController::class, 'create'])->name('users.create-page');

        Route::post('/users', [UserController::class, 'store'])->name('users.store');

        Route::get('/edit-page/{user}', [UserController::class, 'edit'])->name('users.edit-page');

        Route::put('/update-data/{user}', [UserController::class, 'update'])->name('users.update');

        Route::delete('/delete/{user}', [UserController::class, 'destroy'])->name('users.delete');

    });

    Route::post('/departments/fetch', [DepartmentController::class, 'fetchDepartments'])->name('departments.fetch');

    Route::group(['prefix' => 'branch'], function () {
        Route::get('/', [BranchController::class, 'index']);

    });

    Route::group(['prefix' => 'departments'], function () {
        Route::get('/', [DepartmentController::class, 'index'])->name('departments.index');
        Route::get('/edit/{department}', [DepartmentController::class, 'edit'])->name('departments.edit');
        Route::put('/update/{department}', [DepartmentController::class, 'update'])->name('department.update');
        Route::get('/branch', [BranchController::class, 'fetchDepartments']);

    });

    Route::get('/acs/statistics', [ACSController::class, 'statistics']);
    Route::get('/polytrauma/statistics', [PolytraumaController::class, 'statistics']);

});
