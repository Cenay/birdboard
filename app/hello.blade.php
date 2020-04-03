<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<!-- Username Form Input -->
<div class="form-group">
    {{ Form::label( 'username', 'Username:') }}
    {{ Form::text( 'username', null, ['class' => 'form-control']) }}
</div>
<!-- Password Form Input -->
<div class="form-group">
    {{ Form::label( 'password', 'Password:') }}
    {{ Form::password( 'password', ['class' => 'form-control']) }}
</div>

<!-- Website Form Input -->
<div class="form-group">
    {{ Form::label( 'website',       'Website:') }}
    {{ Form::text( 'website', null,          ['class' => 'form-control']) }}
</div>

<!-- Email Form Input -->
<div class="form-group">
    {{ Form::label( 'email', 'Email:') }}
    {{ Form::email( 'email', null, ['class' => 'form-control']) }}
</div>
</body>
</html>
