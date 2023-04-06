Twig Ext
========

Twig Extensions

Usage
-----

    composer require gupalo/twigext

Symfony
-------

Add to `config/services.yaml`

    Gupalo\TwigExt\:
        resource: '../vendor/gupalo/twigext/src/TwigExt'
        tags: ['twig.extension']

You can customize FormatExtension:

    Gupalo\TwigExt\FormatExtension:
        bind:
            $translations:
                span: div
                format-date-zero: text-warning
            $wrapSpan: false


Functions and Filters
---------------------

### random_hour, random_day

Pseudorandom. Default `1..100`.

    {% if random_day() <= 40 %}40% chances to see this text{% endif %}
    {% if random_day() > 40 %}otherwise, you'll see this text{% endif %}

    {% if random_day() <= 20 %}
        20% chances
    {% else if random_day() <= 65 %}
        45% chances
    {% else %}
        35% chances
    {% endif %}

    {% if random_day() <= 20 %}
        {% set content %}
            {% include '_elements/test1' ~ app.request.uri ~ '.html' %}
        {% endset %}
    {% else if random_day() <= 65 %}
        {% set content %}
            {% include '_elements/test2' ~ app.request.uri ~ '.html' %}
        {% endset %}
    {% else %}
        {% set content %}
            35% chances
        {% endset %}
    {% endif %}

`random_day` returns same number same day, `random_hour` - same number same hour.

Optional params:

* `max`: if not `1..100`, but `1..max`
* `salt`: if you need different number at different sites or even at the same page

### random_item

Pick one random item value from items

    {{ random_item(['aaa', 'bbb', 'ccc']) }}

### random_items

Pick several random items from items

Echo 2 random items: "aaa, bbb" or "aaa, ccc", or "bbb, ccc", or "bbb, aaa", or "ccc, aaa", or "ccc, bbb".

    {{ random_items({'k1': 'aaa', 'k2': 'bbb', 'k3': 'ccc'}, 2)|join(', ') }}

Same but preserve keys

    {{ random_items({'k1': 'aaa', 'k2': 'bbb', 'k3': 'ccc'}, 2, true)|json_encode }}

If items is empty then default value (4th argument) is returned: "ouch" in example. Default - `[]`.

    {{ random_items({}, 2, true, 'ouch')|json_encode }}

If you ask for more items that it exists in items then all items will be returned but shuffled: "aaa, bbb" or "bbb, aaa"

    {{ random_items({'k1': 'aaa', 'k2': 'bbb', 1000)|join(', ') }}
