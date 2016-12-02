<?php

namespace app\controllers;

use app\extensions\helper\PdfGetter;
use app\extensions\storage\Rcache;
use \lithium\storage\Session;
use \app\models\Solution;
use \app\models\User;
use app\models\Tag;
use app\models\Searchtag;
use app\models\Pitch;
use \app\extensions\helper\User as UserHelper;
use \app\extensions\mailers\UserMailer;
use \lithium\analysis\Logger;

class SolutionsController extends AppController
{

    public $publicActions = ['like', 'unlike', 'logosale', 'search_logo', 'get_logosale_status', 'getPdfPresentation'];

    public function hide()
    {
        $result = Solution::hideimage($this->request->id, $this->userHelper->getId());
        return compact('result');
    }

    public function unhide()
    {
        $result = Solution::unhideimage($this->request->id, $this->userHelper->getId());
        return compact('result');
    }

    public function like()
    {
        $likes = Solution::increaseLike($this->request->id, $this->userHelper->getId());
        $result = $likes['result'];
        $likes = $likes['likes'];
        return compact('likes', 'result');
    }

    public function unlike()
    {
        $likes = Solution::decreaseLike($this->request->id, $this->userHelper->getId());
        $result = $likes['result'];
        $likes = $likes['likes'];
        return compact('likes', 'result');
    }

    /**
     * Метод пробует установить рейтинг $this->request->data['rating'] для решения $this->request->data['id']
     *
     * @return array
     */
    public function rating()
    {
        $rating = Solution::setRating($this->request->data['id'], $this->request->data['rating'], $this->userHelper->getId());
        return compact('rating');
    }

    /**
     * Метод для выбора победителя
     *
     * @return array|bool|object
     */
    public function select()
    {
        if ($solution = Solution::first(['conditions' => ['Solution.id' => $this->request->id], 'with' => ['Pitch']])) {
            if (!$this->userHelper->isPitchOwner($solution->pitch->user_id) && !$this->userHelper->isManagerOfProject($solution->pitch->user_id) && !$this->userHelper->isAdmin()) {
                $result = false;
                return compact('result');
            }
            $nominatedSolutionOfThisPitch = Solution::first([
                        'conditions' => ['nominated' => 1, 'pitch_id' => $solution->pitch->id]
            ]);
            if ($nominatedSolutionOfThisPitch) {
                $result = false;
                return compact('result');
            }
            // Already has selected winner, need buy second winner
            if ($solution->pitch->awarded > 0) {
                $result = false;
                return compact('result');
            }
            if ((Pitch::isPenaltyNeededForProject($solution->pitch->id)) && (!$this->userHelper->isAdmin())) {
                $result = false;
                $redirect = '/pitches/penalty/' . $solution->id;
                return compact('result', 'redirect');
            }
            $result = Solution::selectSolution($solution);
            return $result;
        }
    }

    /**
     * Метод удаляет решение. Только авторы или админы.
     *
     * @return array
     */
    public function delete()
    {
        $result = false;
        if (($solution = Solution::first($this->request->id)) && (($this->userHelper->isAdmin()) || User::checkRole('admin') || ($this->userHelper->isSolutionAuthor($solution->user_id)))) {
            $projectId = $solution->pitch_id;
            $data = [
                'id' => $solution->id,
                'num' => $solution->num,
                'user_who_deletes' => $this->userHelper->getId(),
                'user_id' => $solution->user_id,
                'date' => date('Y-m-d H:i:s'),
                'isAdmin' => $this->userHelper->isAdmin()
            ];
            Logger::write('info', serialize($data), ['name' => 'deleted_solutions']);
            $result = $solution->delete();
        }
        if ($this->request->is('json')) {
            return compact('result');
        } else {
            $this->redirect(['Pitches::view', 'id' => $projectId]);
        }
    }

    public function warn()
    {
        $user = Session::read('user');
        if ($solution = Solution::first($this->request->params['id'])) {
            $data = ['text' => $this->request->data['text'], 'user' => $user, 'solution' => $solution->data()];
            UserMailer::warn_solution($data);
        }
        return $this->request->params['id'];
    }

    public function saveSelected()
    {
        if (isset($this->request->data['selectedSolutions'])) {
            if (($solution = Solution::first($this->request->data['selectedSolutions'])) && ($solution->user_id == $this->userHelper->getId())) {
                if ($solution->selected == 1) {
                    $solution->selected = 0;
                } else {
                    $solution->selected = 1;
                }
                $solution->save();
            }
        }
        return $this->request->data;
    }

    public function logosale()
    {
        $count = 0;
        $sort_tags = [];
        $search_tags = [];
        if ((isset($this->request->query['search'])) && (!empty($this->request->query['search']))) {
            $words = Solution::stringToWordsForSearchQuery($this->request->query['search']);
            $industries = Solution::getListOfIndustryKeys($words);
            $words = Solution::injectIndustryWords($words);

            $tags_id = 0;
            if (!is_null($words)) {
                $tag_params = ['conditions' => []];
                // сохранение поиска в статистику
                $search_tags = Searchtag::all(['conditions' => ['name' => $words]]);
                if (count($search_tags) < 1) {
                    foreach ($words as $w) {
                        $tag_params['conditions']['OR'][] = ['name' => $w];
                        if (!empty($w)) {
                            $result = Searchtag::create([
                                'name' => $w
                            ]);
                            $result->save();
                        }
                    }
                } else {
                    foreach ($search_tags as $v) {
                        $v->searches += 1;
                    }
                    $search_tags->save();
                    foreach ($words as $w) {
                        $tag_params['conditions']['OR'][] = ['name' => $w];
                    }
                }
                // конец страницы поика в статистику
                // поиск существующих тегов
                $tags = Tag::all($tag_params);
                if (count($tags) > 0) {
                    $tags_id = array_keys($tags->data());
                } else {
                    $tags_id = 0;
                }
            }
            $page = (isset($this->request->id) && !empty($this->request->id)) ? $this->request->id : 1;
            // Ищем указанную страницу результатов
            $params = Solution::buildSearchQuery($words, $industries, $tags_id, $page);
        } else {
            $params = Solution::buildStreamQuery($this->request->id, 28, Solution::randomizeStreamOrder());
        }
        if ($this->request->is('json')) {
            $params['page'] += 1;
            $next = Solution::all($params);
            if ($next) {
                $count = count($next);
            }
        } else {
            $params['page'] = 1;
            $sort_tags = Tag::getPopularTags(15);
            $search_tags = Searchtag::all(['order' => ['searches' => 'desc'], 'limit' => 15]);
            $total_count = Solution::solutionsForSaleCount();
        }
        $userHelper = new UserHelper([]);
        if ($userHelper->isLoggedIn()) {
            $data = Solution::addBlankPitchForLogosale($userHelper->getId(), 0);
        }

        $solutions = Solution::all($params);
        $needToAddSolution = false;
        if ($solutions && count($solutions) > 0) {
            $initialCount = count($solutions->data());
            $solutions = Solution::filterLogoSolutions($solutions);
            if ((isset($this->request->data['prop'])) && (isset($this->request->data['variants']))) {
                $solutions = Solution::applyUserFilters($solutions, $this->request->data['prop'], $this->request->data['variants']);
            }
            $afterFilterCount = count($solutions);

            if ((count($solutions) != 28) && ($initialCount == $afterFilterCount)) {
                $needToAddSolution = true;
            }

            if ($needToAddSolution) {
                $params = Solution::buildStreamQuery(1, 28, Solution::randomizeStreamOrder());
                $addedSolutions = Solution::filterLogoSolutions(Solution::all($params));
                foreach ($addedSolutions as $key => $addedSolution) {
                    $solutions[$key] = $addedSolution;
                    $solutions[$key]['sort'] = 1;
                }
            }
        } else {
            $params = Solution::buildStreamQuery($this->request->id, 28, Solution::randomizeStreamOrder());
            $solutions = Solution::all($params);
            $solutions = Solution::filterLogoSolutions($solutions);
            $solutions = Solution::applyUserFilters($solutions);
        }
        return compact('solutions', 'count', 'sort_tags', 'search_tags', 'data', 'total_count');
    }

    public function search_logo()
    {
        if ((!empty($this->request->query)) && (count($this->request->query) > 1)) {
            $this->request->data = $this->request->query;
        }
        if ($this->request->is('json') && (isset($this->request->data['search_list']) || (isset($this->request->data['prop'])))) {
            $words = Solution::stringToWordsForSearchQuery($this->request->data['search_list']);
            $industries = Solution::getListOfIndustryKeys($words);
            $words = Solution::injectIndustryWords($words);

            $tags_id = 0;
            if (!is_null($words)) {
                $tag_params = ['conditions' => []];
                // сохранение поиска в статистику
                $search_tags = Searchtag::all(['conditions' => ['name' => $words]]);
                if (count($search_tags) < 1) {
                    foreach ($words as $w) {
                        $tag_params['conditions']['OR'][] = ['name' => $w];
                        if (!empty($w)) {
                            $result = Searchtag::create([
                                        'name' => $w
                            ]);
                            $result->save();
                        }
                    }
                } else {
                    foreach ($search_tags as $v) {
                        $v->searches += 1;
                    }
                    $search_tags->save();
                    foreach ($words as $w) {
                        $tag_params['conditions']['OR'][] = ['name' => $w];
                    }
                }
                // конец страницы поика в статистику
                // поиск существующих тегов
                $tags = Tag::all($tag_params);
                if (count($tags) > 0) {
                    $tags_id = array_keys($tags->data());
                } else {
                    $tags_id = 0;
                }
            }

            $page = (isset($this->request->id) && !empty($this->request->id)) ? $this->request->id : 1;
            // Ищем указанную страницу результатов
            $params = Solution::buildSearchQuery($words, $industries, $tags_id, $page);


            $solutions = Solution::all($params);
            $needToAddSolution = false;
            if ($solutions && count($solutions) > 0) {
                $initialCount = count($solutions->data());
                $solutions = Solution::filterLogoSolutions($solutions);
                $solutions = Solution::applyUserFilters($solutions, $this->request->data['prop'], $this->request->data['variants']);
                $afterFilterCount = count($solutions);

                if ((count($solutions) != 28) && ($initialCount == $afterFilterCount)) {
                    $needToAddSolution = true;
                }

                if ($needToAddSolution) {
                    $params = Solution::buildStreamQuery($page, 28, Solution::randomizeStreamOrder());
                    $addedSolutions = Solution::filterLogoSolutions(Solution::all($params));
                    foreach ($addedSolutions as $key => $addedSolution) {
                        $solutions[$key] = $addedSolution;
                        $solutions[$key]['sort'] = 1;
                    }
                }
            } elseif ($page > 1) {
                $totalParams = Solution::buildSearchQuery($words, $industries, $tags_id, false, false);
                $cacheKey = 'totalSolutions' . serialize($words) . '_' . serialize($industries) . '_' . serialize($tags_id);
                if (!$totalPages = Rcache::read($cacheKey)) {
                    $totalSolutions = Solution::all($totalParams);
                    if ($totalSolutions && count($totalSolutions) > 0) {
                        $totalSolutions = Solution::filterLogoSolutions($totalSolutions);
                        $totalSolutions = Solution::applyUserFilters($totalSolutions, $this->request->data['prop'], $this->request->data['variants']);
                    }
                    $totalPages = ceil(count($totalSolutions) / 28);
                    Rcache::write($cacheKey, $totalPages);
                }
                $filteredPage = $page - $totalPages + 1;
                $params = Solution::buildStreamQuery($filteredPage, 28, Solution::randomizeStreamOrder());
                $solutions = Solution::filterLogoSolutions(Solution::all($params));
                $solutions = Solution::applyUserFilters($solutions);
                if ($solutions) {
                    foreach ($solutions as $key => $solution) {
                        $solution['sort'] = 1;
                    }
                }
            } else {
                $params = Solution::buildStreamQuery(1, 28, Solution::randomizeStreamOrder());
                $solutions = Solution::filterLogoSolutions(Solution::all($params));
                $solutions = Solution::applyUserFilters($solutions, $this->request->data['prop'], $this->request->data['variants']);
            }
        }
        $total_solutions = count($solutions);
        return compact('solutions', 'total_solutions', 'page', 'pageParams', 'params');
    }

    public function add_tag()
    {
        if (($solution = Solution::first(['conditions' => ['Solution.id' => $this->request->data['id']], 'with' => []])) && ($this->userHelper->getId() == $solution->user_id)) {
            $result = Tag::saveSolutionTag($this->request->data['tag'], $solution->id);
            return compact($result);
        }
        return $this->request->data;
    }

    public function remove_tag()
    {
        if (($solution = Solution::first(['conditions' => ['Solution.id' => $this->request->data['id']], 'with' => []])) && ($this->userHelper->getId() == $solution->user_id)) {
            $result = Tag::removeTag($this->request->data['tag'], $solution->id);
            return compact($result);
        }
        return $this->request->data;
    }

    public function update_description()
    {
        if (($solution = Solution::first(['conditions' => ['Solution.id' => $this->request->data['id']], 'with' => []])) && ($this->userHelper->getId() == $solution->user_id)) {
            $solution->description = $this->request->data['updatedText'];
            $solution->save();
        }
        return $this->request->data;
    }

    public function get_logosale_status()
    {
        if (isset($this->request->query['solutionsIds'])) {
            $response = [];
            foreach ($this->request->query['solutionsIds'] as $id) {
                $solution = Solution::first([
                    'conditions' => ['Solution.id' => (int) $id],
                    'with' => ['Pitch']
                ]);
                $readyForSale = Solution::isReadyForLogosale($solution, $solution->pitch);
                $response[] = ['id' => $id, 'ready' => $readyForSale];
            }
            return ['status' => 200, 'data' => $response];
        }
        return ['status' => 500, 'error' => 'No solution ids provided'];
    }

    /**
     * Метод выбирает лучшие решения из проекта, генерирует PDF презентацию и скачивает их
     */
    public function getPdfPresentation() {
        if($this->request->id && ($pitch = Pitch::first((int) $this->request->id))) {
            $solutions = Solution::all(['conditions' => [
                'Solution.pitch_id' => $pitch->id,
                'Solution.rating' => ['>' => 3],
                'Solution.hidden' => 0
            ], 'order' => ['Solution.rating' => 'desc']]);
            error_reporting(0);
            require_once LITHIUM_APP_PATH.'/'.'libraries'.'/'.'MPDF54/MPDF54/mpdf.php';
            $pdfWriter = new \mPDF();
            $pdfWriter->SetFont('FuturaDemi');
            $pdfWriter->SetFont('Garamond');
            $options = compact('solutions', 'pitch');
            $pdfWriter->WriteHTML(PdfGetter::get('Presentation', $options));
            $pdfWriter->Output('Presentation.pdf', 'd');
            die();
        }else {
            throw new \UnexpectedValueException ('Public:Такого проекта не существует.', 404);
        }
    }
}
