# ROCKSTONE Content Editor

## Структура директорий проекта
- `.vscode` Настройки проекта для Visual Studio Code
- `assets` TypeScript, SASS и LESS файлы
	- `src` Исходный код TypeScript
- `config` Конфигурационные php-скрипты
- `public` Корневая папка, которую видит пользователь, т.е. DocumentRoot
- `src` Исходный код бэкэнд-части проекта
- `template` Пользовательские шаблоны для серверного рендеринга

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
