@if(Session::has('message'))
    @if(Session::has('message-alert'))
        <div class="alert alert-{{ Session::get('message-alert') }}">
    @else
        <div class="alert alert-info">
    @endif
        {!!  Session::get('message')  !!}
        </div>
@endif

@if (count($errors) > 0)
    <div class="alert alert-danger">
        {{ trans('doubleoptin::activation.form_error') }}<br><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
