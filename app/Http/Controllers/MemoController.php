<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Habit;
use App\Models\Record;
use App\Models\Memo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MemoController extends Controller
{
    //メモ（縦型カレンダー）を表示
    public function show_memo($id) {

        // 習慣のデータを取得（ビューファイルに渡す用）
        $habit_detail = Habit::find($id);

        // タイムゾーン設定
        date_default_timezone_set('Asia/Tokyo');

        // 先月、翌月のリンクが押された場合は、GETで年月を取得し表示
        if (isset($_GET['ym'])) {

            $ym = $_GET['ym'];
        // セレクトボックスから選択された場合
        } elseif (isset($_GET['year']) && isset($_GET['month'])) {

            $year = (int)$_GET['year'];
            $month = (int)$_GET['month'];
            $ym = date('Y-m', strtotime( $year.'-'.$month.'-1'));
            
        } else {
            // 押されていない場合は今月を表示
            $ym = date('Y-m');
        }

        // 表示したい（している）月（例：2023-09-01）のタイムスタンプを作成
        $this_month_ts = strtotime($ym.'-01'); // 

        // 表示したい（している）月の年月を取得　例：2023.9（ビューファイルに渡す用）
        $this_month_ym = date('Y.n', $this_month_ts);

        // 表示している月の前月、翌月の年月を取得（ビューファイルに渡す用）
        $prev = date('Y-m', strtotime('-1 month', $this_month_ts));
        $next = date('Y-m', strtotime('+1 month', $this_month_ts));

        // 今日の年月日 例：2023-09-9
        $today = date('Y-m-j'); // タイムスタンプは省略可。現在時刻になる。

        // 表示している月の日数（月末日）を取得
        $day_count = date('t', $this_month_ts); // 例：28～31

        // 表示している月のメモなどの情報を取得するために
        // 月初日を取得
        $this_month_first_day = date('Y-m-d', $this_month_ts);

        // 月末日を取得
        $this_month_last_day = date('Y-m-d', strtotime($ym.'-'.$day_count));

        // 月初から月末の間で、メモの入力がある日のデータ取得
        $memo_list = Memo::where('habit_id', '=', $id)
            ->whereBetween('registered_on',[$this_month_first_day, $this_month_last_day])->get()->toArray();

        // 月初から月末の間で、習慣を達成した日のデータを取得  
        $achievement_list = Record::where('habit_id', '=', $id)
            ->whereBetween('achieved_at',[$this_month_first_day, $this_month_last_day])->get()->toArray();   
        
        // 検索用のセレクトボックスの初期値を今日の年月にする
        $this_year = date('Y');
        $this_month = date('n');
    
            
        // カレンダーにメモを表示するために
        // 配列を作るための準備
        $calendar_memo = [];
        $calendar_achi = [];
        $calendar_of_memo = [];

        // メモの入力がある日の日付を配列のキーとし、メモの内容を代入する。例：2023年9月2日→$calendar_memo[2]
        foreach ($memo_list as $memo_item) {
            
            $memo_day = date('j', strtotime($memo_item['registered_on']));
            $calendar_memo[$memo_day] = $memo_item['text'];
        }
        // 習慣を達成した日の日付を配列のキーとし、達成日を代入する。
        foreach ($achievement_list as $achievement_item) {

            $achievement_day = date('j', strtotime($achievement_item['achieved_at']));
            $calendar_achie[$achievement_day] = $achievement_item['achieved_at'];
        }
        // 1日から月末までの配列を作成
        for ($i = 1; $i <= $day_count; $i++) {
            
            $calendar_of_memo[$i]['day'] = $i;
            // 例：2023-09-1
            $date = $ym . '-' . $i; 
            // $date（例：2023-09-1）を代入する
            $calendar_of_memo[$i]['date'] = $date;
            // 曜日を取得し、代入する
            $calendar_of_memo[$i]['week'] = date('w', strtotime($date));

            // メモの入力がある日は、$calendar_memoのメモ内容を代入する
            if (isset($calendar_memo[$i])) {
                $calendar_of_memo[$i]['memo'] = $calendar_memo[$i];
            // 無い日は空文字を代入する
            } else {
                $calendar_of_memo[$i]['memo'] = '';
            }
            
            // 習慣を達成した日は、$calendar_achieの内容を代入する
            if (isset($calendar_achie[$i])) {
                $calendar_of_memo[$i]['achievement'] = $calendar_achie[$i];
            // 無い日は空文字を代入する
            } else {
                $calendar_of_memo[$i]['achievement'] = '';
            }
        }
        $week_jp = ['日', '月', '火', '水', '木', '金', '土'];
        
        return view('habit.memo', compact('habit_detail', 'this_month_ym', 'prev', 'next', 'ym',
        'calendar_of_memo', 'week_jp', 'this_year', 'this_month'));
    }

    // メモ編集画面を表示
    public function show_edit_memo($id, $day) {
        // メモの入力がある日は内容を取得
        $memo = Memo::where('habit_id', '=', $id)->whereDate('registered_on', '=', $day)->first();

        $date= date('Y年m月j日', strtotime($day));

        return view('habit.edit-memo', compact('memo', 'id', 'day', 'date'));
    }
    
    // メモを編集
    public function update_memo(Request $request, $id, $day) {      
        // バリデーション
        $request->validate([
            'habit_memo' => 'max:50'
        ],
        [
            'habit_memo.max' => '50文字以内で入力してください。'
        ]);
        //バリデーションにパスした後の処理
        Memo::updateOrCreate([
            'habit_id' => $id,
            'registered_on' => $day
        ],
        [   
            'text' => $request->habit_memo
        ]);

        if (empty($request->habit_memo)) {

            Memo::where('habit_id', '=', $id)->whereDate('registered_on', '=', $day)->delete();
        }

        return redirect()->route('habit.show_memo', ['id' => $id]);
    }
}
