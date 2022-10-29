<div>
    <x-slot name="header">
        <div class="flex flex-row items-center justify-center h-12 w-full">
            <div class="flex items-center justify-center rounded-2xl text-indigo-700 bg-indigo-100 h-10 w-10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                    </path>
                </svg>
            </div>
            <div class="ml-2 font-bold text-2xl">QuickChat</div>
        </div>
    </x-slot>

    <div class="py-12">
        <!-- component -->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex h-[calc(100vh-200px)] antialiased text-gray-800 bg-gray-50">
                <div class="flex flex-row h-full w-full overflow-x-hidden">
                    <div class="flex flex-col py-8 pl-6 pr-2 w-64 bg-white flex-shrink-0">
                        <div
                            class="flex flex-col items-center bg-indigo-100 border border-gray-200 w-full py-6 px-4 rounded-lg">
                            <div class="h-20 w-20 rounded-full border overflow-hidden">
                                <img src="{{ auth()->user()->user_avatar }}" alt="Avatar" class="h-full w-full" />
                            </div>
                            <div class="text-sm font-semibold mt-2">{{ auth()->user()->name }}</div>
                            <div class="flex flex-row items-center mt-3">
                                <div class="flex flex-col justify-center h-4 w-8 bg-indigo-500 rounded-full">
                                    <div class="h-3 w-3 bg-white rounded-full self-end mr-1"></div>
                                </div>
                                <div class="leading-none ml-1 text-xs">Active</div>
                            </div>
                        </div>
                        <div class="flex flex-col mt-8">
                            <div class="flex flex-row items-center justify-between text-xs">
                                <span class="font-bold">Active Conversations</span>
                                <span
                                    class="flex items-center justify-center bg-gray-300 h-4 w-4 rounded-full">{{ count($this->conversations) }}</span>
                            </div>
                            <div class="flex flex-col space-y-1 mt-4 -mx-2 h-96 overflow-y-auto">
                                @foreach ($this->conversations as $item)
                                    <button class="flex flex-row items-center hover:bg-gray-100 rounded-xl p-2"
                                        wire:click="getMessage({{ $item->id }})">
                                        <div
                                            class="flex items-center justify-center h-8 w-8 bg-indigo-200 rounded-full">
                                            <img src="{{ $item->user_avatar }}"
                                                class="object-cover h-8 w-8 rounded-full" alt="" />
                                        </div>
                                        <div class="ml-2 text-sm font-semibold">
                                            @if ($item->to->id == auth()->user()->id)
                                                {{ $item->from->name }}
                                            @else
                                                {{ $item->to->name }}
                                            @endif
                                        </div>
                                    </button>
                                @endforeach

                                <div class="flex flex-row items-center justify-between text-xs pt-6">
                                    <span class="font-bold">Archivied</span>
                                    <span
                                        class="flex items-center justify-center bg-gray-300 h-4 w-4 rounded-full">{{ count($this->users) }}</span>
                                </div>

                                @foreach ($this->users as $user)
                                    <button class="flex flex-row items-center hover:bg-gray-100 rounded-xl p-2"
                                        wire:click="newConversation({{ $user->id }})">
                                        <div
                                            class="flex items-center justify-center h-8 w-8 bg-indigo-200 rounded-full">
                                            H
                                        </div>
                                        <div class="ml-2 text-sm font-semibold">{{ $user->name }}</div>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col flex-auto h-full p-6">
                        <div class="flex flex-col flex-auto flex-shrink-0 rounded-2xl bg-gray-100 h-full p-4">
                            @if ($conversation)
                                <div class="flex flex-col h-full overflow-x-auto mb-4" x-data="{ scroll: () => { $el.scrollTo(0, $el.scrollHeight); } }"
                                    x-init="scroll()" @scroll-bottom.window="scroll()">
                                    <div class="flex flex-col h-full">
                                        <div class="grid grid-cols-12 gap-y-2">
                                            @foreach ($conversation->message as $message)
                                                @if (auth()->user()->id != $message->user_id)
                                                    <div class="col-start-1 col-end-8 p-3 rounded-lg">
                                                        <div class="flex flex-row items-center">
                                                            <div
                                                                class="flex items-center justify-center h-10 w-10 rounded-full bg-indigo-500 flex-shrink-0">
                                                                <img src="https://ui-avatars.com/api/?background=random&name={{ $conversation->to->name }}"
                                                                    class="object-cover h-10 w-10 rounded-full" />
                                                            </div>
                                                            <div
                                                                class="relative ml-3 text-sm bg-white py-2 px-4 shadow rounded-xl">
                                                                <div>{{ $message->message }}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="col-start-6 col-end-13 p-3 rounded-lg">
                                                        <div class="flex items-center justify-start flex-row-reverse">
                                                            <div
                                                                class="flex items-center justify-center h-10 w-10 rounded-full bg-indigo-500 flex-shrink-0">
                                                                <img src="https://ui-avatars.com/api/?background=random&name={{ auth()->user()->name }}"
                                                                    class="object-cover h-10 w-10 rounded-full" />
                                                            </div>
                                                            <div
                                                                class="relative mr-3 text-sm bg-indigo-100 py-2 px-4 shadow rounded-xl">
                                                                <div>{{ $message->message }}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div class="flex flex-row items-center h-16 rounded-xl bg-white w-full px-4">
                                    <div>
                                        <button
                                            class="flex items-center justify-center text-gray-400 hover:text-gray-600">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="flex-grow ml-4">
                                        <div class="relative w-full">
                                            <input type="text" wire:model="newMessage"
                                                wire:keydown.enter="sendMessage()"
                                                class="flex w-full border rounded-xl focus:outline-none focus:border-indigo-300 pl-4 h-10" />
                                            <button
                                                class="absolute flex items-center justify-center h-full w-12 right-0 top-0 text-gray-400 hover:text-gray-600">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <button wire:click="sendMessage()"
                                            class="flex items-center justify-center bg-indigo-500 hover:bg-indigo-600 rounded-xl text-white px-4 py-1 flex-shrink-0">
                                            <span>Send</span>
                                            <span class="ml-2">
                                                <svg class="w-4 h-4 transform rotate-45 -mt-px" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                                </svg>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            @elseif($newUser)
                                <div class="w-full px-5 flex flex-col justify-center h-full">
                                    <div class="flex justify-center">
                                        no message
                                    </div>
                                </div>
                                <div class="flex flex-row items-center h-16 rounded-xl bg-white w-full px-4">
                                    <div>
                                        <button
                                            class="flex items-center justify-center text-gray-400 hover:text-gray-600">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="flex-grow ml-4">
                                        <div class="relative w-full">
                                            <input type="text" wire:model="newMessage"
                                                wire:keydown.enter="sendMessage()"
                                                class="flex w-full border rounded-xl focus:outline-none focus:border-indigo-300 pl-4 h-10" />
                                            <button
                                                class="absolute flex items-center justify-center h-full w-12 right-0 top-0 text-gray-400 hover:text-gray-600">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <button wire:click="sendMessage()"
                                            class="flex items-center justify-center bg-indigo-500 hover:bg-indigo-600 rounded-xl text-white px-4 py-1 flex-shrink-0">
                                            <span>Send</span>
                                            <span class="ml-2">
                                                <svg class="w-4 h-4 transform rotate-45 -mt-px" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8">
                                                    </path>
                                                </svg>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            @else
                                <div class="w-full px-5 flex flex-col justify-center h-full">
                                    <div class="flex justify-center">
                                        no message
                                    </div>
                                </div>

                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>
