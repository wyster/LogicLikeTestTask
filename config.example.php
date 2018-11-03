<?php declare(strict_types=1);

return [
    'cache' => [
        'adapter' => [
            'name' => 'filesystem',
            'options' => [
                'keyPattern' => '',
                'dir_level' => 0,
                'cache_dir' => './data'
            ]
        ],
    ],
    // Режим отладки
    'debug' => false,
    // Лимит повторяющихся запросов
    'requestsLimit' => 5,
    // Интервал при котором будет срабатывать счётчик
    'requestsLimitForInterval' => 'PT5M',
    // Время на которое блокируются запросы
    'blockForInterval' => 'PT10M'
];