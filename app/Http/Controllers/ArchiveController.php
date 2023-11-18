<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Habit;
use App\Models\Record;
use Illuminate\Support\Facades\Auth;

class ArchiveController extends Controller
{

    // アーカイブ保存の確認画面を表示
    public function show_archive_confirmation($id) {

        // 習慣のデータを取得(ビューファイルに渡す用)
        $habit_detail = Habit::find($id);

        return view('habit.save-archive', compact('habit_detail'));
    }

    // アーカイブに保存
    public function store_archive(Request $request) {

        Habit::where('id', '=', $request->habit_id)->update([
            'archive' => 1,
        ]);

        return redirect(route('habit.archive'));
    }

    // アーカイブ一覧を表示
    public function show_archive() {
        // 1ページごとに10項目を表示するようにペジネーションする
        $archive_list = Habit::where('user_id', '=', Auth::id())
            ->where('archive', '=', 1)
            ->paginate(10);

        return view('habit.archive', compact('archive_list'));
    }

    // アーカイブから一覧に戻す
    public function update_archive($id) {

        Habit::where('id', '=', $id)->update([
            'archive' => null,
        ]);

        return back();
    }
}
