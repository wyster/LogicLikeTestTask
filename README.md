# PHP part

Требуется php версии >= 7

* Создаём локальный файл конфигурации: 

`cp config.example.php config.local.php`

Можно настроить любой адаптер из доступных на https://framework.zend.com/manual/2.3/en/modules/zend.cache.storage.adapter.html

Для примера настроен filesystem и memcached

* Запускаем установку пакетов

`composer install`

* Запуск встроенного в php сервера

`composer run serve`

* Тест блокировки через консоль

`composer run console-runner`

* Тесты

`composer run tests`

* Очистка стораджа

`composer run storage-flush`


# JS part

* Достаточно запустить `index.html` в браузере

