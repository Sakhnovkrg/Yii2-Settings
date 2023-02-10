<?php

namespace sakhnovkrg\yii2\settings;

use yii\base\BootstrapInterface;

class Module extends \yii\base\Module implements BootstrapInterface
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'sakhnovkrg\yii2\settings\controllers';
    public function bootstrap($app)
    {
        \Yii::setAlias('@settings', __DIR__);
    }
}
