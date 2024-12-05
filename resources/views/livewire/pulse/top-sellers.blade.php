<x-pulse::card :cols="$cols" :rows="$rows" :class="$class" wire:poll.5s="">
    <x-pulse::card-header name="Top Sellers">
    </x-pulse::card-header>

    <x-pulse::scroll :expand="$expand">
        <ul class="list-none p-0">
            @foreach($topSellers as $record)
                <li class="py-2 flex justify-between items-center">
                    <span class="text-sm text-gray-500">{{ $record->borrow_count }} Borrows</span>
                </li>
            @endforeach
        </ul>
    </x-pulse::scroll>
</x-pulse::card>
