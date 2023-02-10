# Yii2-Settings
[![Total Downloads](https://poser.pugx.org/sakhnovkrg/yii2-settings/downloads.svg)](https://packagist.org/packages/sakhnovkrg/yii2-settings)
[![License](https://poser.pugx.org/sakhnovkrg/yii2-settings/license.svg)](https://packagist.org/packages/sakhnovkrg/yii2-settings)

⚙️ Yii2 Settings Module

![showcase](https://raw.githubusercontent.com/sakhnovkrg/yii2-settings/master/docs/licecap.gif)

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```bash
php composer require --prefer-dist sakhnovkrg/yii2-settings "@dev"
```

or add

```json
"sakhnovkrg/yii2-settings": "@dev"
```

to the require section of your `composer.json` file.

## Configuration
Configure console application

```php
'controllerMap' => [
    'migrate' => [
        'class' => \yii\console\controllers\MigrateController::class,
        'migrationPath' => [
            '@app/migrations',
            '@settings/migrations',
        ],
    ],
]
```

```bash
php yii migrate
```

or just apply

```bash
php yii migrate/up --migrationPath=@settings/migrations
```

Module config example
```php
// /admin/settings/default/index

'modules' => [
    'admin' => [
        'class' => 'app\modules\admin\Module',
        'layout' => '@admin/views/layouts/main.php',
        'as access' => [
            'class' => 'yii\filters\AccessControl',
            'except' => ['default/login'],
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ]
        ],
        'modules' => [
            'settings' => [
                'class' => \sakhnovkrg\yii2\settings\Module::class,
            ],
        ],
    ],
],
```

Components

```php
'components' => [
    'settings' => [
        'class' => \sakhnovkrg\yii2\settings\components\Settings::class,
        // optional
        'modelClass' => \sakhnovkrg\yii2\settings\models\Setting::class,
        'enableFlashMessages' => true,
        'cache' => 'cache',
        'cacheKey' => 'yii2-settings',
        'activeFormClass' => 'yii\\bootstrap5\\ActiveForm',
    ],
    'i18n' => [
        'translations' => [
            'yii2-settings' => [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@settings/messages',
            ],
        ],
    ],
],
```

## Usage

Add settings example

```php
<?php

use yii\helpers\Json;

class m230209_092035_add_settings extends \sakhnovkrg\yii2\settings\migrations\Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addSetting(
            // type
            \sakhnovkrg\yii2\settings\types\StringType::class,
            // section, key
            'Home', 'Title',
            // attributes
            ['en' => 'Site name', 'ru' => 'Название сайта'],
            // attributes leabels
            ['en' => 'English', 'ru' => 'Russian'],
            // validators
            [
                [['en', 'ru'], 'required'],
                [['en', 'ru'], 'string', ['max' => 255]]
            ],
            // extra params (depends on the type)
            ['class' => 'form-control'],
            // description
            'Browser tab title on home page'
        );

        $this->addSetting(
            \sakhnovkrg\yii2\settings\types\TextType::class,
            'Home', 'Meta Description',
            ['en' => '', 'ru' => ''],
            ['en' => 'English', 'ru' => 'Russian'],
            [
                [['en', 'ru'], 'string', ['max' => 155]]
            ],
            ['rows' => '2']
        );

        $this->addSetting(
            \sakhnovkrg\yii2\settings\types\StringType::class,
            'Contacts', 'Phone',
            ['field' => '+7 (777) 777-77-77'],
            ['field' => 'Number'],
            [
                [['field'], 'required'],
                [['field'], 'string', ['max' => 255]],
            ]
        );

        $this->addSetting(
            \sakhnovkrg\yii2\settings\types\StringType::class,
            'Contacts', 'Email',
            ['field' => 'mail@example.com'],
            ['field' => 'E-mail'],
            [
                [['field'], 'required'],
                [['field'], 'email'],
                [['field'], 'string', ['max' => 255]],
            ]
        );

        $this->addSetting(
            \sakhnovkrg\yii2\settings\types\SelectType::class,
            'Site', 'Maintenance mode',
            ['field' => 0],
            ['field' => 'Status'],
            [
                [['field'], 'required'],
            ],
            [
                'items' => [
                    1 => 'Enabled',
                    0 => 'Disabled',
                ]
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // single
        $this->dropSetting('Site', 'Maintenance mode');

        // group
        $this->dropSettings('Home');
        $this->dropSettings('Contacts');
    }
}
```

Read

```php
$settings = \Yii::$app->settings;

echo $settings->get('Contacts', 'Phone') // +7 (777) 777-77-77
echo $settings->get('Home', 'Title')['ru'] // 'Название сайта'
```
