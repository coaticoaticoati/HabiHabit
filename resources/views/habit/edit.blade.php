@section('title', '名前編集')


<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <form action="{{ route('habit.update_habit_name', ['id' => $habit_detail->id]) }}" method="post">
                @csrf
                @method('patch')
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="w-full flex flex-col">
                        <label class="text-xl font-medium text-gray-700 mb-4">
                            新しい習慣名を入力してください。
                        </label>
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
                        <input class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-3/5" 
                        type="text" name="habit_name" placeholder="筋トレする" value="{{ old('habit_name', $habit_detail->name) }}">
                        <label class="text-lg font-medium text-gray-700 mt-5">新しい目標を入力してください。</label>
                        <!-- エラーメッセージ -->

                        <input class="mt-4 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-3/5"
                        type="text" name="habit_goal" placeholder="夏までに3キロ痩せる" value="{{ old('habit_goal', $habit_detail->goal) }}">
                        
                    </div>
                
                <div class="mt-5">
                    <x-primary-button>登録</x-primary-button>
                </div>
                </div>

            </form>     
                
        </div>

    </div>
</x-app-layout>