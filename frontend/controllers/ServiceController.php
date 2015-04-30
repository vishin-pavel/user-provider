<?php
/**
 * 
 * @author Vishin Pavel
 * @date 30.04.15
 * @time 12:11
 */

namespace frontend\controllers;


use frontend\controllers\base\rest\Controller;

class ServiceController extends Controller{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
    public function actionStatus(){
        return 'Service is on-line.';
    }
}