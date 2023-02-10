<?php

namespace sakhnovkrg\yii2\settings\controllers;

use sakhnovkrg\yii2\settings\models\Setting;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class DefaultController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'clear-cache' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex() {
        return $this->render('index');
    }

    public function actionUpdate($id)
    {
        $settingModel = Setting::findOne($id);
        if(!$settingModel) {
            throw new NotFoundHttpException(\Yii::t('yii', 'Page not found.'));
        }

        $settingType = \Yii::createObject($settingModel->type, ['settingModel' => $settingModel]);
        $formModel = &$settingType->formModel;

        if($formModel->load(\Yii::$app->request->post()) && $settingType->updateSetting()) {
            if(\Yii::$app->settings->enableFlashMessages) {
                \Yii::$app->session->setFlash('success',
                    \Yii::t('yii2-settings', 'Setting {path} updated.', [
                        'path' => $settingModel->section . ' → ' . $settingModel->key
                    ])
                );
            }
            return $this->redirect(['index']);
        }

        return $this->render('update', compact('settingModel', 'settingType', 'formModel'));
    }

    public function actionClearCache()
    {
        \Yii::$app->settings->clearCache();
        if(\Yii::$app->settings->enableFlashMessages) {
            \Yii::$app->session->setFlash('success', \Yii::t('yii2-settings', 'Cache cleared.'));
        }
        return $this->redirect($this->request->referrer);
    }
}