@if (count($activity->changes['after']) == 1)
	<em>{{ $activity->user_name() }} updated the {{ key($activity->changes['after']) }} of the Project</em>
@else
	<em>{{ $activity->user_name() }} updated the project ({{count($activity->changes['after'])}} edits)</em>
@endif