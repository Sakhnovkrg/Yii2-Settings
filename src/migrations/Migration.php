<?php

namespace sakhnovkrg\yii2\settings\migrations;

use yii\db\Migration as BaseMigration;
use yii\helpers\Json;

class Migration extends BaseMigration
{
    public function addSetting($typeClass, $section, $key, $values, $attributeLabels, $validators, $extra = [], $description = '')
    {
        $this->insert('{{%setting}}', [
            'type' => $typeClass,
            'section' => $section,
            'key' => $key,
            'val' => Json::encode($values),
            'params' => Json::encode([
                'attributes' => array_keys($values),
                'attributeLabels' => $attributeLabels,
                'validators' => $validators,
                'extra' => $extra
            ]),
            'description' => $description
        ]);
    }

    public function dropSetting($section, $key)
    {
        \Yii::$app->getDb()
            ->createCommand("DELETE FROM `setting` WHERE (`section`='$section') AND (`key`='$key')")
            ->execute();
    }

    public function dropSettings($section)
    {
        \Yii::$app->getDb()
            ->createCommand("DELETE FROM `setting` WHERE `section`='$section'")
            ->execute();
    }
}
