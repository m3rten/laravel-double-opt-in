<!DOCTYPE html>
<html lang="de-DE">
<head>
    <meta charset="utf-8">
</head>
<body>
<div>
    <p>
        {!! trans('doubleoptin::email.greeting', array('name' => $user['name'])) !!},<br>
        {!! trans('doubleoptin::email.click_activation_link') !!} <a href="{{ route('activation.verify', ['token' => $user['activation_token']]) }}" >{{ route('activation.verify', ['token' => $user['activation_token']]) }}</a>
    </p>
</div>
</body>
</html>
