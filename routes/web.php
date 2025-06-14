<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoListController;

// Halaman utama ToDoList
Route::get('/', [TodoListController::class, 'index'])->name('todolist.index');



Route::get('/', [TodoListController::class, 'index'])->name('todolist.index');
Route::post('/tambah', [TodoListController::class, 'store'])->name('todolist.store');
Route::post('/update-status/{id}', [TodoListController::class, 'updateStatus'])->name('todolist.updateStatus');
Route::post('/edit/{id}', [TodoListController::class, 'edit'])->name('todolist.edit');
Route::post('/delete/{id}', [TodoListController::class, 'destroy'])->name('todolist.destroy');
