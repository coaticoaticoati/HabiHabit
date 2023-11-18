@section('title', 'ホーム')

<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <form action="{{ route('habit.store_habit_date') }}" method="post">
                @csrf
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <p class="text-xl font-medium text-gray-700 mb-4">
                        実績を記録しましょう!
                    </p>
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
                    <!-- セレクトボックス -->
                    <select name="habit_selection" class="text-gray-700 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-3/6">
                        <option value="">選択してください</option>     
                        @foreach ($habit_list as $habit_item)
                            <option value="{{ $habit_item->id }}">
                                {{ $habit_item->name }}
                            </option>   
                        @endforeach
                    </select>
                    <input type="date" name="achievement_date" class="text-gray-700 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm ml-1">
                    <div class="mt-5">
                        <x-primary-button>登録</x-primary-button>
                    </div>
                </div>    
            </form>
        
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <p class="text-xl font-medium text-gray-700 mb-4">
                    習慣一覧
                </p>
                @foreach ($habit_list as $habit_item) 
                    <p class="text-xl text-gray-700 border-t border-gray-200/75 py-6">
                        <span class="text-amber-400">● </span>{{ $habit_item->name }}
                            @if (isset($habit_item->goal))
                                　目標：{{ $habit_item->goal }}  
                            @endif    
                    </p>
                    
                    <a href="/habit/detail/{{ $habit_item->id }}" class="bg-emerald-400 text-base text-white py-1 px-1 my-2 rounded-full">実績の詳細</a>
                    <a href="/habit/memo/{{ $habit_item->id }}" class="bg-emerald-400 text-base text-white py-1 px-1 my-2 rounded-full">メモ</a>
                @endforeach
                
                <!-- ペジネーション -->
                <div class="mt-4">
                    {{ $habit_list->links() }}
                </div>    
            </div>
        </div>
    </div>
</x-app-layout>