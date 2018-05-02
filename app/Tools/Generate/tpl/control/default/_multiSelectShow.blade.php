<div class="profile-user-info profile-user-info-striped">
    <div class="profile-info-row">
        <div class="profile-info-name">__FieldName__</div>
        <div class="profile-info-value">
            @foreach(__Value__ as $v)
                <div>{{$v->name}}</div>
            @endforeach
        </div>
    </div>
</div>
