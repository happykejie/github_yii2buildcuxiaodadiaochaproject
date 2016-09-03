<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/1/31
 * Time: 17:33
 */

namespace app\modules\admin\controllers;
use app\models\User;
use Yii;
use yii\web\Controller;
use app\models\Follow;
use app\models\YiiUser;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

class UseradminController extends Controller{
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
        $currentUser = YiiUser::findOne(['id'=>Yii::$app->user->getId()]);
        $model=User::find()->orderBy("userorder desc")->where(['userstate'=>3])->where(['isenable'=>1]);
        
        $search=new User();
        if ($search->load(Yii::$app->request->post())) {
            if (!isset($model->nickname))
            {
                
                $model->andWhere(['like', 'nickname',$search->nickname]);
            }
        }
        $count=$model->count();
        $page=new Pagination(['defaultPageSize'=>50,'totalCount'=>$count]);
        $items=$model->offset($page->offset)->limit($page->limit)->all();
        return $this->render('index',['page'=>$page,'items'=>$items,'search'=>$search,'currentUser'=>$currentUser]);
    }
    
    /**
     * @add
     */
    public function actionAdd(){
        $model=new User();
        if($model->load(Yii::$app->request->post())//判断是否是表单提交
            && $model->validate() //验证表单提交的内容正确性
            ){
            $model->userstate=3;
            $model->pwd=md5( $model->pwd);
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
    
    public function actionPwd($id){
        $model=User::findOne(['id'=>$id]);
        if($model->load(Yii::$app->request->post())//判断是否是表单提交
            && $model->validate() //验证表单提交的内容正确性
            ){
            $model->pwd=md5($model->pwd);
            if($model->save()){
                Yii::$app->session->setFlash('success','修改成功！');
            }else{
                Yii::$app->session->setFlash('error','修改失败！');
            }
        }
        return $this->render('pwd',['model'=>$model]);
    }
    
    public function actionDelete($id){ 
        //$user = User::findOne(["id"=>$id]);
        if(Yii::$app->request->isAjax){
            
            //$user->userstate=3;
            //$user->save();
            //ajax请求删除
            User::deleteAll(['id'=>$id]);
            return 0;
        }
    }
    
    //审核用户通过
    public function actionExamine($id){
        $model=User::findOne(['id'=>$id]);
        if($model->load(Yii::$app->request->post())//判断是否是表单提交
            && $model->validate() //验证表单提交的内容正确性
            ){
            $model->userstate=1;
            if($model->save()){
                Yii::$app->session->setFlash('success','审核成功！');
            }else{
                Yii::$app->session->setFlash('error','审核失败！');
            }
        }
        return $this->render('examine',['model'=>$model]);
        
        
    }
    
    
    //审核用户不通过
    public function actionExamineno($id){
        
        $model=User::findOne(['id'=>$id]);
        
        
        if($model->load(Yii::$app->request->post())//判断是否是表单提交
            && $model->validate() //验证表单提交的内容正确性
            ){
            $model->userstate=0;
            if($model->save()){
                Yii::$app->session->setFlash('success','修改成功！');
            }else{
                Yii::$app->session->setFlash('error','修改失败！');
            }
        }
        
        return $this->render('examineno' ,['model'=>$model]);
    }
    
} 