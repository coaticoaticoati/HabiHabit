<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Habit;
use App\Models\Record;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DetailController extends Controller
{
    // 詳細画面（カレンダー）を表示
    public function show_detail($id) {

        // タイムゾーン設定
        date_default_timezone_set('Asia/Tokyo');

        // 習慣のデータを取得（ビューファイルに渡す変数）
        $habit_detail = Habit::find($id); //$idは文字列
        
        // 習慣の開始日を取得（ビューファイルに渡す変数）
        $min_achievement_timestamp = Record::where('habit_id', '=', $id)->min('achieved_at');

        if (isset($min_achievement_timestamp)) {

            $min_achievement_date = date('Y.m.j', strtotime($min_achievement_timestamp));

        } else {
            // 1回も記録していない場合は空文字を表示する
            $min_achievement_date = '';
        }
        // トータルの日数を取得（ビューファイルに渡す変数）
        $total_achievement_date = Record::where('habit_id', '=', $id)->count(); 
        // 検索用のセレクトボックスの初期値を今日の年月にする（ビューファイルに渡す変数）
        $this_year = date('Y');
        $this_month = date('n');

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

        // $ymからタイムスタンプを作成
        $timestamp = strtotime($ym.'-01'); // 例：2023-09-01

        // 表示している月の日数（月末日）を取得（ビューファイルにも渡す）
        $day_count = date('t', $timestamp); // 28～31

        // 表示したい（している）月の年・月を取得　例：2023.9（ビューファイルに渡す変数）
        $this_y_m = date('Y.n', $timestamp);
        $this_y = date('Y', $timestamp);
        $this_m = date('n', $timestamp);
        // 表示したい（している）月の年単位の実績を取得（ビューファイルに渡す変数）
        $year_first = $this_y.'-01-01';
        $year_last = $this_y.'-12-31';
        $year_record = Record::where('habit_id', '=', $id)->whereBetween('achieved_at', [$year_first, $year_last])->count();
        // 表示したい（している）月の月単位の実績を取得（ビューファイルに渡す変数）
        $month_first = $ym.'-01';
        $month_last = $ym.'-'.$day_count;
        $month_record = Record::where('habit_id', '=', $id)->whereBetween('achieved_at', [$month_first, $month_last])->count();

        // 閏年かどうか。閏年の場合1、閏年でない場合0。（ビューファイルに渡す変数）
        $this_y_day = date('L', $timestamp);

        // 1日も休まず継続した期間の最大値を取得（ビューファイルに渡す変数）
        $sub_cont_days_first = DB::table('records')
                        ->select('habit_id', 'achieved_at', DB::raw('@group_number := 0, @prev_habit := NULL, @prev_achieved_at := NULL'))
                        ->where('habit_id', ':habit_id')
                        ->orderBy('achieved_at', 'asc')
                        ->toSql();

        $sub_cont_days_mid = DB::table(DB::raw('('.$sub_cont_days_first.') sorted_records'))    
                        ->select('habit_id', 'achieved_at', 
                        DB::raw('@group_number := IF(@prev_habit = habit_id AND DATEDIFF(achieved_at, @prev_achieved_at) = 1, 
                        @group_number, @group_number + 1) AS group_number, 
                        @prev_habit := habit_id, @prev_achieved_at := achieved_at'))
                        ->toSql();      

        $sub_cont_days_last = DB::table(DB::raw('('.$sub_cont_days_mid.') ranked_records'))
                        ->select('habit_id', DB::raw('COUNT(*) AS streak'))
                        ->groupBy('habit_id', 'group_number')
                        ->toSql();

        $max_continuous_days = DB::table(DB::raw('('.$sub_cont_days_last.') streaks'))
                        ->select('habit_id', DB::raw('MAX(streak) AS max_streak'))
                        ->groupBy('habit_id')
                        ->setBindings([':habit_id'=> $id])
                        ->get()->toArray();

        // 表示している月の前月、翌月の年月を取得。（ビューファイルに渡す変数）
        $prev = date('Y-m', strtotime('-1 month', $timestamp));
        $next = date('Y-m', strtotime('+1 month', $timestamp));


        // 1日の曜日を取得
        $first_day_of_the_week = date('w', $timestamp);

        // 月末日の曜日を取得
        $last_day_of_the_week = date('w', strtotime($ym.'-'.$day_count));
        
        // 達成状況のデータを配列で取得 
        $habit_all = Record::where('habit_id', '=', $id)->get()->toArray();

        // 今日の年月日 例）2023-09-9
        $today = date('Y-m-j'); // タイムスタンプは省略可。現在時刻になる。

        // 配列を作るための準備
        $calendar = [];
        $week_number = 0;
        $day_number = $first_day_of_the_week;

        // 第一週目の月初日より前の処理
        for ($i = 0; $i < $first_day_of_the_week; $i++) {

            $calendar[$week_number][][]= '';
        }

        // 1日から月末日までの処理
        for ($i = 1; $i <= $day_count; $i++, $day_number++) {
            
            // 土曜日まできたら改行させるため、配列を作成
            if (isset($calendar[$week_number]) && count($calendar[$week_number]) === 7) {

                $week_number++;
                // $calendarを$calendarsに格納
                $calendars[] = $calendar;
                // $calendarをリセット
                $calendar = [];
                // $day_numberをリセット
                $day_number = 0;
            }
            // 日付を表示させるための処理
            $calendar[$week_number][$day_number][] = $i;
            
            // 2023-09-9という形のものを作っておく
            $date= $ym.'-'.$i;

            // 達成した日が0日の場合
            if (empty($habit_all)) {

                $calendar[$week_number][$day_number][1] = '';
                // 日付が今日の場合
                if ($date == $today) {
                    $calendar[$week_number][$day_number][1] = 'not_achieve_today';
                }
            // 達成した日が1日以上の場合    
            } else {
                foreach ($habit_all as $habit_each) {
                
                    // DB上の値'2023-10-15 00:00:00'を'2023-10-9'に変更する
                    $achieved_day = date('Y-m-j', strtotime($habit_each['achieved_at']));

                    // 達成している日の場合の処理   
                    if ($achieved_day == $date) {

                        // 今日以外で達成している場合
                        $calendar[$week_number][$day_number][1] = 'achieved';
                        // 今日達成している場合
                        if ($date == $today) {

                            $calendar[$week_number][$day_number][1] = 'achieved_today';
                            break;
                        }
                        break;

                    // 達成していない日の場合の処理
                    } else {

                        $calendar[$week_number][$day_number][1] = '';
                        // 達成していないが、日付が今日の場合
                        if ($date == $today) {
                            $calendar[$week_number][$day_number][1] = 'not_achieve_today';
                        }
                    }
                }
            }
        }
        // 月末日以降
        for ($i = count($calendar[$week_number]); $i < 7; $i++) {

            $calendar[$week_number][][] = '';

        }
        // $calendarを$calendarsに格納
        $calendars[] = $calendar;

        $week_jp = ['日', '月', '火', '水', '木', '金', '土'];

        return view('habit.detail', compact('habit_detail', 'this_y_m', 'this_y', 'this_m', 'year_record', 
        'month_record', 'this_y_day', 'day_count', 'prev', 'next', 'calendars', 'week_jp', 'min_achievement_date', 
        'total_achievement_date', 'this_year', 'this_month', 'max_continuous_days','ym'));
    }

    // 達成した日を削除
    public function destroy_detail(Request $request, $id) {

        $request->validate([

            'delete_date' => [
                'required',
                function ($attribute, $value, $fail) use($request, $id){

                    if (!Record::where('habit_id', '=', $id)->whereDate('achieved_at', '=', $request->delete_date)->exists()) {
                        
                        $fail('この日付の記録は存在しません。');
                    }
                }
            ]],
            [
            'delete_date.required' => '日付を選択してください。'
            ]
        );
        
        Record::where('habit_id', '=', $id)->whereDate('achieved_at', '=', $request->delete_date)->delete();

        return back();
    }
}