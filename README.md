# ROCKSTONE Content Editor

## Структура директорий проекта
- `.vscode/` Настройки проекта для Visual Studio Code
- `assets/` TypeScript, SASS и LESS файлы
	- `src/` Исходный код TypeScript
		- `view/` React-компоненты
			- `page/` Шаблоны страниц
- `config/` Конфигурационные php-скрипты
	- `404.php` Обработчик для ответа Not Found
	- `definition.php` Настройки для контейнера зависимостей
	- `route.php` URL-маршруты приложения
- `public/` Корневая папка, которую видит пользователь, т.е. DocumentRoot
- `src/` Исходный код бэкэнд-части проекта
	- `Controller/` Основные обработчики запросов
	- `Http/` Классы для внутренней работы по обработке HTTP-запросов
	- `Middleware/` Обработчики запросов, выполняющиеся до или после основных
	- `Routing/` Классы для внутренней работы по маршрутизации
- `view/` Пользовательские шаблоны для серверного рендеринга

## Composer скрипты
- `server` Запускает встроенный в PHP HTTP сервер по порту 80

## NPM скрипты
- `clean` Очищает папку `public` от скомпилированных файлов
- `sass` Компиляция SASS-стилей
- `webpack` Компиляция TypeScript
- `webpack:dev` Компиляция TypeScript в режиме разработчика
- `build` Сборка фронтэнд-части проекта
- `build:dev` Сборка фронтэнд-части проекта в режиме разработчика. В режиме разработчика происходит следующее - React собирается в режиме разработчика, генерируются source maps, отключается минимизация, компилятор TypeScript менее строгий

### Переменные для webpack
Использование:
```
npx webpack --env <...переменные>
```
где `<...переменные>` одно из:
- `dev` Собирает проект в режиме разработчика
