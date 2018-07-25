<?php


/**
 * Converts cyrillic to latin alphabet
 *
 * @param $string
 * @return string
 */
function rus2translit($string)
{
    $converter = [
        'а' => 'a',   'б' => 'b',   'в' => 'v',
        'г' => 'g',   'д' => 'd',   'е' => 'e',
        'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
        'и' => 'i',   'й' => 'y',   'к' => 'k',
        'л' => 'l',   'м' => 'm',   'н' => 'n',
        'о' => 'o',   'п' => 'p',   'р' => 'r',
        'с' => 's',   'т' => 't',   'у' => 'u',
        'ф' => 'f',   'х' => 'h',   'ц' => 'c',
        'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
        'ь' => '\'',  'ы' => 'y',   'ъ' => '\'',
        'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

        'А' => 'A',   'Б' => 'B',   'В' => 'V',
        'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
        'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
        'И' => 'I',   'Й' => 'Y',   'К' => 'K',
        'Л' => 'L',   'М' => 'M',   'Н' => 'N',
        'О' => 'O',   'П' => 'P',   'Р' => 'R',
        'С' => 'S',   'Т' => 'T',   'У' => 'U',
        'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
        'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
        'Ь' => '\'',  'Ы' => 'Y',   'Ъ' => '\'',
        'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
    ];

    return strtr($string, $converter);
}

/**
 * Converts string to url compatible view
 *
 * @param $string
 * @return string
 */
function str2url($string)
{
    $string = rus2translit($string);
    $string = strtolower($string);
    $string = str_replace("'", '', $string);
    $string = preg_replace('/[^a-z0-9_]+/u', '_', $string);
    $string = trim($string, '_');

    return $string;
}

/**
 * @return \Illuminate\Support\Collection
 */
function catchUrlUtmParameters()
{
    return collect([
        'utm_source',   // источник перехода. Например, direct.yandex.ru, begun.ru и др.
        'utm_medium',   // средство маркетинга. Например, cpc(или ppc), banner, email и др.
        'utm_campaign', // название проводимой рекламной кампании.
        'utm_content',  // дополнительная информация, которая помогает различать объявления.
        'utm_term',     // ключевая фраза.
    ])->map(function ($utm) {
        if ($utm == 'utm_source') {
            if (\Request::is('instaprofile')) {
                return (object) [
                    'key'   => $utm,
                    'value' => 'instaprofile',
                ];
            }
        }
        return (object) [
            'key'   => $utm,
            'value' => \Request::get($utm, ''),
        ];
    });
}

/**
 * @param \Illuminate\Support\Collection $collect
 * @return \Illuminate\Support\Collection
 */
function onlyFilledUtmParameters(\Illuminate\Support\Collection $collect = null)
{
    if (is_null($collect)) {
        $collect = catchUrlUtmParameters();
    }
    return $collect->filter(function ($utm) {
        return $utm->value;
    });
}


/**
 * Получить путь до asset-файла в зависимости от окружения.
 * 
 * @param $path
 * @param null $buildDirectory
 * @return string
 */
function _el($path, $buildDirectory = null)
{
    if (env('APP_ENV') == 'production') {
        return elixir($path, $buildDirectory);
    }

    return asset($path);
}

/**
 * Получить хэш-массив всех роутов.
 * 
 * @return array
 */
function getRoutes()
{
    $frontendRoutes = [];
    $routes = \Route::getRoutes();
    foreach ($routes as $route) {
        if($route->getName()) {
            $p = $route->getPath();
            array_set($frontendRoutes, $route->getName(), ($p[0] == '/') ? $p : '/'.$p );
        }
    }
    return $frontendRoutes;
}