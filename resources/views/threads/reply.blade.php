<div class="panel panel-default">
    <div class="panel-heading">
        <div class="level">
            <h5 class="flex">
                <a href="#">
                    {{ $reply->owner->name }}
                </a> said ...
                {{ $reply->created_at->diffForHumans() }}
            </h5>

            <div>

                <form action="/replies/{{ $reply->id }}/favourites" method="POST">
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-default" {{ $reply->isFavourited() ? 'disabled' : '' }}>
                        {{ $reply->favourites()->count() }}
                        {{ str_plural('Favourite', $reply->favourites()->count()) }}
                        </button>
                </form>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="body">
                {{ $reply->body }}
            </div>
        </div>
    </div>
</div>
