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


ArrayExtensionK
--------------

### uniq(items, keepKeys = false)

`array_unique`

If `keepKeys = false` (default) then `array_values` of the result.

### max_value(items, field)

Max of `field` values of `items`.

Uses `PropertyAccessor` so it may be object with properties

### max_ratio_value(items, field, field2)

Max of `field/field2` values of `items`.

Uses `PropertyAccessor` so it may be object with properties

### sum_value(items, field)

Sum of field values.

Uses `PropertyAccessor` so it may be object with properties

### array_sum(items)

https://www.php.net/manual/en/function.array-sum.php


EncodingExtension
-----------------

### base64_encode

https://www.php.net/manual/en/function.base64-encode.php

### base64_decode

https://www.php.net/manual/en/function.base64-decode.php

### md5

https://www.php.net/manual/en/function.md5.php


ExitExtension
-------------

### exit

Throw `TwigExitException`. You app should intercept and process it. 


FormatExtension
---------------

### int

### float

### money

### percents

### date_full

### date_short

### date_noyear


JsonExtension
-------------

### json_decode

`json_decode` array.

If there will be invalid string then there will be no error, just an empty array.


ProgressExtension
-----------------

### progress_class

### progress_percents

### progress_int

### progress_float


RandomExtension
---------------

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


StringExtension
---------------

### truncate(s, length = 100)

Limit length of the string. If it's longer - add `&hellip;` (`...`)

### underscore(s, character = '_')

Change text to underscore

    {{ 'CamelCase? ok'|undersore }}

Will return `camel_case? ok`

### mask_password(s)

Keep only first 3 letters and mask next ones

    {{ 'mySuperPassword'|mask_password }}

Will show `myS*****`.

### safe_title(s)

Prepare value to be inserted into HTML attribute.

    <a title="{{ unsafe_text|safe_title }}">hello</a>


### ucfirst(s)

https://www.php.net/manual/en/function.ucfirst.php
