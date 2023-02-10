<?php

namespace sakhnovkrg\yii2\settings\types;

use yii\base\DynamicModel;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

class TextType extends BaseType
{
    protected function renderField(ActiveForm $form, DynamicModel $formModel, $attribute)
    {
        return $form->field($formModel, $attribute)->textarea(
            [
                'maxLength' => true,
                'class' => ArrayHelper::getValue($this->params, 'class', 'form-control'),
                'rows' => ArrayHelper::getValue($this->params, 'rows', 4)
            ]
        );
    }
}
