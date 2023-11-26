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
                        <a href="/habit/detail/{{ $archive_item->id }}"><span class="text-amber-400">● </span>{{ $archive_item->name }}</a>
                        @if (isset($archive_item->goal))
                            　目標：{{ $archive_item->goal }}</a>
                        @else
                            </a>
                        @endif    
                    </p>
                    <a href="/habit/detail/{{ $archive_item->id }}" class="bg-emerald-400 text-base text-white py-1 px-2 ml-4 rounded-full">実績の詳細</a>
                    <a href="/habit/memo/{{ $archive_item->id }}" class="bg-emerald-400 text-base text-white py-1 px-2 ml-2 rounded-full">メモ</a>
                @endforeach

                <!-- ペジネーション -->
                <div class="mt-4">
                    {{ $archive_list->links() }}
                </div>    
                
            </div>
        </div>
    </div>  
</x-app-layout>