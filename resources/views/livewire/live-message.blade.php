<div>
    <div class="py-4" x-data="{ isMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 flex flex-row h-full w-full overflow-hidden relative">
            <div tabindex="0" role="button" @click="isMenuOpen = false" :class="isMenuOpen ? 'block' : 'hidden'"
                class="fixed inset-0 z-10 transition-opacity bg-black opacity-50 lg:hidden"></div>
            <aside :class="isMenuOpen ? 'translate-x-0' : '-translate-x-full'"
                class="fixed z-10 top-[80px] lg:static lg:inset-0 lg:translate-x-0 flex flex-col p-6 w-80 bg-white flex-shrink-0 h-[calc(100vh-100px)] overflow-y-auto scrollbar-thumb-blue scrollbar-thumb-rounded scrollbar-track-blue-lighter scrollbar-w-2">
                <div class="flex items-center bg-indigo-100 border border-gray-200 w-full py-6 px-4 rounded-lg">
                    <img src="{{ auth()->user()->user_avatar }}" alt="Avatar"
                        class="h-16 w-16 rounded-full border overflow-hidden object-cover" />

                    <div class="ml-3">
                        <div class="text-sm font-semibold">{{ auth()->user()->name }}</div>
                        <div class="flex items-center">
                            <div class="flex flex-col justify-center h-4 w-8 bg-indigo-500 rounded-full">
                                <div class="h-3 w-3 bg-white rounded-full self-end mr-1"></div>
                            </div>
                            <div class="leading-none ml-1 text-xs">Active</div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col mt-8">
                    <div class="flex flex-row items-center justify-between text-xs">
                        <span class="font-bold">Recent Messages</span>
                        <span
                            class="flex items-center justify-center bg-gray-300 h-4 w-4 rounded-full">{{ count($this->conversations) }}</span>
                    </div>
                    <div class="flex flex-col space-y-1 mt-4 -mx-2 overflow-y-auto">
                        @foreach ($this->conversations as $item)
                            <button
                                class="flex flex-row items-center hover:bg-gray-100 rounded-xl p-2 {{ isset($this->conversation) && $item->id == $this->conversation->id ? 'bg-gray-100' : '' }}"
                                wire:click="getMessage({{ $item->id }})">
                                <div class="flex items-center justify-center h-8 w-8 rounded-full relative">
                                    <img src="{{ $item->user_avatar }}" class="object-cover h-8 w-8 rounded-full"
                                        alt="" />
                                    @if ($item->unread_message_count > 0)
                                        <span
                                            class="absolute left-0 top-[-5px] flex items-center justify-center bg-red-500 text-white p-0.5 h-4 w-4 rounded-full text-xs">
                                            {{ $item->unread_message_count }}
                                        </span>
                                    @endif
                                </div>
                                <div class="ml-2 text-sm font-semibold truncate">
                                    @if ($item->to->id == auth()->user()->id)
                                        {{ $item->from->name }}
                                    @else
                                        {{ $item->to->name }}
                                    @endif
                                </div>
                            </button>
                        @endforeach

                    </div>
                    <div class="flex flex-row items-center justify-between text-xs pt-6">
                        <span class="font-bold">Friends</span>
                        <span
                            class="flex items-center justify-center bg-gray-300 h-4 w-4 rounded-full">{{ count($this->users) }}</span>
                    </div>
                    <div class="flex flex-col space-y-1 mt-4 -mx-2 overflow-y-auto">

                        @foreach ($this->users as $user)
                            <button
                                class="flex flex-row items-center hover:bg-gray-100 rounded-xl p-2 {{ isset($this->newMessage) && $user->id == $this->newMessage->id ? 'bg-gray-100' : '' }}"
                                wire:click="startNewMessage({{ $user->id }})">
                                <div class="flex items-center justify-center h-8 w-8 bg-indigo-200 rounded-full">
                                    <img src="{{ $user->user_avatar }}" class="object-cover h-8 w-8 rounded-full"
                                        alt="" />
                                </div>
                                <div class="ml-2 text-sm font-semibold truncate">{{ $user->name }}</div>
                            </button>
                        @endforeach
                    </div>
                </div>
            </aside>

            <div class="flex-1 p-6 justify-between flex flex-col h-[calc(100vh-100px)] bg-gray-50">
                <button @click="isMenuOpen = ! isMenuOpen"
                    class="lg:hidden inline-flex items-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': isMenuOpen, 'inline-flex': !isMenuOpen }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !isMenuOpen, 'inline-flex': isMenuOpen }" class="hidden"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                @if ($isSelected)
                    <div class="flex sm:items-center justify-between py-3 border-b-2 border-gray-200">
                        <div class="relative flex items-center space-x-4">
                            <div class="relative">
                                <span class="absolute text-green-500 right-0 bottom-0">
                                    <svg width="20" height="20">
                                        <circle cx="8" cy="8" r="8" fill="currentColor">
                                        </circle>
                                    </svg>
                                </span>
                                @if ($conversation)
                                    <img src="{{ $conversation->user_avatar }}" alt="Avatar" alt=""
                                        class="w-10 sm:w-16 h-10 sm:h-16 rounded-full" />
                                @else
                                    <img src="{{ $newMessage->user_avatar }}" alt="Avatar" alt=""
                                        class="w-10 sm:w-16 h-10 sm:h-16 rounded-full" />
                                @endif
                            </div>
                            <div class="flex flex-col leading-tight">
                                <div class="text-2xl mt-1 flex items-center">
                                    <span class="text-gray-700 mr-3">
                                        @if ($conversation)
                                            {{ auth()->id() == $conversation->to_user_id ? $conversation->from->name : $conversation->to->name }}
                                        @else
                                            {{ $newMessage->name }}
                                        @endif
                                    </span>
                                </div>
                                <span class="text-lg text-gray-600">Junior Developer</span>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button type="button"
                                class="inline-flex items-center justify-center rounded-lg border h-10 w-10 transition duration-500 ease-in-out text-gray-500 hover:bg-gray-300 focus:outline-none">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" class="h-6 w-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>
                            <button type="button"
                                class="inline-flex items-center justify-center rounded-lg border h-10 w-10 transition duration-500 ease-in-out text-gray-500 hover:bg-gray-300 focus:outline-none">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" class="h-6 w-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                    </path>
                                </svg>
                            </button>
                            <button type="button"
                                class="inline-flex items-center justify-center rounded-lg border h-10 w-10 transition duration-500 ease-in-out text-gray-500 hover:bg-gray-300 focus:outline-none">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" class="h-6 w-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    @if ($conversation)
                        <div id="messages" x-data="{ scroll: () => { $el.scrollTo(0, $el.scrollHeight); } }" x-init="scroll()"
                            @scroll-bottom.window="scroll()"
                            class="flex flex-col space-y-4 p-3 overflow-y-auto scrollbar-thumb-blue scrollbar-thumb-rounded scrollbar-track-blue-lighter scrollbar-w-2">
                            @forelse ($conversation->messages as $message)
                                @if (auth()->user()->id != $message->user_id)
                                    <div class="chat-message">
                                        <div class="flex items-end">
                                            <div
                                                class="flex flex-col space-y-2 text-xs max-w-xs mx-2 order-2 items-start">
                                                <div>
                                                    <span
                                                        class="px-4 py-2 rounded-lg inline-block rounded-bl-none bg-gray-300 text-gray-600">
                                                        {{ $message->message_text }}
                                                    </span>
                                                </div>
                                            </div>
                                            <img src="https://ui-avatars.com/api/?background=random&name={{ $conversation->to->name }}"
                                                alt="My profile" class="w-6 h-6 rounded-full order-1" />
                                        </div>
                                    </div>
                                @else
                                    <div class="chat-message">
                                        <div class="flex items-end justify-end">
                                            <div
                                                class="flex flex-col space-y-2 text-xs max-w-xs mx-2 order-1 items-end">
                                                <div>
                                                    <span
                                                        class="px-4 py-2 rounded-lg inline-block rounded-br-none bg-blue-600 text-white">
                                                        {{ $message->message_text }}
                                                    </span>
                                                </div>
                                            </div>
                                            <img src="https://ui-avatars.com/api/?background=random&name={{ auth()->user()->name }}"
                                                alt="My profile" class="w-6 h-6 rounded-full order-2" />
                                        </div>
                                    </div>
                                @endif
                            @empty
                                <div class="w-full px-5 flex flex-col justify-center h-full">
                                    <div class="flex justify-center">
                                        No Previous message, You can start new conversation
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    @elseif($newMessage)
                        <div class="w-full px-5 flex flex-col justify-center h-full">
                            <div class="flex justify-center">
                                No Previous message, You can start new conversation
                            </div>
                        </div>
                    @endif

                    <div class="border-t-2 border-gray-200 pt-4 mb-2 sm:mb-0">
                        <div class="relative flex">
                            <span class="absolute inset-y-0 flex items-center">
                                <button type="button"
                                    class="inline-flex items-center justify-center rounded-full h-12 w-12 transition duration-500 ease-in-out text-gray-500 hover:bg-gray-300 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" class="h-6 w-6 text-gray-600">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z">
                                        </path>
                                    </svg>
                                </button>
                            </span>
                            <input type="text" placeholder="Write your message!" wire:model.lazy="messageText"
                                wire:keydown.enter="sendMessage()"
                                class="w-full focus:outline-none focus:placeholder-gray-400 text-gray-600 placeholder-gray-600 pl-12 bg-gray-200 rounded-md py-3" />

                            <div class="absolute right-0 items-center inset-y-0 hidden sm:flex">
                                <button type="button"
                                    class="inline-flex items-center justify-center rounded-full h-10 w-10 transition duration-500 ease-in-out text-gray-500 hover:bg-gray-300 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" class="h-6 w-6 text-gray-600">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                        </path>
                                    </svg>
                                </button>
                                <button type="button"
                                    class="inline-flex items-center justify-center rounded-full h-10 w-10 transition duration-500 ease-in-out text-gray-500 hover:bg-gray-300 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" class="h-6 w-6 text-gray-600">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </button>
                                <button type="button"
                                    class="inline-flex items-center justify-center rounded-full h-10 w-10 transition duration-500 ease-in-out text-gray-500 hover:bg-gray-300 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" class="h-6 w-6 text-gray-600">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                        </path>
                                    </svg>
                                </button>
                                <button type="button" wire:click="sendMessage()"
                                    class="inline-flex items-center justify-center rounded-lg px-4 py-3 transition duration-500 ease-in-out text-white bg-blue-500 hover:bg-blue-400 focus:outline-none">
                                    <span class="font-bold">Send</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                        class="h-6 w-6 ml-2 transform rotate-90">
                                        <path
                                            d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('messageText')" class="mt-2" />
                    </div>
                @else
                    <div class="w-full px-5 flex flex-col justify-center h-full">
                        <div class="flex justify-center">
                            No conversation selected
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <x-slot name="head">
        <style>
            .scrollbar-w-2::-webkit-scrollbar {
                width: 0.25rem;
                height: 0.25rem;
            }

            .scrollbar-track-blue-lighter::-webkit-scrollbar-track {
                --bg-opacity: 1;
                background-color: #f7fafc;
                background-color: rgba(247, 250, 252, var(--bg-opacity));
            }

            .scrollbar-thumb-blue::-webkit-scrollbar-thumb {
                --bg-opacity: 1;
                background-color: #edf2f7;
                background-color: rgba(237, 242, 247, var(--bg-opacity));
            }

            .scrollbar-thumb-rounded::-webkit-scrollbar-thumb {
                border-radius: 0.25rem;
            }
        </style>
    </x-slot>

    <x-slot name="footer">
        <script>
            window.livewire.on('connected-to-message', (message) => {
                window.Echo.channel(`message.${message.id}`).on('message.new', (response) => {
                    console.log(response);
                    window.livewire.emit('refreshMessage', message.id);
                });
            });
            window.onload = function() {
                window.Echo.channel('conversation.{{ auth()->id() }}').on('conversation.new', (response) => {
                    console.log(response);
                    window.livewire.emit('refreshConversation');
                    window.livewire.emit('connected-to-message', response.conversation);
                });
            }
        </script>
    </x-slot>
</div>
