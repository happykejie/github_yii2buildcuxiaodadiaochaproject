<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/1/31
 * Time: 17:33
 */

namespace app\modules\admin\controllers;
use app\models\User;
use app\models\Askproblem;
use app\models\Answerquestion;


use Yii;
use yii\web\Controller;
use app\models\Follow;
use app\models\YiiUser;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

class StatisticsController extends Controller{
    public $layout  = 'layout';
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
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @msg消息列表
     */

    public function  actionIndex(){
        //获取所有用户信息
        $user=User::find()->where(['<>','openid',''])->all();
        
        //获取所有问题信息
        $askproblem=Askproblem::find()->all();
        
        //获取所有的回答信息
        $answerquestion=Answerquestion::find()->all();
        
        return $this->render('index',['user'=>$user,'askproblem'=>$askproblem,'answerquestion'=>$answerquestion]);
    }
    /**
     * @add
     */
    public function actionAdd(){
        
        $model=new User();
        if($model->load(Yii::$app->request->post())//判断是否是表单提交
            && $model->validate() //验证表单提交的内容正确性
            ){
            if($model->save()){
                Yii::$app->session->setFlash('success','新增成功！');
            }else{
                Yii::$app->session->setFlash('error','新增失败！');
            }
            //echo "<pre/>";print_r(Yii::$app->request->post());die();
        }
        return $this->render('add',['model'=>$model]);
    }
    
    
    public function actionEdit($id){
        
        $model=User::findOne(['id'=>$id]);
        if($model->load(Yii::$app->request->post())//判断是否是表单提交
            && $model->validate() //验证表单提交的内容正确性
            ){
            if($model->save()){
                Yii::$app->session->setFlash('success','修改成功！');
            }else{
                Yii::$app->session->setFlash('error','修改失败！');
            }
        }
        return $this->render('edit',['model'=>$model]);
    }
    
    
    public function actionDelete($id){ 
        if(Yii::$app->request->isAjax){
            //ajax请求删除
            User::deleteAll(['id'=>$id]);
            return 0;
        }
    }
    

} 