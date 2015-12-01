### После установки

## PECL
pear install Mail
pear install Mail_Mime
pear install Net_SMTP

Компиляция скриптов из /app/src
babel --babelrc /Users/dima/www/godesigner/.babelrc app/src/ --watch --out-dir app/webroot/js/

### Типы
По возможности, использовать типы и аннотации
Если надо вернуть целое, которое может быть не установлено/не существовать,
возвращаем либо integer, либо null

### Jquery
Использовать Jquery для селекторов, плагинов и событый
var допустим только не в компилируемом джаваскрипте