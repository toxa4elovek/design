<?php

namespace app\extensions\command;

use app\models\Tag;
use app\models\Solutiontag;
use app\extensions\storage\Rcache;

class ClearTags extends \app\extensions\command\CronJob
{

    public function run()
    {
        $count = 100;
        $continue = false;
        set_time_limit(120);
        $compositeTagsIds = [11, 12, 13, 14, 15, 6030, 139];
        foreach ($compositeTagsIds as $tag) {
            $tag = Tag::first($tag);
            $result = preg_split('/(?=\p{Lu})/u', $tag->name);
            foreach ($result as $newtagname) {
                if (empty($newtagname)) {
                    continue;
                }
                $newtag = Tag::saveTag($newtagname);
                $this->cleanCopies($newtag, false);
            }
            $tag->delete();
        }

        while ($continue) {
            $continue = false;
            for ($i = 1; $i < $count; $i++) {
                if ($tags = Tag::all(['limit' => 100, 'page' => $i, 'order' => ['id' => 'asc']])) {
                    foreach ($tags as $tag) {
                        $this->out($tag->id);
                        if (preg_match('@/@', $tag->name)) {
                            $this->out('splitted');
                            $exploded = explode('/', $tag->name);
                            foreach ($exploded as $exptag) {
                                $newtag = Tag::saveTag($exptag);
                                $continue = $this->cleanCopies($newtag, $continue);
                            }
                            $tag->delete();
                        } else {
                            $continue = $this->cleanCopies($tag, $continue);
                        }
                    }
                }
            }
        }
    }

    private function cleanCopies($tag, $continue)
    {
        if ($copiesCount = Tag::count(['conditions' => [
            'name' => $tag->name,
            'id' => ['!=' => $tag->id]
        ]])) {
            $continue = true;
            $this->out('Copies found - ' . $copiesCount);
            $copies = Tag::all(['conditions' => [
                'name' => $tag->name,
                'id' => ['!=' => $tag->id]
            ]]);
            foreach ($copies as $copy) {
                if ($solutionsOfCopies = Solutiontag::all(['conditions' => ['tag_id' => $copy->id]])) {
                    foreach ($solutionsOfCopies as $solutionsOfCopy) {
                        $solutionsOfCopy->tag_id = $tag->id;
                        $solutionsOfCopy->save();
                        $this->out('Solution tag changed');
                    }
                }
                $copy->delete();
                $this->out('Copy deleted');
            }
        }
        return $continue;
    }
}
