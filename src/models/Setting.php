<?php

namespace sakhnovkrg\yii2\settings\models;

/**
 * @property int $id
 * @property string $type
 * @property string $section
 * @property string $key
 * @property string|null $val
 * @property string|null $description
 * @property string|null $params
 */
class Setting extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%setting}}';
    }
}
