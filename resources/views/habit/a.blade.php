
    use Carbon\Carbon;
    // habitsテーブルから未完了一覧を取得し、表示
        // recordsテーブルに登録があるものを取得（今日の日付分があるか）
        /*$records = Record::where('user_id', '=', $user_id)
                   ->whereDate('created_at', Carbon::today())
                   ->get();
        foreach ($habits as $habit) {
            $finished = false;
            foreach ($records as $record) {
                if ($habit->id === $record->habit_id) {
                    $finished = true;
                    break;
                }
            }
            // recordsテーブルに無い場合は一覧に表示
            if ($finished === false) {
                $unfinished_habits[] = $habit;
            }
        }
        if (isset($unfinished_habits)) {
            return view('habit.index', compact('unfinished_habits')); // 取得したものをhabit/index.blade.phpで表示
        } else}*/


  

    
*/

requiredのphp側でバリデーションする


<h3 class="year"><a href="?ym={{ $prev }}">&lt;</a>{{ $this_month }}<a href="?ym={{ $next }}">&lt;</a></h3>
    <table class="month">
        <tr>
        @foreach ($calendar as $value)
            {{$value['day']}}({{$weekjp[$value['week']]}})</td>
            {{ $value['habit'] }}
        @endforeach    
        </tr>



public function show_tate_calendar() {
        // タイムゾーン設定
        date_default_timezone_set('Asia/Tokyo');
                // 先月、翌月のリンクが押された場合は、GETで年月を取得し表示
                if (isset($_GET['ym'])) {
                    $ym = $_GET['ym'];
                } else {
                    // 押されていない場合は今月を表示
                    $ym = date('Y-m');
                }
        
                // タイムスタンプを作成
                $timestamp = strtotime($ym.'-01'); // 例：2023-09-01
        
                // 今日の年月日 例）2023-09-9
                $today = date('Y-m-j'); // タイムスタンプは省略可。現在時刻になる。
        
                // 今月　例：2023年9月
                $this_month = date('Y年n月', $timestamp);

        
                // 先月、翌月の年月を取得
                $prev = date('Y-m', strtotime('-1 month', $timestamp));
                $next = date('Y-m', strtotime('+1 month', $timestamp));
                // 今月の日数（月末日）を取得
                $day_count = date('t', $timestamp);
        
                // 1日の曜日を取得
                $day_of_the_week = date('w', $timestamp);
        
                $weekjp = ['日', '月', '火', '水', '木', '金', '土'];
        
                $schedule[5] = '運動';
                $schedule[7] = '早起き';
        
        $calendar = [];
                for ($day = 1; $day <= $day_count; $day++, $day_of_the_week++) {
        
                    // 
                    $date = $ym.'-'.$day;
                    $calendar[$day]['day'] = '<td>'.$day;
                    $calendar[$day]['week'] = date('w', strtotime($ym.'-'.$day));
                    if (isset($schedule[$day])) {
                        $calendar[$day]['habit'] = '<td>'.$schedule[$day].'</td>';
                    } else {
                        $calendar[$day]['habit'] = '<td></td>';
                    }
        
                   
                }
        
                return view('habit.tatecalendar', compact('this_month', 'prev', 'next', 'calendar', 'weekjp'));
            }

            // aタグhref属性にルート名を記述することでルート設定を呼び出している

// コントローラーで処理するほどのボリュームがない場合は、第2引数にクロージャーを書いて定義することが可能。


//第一週目の1日より前に空白を入れる
        //$week = str_repeat('<td></td>', $day_of_the_week);

        for ($day = 1; $day <= $day_count; $day++, $day_of_the_week++) {

            // 2023-09-2という形のものを作る
            $date= $ym.'-'.$day;

            // 達成日や今日の日付には、classを付ける。そのために適当な変数を用意する。
            $ = 'not_achieve';

            foreach ($habit_all as $habit_each) {
            
                // DB上の値'2023-10-15 00:00:00'を'2023-10-9'に変更する
                $achieved_day = date('Y-m-j', strtotime($habit_each['achieved_at']));

                // 今日の日付
                if (($achieved_day == $date) && ($date == $today)) {

                    $ = 'achieved_today';
                    break;

                // 今日以外の達成した日
                } elseif ($achieved_day == $date) {

                    $ = 'achieved';
                    break;

                // 達成していないが、日付が今日の場合    
                } elseif ($date == $today) {

                    $ = 'not_achieve_today';

                }
            }

            if ($ === 'achieved_today') {

                $week .= '<td class="w-12 h-12 bg-cyan-200 text-lg text-white font-semibold 
                rounded-full border-2 border-amber-600">'.$day;

            } elseif ($ === 'achieved') {

                $week .= '<td class="w-12 h-12 bg-cyan-200 text-lg text-white font-semibold rounded-full">'.$day;

            } elseif ($ === 'not_achieve_today') {

                $week .= '<td class="rounded-full border-2 border-amber-600">'.$day;

            } elseif ($ === 'not_achieve') {

                $week .= '<td class="">'.$day;
            }

            $week .= '</td>';    
    
            //  週の終わり、または、月末の場合
            if ($day_of_the_week % 7 == 6 || $day == $day_count) {

                if ($day === $day_count) {
                    // 月末の後に空白を入れる
                    $week .= str_repeat('<td class=""></td>', 6 - ($day_of_the_week % 7));
                }
                // $weeksの配列にtrと$weekを追加する
                $weeks[] = '<tr class="h-20">'.$week.'<tr>';
                // weekをリセット
                $week = '';
            }
        }

        return view('habit.detail', compact('habit_detail', 'this_month', 'prev', 'next', 
        'weeks', 'min_achievement_date', 'total_achievement_date'));
    }

    <table class="w-3/6 m-auto">
                        <tr class="h-20">
                            <th class="">日</th>
                            <th class="">月</th>
                            <th class="">火</th>
                            <th class="">水</th>
                            <th class="">木</th>
                            <th class="">金</th>
                            <th class="">土</th>
                        </tr>
                        @foreach ($weeks as $week) 
                            {!! $week !!}
                        @endforeach
                    </table>

                    $subQuery1 = DB::table('records')
            ->selectRaw('habit_id, 
            achieved_at, 
            DATE_DIFF(achieved_at, LAG(achieved_at) OVER(PARTITION BY habit_id ORDER BY achieved_at), DAY) as step');
        
        $subQuery2 = DB::table($subQuery1)
        ->selectRaw('habit_id, 
        COUNTIF(step > 1) OVER(PARTITION BY habit_id ORDER BY achieved_at) as grp');

        $subQuery3 = DB::table($subQuery2)
        ->selectRaw('habit_id, grp, COUNT(1) as consecuitive_days')
        ->groupBy('habit_id, grp');

        $query = DB::table($subQuery3)
        ->selectRaw('habit_id, MAX(consecuitive_days) as max_consecuitive_days')
        ->groupBy('habit_id')
        ->orderBy('habit_id');

        dd($query);
       