# Приложение Symfony Feedback

## Начало работы

1. Склонируйте данный репозиторий:

   ```shell
   git clone <repository_url>
   
2. Создайте файл .env по примеру env.example в корневой директории и настройте его, указав необходимые переменные окружения для Docker. Например:
3. Создайте файл www/.env по примеру www/.env.example в директории www и настройте его, указав необходимые переменные окружения приложения.
4. Запустите Docker контейнеры:

   ```shell
   docker-compose up -d
   
5. Выполните обновление зависимостей Composer(Внутри контейнера с php):
   ```
   composer update
   ```

6. Создайте миграции(Внутри контейнера с php):
   ```
   php bin/console make:migration
   ```

7. Выполните миграции для создания необходимых таблиц в базе данных(Внутри контейнера с php):
   ```
   php bin/console doctrine:migrations:migrate
   ```
   
# Коллекция postman с запросами лежит в корне
## API Endpoints

- POST /api/v1/requests -  Создание новой заявки
- GET /api/v1/requests/:id - Получение заявки по идентификатору
- GET /api/v1/requests/user - Получение заявок для текущего пользователя
- GET /api/v1/requests/status/:id - Получение статуса заявки по идентификатору
- GET /api/v1/manager/requests - Получение заявок для менеджера
- PUT /api/v1/manager/requests/:id - Обновление заявки по идентификатору

Эндпоинты имеющие в url "manager", требуют ROLE_MANAGER.

## Auth Endpoints

- POST /api/v1/auth/register - Регистрация пользователя 
- POST /api/v1/auth/login - Логин
