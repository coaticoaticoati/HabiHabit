@section('title', '実績登録完了')

<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <p class="text-lg font-medium text-gray-700 mb-4">
                    お疲れさまでした。この調子でいきましょう!
                </p>
                <a href="{{ route('habit.show_detail', ['id' => $habit_id]) }}">実績の詳細を見る</a>
                <a href="{{ route('habit.show_memo', ['id' => $habit_id]) }}">メモを見る</a>
            </div>
        </div>
    </div>

</x-app-layout>