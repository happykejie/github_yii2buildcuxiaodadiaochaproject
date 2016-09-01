<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/1/31
 * Time: 17:33
 */

namespace app\modules\admin\controllers;
use app\models\User;
use app\models\Category;

use Yii;
use yii\web\Controller;
use app\models\Follow;
use app\models\YiiUser;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use yii\data\ActiveDataProvider;

class TeacherController extends Controller{
    public $layout  = 'layout';
	 private $_user;
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
        
        $this->_user = User::findOne(['id'=>Yii::$app->user->getId()]);
       
        $currentUser =$this->_user;
        
        $Sql="select u.* from  sm_user as u where u.userstate=1 and u.isenable=0  ORDER BY u.userorder asc";

        $model = User::findBySql($Sql);
        
        
      
        
        $search=new User();
        
      
        if ($search->load(Yii::$app->request->post())) {
            if (!isset($model->nickname))
            {
                $model->andWhere(['like', 'nickname',$search->nickname]);
            }
        }
      
        
        $count=count($model->all());
        $page=new Pagination(['defaultPageSize'=>50,'totalCount'=>$count]);
        $items=$model->offset($page->offset)->limit($page->limit)->all();
        return $this->render('index',['page'=>$page,'items'=>$items,'search'=>$search,'currentuser'=>$currentUser]);
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
                Yii::$app->session->setFlash('success','新增成功');
            }else{
                Yii::$app->session->setFlash('error','新增失败');
            }
            //echo "<pre/>";print_r(Yii::$app->request->post());die();
        }
        return $this->render('add',['model'=>$model]);
    }
    
    
  public function actionEdit($id){
      $category=Category::find()->all();
      
      $model=User::findOne(['id'=>$id]);
      if($model->load(Yii::$app->request->post())//判断是否是表单提交
          && $model->validate() //验证表单提交的内容正确性
          ){
          $headimgurl_files = UploadedFile::getInstance($model, 'headimgurl');
          if($headimgurl_files){
              $model->headimgurl = $model->fileInput($headimgurl_files);
          }
          if($model->save()){
              Yii::$app->session->setFlash('success','修改成功！');
          }else{
              Yii::$app->session->setFlash('error','修改失败！');
          }
      }
      return $this->render('edit',['model'=>$model,'category'=>$category]);
    }
    
    /**
     * @问题置顶
     */
    public function actionTop($id){
        
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
        return $this->render('top',['model'=>$model]);
    }
    
    
    /**
     * @禁用老师
     */
    public function actionBanuser($id){ 
        $user = User::findOne(["id"=>$id]);
        if(Yii::$app->request->isAjax){
            
            $user->isenable=1;
            $user->save();
            ////ajax请求删除
            //User::deleteAll(['id'=>$id]);
            return 0;
        }
    }
    
    
    /**
     * @启用老师
     */
    public function actionStartuser($id){ 
        $user = User::findOne(["id"=>$id]);
        if(Yii::$app->request->isAjax){
            
            $user->isenable=0;
            $user->save();
            ////ajax请求删除
            //User::deleteAll(['id'=>$id]);
            return 0;
        }
    }
    
    
    
    /**
     * @冻结老师金额
     */
    public function actionBanfee($id){ 
        $user = User::findOne(["id"=>$id]);
        if(Yii::$app->request->isAjax){
            
            $user->banfee=1;
            $user->save();
            ////ajax请求删除
            //User::deleteAll(['id'=>$id]);
            return 0;
        }
    }
    
    
    /**
     * @解冻老师金额
     */
    public function actionStartfee($id){ 
        $user = User::findOne(["id"=>$id]);
        if(Yii::$app->request->isAjax){
            
            $user->banfee=0;
            $user->save();
            ////ajax请求删除
            //User::deleteAll(['id'=>$id]);
            return 0;
        }
    }
    

} 