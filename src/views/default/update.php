<?php

/**
 * @var \yii\web\View $this
 * @var \sakhnovkrg\yii2\settings\types\BaseType $settingType
 * @var \sakhnovkrg\yii2\settings\models\Setting $settingModel
 */

$this->title = $settingModel->section . ' → ' . $settingModel->key;
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii2-settings', 'Settings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

echo \yii\helpers\Html::beginTag('div', ['class' => 'yii2-settings-update']);

    if($settingModel->description) {
        echo \yii\helpers\Html::tag('p', nl2br($settingModel->description), ['class' => 'yii2-settings-description']);
    }
    echo \yii\helpers\Html::beginTag('div', ['class' => 'yii2-settings-form-wrap']);
        $settingType->renderForm();
    echo \yii\helpers\Html::endTag('div');

echo \yii\helpers\Html::endTag('div');
