<?php

namespace app\models;

/**
 * Model class
 *
 * @description Extended model class to provide auto loading and convenience
 *              methods for attaching behaviors
 *
 * @package 	app
 */
class AppModel extends \lithium\data\Model
{

    /**
     * Behaviors to be aplied to this model
     *
     * @var array
     */
    protected static $_behaviors = [];

    /**
     * Overidden __init to attach behaviors
     *
     */
    public static function __init()
    {
        static::_isBase(__CLASS__, true);
        if (static::_isBase()) {
            return;
        }
        parent::__init();
        
        self::applyFilter('find', function ($self, $params, $chain) {
            $result = $chain->next($self, $params, $chain);
            $model = new $self;
            $getKeyName = function ($model, $relationship) {
                $united = array_merge($model->hasOne, $model->hasMany);

                foreach ($united as $key => $bind) {
                    if (is_numeric($key)) {
                        $params = [];
                    } else {
                        $params = $bind;
                        $bind = $key;
                    }
                    if ($bind == $relationship) {
                        if ((is_array($params)) && (isset($params['key']))) {
                            return $params['key'];
                        } else {
                            $exploded = explode('\\', get_class($model));
                            return strtolower($exploded[count($exploded) -1] . '_id');
                        }
                    }
                }
            };
            $getKeyNameBelongsTo = function ($model, $relationship) {
                $temp = array_merge($model->belongsTo);
                $united = [];
                foreach ($temp as $key => $value) {
                    if (is_numeric($key)) {
                        $united[$value] = [];
                    } else {
                        $united[$key] = $value;
                    }
                }
                
                foreach ($united as $key => $params) {
                    if ($key == $relationship) {
                        if ((is_array($params)) && (isset($params['key']))) {
                            return $params['key'];
                        } else {
                            return strtolower($relationship . '_id');
                        }
                    }
                }
            };
            if (!isset($model->hasOne[0])) {
                $hasOneModels = array_keys($model->hasOne);
            } else {
                $hasOneModels = $model->hasOne;
            }
            if (!isset($model->hasMany[0])) {
                $hasManyModels = array_keys($model->hasMany);
            } else {
                $hasManyModels = $model->hasMany;
            }
            if (!isset($model->belongsTo[0])) {
                $belongsToModels = array_keys($model->belongsTo);
            } else {
                $belongsToModels = $model->belongsTo;
            }
            
            if (($params['type'] == 'first') && (isset($params['options']['recursiveWith']))) {
                foreach ($params['options']['recursiveWith'] as $key => $bindedModel) {
                    if (is_numeric($key)) {
                        $bindedParams = [];
                    } else {
                        $bindedParams = $bindedModel;
                        $bindedModel = $key;
                    }
                    if ((in_array($bindedModel, $hasManyModels)) && (!is_null($result))) {
                        $recursiveModelFetch = function ($result) use ($getKeyName, $bindedModel, $bindedParams, $model) {
                            $bindingName = 'app\models\\' . $bindedModel;
                            $keyName = $getKeyName($model, $bindedModel);
                            $alias = strtolower($bindedModel);
                            if (isset($model->hasMany[$bindedModel]['alias'])) {
                                $alias = $model->hasMany[$bindedModel]['alias'];
                            }
                            $result->$alias
                            = $bindingName::find('all', (['conditions' => [$keyName => $result->id], 'recursiveWith' => [$bindedModel => $bindedParams]] + $bindedParams));
                        };
                        $recursiveModelFetch($result);
                    }
                }
            }
            
            if (($params['type'] == 'all') && (isset($params['options']['recursiveWith']))) {
                foreach ($params['options']['recursiveWith'] as $key => $bindedModel) {
                    if (is_numeric($key)) {
                        $bindedParams = [];
                    } else {
                        $bindedParams = $bindedModel;
                        $bindedModel = $key;
                    }
                    if ((in_array($bindedModel, $hasManyModels)) && (!is_null($result))) {
                        foreach ($result as $record) {
                            $bindingName = 'app\models\\' . $bindedModel;
                            $keyName = $getKeyName($model, $bindedModel);
                            $alias = strtolower($bindedModel);
                            if (isset($model->hasMany[$bindedModel]['alias'])) {
                                $alias = $model->hasMany[$bindedModel]['alias'];
                            }
                            $record->{strtolower($bindedModel)}
                            = $bindingName::find('all', (['conditions' => [$keyName => $record->id], 'recursiveWith' => [$bindedModel => $bindedParams]] + $bindedParams));
                        }
                    }
                }
            }
            
            if (($params['type'] == 'first') && (isset($params['options']['bindmodel']))) {
                foreach ($params['options']['bindmodel'] as $key => $bindedModel) {
                    if (is_numeric($key)) {
                        $bindedParams = [];
                    } else {
                        $bindedParams = $bindedModel;
                        $bindedModel = $key;
                    }
                    
                    if ((in_array($bindedModel, $hasOneModels)) && (!is_null($result))) {
                        $bindingName = 'app\models\\' . $bindedModel;
                        $keyName = $getKeyName($model, $bindedModel);
                        $alias = strtolower($bindedModel);
                        if (isset($model->hasOne[$bindedModel]['alias'])) {
                            $alias = $model->hasOne[$bindedModel]['alias'];
                        }
                        $result->$alias
                        = $bindingName::find('first', (['conditions' => [$keyName => $result->id]] + $bindedParams));
                    }
                    if ((in_array($bindedModel, $hasManyModels)) && (!is_null($result))) {
                        $bindingName = 'app\models\\' . $bindedModel;
                        $keyName = $getKeyName($model, $bindedModel);
                        $alias = strtolower($bindedModel);
                        if (isset($model->hasMany[$bindedModel]['alias'])) {
                            $alias = $model->hasMany[$bindedModel]['alias'];
                        }
                        $result->$alias
                        = $bindingName::find('all', (['conditions' => [$keyName => $result->id]] + $bindedParams));
                    }
                    if ((in_array($bindedModel, $belongsToModels) || isset($belongsToModels[$bindedModel])) && (!is_null($result))) {
                        $bindingName = 'app\models\\' . $bindedModel;
                        $keyName = $getKeyNameBelongsTo($model, $bindedModel);
                        $alias = strtolower($bindedModel);
                        if (isset($model->belongsTo[$bindedModel]['alias'])) {
                            $alias = $model->belongsTo[$bindedModel]['alias'];
                        }
                        $result->$alias
                            = $bindingName::find('first', (['conditions' => ['id' => $result->{$keyName}]] + $bindedParams));
                    }
                }
            }
            if (($params['type'] == 'all') && (isset($params['options']['bindmodel']))) {
                foreach ($params['options']['bindmodel'] as $key => $bindedModel) {
                    if (is_numeric($key)) {
                        $bindedParams = [];
                    } else {
                        $bindedParams = $bindedModel;
                        $bindedModel = $key;
                    }
                    if (((in_array($bindedModel, $hasOneModels))) && (!is_null($result))) {
                        foreach ($result as $record) {
                            $bindingName = 'app\models\\' . $bindedModel;
                            $keyName = $getKeyName($model, $bindedModel);
                            $alias = strtolower($bindedModel);
                            if (isset($model->hasOne[$bindedModel]['alias'])) {
                                $alias = $model->hasOne[$bindedModel]['alias'];
                            }
                            $record->{strtolower($bindedModel)}
                            = $bindingName::find('first', (['conditions' => [$keyName => $record->id]] + $bindedParams));
                        }
                    }
                    if ((in_array($bindedModel, $hasManyModels)) && (!is_null($result))) {
                        foreach ($result as $record) {
                            $bindingName = 'app\models\\' . $bindedModel;
                            $keyName = $getKeyName($model, $bindedModel);
                            $alias = strtolower($bindedModel);
                            if (isset($model->hasMany[$bindedModel]['alias'])) {
                                $alias = $model->hasMany[$bindedModel]['alias'];
                            }
                            $record->{strtolower($bindedModel)}
                            = $bindingName::find('all', (['conditions' => [$keyName => $record->id]] + $bindedParams));
                        }
                    }
                    if ((in_array($bindedModel, $belongsToModels) || isset($belongsToModels[$bindedModel])) && (!is_null($result))) {
                        foreach ($result as $record) {
                            $bindingName = 'app\models\\' . $bindedModel;
                            $keyName = $getKeyNameBelongsTo($model, $bindedModel);
                            $alias = strtolower($bindedModel);
                            if (isset($model->belongsTo[$bindedModel]['alias'])) {
                                $alias = $model->belongsTo[$bindedModel]['alias'];
                            }
                            $record->{strtolower($bindedModel)}
                            = $bindingName::find('first', (['conditions' => ['id' => $record->{$keyName}]] + $bindedParams));
                        }
                    }
                }
            }
            return $result;
        });
        
        self::applyFilter('delete', function ($self, $params, $chain) {
            $getKeyName = function ($model, $relationship) {
                $united = array_merge($model->hasOne, $model->hasMany);

                foreach ($united as $key => $bind) {
                    if (is_numeric($key)) {
                        $params = [];
                    } else {
                        $params = $bind;
                        $bind = $key;
                    }
                    if ($bind == $relationship) {
                        if ((is_array($params)) && (isset($params['key']))) {
                            return $params['key'];
                        } else {
                            $exploded = explode('\\', get_class($model));
                            return strtolower($exploded[count($exploded) -1] . '_id');
                        }
                    }
                }
            };
            $model = new $self;
            foreach ($model->hasOne as $key =>$dependant) {
                if (!is_numeric($key)) {
                    $dependant = $key;
                }
                $keyName = $getKeyName($model, $dependant);
                $dependantName = 'app\models\\' . $dependant;
                $dependantName::remove([$keyName => $params['entity']->id]);
            }
            foreach ($model->hasMany as $key => $dependant) {
                if (!is_numeric($key)) {
                    $dependant = $key;
                }
                $keyName = $getKeyName($model, $dependant);
                $dependantName = 'app\models\\' . $dependant;
                $dependantName::remove([$keyName => $params['entity']->id]);
            }
            return $chain->next($self, $params, $chain);
        });
        if (!empty(static::$_behaviors)) {
            foreach (static::$_behaviors as $behavior => $config) {
                if (is_numeric($behavior)) {
                    $behavior = $config;
                    $config = [];
                }
                if ($config === false) {
                    $config = ['apply' => false];
                }
                static::attachBehavior($behavior, $config);
            }
        }
    }

    /**
     * Fetch an instance of the specifeied behavior for this model
     *
     * @param string $behavior full name spaced classpath of the behavior
     */
    public static function &behavior($behavior)
    {
        $model = get_called_class();
        return $behavior::with($model);
    }

    /**
     * Attach a behavior to this model
     *
     * @param string $behavior full name spaced classpath of the behavior
     * @param array $config config options to assign to the behavior instance
     */
    public static function &attachBehavior($behavior, array $config = [])
    {
        $model = get_called_class();
        $behavior = "\app\models\behaviors\\" . $behavior;
        return $behavior::attach($model, $config);
    }

    /**
     * Detach behavior from this model
     *
     * @param string $behavior full name spaced classpath of the behavior
     */
    public static function detachBehavior($behavior)
    {
        $model = get_called_class();
        $behavior = "\app\models\behaviors\\" . $behavior;
        return $behavior::detach($model);
    }
    
    public static function shiftParams($params)
    {
        if (is_object($params[0])) {
            array_shift($params);
        }
        return $params;
    }
    
    public static function deleteItem($id)
    {
        if ($item = self::first($id)) {
            return $item->delete();
        } else {
            return false;
        }
    }
}
