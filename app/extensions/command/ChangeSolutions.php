<?php

namespace app\extensions\command;

use app\extensions\storage\Rcache;
use app\models\Solution;
use app\models\Solutionfile;

class ChangeSolutions extends CronJob
{

    public function run()
    {
        $this->_renderHeader();
        $solutions = Solution::all(['page' => 4, 'limit' => 10000]);
        $rerecordSolution = function ($imageFile, $dryRun = false) {
            $existingFileName = $imageFile['filename'];
            if (!preg_match('@\/var\/godesigner\/webroot\/solutions\/[a-z0-9]\/[a-z0-9]{2}\/[a-z0-9]{3}\/[a-z0-9]{32}(_[a-zA-Z]+)?\.([a-zA-Z]){3,4}@', $existingFileName)) {
                $newFilePath = (generateHashName($imageFile['id'], $existingFileName));
                if ((file_exists($existingFileName)) && (!file_exists($newFilePath))) {
                    $this->out("File $existingFileName exists, need to copy");
                    if (!$dryRun) {
                        copy($existingFileName, $newFilePath);
                        $solutionFileRecord = Solutionfile::first(['conditions' => ['filename' => $existingFileName]]);
                        $solutionFileRecord->filename = $newFilePath;
                        $solutionFileRecord->save();
                        unlink($existingFileName);
                        $cacheKey = "solutionfiles_$solutionFileRecord->id";
                        $this->out("Cache Key $cacheKey");
                        Rcache::delete($cacheKey);
                    }
                    $this->out("New Path - $newFilePath");
                    return 1;
                } else {
                    $this->out("File $existingFileName does not exists");
                    return 0;
                }
            }
            return 0;
        };
        foreach ($solutions as $solution) {
            $count = 0;
            $this->out("Solution - id $solution->id");
            foreach ($solution->images as $imageFile) {
                if (isset($imageFile[0])) {
                    foreach ($imageFile as $imageFileRecord) {
                        $count += $rerecordSolution($imageFileRecord, false);
                    }
                } else {
                    $count += $rerecordSolution($imageFile, false);
                }
            }
        }
        $this->_renderFooter("*$count* solutions fixed");
        $directory = '/var/godesigner/webroot/solutions';
        $fi = new \FilesystemIterator($directory, \FilesystemIterator::SKIP_DOTS);
        printf("%s - there were %d Files", $directory, iterator_count($fi));
    }
}

function generateHashName($base, $filename)
{
    do {
        $pathInfo = pathinfo($filename);
        $hashedName = md5($base . uniqid());
        $hashedPath = substr($hashedName, 0, 1) . '/' . substr($hashedName, 0, 2) . '/' . substr($hashedName, 0, 3) . '/';
        createPath($pathInfo['dirname'] . '/' . $hashedPath);
        $hashedName = $hashedPath . $hashedName;
        $possibleNewFilename = $pathInfo['dirname'] . '/' . $hashedName . '.' . $pathInfo['extension'];
    } while (file_exists($possibleNewFilename));
    return $possibleNewFilename;
}
/**
 * recursively create a long directory path
 */
function createPath($path)
{
    if (is_dir($path)) {
        return true;
    }
    $prev_path = substr($path, 0, strrpos($path, '/', -2) + 1);
    $return = createPath($prev_path);
    return ($return && is_writable($prev_path)) ? mkdir($path) : false;
}
