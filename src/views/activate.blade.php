@extends('app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<div class="panel panel-default">
				<div class="panel-heading">
					{{ trans('doubleoptin::activation.activate') }}
				</div>
				<div class="panel-body">
					@include('doubleoptin::partials.message')

					<form class="form-horizontal" role="form" method="POST" action="{{ route('activation.update') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">

						<div class="form-group">
							<div class="col-md-12">
								<input type="email" class="form-control" name="email" placeholder="{{ trans('doubleoptin::activation.email') }}" value="{{ old('email') }}">
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-primary">
									{{ trans('doubleoptin::activation.button_activate') }}
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
