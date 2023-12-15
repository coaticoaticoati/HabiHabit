<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HabitController;
use App\Http\Controllers\DetailController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\MemoController;

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

//Route::get('/', function () {
    // return view('welcome');
//});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// トップ画面（習慣一覧）を表示
Route::get('/', [HabitController::class, 'show_index'])->name('habit.index')->middleware('auth');

// 実行した習慣を登録
Route::post('index', [HabitController::class, 'store_habit'])->name('habit.store'); 


// 習慣の新規登録画面を表示
Route::get('create', [HabitController::class, 'create_habit'])->name('habit.create')->middleware('auth');

// 習慣を新規登録
Route::post('create', [HabitController::class, 'store_habit_name'])->name('habit.store_name'); 

// 習慣の名前を編集する画面を表示
Route::get('edit/{id}', [HabitController::class, 'show_habit_name_edit'])->name('habit.show_name_edit')->middleware('auth');

// 習慣の名前を編集
Route::patch('edit/{id}', [HabitController::class, 'update_habit_name'])->name('habit.update_name');  

// 習慣削除の確認画面を表示
Route::get('destroy/{id}', [HabitController::class, 'show_habit_deletion'])->name('habit.show_deletion')->middleware('auth');

// 習慣を削除
Route::delete('destroy/{id}', [HabitController::class, 'destroy_habit'])->name('habit.destroy');


// 詳細画面を表示
Route::get('detail/{id}', [DetailController::class, 'show_detail'])->middleware('auth')->name('detail.show');

// 実績（達成日）を削除
Route::delete('detail/{id}', [DetailController::class, 'destroy_detail'])->name('detail.destroy');

// メモ一覧画面から詳細画面（カレンダー）を表示
Route::get('detail/{id}?ym={ym}', [DetailController::class, 'show_detail'])->name('detail.show_from_memo');


// メモ一覧画面を表示
Route::get('memo/{id}', [MemoController::class, 'show_memo'])->name('memo.show');

// 詳細画面からメモ一覧画面を表示。または、メモ編集画面からメモ一覧画面を表示
Route::get('memo/{id}?ym={ym}', [MemoController::class, 'show_memo'])->name('memo.show_from_detail');

// メモ編集画面を表示
Route::get('edit-memo/{id}/{day}', [MemoController::class, 'show_memo_edit'])->name('memo.show_edit');

// メモを編集
Route::patch('edit-memo/{id}/{day}', [MemoController::class, 'update_memo'])->name('memo.update');


// アーカイブ保存の確認画面を表示
Route::get('save-archive/{id}', [ArchiveController::class, 'show_archive_confirmation'])->name('archive.show_confirmation')->middleware('auth');

// アーカイブに保存
Route::post('archive', [ArchiveController::class, 'store_archive'])->name('archive.store');

// アーカイブ一覧画面を表示
Route::get('archive', [ArchiveController::class, 'show_archive'])->name('archive.show')->middleware('auth');

// アーカイブから一覧に戻す
Route::get('archive/{id}', [ArchiveController::class, 'update_archive'])->name('archive.update');