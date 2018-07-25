<?php

return [
    'class' => 'yii\web\UrlManager',
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'enableStrictParsing' => true,
    'rules' => [
        '' => 'main/index',
        '/<id:\d+>' => 'main/index',
        '/<text:\w+>' => 'main/index',
        'PATCH like-design/<id:\d+>' => 'main/like-design',
        'PUT bad-query/create' => 'bad-query/create',
        'PATCH hashtag-stat/transition' => 'hashtag-stat/transition',
    ],
];