@section('title', 'アーカイブ一覧')

<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <p class="text-xl font-medium text-gray-700 mb-4">
                    アーカイブ一覧
                </p>
                @foreach ($archive_list as $archive_item)
                    <p class="text-xl text-gray-700 border-t border-gray-200/75 pt-4 mb-4 mt-4">
                        <span class="text-amber-400">● </span>{{ $archive_item->name }}</a>
                        @if (isset($archive_item->goal))
                            　目標：{{ $archive_item->goal }}
                        @endif    
                    </p>
                    <a href="{{ route('detail.show', ['id' => $archive_item->id]) }}" class="ml-6 inline-flex items-center px-4 py-2 bg-emerald-400 border border-transparent rounded-full font-semibold text-sm 
                    text-white uppercase tracking-widest hover:text-white focus:bg-emerald-500 active:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">詳細</a>
                    <a href="{{ route('memo.show', ['id' => $archive_item->id]) }}" class="ml-2 inline-flex items-center px-4 py-2 bg-emerald-400 border border-transparent rounded-full font-semibold text-sm 
                    text-white uppercase tracking-widest hover:text-white focus:bg-emerald-500 active:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">メモ</a>
                    @endforeach

                <!-- ペジネーション -->
                <div class="mt-4">
                    {{ $archive_list->links() }}
                </div>    
                
            </div>
        </div>
    </div>  
</x-app-layout>