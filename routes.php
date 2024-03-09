<?php

use EvolutionCMS\Scraper\Controllers\IndexController;
use EvolutionCMS\Scraper\Controllers\Parser\CreateTask;
use EvolutionCMS\Scraper\Controllers\Parser\DeleteAllTasks;
use EvolutionCMS\Scraper\Controllers\Parser\MarkCompletedTasksAsCreated;
use EvolutionCMS\Scraper\Controllers\Tasks\MassActions;
use EvolutionCMS\Scraper\Controllers\Tasks\RowActions\DeleteTask;
use EvolutionCMS\Scraper\Controllers\Tasks\RowActions\ProcessTask;
use EvolutionCMS\Scraper\Controllers\Tasks\RowActions\UpdateTask;
use Illuminate\Support\Facades\Route;

Route::name('scraper::')->group(function () {
    Route::match(['get', 'post'], '', IndexController::class)
        ->name('index');


    Route::prefix('tasks')->name('tasks.')->group(function () {
        Route::post('create', CreateTask::class)->name('create');
        Route::post('delete/{task}', DeleteTask::class)->name('delete');

        Route::get('get-form/{task}', [UpdateTask::class, 'getForm'])->name('get-form');
        Route::put('update/{task}', [UpdateTask::class, 'save'])->name('update');

        Route::post('process/{task}', ProcessTask::class)->name('process');

        Route::prefix('mass-actions')->name('mass_action.')->group(function () {
            Route::post('delete', [MassActions::class, 'delete'])->name('delete');
            Route::post('process', [MassActions::class, 'process'])->name('process');
        });

        Route::prefix('console')->name('console.')->group(function () {
            Route::post('mark-completed-as-created',
                MarkCompletedTasksAsCreated::class)->name('mark-completed-as-created');
            Route::post('delete-all', DeleteAllTasks::class)->name('delete-all');
        });
    });
});
