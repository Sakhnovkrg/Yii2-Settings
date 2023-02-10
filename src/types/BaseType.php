<?php

namespace sakhnovkrg\yii2\settings\types;

use sakhnovkrg\yii2\settings\models\Setting;
use yii\base\DynamicModel;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\ActiveForm;

abstract class BaseType
{
    /**
     * @var Setting
     */
    public $settingModel;
    /**
     * @var DynamicModel
     */
    public $formModel;
    public $params;

    /**
     * @param Setting $settingModel
     * @throws \Exception
     */
    public function __construct(Setting $settingModel) {
        $params = Json::decode($settingModel->params);
        $values = Json::decode($settingModel->val);

        $formModel = new DynamicModel(array_combine($params['attributes'], $values));
        $formModel->setAttributeLabels(ArrayHelper::getValue($params, 'attributeLabels', []));

        foreach ($params['validators'] as $item) {
            $formModel->addRule($item[0], $item[1], ArrayHelper::getValue($item, 2, []));
        }

        $this->settingModel = $settingModel;
        $this->formModel = $formModel;
        $this->params = ArrayHelper::getValue($params, 'extra', []);
    }

    /**
     * @param ActiveForm $form
     * @param DynamicModel $formModel
     * @param $attribute
     * @return mixed
     */
    protected abstract function renderField(ActiveForm $form, DynamicModel $formModel, $attribute);

    /**
     * @return void
     */
    public function renderForm()
    {
        $activeForm = \Yii::$app->settings->activeFormClass;
        $form = $activeForm::begin();
            foreach ($this->formModel->getAttributes() as $attribute => $val) {
                echo $this->renderField($form, $this->formModel, $attribute);
            }
            echo Html::beginTag('div', ['class' => 'form-group']);
            echo Html::submitButton(\Yii::t('yii2-settings', 'Save'), ['class' => 'btn btn-primary']);
            echo Html::endTag('div');
        $activeForm::end();
    }

    public function updateSetting()
    {
        if($this->formModel->validate()) {
            $this->settingModel->val = Json::encode($this->formModel->getAttributes());
            $this->settingModel->save();

            \Yii::$app->settings->clearCache();
            return true;
        }

        return false;
    }
}
