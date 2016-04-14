<div class="profile-block">
	
	<h2>{{ trans('sanatorium/rewards::common.title') }}</h2>

	@if ( $total > 0 )

		<p>{{ trans('sanatorium/rewards::common.current_balance') }}</p>

		<span class="reward-total">
			{{ $total }}
		</span>

	@else

		<p>{{ trans('sanatorium/rewards::common.no_balance', ['points' => $points]) }}</p>

	@endif

	<br>
	<hr>

	<h3>{{ trans('sanatorium/rewards::common.invite.title') }}</h3>

	<p>{{ trans('sanatorium/rewards::common.invite.description') }}</p>

	@if ( $currentUser )
		<pre>{{ route('sanatorium.rewards.invite', ['by' => $currentUser->email]) }}</pre>
	@endif

</div>