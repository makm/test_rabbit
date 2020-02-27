## Описание и алгоритм работы
В данном примере вся работа по распределению задач заключается в том, что бы просто создать класс
процедуры реализуюзей интерфейс ProcessInterface
и добавления класса TaskInterface для его использования как DTO в процессе.

Далее нужно просто загеристрировать новый процесс в 
WorkerApplication
```
$application
    ->addProcess(new ProcessOrderCredit($logger))
```

так же (необязательно) добавить конфигурацию воркер с приоритетом для этой задачи
для supervisor на примере 
> docker/supervisor/conf.d/TaskOrderCredit.conf

Алгоритм работы TaskConsumer заключается в том, что он в качестве основой работы ориентируется на приоритетную очередь.
В случае, если в приоритетной очереди нет задач, consumer переключается на любую другую, после чего, возвращается в свою и т.д.



## Запуск
```
git clone https://github.com/makm/test_rabbit
cd test_rabbit
docker-compose up -d
docker-compose exec --user 1000 test_php-fpm composer install -d ./test-src/
```

### Cгенерировать задачи 
```
docker-compose exec --user 1000 test_php-fpm php test-src/src/generate.php
```

### Лог 
расположен в test-src/src/processes.php

### Посмотреть состояние очередей rabbitMq из web
http://localhost:15672/#/queues
> логин guest
>
> пароль guest

###Задачи описана 
https://gitlab.com/vladimir.samuylov/backend-test

###Используемый стек
>rabbitMq
>
>supervisor
>
>php


### todo
1. перенести скрипты в отдельную  папку bin 
2. тесты

