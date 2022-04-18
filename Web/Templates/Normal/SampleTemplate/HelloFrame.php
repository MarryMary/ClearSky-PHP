<!doctype html>
<html lang="{{ $lang }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
    {% paste_here(css) %}
</head>
<body>
    {% convey(Normal/SampleTemplate/SampleFrames/navbar.php) %}
    <div class="container">
        <div class="card_back">
            {% paste_here(contents) %}
        </div>
    </div>
</body>
</html>