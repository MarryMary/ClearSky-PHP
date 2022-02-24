{% comment %}
    テンプレート継承のテスト
{% endcomment %}


{% parts_block(content) %}
    <h1>{{ $variable_test }}</h1>
    {% foreach($array as $key => $value) %}
        <p>{{ $key }} => {{ $value }}</p>
    {% endforeach %}
    <h1>{{ $access_test->Access->a }}</h1>
    {% for($i = 0; $i > 10; $i++) %}
        <p>{{ $i }}</p>
    {% endfor %}
    {{ $vardump_test }}
{% endparts_block %}


{% assembleTo(HelloFrame.php) %}