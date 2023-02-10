<?php

namespace sakhnovkrg\yii2\settings\components;

use Yii;
use sakhnovkrg\yii2\settings\exceptions\UndefinedSettingException;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\caching\Cache;
use yii\di\Instance;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class Settings extends Component
{
    /**
     * @var string
     */
    public $modelClass = 'sakhnovkrg\yii2\settings\models\Setting';
    /**
     * @var Cache
     */
    public $cache = 'cache';
    /**
     * @var string
     */
    public $cacheKey = 'yii2-settings';
    /**
     * @var bool
     */
    public $enableFlashMessages = true;
    /**
     * @var string 
     */
    public $activeFormClass = 'yii\\bootstrap5\\ActiveForm';
    /**
     * @var array
     */
    protected $items;

    /**
     * @return void
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if ($this->cache !== null) {
            $this->cache = Instance::ensure($this->cache, Cache::class);
        }
    }

    /**
     * @return true
     */
    public function clearCache()
    {
        if ($this->cache !== null) {
            $this->cache->delete($this->cacheKey);
        }

        return true;
    }

    /**
     * @return array|mixed
     */
    public function getTree()
    {
        if($this->items)
            return $this->items;

        $items = Yii::$app->cache->getOrSet($this->cacheKey, function () {
            return Yii::createObject($this->modelClass)->find()->asArray()->all();
        });

        $items = array_reduce($items, function($carry, $item) {
            $carry[$item['section']][$item['key']] = $item;
            return $carry;
        }, []);

        ArrayHelper::recursiveSort($items);

        return $this->items = $items;
    }

    /**
     * @param $section
     * @param $key
     * @return bool
     */
    public function has($section, $key)
    {
        $items = $this->getTree();
        return isset($items[$section]) && isset($items[$section][$key]);
    }

    /**
     * @param $section
     * @param $key
     * @param $default
     * @return mixed|string
     * @throws UndefinedSettingException
     */
    public function get($section, $key, $default = 'throwException')
    {
        if(!$this->has($section, $key)) {
            if($default === 'throwException') {
                throw new UndefinedSettingException(Yii::t('yii2-settings', 'Setting {path} not defined.', [
                    'path' => "$section.$key"])
                );
            }
            return $default;
        }

        $val = $this->getTree()[$section][$key]['val'];
        $valArr = Json::decode($val);
        if(count($valArr) == 1) {
            return array_values($valArr)[0];
        }

        return $valArr;
    }
}
