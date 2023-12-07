<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Habit; // Eloquentを使用するにはuseするモデル名を宣言する
use App\Models\Record;
use App\Models\Memo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class HabitController extends Controller
{
    // 習慣の新規登録画面を表示
    public function create_habit() {

        $user_id = Auth::id();

        return view('habit.create', compact('user_id')); // resources/viewsの後のパスを入れる

    }

    // 新規登録のフォームの内容をデータベースに保存
    public function store_habit_name(Request $request) { // Request $requestでフォームから送信されたデータを受けとっている
        
        // バリデーション
        $request->validate([
            'habit_name' => 'max:20|required',
            'habit_goal' => 'max:40'
        ],
        [
            'habit_name.max' => '20文字以内で入力してください。',
            'habit_name.required' => '入力してください。',
            'habit_goal.max' => '40文字以内で入力してください。'
        ]);

        //バリデーションにパスした後の処理
        Habit::create([ // Habitクラスからインスタンスを生成し、createメソッドを呼び出し。Habitインスタンスの各要素に入れる値は、引数に直接配列で指定
            
            'name' => $request->habit_name, // inputタグname属性の値をnameカラムに挿入
            'user_id' => Auth::id(),
            'goal' => $request->habit_goal
        ]);

        return back(); // 元のページに戻る
    }

    // 習慣一覧を表示
    public function index_list() { // habit/indexにアクセス後、ルート設定からshow_listメソッドへ

        $user_id = Auth::id();
        // habitテーブルからユーザーの登録済みの習慣を取得
        // 1ページごとに10項目を表示するようにペジネーションする
        $habit_list = Habit::where('user_id', '=', $user_id)->where('archive', '=', null)->paginate(10);
        // レコードのidカラムのみを抽出
        $habit_id = Habit::where('user_id', '=', $user_id)->value('id');

        // 今日の日付を取得
        $today = date('Y-m-d');

        // 習慣の登録が無い場合、新規登録画面を表示
        if (empty($habit_id)) {
           
            return redirect(route('habit.create'));

        } else { // 習慣の登録が1つ以上ある場合、習慣一覧（トップ画面）を表示

            return view('habit.index', compact('habit_list', 'today'));
            
        }
    }

    // 実行した習慣を登録
    public function store_habit(Request $request) { 

        // バリデーション
        $request->validate([

            'achievement_date' => [
                Rule::unique('records', 'achieved_at')
                ->where('habit_id', $request->habit_id)
            ]],
            [
            'achievement_date.unique' => 'この日付は記録されています。'
            ]
        );

        //バリデーションにパスした後の処理
        Record::create([
            'habit_id' => $request->habit_id,
            'achieved_at' => $request->achievement_date
        ]);

        $achievement_date = $request->achievement_date;

        return back()->with('achievement_date', $achievement_date);
    }

    // 習慣の名前、目標を編集する画面を表示
    public function show_habit_name_edit($id) {

        $habit_detail = Habit::find($id);

        return view('habit.edit', compact('habit_detail'));
    }

    // 習慣の名前、目標を編集
    public function update_habit_name(Request $request, $id) {
        
        // バリデーション
        $request->validate([
            'habit_name' => 'required|max:20',
            'habit_goal' => 'max:40'
        ],
        [
            'habit_name.required' => '入力してください。',
            'habit_name.max' => '20文字以内で入力してください。',
            'habit_goal.max' => '40文字以内で入力してください。'
        ]);

        //バリデーションにパスした後の処理
        Habit::where('id', '=', $id)->update([
            
            'name' => $request->habit_name,
            'goal' => $request->habit_goal
        ]);

        return redirect()->route('detail.show', ['id' => $id]);
    }

    // 習慣削除画面を表示
    public function show_habit_deletion($id) {

        $habit_detail = Habit::find($id);

        if (isset($habit_detail)) {

            return view('habit.destroy', compact('habit_detail'));
        }
    }

    // 習慣を削除
    public function destroy_habit($id) {
       
        Habit::where('id', '=', $id)->delete();
        Record::where('habit_id', '=', $id)->delete();
        Memo::where('habit_id', '=', $id)->delete();

        return redirect(route('habit.index'));
    }
}