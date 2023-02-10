<?php

use sakhnovkrg\yii2\settings\components\Settings;
use yii\helpers\Html;

$this->title = Yii::t('yii2-settings', 'Settings');
$this->params['breadcrumbs'][] = $this->title;

/**
 * @var Settings $settings
 */
$settings = Yii::$app->settings;
$items = $settings->getTree();
$sections = array_keys($items);

echo Html::beginTag('div', ['class' => 'yii2-settings-index']);
    if($settings->cache) {
        echo Html::beginForm(['clear-cache'], 'post', ['class' => 'mb-3']);
        echo Html::submitButton(Yii::t('yii2-settings', 'Clear settings cache'),
            [
                'class' => 'btn btn-primary',
            ]
        );
        echo Html::endForm();
    }
    echo Html::beginTag('div', ['class' => 'yii2-settings-list']);
        foreach ($sections as $section) {
            echo Html::tag('h4', $section, ['class' => 'yii2-settings-section']);

            foreach ($items[$section] as $item) {
                echo Html::beginTag('ul');
                    echo Html::tag('li',
                        Html::a($item['key'], ['update', 'id' => $item['id']], [
                            'class' => 'yii2-settings-link', 'title' => $item['description']]
                        ),
                        ['class' => 'yii2-settings-item']
                    );
                echo Html::endTag('ul');
            }
        }
    echo Html::endTag('div');
echo Html::endTag('div');
