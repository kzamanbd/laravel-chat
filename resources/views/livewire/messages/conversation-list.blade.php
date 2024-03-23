<div>
    @foreach ($this->conversations as $item)
        <button type="button" @class([
            'chat-user-item',
            'bg-gray-100 text-primary' => $item->id == $conversationId,
        ]) wire:click="getUserMessage({{ $item->id }})">
            <div class="flex-1">
                <div class="flex items-center">
                    <div class="relative flex-shrink-0">
                        <img src="{{ $item->user_avatar }}" class="h-12 w-12 rounded-full object-cover" />
                        @if ($item->is_online)
                            <div class="absolute bottom-0 right-0">
                                <div class="h-4 w-4 rounded-full bg-success"></div>
                            </div>
                        @endif
                    </div>
                    <div class="mx-3 text-left">
                        <p class="mb-1 font-semibold">{{ $item->username }}</p>
                        <p class="text-white-dark max-w-[185px] truncate text-xs">
                            {!! $item->latest_message !!}
                        </p>
                    </div>
                </div>
            </div>
            <div class="whitespace-nowrap text-xs font-semibold">
                <p>
                    {{ $item->latest_message_time }}
                </p>
            </div>
        </button>
        {{-- </template> --}}
    @endforeach
</div>
