<?php

namespace sakhnovkrg\yii2\settings\types;

use yii\base\DynamicModel;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

class SelectType extends BaseType
{
    protected function renderField(ActiveForm $form, DynamicModel $formModel, $attribute)
    {
        return $form->field($formModel, $attribute)->dropDownList(ArrayHelper::getValue($this->params, 'items', []), [
            'class' => ArrayHelper::getValue($this->params, 'class', 'form-control'),
        ]);
    }
}
