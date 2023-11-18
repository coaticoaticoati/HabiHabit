@section('title', 'メモ')

<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="justify-center flex">
                    <p class="text-xl font-medium text-gray-700">
                            {{ $habit_detail->name }}
                    </p>
                </div>
                <div class="justify-center flex">
                    @if (isset($habit_detail->goal))
                        <p class="text-base font-medium text-gray-700 ">
                            目標：{{ $habit_detail->goal }}
                        </p>
                    @endif
                </div>
                <div class="justify-center flex">    
                    <a href="{{ route('habit.show_detail', ['id' => $habit_detail->id]) }}" 
                    class="bg-emerald-400 text-base text-white py-1 px-1 my-2 rounded-full">detail</a>
                </div>
                <div class="justify-center flex mb-4">
                    <p class="text-gray-700">
                        <a href="?ym={{ $prev }}">
                            &lt;
                        </a>
                            {{ $this_month_ym }}
                        <a href="?ym={{ $next }}">
                            &gt;
                        </a>
                    </p>    
                </div>

                <!-- セレクトボックス -->
                <form action="{{ route('habit.show_memo',  ['id' => $habit_detail->id]) }}" method="get" class="justify-center flex">
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

                <p class="justify-center flex mb-4 text-gray-700">日付を選択すると、メモを編集できます。 </p>

                <div class="justify-center flex">      
                    <!-- カレンダー -->
                    <table class="w-4/5">
                        @foreach ($calendar_of_memo as $memo_item)
                            <tr class="">
                                <!--  -->
                                <td class="w-1/5 text-center p-5 border-y border-r text-gray-700">
                                    <a href="{{ route('habit.show_edit_memo', ['id' => $habit_detail->id, 'day'=> $ym.'-'.$memo_item['day']]) }}">
                                        @if (empty($memo_item['achievement']))
                                            @if ($memo_item['day'] == date('j'))
                                                <span class="text-emerald-400">　 {{ $memo_item['day'] }}</span>
                                            @else
                                                　 {{ $memo_item['day'] }}
                                            @endif
                                        @else
                                            @if ($memo_item['day'] == date('j'))
                                                <span class="text-amber-400">● </span><span class="text-emerald-400">{{ $memo_item['day'] }}</span>
                                            @else
                                                <span class="text-amber-400">● </span>{{ $memo_item['day'] }}
                                            @endif
                                        @endif    
                                        
                                        @if($memo_item['week'] == '0')
                                            （<span class="text-red-500">{{ $week_jp[$memo_item['week']] }}</span>）
                                        @elseif ($memo_item['week'] == '6')
                                                （<span class="text-blue-500">{{ $week_jp[$memo_item['week']] }}</span>）
                                        @else
                                            （{{ $week_jp[$memo_item['week']] }}）
                                        @endif
                                </td>

                                <!--  -->
                                <td class="w-4/5 p-5 border-y text-gray-700">{{ $memo_item['memo'] }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>  
            </div>
        </div>
    </div>
</x-app-layout>