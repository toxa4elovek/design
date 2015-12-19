<?php

namespace app\extensions\command;

use ByteUnits\Binary;
use ImageOptimizer\OptimizerFactory;
use lithium\net\http\Media;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * Class OptimizeImages
 *
 * Команда для оптимизации дизайн-изображений
 *
 * @package app\extensions\command
 */
class OptimizeImages extends CronJob
{

    /**
     * Метод находит картинки из директории img для оптимизации
     */
    public function run()
    {
        $this->header('Welcome to the OptimizeImages command!');
        $finder = new Finder();
        $webRoot = Media::webroot(true);
        $finder->files()->name('/\.(png|gif|jpg|jpeg)$/')->in($webRoot . '/img');
        $stopWatch = new Stopwatch();
        $stopWatch->start('Compressing images');
        $factory = new OptimizerFactory(['ignore_errors' => false, 'optipng_options' => ['-i0', '-o7', '-quiet']]);
        $optimizer = $factory->get();
        foreach ($finder as $file) {
            $pathToFile = $file->getRealPath();
            $this->_optimizeImageFile($pathToFile, $optimizer);
        }
        $period = $stopWatch->stop('Compressing images');
        $duration = $period->getDuration();
        $memoryUsage = $period->getMemory();
        $memoryUsageString = Binary::bytes($memoryUsage)->format('MB', ' ');
        $this->out("Took $duration ms, $memoryUsageString");
    }

    /**
     * Метод который оптимизирует картинку
     *
     * @param $filePath
     * @param $optimizer
     */
    protected function _optimizeImageFile($filePath, \ImageOptimizer\Optimizer $optimizer)
    {
        $this->out("Starting optimize image $filePath");
        $optimizer->optimize($filePath);
        $this->out("Finished optimization of image $filePath");
        $this->hr();
    }
}
