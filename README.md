# Application

```sh
docker-compose exec app bash
```

## Использование xDebug

Для запуска, остановки или проверки статуса

```sh
./app/xdebug {start|stop|status}
```

Описание настройки с phpStorm
https://blog.denisbondar.com/post/phpstorm_docker_xdebug

## Использование xHProf

Проверить права

```sh
chmod -R 0777 ./xhgui/cache
```

Раскоментировать параметр `auto_prepend_file` в конфигурации nginx

Нужно установить зависимости используя `composer`

```sh
docker-compose exec app bash
cd /var/www/xhprof
composer install
```

http://gui.loc

### MongoDB

Используется для хранения данных профилирования xHProf

# Common

Если работа идет с проброшенными портами, то для доступа к портам хоста нужно вместо `127.0.0.1` -> `host.docker.internal`

## Nginx

В файл `hosts` необходимо прописать пару строк

```
127.0.0.1       mobile.app
127.0.0.1       gui.loc
```