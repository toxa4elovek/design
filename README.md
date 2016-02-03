### После установки

Компиляция скриптов из /app/src
./node_modules/.bin/babel --babelrc /Users/dima/www/godesigner/.babelrc app/src/ --watch --out-dir app/webroot/js/
babel --babelrc C:/server/www/godesigner/.babelrc C:/server/www/godesigner/app/src/ --watch --out-dir C:/server/www/godesigner/app/webroot/js/
### Типы
По возможности, использовать типы и аннотации
Если надо вернуть целое, которое может быть не установлено/не существовать,
возвращаем либо integer, либо null

### Jquery
Использовать Jquery для селекторов, плагинов и событый
var допустим только не в компилируемом джаваскрипте


### Webpack
./node_modules/.bin/webpack --progress --colors --watch