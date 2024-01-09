@section('title', '詳細')

<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <!-- 習慣の名前、目標、年月 -->
                <div class="justify-center flex mb-2">
                    <h2 class="text-xl font-medium text-gray-700 ">
                        {{ $habit_detail->name }}
                    </h2>
                </div>

                <div class="justify-center flex mb-1">
                    @if (isset($habit_detail->goal))
                        <h3 class="text-base font-medium text-gray-700 ">
                            目標：{{ $habit_detail->goal }}
                        </h3>
                    @endif
                </div>
                
                <div class="justify-center flex text-lg mb-2">
                    <p class="text-gray-700">
                        <a href="?ym={{ $prev }}">
                            &lt;
                        </a>
                            {{ $this_y_m }}
                        <a href="?ym={{ $next }}">
                            &gt;
                        </a>
                    </p>
                </div> 

                <!-- セレクトボックス -->
                <form action="{{ route('detail.show',  ['id' => $habit_detail->id]) }}" method="get" class="justify-center flex">
                    <select name="year" class="text-gray-700 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        @for ($i = 1990; $i <= 2035; $i++)
                            @if ($i == $this_year)
                                <option value="{{ $i }}" selected>{{ $i }}年</option>
                            @else
                            <option value="{{ $i }}">{{ $i }}年</option>
                            @endif
                        @endfor   
                    </select>
                    <select name="month" class="text-gray-700 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm ml-2">
                        @for ($i = 1; $i <= 12; $i++)
                            @if ($i == $this_month)
                                <option value="{{ $i }}" selected>{{ $i }}月</option>
                            @else
                                <option value="{{ $i }}">{{ $i }}月</option>
                            @endif
                        @endfor   
                    </select>
                    <x-primary-button class="ml-2">移動</x-primary-button>
                </form>

                <!-- カレンダー -->
                <div class="justify-center flex"> 
                    <table class="text-center text-gray-700">
                        <tr class="w-20 h-20">
                            <!-- 曜日 -->
                            @foreach ($week_jp as $week)
                                <!-- 日曜日の場合 -->
                                @if ($week == '日')
                                    <th class="text-red-500">{{ $week }}</th>
                                <!-- 土曜日の場合 -->    
                                @elseif ($week == '土')
                                    <th class="text-blue-500">{{ $week }}</th>
                                <!-- それ以外 -->
                                @else
                                    <th>{{ $week }}</th>
                                @endif
                            @endforeach
                        </tr>
                        <!-- 日付 -->
                        @foreach ($calendars as $calendar_week)
                            <tr class="w-20 h-20 text-gray-700">
                                @foreach ($calendar_week as $calendar_day)
                                    @foreach ($calendar_day as $calendar)
                                        <!-- 習慣を達成していない、かつ日付が今日でない日 -->
                                        @if (empty($calendar[1]))                                            
                                            <td class="w-20 h-20">{{ $calendar[0] }}</td>
                                        @else
                                            <!-- 習慣を達成している、かつ日付が今日である日 --> 
                                            @if ($calendar[1] == 'achieved_today')
                                                <td class="w-20 h-20 bg-amber-200 text-emerald-400 rounded-full">
                                                    {{ $calendar[0] }}
                                                </td>
                                            <!-- 習慣を達成しているが、日付が今日でない日 --> 
                                            @elseif ($calendar[1] === 'achieved')
                                                <td class="w-20 h-20 bg-amber-200 rounded-full">
                                                    {{ $calendar[0] }}
                                                </td> 
                                            <!-- 習慣を達成していないが、日付が今日である日 -->      
                                            @elseif ($calendar[1] === 'not_achieve_today')
                                                <td class="w-20 h-20 text-emerald-400">
                                                    {{ $calendar[0] }}
                                                </td> 
                                            @endif     
                                        @endif
                                    @endforeach
                                @endforeach
                                </tr>
                        @endforeach    
                    </table>
                </div>
                
                <div class="justify-center flex">
                    <a href="{{ route('memo.show_from_detail', ['id' => $habit_detail->id, 'ym' => $ym]) }}" 
                    class="bg-emerald-400 text-base text-white py-1 px-2 my-2 rounded-full">メモへ移動</a> 
                </div>

            </div>

            <!-- 実績の削除 -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h2 class="text-xl font-medium text-gray-700 mb-4">
                    実績の削除（メモは残ります）
                </h2>
                <!-- エラーメッセージ -->
                @if ($errors->any())
                    <div>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li class="text-red-600">{{ $error }}<li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <!-- カレンダーとボタン -->  
                <form action="{{ route('detail.destroy', ['id' => $habit_detail->id]) }}" method="post">
                    @csrf
                    @method('delete')
                    <input type="date" name="delete_date" class="rounded-md">
                    <div class="mt-6">
                        <x-primary-button>削除</x-primary-button>
                    </div>
                </form>
            </div>
            <!-- 習慣の詳細 -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h2 class="text-xl font-medium text-gray-700 mb-4">習慣の詳細</h4>
                
                <!-- 表示している月が属する年の、1年間の達成日数 -->
                <h3 class="text-lg font-medium text-gray-700"><span class="text-amber-400">● </span>{{ $this_y }}年</h3>
                @if ($this_y_day == 1)
                <p class="mb-2 mt-1">
                    {{ $year_record }} days / 366 days
                </p>
                @else
                <p class="mb-2 mt-1">
                    {{ $year_record }} days / 365 days
                </p>
                @endif

                <!-- 表示している月の達成日数 -->
                <h3 class="text-lg font-medium text-gray-700"><span class="text-amber-400">● </span>{{ $this_m }}月</h3>
                <p class="mb-2 mt-1">{{ $month_record }} days / {{ $day_count }} days</p>

                <!-- 累計の達成日数 -->
                <h3 class="text-lg font-medium text-gray-700"><span class="text-amber-400">● </span>合計</h3>
                    @if (empty($total_achievement_date))
                        <p class="mb-2 mt-1"> 0 days </p>
                    @else
                        <p class="mb-2 mt-1"> {{ $total_achievement_date }} days / {{ $min_achievement_date }} ~</p>
                    @endif
                
                <!-- 休まず継続した期間の最大値 -->
                <h3 class="text-lg font-medium text-gray-700"><span class="text-amber-400">● </span>継続（最長）</h3>
                    <p class="mb-2 mt-1">

                    @if (is_array($max_continuous_days) && empty($max_continuous_days)) 
                        0 days
                    @else   
                        @foreach ($max_continuous_days as $max_cont_days_value)
                            {{ $max_cont_days_value->max_streak }} days   
                        @endforeach    
                    @endif    
                    </p>
            </div>

            <!-- 習慣の設定 -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h2 class="text-xl font-medium text-gray-700 mb-4">習慣の設定</h2>
                <ul class="text-lg text-gray-700">
                    <li class="mb-2"><a href="{{ route('habit.show_name_edit', ['id' => $habit_detail->id]) }}">
                        <span class="text-amber-400">● </span>習慣・目標の内容を編集する
                    </a></li>
                    @if ($habit_detail->archive === 1)
                        <li class="mb-2"><a href="{{ route('archive.update', ['id' => $habit_detail->id]) }}">
                            <span class="text-amber-400">● </span>挑戦中の習慣に戻す
                        </a></li>
                    @else
                        <li class="mb-2"><a href="{{ route('archive.show_confirmation', ['id' => $habit_detail->id]) }}">
                            <span class="text-amber-400">● </span>アーカイブに移動する
                        </a></li>
                    @endif
                    <li class=""><a href="{{ route('habit.show_deletion', ['id' => $habit_detail->id]) }}">
                        <span class="text-amber-400">● </span>習慣を削除する
                    </a></li>
                </ul>
            </div>    
        </div>
    </div>
</x-app-layout>