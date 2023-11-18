@section('title', 'アーカイブへ保存')

<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <form action="{{ route('habit.store_archive') }}" method="post">
                @csrf
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <p class="text-lg font-medium text-gray-700 mb-4">
                            {{ $habit_detail->name }}をアーカイブに移動しますか?
                        </p>
                        <p class="text-lg font-medium text-gray-700">
                            アーカイブに保存された習慣は後で戻すことができます。
                        </p>
                        <div class="mt-5">
                            <input type="hidden" name="habit_id" value="{{ $habit_detail->id }}" >
                            <x-primary-button>移動</x-primary-button>
                        </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>