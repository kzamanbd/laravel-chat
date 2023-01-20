<li>
    @if ($isTyping && $user)
        <div class="conversation-list">
            <div class="chat-avatar">
                <img src="{{ $user['avatar_path'] }}" />
            </div>
            <div class="user-chat-content">
                <div class="ctext-wrap">
                    <div class="ctext-wrap-content">
                        <p class="mb-0">
                            typing
                            <span class="animate-typing">
                                <span class="dot"></span>
                                <span class="dot"></span>
                                <span class="dot"></span>
                            </span>
                        </p>
                    </div>
                </div>
                <div class="conversation-name">{{ $user['name'] }}</div>
            </div>
        </div>
    @endif
</li>
