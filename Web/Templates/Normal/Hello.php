{% comment %}
    Welcome to ClearSky PHP Framework!
    This is comment.When analyzing this file, this comment is delete automatically.
    ClearSky including ORTHIA Template engine can extends Template.
    This template is child, "HelloFrame.php" is parent.
{% endcomment %}


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

    {% comment %}
        {% if($test == "test") %}
            {% foreach($array as $key => $value) %}
                <p>{{ $key }} => {{ $value }}</p>
            {% endforeach %}
        {% endif %}
    {% endcomment %}


{% endparts_block %}


{% assembleTo(HelloFrame.php) %}