<?php
/**
 * Slicedup: a fancy tag line here
 *
 * @copyright	Copyright 2010, Paul Webster / Slicedup (http://slicedup.org)
 * @license 	http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace app\models\behaviors;

/**
 * Model Behavior
 *
 * @description	ModelBehavior provides a standardized way of reusing model logic
 * 				by packaging commmon methods, and creating callback filters that
 * 				are applied to filterable model methods
 *
 */
class ModelBehavior extends \lithium\core\StaticObject
{

    /**
     * Full model name of the model the behvaior instance is attached to
     *
     * @var string
     */
    protected $_model;

    /**
     * Configuration for a behavior instance
     *
     * @var array
     */
    protected $_config = [];

    /**
     * Behavior instances
     *
     * @var array
     */
    protected static $_instances = [];

    /**
     * Map models and their model aliases
     *
     * @var array
     */
    protected static $_aliases = [];

    /**
     * Map behaviors applied to models
     *
     * @var array
     */
    protected static $_appliedMap = [];

    /**
     * Model method names on which to load callback filters for the behavior
     * and the before and after keys with boolean values to indicate if the
     * callback should be applied to models the behavior is attached to
     *
     * @var array
     */
    protected static $_callbacks = [
        'create' => [
            'before' => true,
            'after' => true
        ],
        'validates' => [
            'before' => true,
            'after' => true
        ],
        'save' => [
            'before' => true,
            'after' => true
        ],
        'find' => [
            'before' => true,
            'after' => true
        ],
        'delete' => [
            'before' => true,
            'after' => true
        ]
    ];

    /**
     * Static call method to pass usage of {Behavior}::{$Model}() to get instance of
     * {Behavior} attached to {Model}, by passing to 'with' method
     *
     * @param string $method called method
     * @param array $params passed params
     * @return behavior instance if method matches an attached model
     */
    public static function __callStatic($method, $params)
    {
        return static::with($method);
    }

    /**
     * Attach an instance of a behaior to a model
     *
     * @param string $model model name if in app\models namespace, other wise full path
     * @param array $config configuraion for the created instance
     * @return behavior instance
     */
    public static function &attach($model, array $config = [])
    {
        $defaults = ['apply' => true];
        $config = $config + $defaults;
        $namespace = '\app\models';
        $split = explode('\\', $model);
        $model = array_pop($split);
        if (count($split) > 1) {
            $namespace = implode('\\', $split);
        }
        if (strpos($namespace, '\\') !== 0) {
            $namespace = '\\' . $namespace;
        }
        $alias = isset($config['alias']) ? $config['alias'] : $model;
        $model = "{$namespace}\\{$model}";
        $class = get_called_class();
        $instance = static::_getInstance($class, $alias);
        static::$_aliases[$class][$model] = $alias;
        $instance->_model = $model;
        $instance->config($config);
        $instance->_init();
        return $instance;
    }

    /**
     * Detach behavior from a model instance
     *
     * @param  string $alias, model name, full model path, or alias passed to attach
     * @return boolean instance detached
     */
    public static function detach($alias)
    {
        $class = get_called_class();
        $alias = static::_getAlias($class, $alias);
        if (isset(static::$_instances[$class][$alias])) {
            $_alias = array_search($alias, (array) static::$_aliases[$class]);
            unset(static::$_aliases[$class][$_alias]);
            unset(static::$_instances[$class][$alias]);
            return true;
        }
        return true;
    }

    /**
     * Allows public checking for behavior instances attatched to a model
     *
     * @param string $alias
     */
    public static function attached($alias)
    {
        $class = get_called_class();
        $alias = static::_getAlias($class, $alias);
        return isset(static::$_instances[$class][$alias]);
    }

    /**
     * Return the instance of a behavir attached to a given model identified by alias
     *
     * @param string $alias
     */
    public static function &with($alias)
    {
        $class = get_called_class();
        $alias = static::_getAlias($class, $alias);
        if (!isset(static::$_instances[$class][$alias])) {
            $instance = false;
            return $instance;
        }
        return static::_getInstance($class, $alias);
    }

    /**
     * Attempt to get the alias of a model name
     *
     * @param string $class class name of the behavior
     * @param string $alias model name or full model class path
     */
    protected static function _getAlias($class, $alias)
    {
        if (isset(static::$_aliases[$class][$alias])) {
            $alias = static::$_aliases[$class][$alias];
        } elseif (isset(static::$_aliases[$class]['\\' . $alias])) {
            $alias = static::$_aliases[$class]['\\' . $alias];
        }
        return $alias;
    }

    /**
     * Get/Create a new instance of behavior class for model identifed by alias
     *
     * @param string $class class name of the behavior
     * @param string $alias model alias
     */
    protected static function &_getInstance($class, $alias)
    {
        if (!isset(static::$_instances[$class][$alias])) {
            static::$_instances[$class][$alias] = new $class();
        }
        return static::$_instances[$class][$alias];
    }

    /**
     * Initialize the behavior instance by calling apply
     *
     */
    protected function _init()
    {
        $config = $this->config();
        if ($config['apply']) {
            $this->apply((array) $config['apply']);
        }
        return true;
    }

    /**
     * Set config values for this behavior instance
     *
     * @param array $config
     */
    public function config(array $config = [])
    {
        if (!empty($config)) {
            $this->_config = $config + $this->_config;
        }
        return $this->_config;
    }

    /**
     * Apply this behavior instance to its attached model
     *
     * @param array $apply
     */
    public function apply(array $apply = [])
    {
        $behavior = get_called_class();
        if (!isset(static::$_appliedMap[$behavior])) {
            static::$_appliedMap[$behavior] = [];
        }
        if (in_array($this->_model, static::$_appliedMap[$behavior])) {
            return false;
        }
        if (empty($apply)) {
            $apply = [true];
        }
        $this->applyFilters($apply);
        static::$_appliedMap[$behavior][] = $this->_model;
        return true;
    }

    /**
     * Has the current instance been 'applied' to its attached model
     *
     */
    public function applied()
    {
        $behavior = get_called_class();
        $applied = false;
        if (isset(static::$_appliedMap[$behavior])) {
            $applied = in_array($this->_model, static::$_appliedMap[$behavior]);
        }
        return $applied;
    }

    /**
     * Return the model this instance is attatched to
     *
     */
    public function model()
    {
        return $this->_model;
    }

    /**
     * Apply filters for configured callacks to the attched model
     *
     * @param array $apply
     */
    protected function applyFilters(array $apply)
    {
        if (!$apply) {
            return;
        }
        if (isset($apply[0]) && $apply[0] === true) {
            $apply = static::$_callbacks;
        } else {
            $apply += static::$_callbacks;
        }
        $model = $this->_model;
        $behavior = get_called_class();
        foreach ($apply as $method => $applyFilters) {
            if ($applyFilters) {
                $before = $after = true;
                if (is_array($applyFilters)) {
                    extract($applyFilters);
                }
                if ($before) {
                    $this->applyBeforeFilter($model, $behavior, $method);
                }
                if ($after) {
                    $this->applyAfterFilter($model, $behavior, $method);
                }
            }
        }
    }

    /**
     * Apply a behavior method as a before filter to a model
     *
     * @param string $model
     * @param string $behavior
     * @param string $method
     */
    protected function applyBeforeFilter($model, $behavior, $method)
    {
        $before = "before" . ucfirst($method);

        $model::applyFilter($method, function ($model, $params, $chain) use ($behavior, $before) {
            $instance =& $behavior::with($model);
            if ($instance) {
                $params = $instance->$before($params);
                if (!$params) {
                    return false; //do we need to change this?
                }
            }
            return $chain->next($model, $params, $chain);
        });
    }

    /**
     * Apply a behavior method as an after filter to a model
     *
     * @param string $model
     * @param string $behavior
     * @param string $method
     */
    protected function applyAfterFilter($model, $behavior, $method)
    {
        $after = "after" . ucfirst($method);
        $model::applyFilter($method, function ($model, $params, $chain) use ($behavior, $after) {
            $instance =& $behavior::with($model);
            if ($instance) {
                $result = $chain->next($model, $params, $chain);
                return $instance->$after($result);
            }
            return $chain->next($model, $params, $chain);
        });
    }

    /**
     * Before Save
     *
     * @param array $params prepared paramaters for creating a new record/document
     * 		Keys are:
     *      - `'data'`: array of data passed to create the record
     */
    public function beforeCreate($params)
    {
        return $params;
    }

    /**
     * After Save
     *
     * @param Record|Document $result the newly created record/document
     */
    public function afterCreate($result)
    {
        return $result;
    }

    /**
     * Before Validate
     *
     * @param array $params prepared paramaters for creating a new record/document
     * 		Keys are:
     *      - `'data'`: array of data passed to create the record
     */
    public function beforeValidates($params)
    {
        return $params;
    }

    /**
     * After validate
     *
     * @param boolean $result result of record validated
     */
    public function afterValidates($result)
    {
        return $result;
    }

    /**
     * Before Save
     *
     * @param array $params prepared paramaters for saving a record/document
     * 		Keys are:
     * 		- `'record'`: Record/Document the record/document object to be saved
     *      - `'data'`: array of data passed to update the record
     *      - `'options'`: array options passed to save
     *      	Keys are:
     *      	- `'validate'`:
     *      	- `'whitelist'`:
     *      	- `'callbacks'`:
     *      	- `'classes'`:
     */
    public function beforeSave($params)
    {
        return $params;
    }

    /**
     * After Save
     *
     * @param boolean $result record saved
     */
    public function afterSave($result)
    {
        return $result;
    }

    /**
     * Before find
     *
     * @param array $params prpepared parameters for finding records/documents
     * 		Keys are:
     * 		- `'type'`:
     * 		- `'options'`:
     *      	Keys are:
     *      	- `'conditions'`:
     *      	- `'fields'`:
     *      	- `'order'`:
     *      	- `'limit'`:
     *      	- `'page'`:
     *      	- `'classes'`:
     *      		Keys are:
     *      		- `'connections'`:
     *      		- `'query'`:
     *      		- `'validator'`:
     *
     */
    public function beforeFind($params)
    {
        return $params;
    }

    /**
     * After Find
     *
     * @param RecordSet|Document $result result of the find operation
     */
    public function afterFind($result)
    {
        return $result;
    }

    /**
     * Before Delete
     *
     * @param RecordSet|Document $params record/document being deleted
     */
    public function beforeDelete($params)
    {
        return $params;
    }

    /**
     * After Delete
     *
     * @param boolean $result resut of the delete operation
     */
    public function afterDelete($result)
    {
        return $result;
    }
}
