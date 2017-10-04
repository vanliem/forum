@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="page-header">
					<h1>
						{{ $user->name }}
						<small>Since {{ $user->created_at->diffForHumans() }}</small>
					</h1>
				</div>
				@can('update', $user)
                    <form method="POST" action="{{ route('avatar', $user) }}" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <input type="file" name="avatar">

                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
				@endcan

                <img src="{{ asset('storage/' . $user->avatar_path) }}" alt="" width="50" height="50"/>

				@forelse ($activities as $date => $activity)
					<h3 class="page-header">
						{{ $date }}
					</h3>
					@foreach ($activity as $record)
						@if (view()->exists("profiles.activities.{$record->type}"))
							@include("profiles.activities.{$record->type}", ['activity' => $record])
						@endif
					@endforeach	
				@empty
					<p>There is no activity for this user yet.</p>
				@endforelse
			</div>
		</div>
	</div>

@endsection