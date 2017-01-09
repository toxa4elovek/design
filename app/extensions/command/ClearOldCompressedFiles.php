<?php

namespace app\extensions\command;

use DirectoryIterator;

/**
 * Class ClearOldCompressedFiles
 * @package app\extensions\command
 *
 * Команда для очистки удаления устаревших сжатых файлов
 *
 */
class ClearOldCompressedFiles extends CronJob
{

    /**
     * @var array массив списков директорий для проверки
     */
    public $paths = [
        '/var/godesigner/webroot/js/minified/',
        '/var/godesigner/webroot/css/minified/',
    ];

    /**
     * @var int количество дней, после которых файл будет считаться устаревших
     */
    public $oldThreshold = 10;

    /**
     * @var array список файловых расширений, которые можно удалять
     */
    public $extensionsAllowedForDeleteion = [
        'js',
        'css'
    ];

    /**
     * Запуск команды
     */
    public function run()
    {
        $this->header('Команда очистки старых минифицированных файлов стилей и скриптов!');
        $currentTimeStamp = time();
        $totalCount = 0;
        $totalDeleted = 0;
        foreach ($this->paths as $directory) {
            $this->out('Смотрим директорию ' . $directory . "\n\n");
            foreach (new DirectoryIterator($this->paths[0]) as $fileInfo) {
                if ($fileInfo->isDot()) {
                    continue;
                }
                if ((in_array($fileInfo->getExtension(), $this->extensionsAllowedForDeleteion)) && ($fileInfo->getMTime() <  $currentTimeStamp - ($this->oldThreshold * DAY))) {
                    $totalCount++;
                    $this->out('Файл на удаление - ' . $fileInfo->getFilename() . ' (' . date('Y-m-d', $fileInfo->getMTime()) . ')');
                    if (unlink($fileInfo->getRealPath())) {
                        $this->out('Файл удалён');
                        $totalDeleted++;
                    } else {
                        $this->out('Файл не удалён, возникла ошибка');
                    }
                }
            }
        }
        $this->out('Всего файлов подходящих для удаления - ' . $totalCount);
        $this->out('Всего файлов удалено - ' . $totalDeleted);
        $this->out('Команда закончила работу!');
    }
}
