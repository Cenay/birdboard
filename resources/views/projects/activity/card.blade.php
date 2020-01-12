<div class="card mt-3">
					
	<h3 class="font-normal text-xl py-4 -ml-5 border-l-4 border-blue-light pl-4 mb-3">Activity Log</h3>
	<ul class="text-xs list-reset">
		@foreach ($project->activity as $activity)
			<li class="{{ $loop->last ? '' : 'mb-1' }}">
				@include ("projects.activity.{$activity->description}")
				<span class="text-grey-dark"><em>{{ $activity->created_at->diffForHumans(null, true) }}</em></span>
			</li>
		@endforeach
	</ul>
	
</div>