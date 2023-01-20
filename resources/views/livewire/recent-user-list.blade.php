<div class="owl-carousel owl-theme" id="user-status-carousel">
    @foreach ($this->users as $user)
        <div class="item">
            <div role="button" class="user-status-box">
                <div @class([
                    'avatar-xs mx-auto d-block chat-user-img',
                    'online' => $user->is_online,
                ])>
                    <img src="{{ $user->avatar_path }}" alt="user-img" class="img-fluid rounded-circle" />
                    <span class="user-status"></span>
                </div>

                <h5 class="font-size-13 text-truncate mt-3 mb-1">{{ $user->name }}</h5>
            </div>
        </div>
    @endforeach
</div>
