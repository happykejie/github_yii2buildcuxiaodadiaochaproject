<?php

namespace app\modules\api\controllers;
use app\models\Follow;
use app\models\Msg;
use Yii;
use yii\web\Controller;
use yii\web\Response;

use app\models\YiiUser;
use yii\web\session;
use app\models\UserForm;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class V1Controller extends Controller{
    public $enableCsrfValidation = false;//yii默认表单csrf验证，如果post不带改参数会报错！
   
    public function init()
    {
        parent::init();
        Yii::$app->response->format = Response::FORMAT_JSON;   
    }
    
    /**
     * accesscontrol
     */

    /**
     * @用户授权规则
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login','captcha'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout','edit','add','del','index','users','thumb','upload','cutpic','follow','nofollow'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @验证码独立操作
     */

    public function actions(){
        return [

            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }


    /**
     * @return string 后台默认页面
     */
    public function actionIndex()
    { 
        return $this->render("index");
    }

}
