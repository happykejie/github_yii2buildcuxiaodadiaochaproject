<?php

namespace app\modules\wenda\controllers;
use Yii;
use yii\web\Controller;
use yii\web\session;
use yii\filters\AccessControl;
use yii\helpers\Url;

use app\models\YiiUser;
use app\models\UserForm;

class TestController extends Controller{
    public $layout  = 'layout';
    public function actionIndex(){
        return $this->render("index");
    }    
}
