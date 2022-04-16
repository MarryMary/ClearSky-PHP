<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>HelloPage | ClearSky Framework</title>
    {% paste_here(css) %}
</head>
<body>
    {% convey(Normal/SampleTemplate/SampleFrames/navbar.php) %}
    <div class="container">
        <div class="card_back">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="card">
                            {% paste_here(contents_leftup) %}
                        </div>
                        <div class="card">
                            {% paste_here(contents_leftbottom) %}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="card">
                            {% paste_here(contents_rightup) %}
                        </div>
                        <div class="card">
                            {% paste_here(contents_rightdown) %}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>