
{% parts_block(content) %}
    
    <h1>{{ $access_test->Access->a }}</h1>

    {% foreach($array as $key => $value) %}
        <p>{{ $key }}  =>  {{ $value }}</p>
    {% endforeach %}

    <p>{{ $variable_test }}</p>

    {{ $vardump_test }}

    {% for($i = 0; $i > 10; $i++) %}
        <p>{{ $i }}</p>
    {% endfor %}

    {% if($test == "test") %}
        {% foreach($array as $key => $value) %}
            <p>{{ $key }} => {{ $value }}</p>
        {% endforeach %}
    {% endif %}
{% endparts_block %}


{% assembleTo(HelloFrame.php) %}