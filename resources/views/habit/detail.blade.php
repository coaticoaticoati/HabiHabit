@section('title', '詳細')

<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
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

                <div class="justify-center flex">
                    <a href="{{ route('habit.show_memo', ['id' => $habit_detail->id]) }}" 
                    class="bg-emerald-400 text-base text-white py-1 px-1 my-2 rounded-full">memo</a> 
                </div>
                
                <div class="justify-center flex text-lg mb-2">
                    <p class="">
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
                <form action="{{ route('habit.show_detail',  ['id' => $habit_detail->id]) }}" method="get" class="justify-center flex">
                    <select name="year" class="text-gray-700 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        @for ($i = 1970; $i <= 2035; $i++)
                            @if ($i == $this_year)
                                <option value="{{ $i }}" selected>{{ $i }}年</option>
                            @endif
                            <option value="{{ $i }}">{{ $i }}年</option>
                        @endfor   
                    </select>
                    <select name="month" class="text-gray-700 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm ml-2">
                        @for ($i = 1; $i <= 12; $i++)
                            @if ($i == $this_month)
                                <option value="{{ $i }}" selected>{{ $i }}月</option>
                            @endif
                            <option value="{{ $i }}">{{ $i }}月</option>
                        @endfor   
                    </select>
                    <x-primary-button class="ml-2">移動</x-primary-button>
                </form>

                <!-- カレンダー -->
                <div class="justify-center flex"> 
                    <table class="text-center text-gray-700">
                        <tr class="w-20 h-20">
                            @foreach ($week_jp as $week)
                                @if ($week == '日')
                                    <th class="text-red-500">{{ $week }}</th>
                                @elseif ($week == '土')
                                    <th class="text-blue-500">{{ $week }}</th>
                                @else
                                    <th>{{ $week }}</th>
                                @endif
                            @endforeach
                        </tr>
                        @foreach ($calendars as $calendar_week)
                            <tr class="w-20 h-20 text-gray-700">
                                @foreach ($calendar_week as $calendar_day)
                                    @foreach ($calendar_day as $calendar)
                                        @if (empty($calendar[1]))                                            
                                            <td class="w-20 h-20">{{ $calendar[0] }}</td>
                                        @else
                                            @if ($calendar[1] == 'achieved_today')
                                                <td class="w-20 h-20 bg-amber-200 text-emerald-400 rounded-full">
                                                    {{ $calendar[0] }}
                                                </td>
                                            @elseif ($calendar[1] === 'achieved')
                                                <td class="w-20 h-20 bg-amber-200 rounded-full">
                                                    {{ $calendar[0] }}
                                                </td>  
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
                <form action="{{ route('habit.destroy_detail', ['id' => $habit_detail->id]) }}" method="post">
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

                <h3 class="text-lg font-medium text-gray-700"><span class="text-amber-400">● </span>{{ $this_m }}月</h3>
                <p class="mb-2 mt-1">{{ $month_record }} days / {{ $day_count }} days</p>

                <h3 class="text-lg font-medium text-gray-700"><span class="text-amber-400">● </span>合計</h3>
                    @if (empty($total_achievement_date))
                        <p class="mb-2 mt-1"> 0 days </p>
                    @else
                        <p class="mb-2 mt-1"> {{ $total_achievement_date }} days / {{ $min_achievement_date }} ~</p>
                    @endif
                
                <h3 class="text-lg font-medium text-gray-700"><span class="text-amber-400">● </span>継続（最大）</h3>
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
                    <li class="mb-2"><a href="/habit/edit/{{ $habit_detail->id }}">
                        <span class="text-amber-400">● </span>習慣・目標の内容を編集する
                    </a></li>
                    @if ($habit_detail->archive === 1)
                        <li class="mb-2"><a href="/habit/archive/{{ $habit_detail->id }}">
                            <span class="text-amber-400">● </span>挑戦中の習慣に戻す
                        </a></li>
                    @else
                        <li class="mb-2"><a href="/habit/save-archive/{{ $habit_detail->id }}">
                            <span class="text-amber-400">● </span>アーカイブに移動する
                        </a></li>
                    @endif
                    <li class=""><a href="/habit/destroy/{{ $habit_detail->id }}">
                        <span class="text-amber-400">● </span>習慣を削除する
                    </a></li>
                </ul>
            </div>    
        </div>
    </div>
</x-app-layout>