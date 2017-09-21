@component('profiles.activities.activity')
	@slot('heading')
		<a href="{{ $activity->subject->favourite->path() }}">
			{{ $user->name }} favourited a reply
		</a>
	@endslot

	@slot('body')
		{{ optional($activity->subject->favourite)->body }}
	@endslot
@endcomponent
