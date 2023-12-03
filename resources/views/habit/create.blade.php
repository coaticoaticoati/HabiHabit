@section('title', '新規作成')

<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <form action="{{ route('habit.store_name') }}" method="post">
                @csrf
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="w-full flex flex-col">
                        <label class="text-xl font-medium text-gray-700 mb-4">習慣化したいことを入力してください。</label>
                        <!-- エラーメッセージ -->
                        @if ($errors->has('habit_name'))
                            <ul>
                                @foreach($errors->get('habit_name') as $name_msg)
                                    <li class="text-red-600">{{ $name_msg }}</li>
                                @endforeach
                            </ul>
                        @endif            
                        <input class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-3/5" 
                        type="text" name="habit_name" placeholder="筋トレする" value="{{ old('habit_name') }}">
                        <label class="text-lg font-medium text-gray-700 mt-5 mb-4 ">目標を設定できます。</label>
                        <!-- エラーメッセージ -->
                        @if ($errors->has('habit_goal'))
                            <ul>
                                @foreach($errors->get('habit_goal') as $goal_msg)
                                    <li class="text-red-600">{{ $goal_msg }}</li>
                                @endforeach
                            </ul>
                        @endif    
                        <input class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-3/5"
                        type="text" name="habit_goal" placeholder="夏までに3キロ痩せる" value="{{ old('habit_goal') }}">
                    </div>
                
                    <div class="mt-5">
                        <x-primary-button>登録</x-primary-button>
                    </div>
                </div>
            </form>     
        </div>
    </div>
</x-app-layout>