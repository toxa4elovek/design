<?php
namespace app\extensions\helper;

class Debug extends User
{

    /**
     * Визуальные стили для вывода
     *
     * @var array
     */
    public $styles = [
        'verySlow' => 'font-weight: bold, color: red',
        'slow' => 'color: orange',
        'fast' => 'font-style: italic',
        'info' => 'font-weight: bold'
    ];

    /**
     * @var array границы между скоростными категориями запросов
     */
    public $speedBoundaries = [
        'verySlow' => 0.4,
        'slow' => 0.2,
    ];

    /**
     * Метод сортируем запросы по их времени создания
     *
     * @param $debugQueries
     * @return array|null
     */
    public function sortQueriesByTimestamp($debugQueries)
    {
        if (!is_array($debugQueries)) {
            return null;
        }
        usort($debugQueries, function ($a, $b) {
            if ($a['timestamp'] < $b['timestamp']) {
                return -1;
            } elseif ($a['timestamp'] > $b['timestamp']) {
                return 1;
            }
            return 0;
        });
        return $debugQueries;
    }

    /**
     * Метод определяет, есть ли дебажные данные
     *
     * @return bool
     */
    public function isDebugInfoExists()
    {
        $debugQueries = $this->read('debug.queries');
        if (($debugQueries) && (is_array($debugQueries)) && (!empty($debugQueries))) {
            return true;
        }
        return false;
    }

    /**
     * Метод удаляет дебажную информацию
     *
     * @return bool
     */
    public function clearDebugInfo()
    {
        if ($this->isDebugInfoExists()) {
            $this->write('debug.queries', []);
            return true;
        }
        return false;
    }

    /**
     * Метод определяет скоростную категорию запроса
     *
     * @param $query
     * @return null|string
     */
    public function detectSpeedOfQuery($query)
    {
        if ($this->isDebugQuery($query)) {
            if ($query['elapsed_time'] > $this->speedBoundaries['verySlow']) {
                $style = 'verySlow';
            } elseif ($query['elapsed_time'] > $this->speedBoundaries['slow']) {
                $style = 'slow';
            } else {
                $style = 'fast';
            }
            return $style;
        }
        return null;
    }

    /**
     * Метод подготовлиает запрос к выводу в html код
     *
     * @param $stringQuery
     * @return string
     */
    public function escapeQuery($stringQuery)
    {
        return addslashes($stringQuery);
    }

    /**
     * Метод округляет число
     *
     * @param $float
     * @return float
     */
    public function roundTime($float)
    {
        return round($float, 5);
    }

    /**
     * Метод возвращяет массив текущих дебажных запросов
     *
     * @return mixed
     */
    public function getDebugQueries()
    {
        return $this->read('debug.queries');
    }

    /**
     * Метод возвращяет стиль для указанного ключа
     *
     * @param $key
     * @return null
     */
    public function getVisualStyle($key)
    {
        if ((is_string($key)) && (isset($this->styles[$key]))) {
            return $this->styles[$key];
        }
        return null;
    }

    /**
     * Метод возвращяет запрос для вставки в html
     *
     * @param $query
     * @return null|string
     */
    public function getHtmlForQuery($query)
    {
        if ($this->isDebugQuery($query)) {
            $escapedQuery = $this->escapeQuery($query['query']);
            $elapsed = $this->roundTime($query['elapsed_time']);
            $style = $this->getVisualStyle($this->detectSpeedOfQuery($query));
            return "console.log('%c$elapsed $escapedQuery', '$style');\r\n";
        } else {
            //var_dump($query);
            //var_dump($this->isDebugQuery($query));
            //die();
        }
        return null;
    }

    /**
     * Метод возвращяет параметр как дебажный массив
     *
     * @param $query
     * @return null
     */
    public function getQueryArray($query)
    {
        if ($this->isDebugQuery($query)) {
            $query['style'] = $this->getVisualStyle($this->detectSpeedOfQuery($query));
            return $query;
        }
        return null;
    }

    /**
     * Метод определяет, является ли параметр дебажным запросом
     *
     * @param $query
     * @return bool
     */
    public function isDebugQuery($query)
    {
        $keys = ['timestamp', 'elapsed_time', 'query', 'type'];
        if (is_array($query)) {
            foreach ($keys as $key) {
                if (!array_key_exists($key, $query)) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    public function dumpDebugInfo()
    {
        if ($this->isDebugInfoExists()) {
            $queries = $this->sortQueriesByTimestamp($this->getDebugQueries());
            $result = [];
            foreach ($queries as $query) {
                $result[] = $this->getQueryArray($query);
            }
            return $result;
        }
        return null;
    }
}
