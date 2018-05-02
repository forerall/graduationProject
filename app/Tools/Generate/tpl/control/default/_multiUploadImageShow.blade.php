<div class="profile-user-info profile-user-info-striped">
    <div class="profile-info-row">
        <div class="profile-info-name">__FieldName__</div>
        <div class="profile-info-value">
            @if(__Value__)
                @foreach(__Value__ as $v)
                    <div>
                        <img class="photo-display" src="{{$v}}">
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
