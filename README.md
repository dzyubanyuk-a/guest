# Стек
 - PHP - 8.3.13-fpm
 - MariaDb - 11.5.2
 - Nginx - 1.27.2

 # Swagger
 - php artisan l5-swagger:generate (либо storage/api-docs/api-docs.json)
 - В той же папке лежит готовая коллекция.

# Docker
- Конфиги и прочее лежит в папке docker.

# Сборка проекта
- Создаем папку www
- рядом распологаем содержимое папки docker.
- Клонируем проект в папку www.
- Запускаем сборку через docker compose up -d
- Настраиваем файл env (подключаем базу данных, данные для которой можно взять в docker-compose.yml в сервисе db)
- composer install
- php artisan key:generate
- php artisan migrate
- php artisan db:seed
- php artisan route:list - посомтреть все роуты

# Описание
- Реализован CRUD для работы с гостем. 
- Используется фреймворке Laravel.

# P.S.
- Приношу извинения за 2 комита. 
- Код писал без остановки за 5 часов и корректно разделить не получится быстро.
- Если занимался бы ТЗ в течение 3-х дней как положено, код был бы другой.