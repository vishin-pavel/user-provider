<?php
/**
 * 
 * @author Vishin Pavel
 * @date 29.04.15
 * @time 18:10
 */

namespace frontend\controllers;

use frontend\controllers\base\rest\ActiveController;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;
use yii\web\UnauthorizedHttpException;

class UserController extends ActiveController{
    public $modelClass = 'common\models\User';
    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                return $model;
            } else {
                throw new HttpException(503, 'Service Unavailable');
            }
        }
        else{
            throw new BadRequestHttpException();
        }
    }
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($model->load(\Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            return $model;
        }
        throw new BadRequestHttpException();
    }
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->attributes = \Yii::$app->request->post()) {
            if ($user = $model->signup()) {
                \Yii::$app->response->setStatusCode('201');
                \Yii::$app->response->headers->set('Location', 'user/'.$user->getId());
                return $model;
            }
            else{
                \Yii::$app->response->setStatusCode(400, 'Bad Request');
                return $model->getErrors();
            }
        }
        throw new BadRequestHttpException();
    }
    public function actionValidate(){
        $user = \common\models\User::findIdentity(\Yii::$app->request->get('id'));
        if(!$user) throw new UnauthorizedHttpException('Bad login and password pair');
        if($user->validatePassword(\Yii::$app->request->get('password'))){
            \Yii::$app->response->setStatusCode(200);
            return ['id'=>$user->getId()];
        }
        else{
            throw new UnauthorizedHttpException('Bad id and password pair');
        }
    }
}