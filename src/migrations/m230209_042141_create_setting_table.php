<?php

use yii\db\Migration;

class m230209_042141_create_setting_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%setting}}', [
            'id' => $this->primaryKey(),
            'type' => $this->string()->notNull(),
            'section' => $this->string(64)->notNull(),
            'key' => $this->string(64)->notNull(),
            'val' => $this->text(),
            'description' => $this->string(1000),
            'params' => $this->string(5000)
        ], 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB');

        $this->createIndex('{{%setting_uniq}}', '{{%setting}}', ['section', 'key'], true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%setting}}');
    }
}
