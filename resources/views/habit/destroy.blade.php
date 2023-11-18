@section('title', '習慣を削除')

<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <form action="{{ route('habit.destroy_habit', ['id' => $habit_detail->id]) }}" method="post">
                @csrf
                @method('delete')
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="w-full flex flex-col">
                        <label class="text-lg font-medium text-gray-700">本当に削除しますか?</label>
                        <div class="mt-5">
                            <x-primary-button name="delete_habit" value="{{ $habit_detail->id }}">
                                習慣を削除
                            </x-primary-button>
                        </div>
                    </div>
                </div>    
            </form>
        </div>
    </div>
</x-app-layout>