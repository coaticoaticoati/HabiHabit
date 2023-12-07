@section('title', 'ホーム')

<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <p class="text-xl font-medium text-gray-700 mb-4">
                    <!-- 実績を登録した場合 -->
                    @if(session()->has('achievement_date'))
                        お疲れ様でした！
                    <!-- 未登録の場合 -->    
                    @else
                        実績を記録しましょう!
                    @endif
                </p>
                <!-- エラーメッセージ -->
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <p class="text-red-600 ml-2">{{ $error }}</p>
                    @endforeach
                @endif
                <!-- 習慣一覧 -->
                @foreach ($habit_list as $habit_item) 
                    <p class="text-xl text-gray-700 border-t border-gray-200/75 pt-4 mb-4 mt-4">
                        <span class="text-amber-400">● </span>{{ $habit_item->name }}
                            @if (isset($habit_item->goal))
                                　目標：{{ $habit_item->goal }}  
                            @endif    
                    </p>
                    <div>
                        <form action="{{ route('habit.store') }}" method="post">
                            @csrf
                            <input type="date" name="achievement_date" value="{{ $today }}" min="1990-01-01" max="2035-12-31"
                            class="text-gray-700 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm ml-4">
                            <input type="hidden" name="habit_id" value="{{ $habit_item->id }}">
                            <x-primary-button class="ml-2">登録</x-primary-button>
                            <a href="/habit/detail/{{ $habit_item->id }}" class="ml-4 inline-flex items-center px-4 py-2 bg-emerald-400 border border-transparent rounded-full font-semibold text-sm 
                            text-white uppercase tracking-widest hover:text-white focus:bg-emerald-500 active:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">詳細</a>
                            <a href="/habit/memo/{{ $habit_item->id }}" class="ml-2 inline-flex items-center px-4 py-2 bg-emerald-400 border border-transparent rounded-full font-semibold text-sm 
                            text-white uppercase tracking-widest hover:text-white focus:bg-emerald-500 active:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">メモ</a>
                        </form>
                    </div>
                @endforeach
                
                <!-- ペジネーション -->
                <div class="mt-4">
                    {{ $habit_list->links() }}
                </div>    
            </div>
        </div>
    </div>
</x-app-layout>