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

require_once "models/WxJsSdk.php";
class UserController extends Controller{
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
        
        $Sql="select u.* from  sm_user as u where u.userstate=0 and u.isenable=0 or u.userstate =2  ORDER BY u.userorder asc";

        $model = User::findBySql($Sql);
        
       // $model=User::find()->orderBy("userstate desc")->where(['<>','userstate',1]);
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
        return $this->render('index',['page'=>$page,'items'=>$items,'search'=>$search]);
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
    
    //public function actionEdit($id){
    
    //    $model=User::findOne(['id'=>$id]);
    //    if($model->load(Yii::$app->request->post())//判断是否是表单提交
    //        && $model->validate() //验证表单提交的内容正确性
    //        ){
    //        if($model->save()){
    //            Yii::$app->session->setFlash('success','修改成功！');
    //        }else{
    //            Yii::$app->session->setFlash('error','修改失败！');
    //        }
    //    }
    //    return $this->render('edit',['model'=>$model]);
    //}

    /**
     * @禁用用户
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
     * @启用用户
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
    
      //审核用户通过
    public function actionExamine($id){
        
        
        $model=User::findOne(['id'=>$id]);
        if($model->load(Yii::$app->request->post())//判断是否是表单提交
            && $model->validate() //验证表单提交的内容正确性
            ){
            $model->userstate=1;
            if($model->save()){
                Yii::$app->session->setFlash('success','审核成功！');
                
                ///微信模板消息通知用户成为老师通过
                $jssdk = new  \WxJsSdk(WX_APPID, WX_APPSECRET); 
                $touser =  $model->openid;
                $jssdk->sendapproveresulttouser($touser,true,"通过");
                
                //end
   
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
                ///微信模板消息通知用户成为老师没通过
                
                $jssdk = new  \WxJsSdk(WX_APPID, WX_APPSECRET); 

                
                $touser =  $model->openid;
                
                $reason = $model->explain;
                

                $jssdk->sendapproveresulttouser($touser,false,$reason);
                
            }else{
                Yii::$app->session->setFlash('error','修改失败！');
            }
        }
        
        return $this->render('examineno' ,['model'=>$model]);
    }
    
} 
    
