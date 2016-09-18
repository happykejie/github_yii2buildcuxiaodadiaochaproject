<?php

namespace app\modules\admin\controllers;
use app\models\Follow;
use app\models\Msg;
use Yii;
use yii\web\Controller;
use app\models\YiiUser;
use app\models\User;
use yii\web\session;
use app\models\UserForm;
use yii\filters\AccessControl;
use yii\helpers\Url;



use app\models\Activity;

use yii\web\NotFoundHttpException;

use common\widgets\payment\Weixinjspi;
use common\widgets\payment\Notifyurl;

use yii\app;
use yii\web\Response;

use yii\web\UploadedFile;
use app\models\UploadForm;

class MindexController extends Controller{
    public $enableCsrfValidation = false;//yii默认表单csrf验证，如果post不带改参数会报错！
    public $layout  = 'layout';
    private $_openid ,$_access_token,$_wxuser,$_user,$_users;

    /**
     * accesscontrol
     */

    /**
     * 首页
     */
    public function actionIndex()
    {
        
        
        $currentuserid= Yii::$app->user->getId();  //获取当前用户ID
        $items =YiiUser::findOne(['id'=>$currentuserid]);

        return $this->render('index',['items'=>$items]);
        
    
        
    }
    
    
    /**
     * 登陆页面
     */
    public function actionLogin()
    {
        
        $model=new UserForm();

        if($model->load(Yii::$app->request->post())){

            if($model->login()){
                //查询未读消息
                $count=Msg::find()->andwhere(['tid'=>Yii::$app->user->getId(),'status'=>0])->count();
                $session=Yii::$app->session;
                $session->set('msg',$count);

                return $this->redirect(['mindex/index']);
                
            }else{
                return $this->render('login',['model'=>$model]);
            }
        }

        return $this->render('login',['model'=>$model]);
        
        
    }
    
    
    /**
     * 发布信息
     */
    public function actionPublishinfo()
    {
        
		//获取当前用户定位城市
		
		
        $currentuserid= Yii::$app->user->getId();  //获取当前用户ID
        $items =User::findOne(['id'=>$currentuserid]);
		
		$getcity =$items->locationcity;			
		if(!$getcity)//如果定位城市不存在设定默认城市
		{

			///获取微信关注默认获取城市
			
			$wxcity =$items->city;
			
			if($wxcity)
			{
				$getcity =$wxcity.'市';
			}
			else
			{
				$getcity = '成都市';
			}
		}
		

        $group= Category::find()->all();
        $to=array();
        foreach($group as $v){
            $to[$v->id]=$v->categoryname;
        }
        $model=new Activity();
        
        if($model->load(Yii::$app->request->post())//判断是否是表单提交s
            ){
            
            if (Yii::$app->request->isPost) {
                $surface_files = UploadedFile::getInstance($model, 'surface_file');
                if($surface_files){
                    $model->surface = $model->fileInput($surface_files);
                    $model->setAttr("surface",$model->surface);
                }
                $homepictures_val=  Yii::$app->request->post("homepictures_val");
                if($homepictures_val){
                    $model->homepictures= explode('-',$homepictures_val);
                    $model->setAttr("homepictures",$homepictures_val);
                }else{
                    $homepictures_files = UploadedFile::getInstances($model, 'homepictures');
                    if($homepictures_files)
                    {
                        $model->homepictures=$model->fileInput($homepictures_files);
                        $model->setAttr("homepictures",$model->homepictures);
                    }
                }
                $newspictures_val=  Yii::$app->request->post("newspictures_val");
                if($newspictures_val){
                    $model->newspictures= explode('-',$newspictures_val);
                    $model->setAttr("newspictures",$newspictures_val);
                }else{
                    $newspictures_files = UploadedFile::getInstances($model, 'newspictures');
                    if($newspictures_files)
                    {
                        $model->newspictures=$model->fileInput($newspictures_files);
                        $model->setAttr("newspictures",$model->newspictures);
                    }
                }
            }
            
            
            
            $userid=Yii::$app->user->getId();
            $model->publishpeople=$userid;
            $model->ispay='否';
            $model->viewcount=0;
			
            
            
            if( $model->validate()){
                if($model->save()){
                    Yii::$app->response->redirect("/cxddc/cuxiao/detail?id=$model->id");
                }else{
                    Yii::$app->session->setFlash('error','添加失败！');
                }
            }
        }
        $currentuserid= Yii::$app->user->getId();  //获取当前用户ID
        return $this->render('publishinfo',['model'=>$model,'to'=>$to,'currentuserid'=>$currentuserid,'getcity'=>$getcity]);
    }
    
    
    /**
     * 我的发布页面
     */
    public function actionMypublished($id=-1)
    {

        ///开始禁用用户
        $activity= new Activity();

        if($activity->load(Yii::$app->request->post()))//判断是否是表单提交
        {
            
            ///先获取该活动关联图片
            $model = Activity::findOne(['id'=>$activity->id]);
            
            $surfaceimg = $model->surface; //封面图片
            
            
            $newspicturesarraryimg = $model->newspictures;  // 展示图片
            
            $resultint =  Activity::deleteAll(['id'=>$activity->id]);
            
            if($resultint>0)
            {
                $app_root =APP_ROOT;

                $del=unlink($app_root.$surfaceimg);
                
                foreach($newspicturesarraryimg as  $item)
                {
                    if(strlen($item)>5)
                    {
                        $del=unlink($app_root.$item);
                        
                    }
                    
                    
                }
            }
            
            Yii::$app->session->setFlash('success','删除成功！');
            
        }
        
        ///结束禁用用户
        
        
        if($id==-1)
        {
            $this->_user = YiiUser::findOne(['id'=>Yii::$app->user->getId()]);
            $userid =Yii::$app->user->getId();
            if( $this->_user ){

                $Sqlitem="select a.* from sm_activity  as a inner join sm_category as b on a.group_id=b.id where a.publishpeople ='$userid'";
                
                $Sqlitem=$Sqlitem." order by createtime DESC";
                //获取所有问题信息
                $mypublishitems=\app\models\Activity::findBySql($Sqlitem)->all();
                
                $currentuserid= Yii::$app->user->getId();  //获取当前用户ID
                
                return $this->render('mypublished',['mypublishitems'=>$mypublishitems,'currentuserid'=>$currentuserid]);
            }
            else
            {
                //返回登陆
                Yii::$app->response->redirect(Url::to(['/cxddc/index'],true));
                return false;			
            }
        }
        
        else
        {
            $this->_user = YiiUser::findOne(['id'=>$id]);
           
            if( $this->_user ){

                $Sqlitem="select a.* from sm_activity  as a inner join sm_category as b on a.group_id=b.id where a.publishpeople ='$id'";
                
                $Sqlitem=$Sqlitem." order by createtime DESC";
                //获取所有问题信息
                $mypublishitems=\app\models\Activity::findBySql($Sqlitem)->all();
                
                $currentuserid= Yii::$app->user->getId();  //获取当前用户ID
                
                return $this->render('mypublished',['mypublishitems'=>$mypublishitems,'currentuserid'=>$currentuserid]);
            }
            else
            {
                //返回登陆
                Yii::$app->response->redirect(Url::to(['/cxddc/index'],true));
                return false;			
            }
        }
            
        
       
    }
    
    
    
    
  
    
    /**
     * 所有发布页面
     */
    public function actionAllpublished()
    {
        ///开始删除促销活动
        $activity= new Activity();

        if($activity->load(Yii::$app->request->post()))//判断是否是表单提交
        {
            
            ///先获取该活动关联图片
            $model = Activity::findOne(['id'=>$activity->id]);
            
            $surfaceimg = $model->surface; //封面图片
            
            
            $newspicturesarraryimg = $model->newspictures;  // 展示图片
            
            $resultint =  Activity::deleteAll(['id'=>$activity->id]);
            
            if($resultint>0)
            {
                $app_root =APP_ROOT;

                $del=unlink($app_root.$surfaceimg);
                
                foreach($newspicturesarraryimg as  $item)
                {
                    if(strlen($item)>5)
                    {
                        $del=unlink($app_root.$item);
                        
                    }
                    
                    
                }
            }
            
            Yii::$app->session->setFlash('success','删除成功！');
            
        }
        
        ///结束删除促销活动
        
        
        $this->_user = YiiUser::findOne(['id'=>Yii::$app->user->getId()]);
        $userid =Yii::$app->user->getId();
        if( $this->_user ){

            $Sqlitem="select a.* from sm_activity  as a inner join sm_category as b on a.group_id=b.id";
            
            $Sqlitem=$Sqlitem." order by createtime DESC";
            //获取所有问题信息
            $mypublishitems=\app\models\Activity::findBySql($Sqlitem)->all();
            
            $currentuserid= Yii::$app->user->getId();  //获取当前用户ID
            
            return $this->render('mypublished',['mypublishitems'=>$mypublishitems,'currentuserid'=>$currentuserid]);
        }
        else
        {
            //返回登陆
            Yii::$app->response->redirect(Url::to(['/cxddc/index'],true));
            return false;			
        }
    }
    
    
    
    
    
    /**
     * 用户管理
     */
    public function actionUsermanage()
    {
        
        ///开始禁止用户
        $user= new User();

        if($user->load(Yii::$app->request->post()))//判断是否是表单提交
        {
            
            $testid =$user->id;
            
            $user = User::findOne(["id"=>$user->id]);
         
                
                $user->isenable=1;
                $user->save();
        
         
            
            Yii::$app->session->setFlash('success','禁用成功！');
            
        }
        
        ///结束删除促销活动
        
        
        $Sqlitem="select * from sm_user where isenable=0";
        
        $Sqlitem=$Sqlitem." order by createusertime DESC";
        //获取所有问题信息
        $items=\app\models\User::findBySql($Sqlitem)->all();
        
        return $this->render('usermanage',['items'=>$items]);
        
    }
    
    
    /**
     * @禁用用户
     */
    public function actionBanuser(){ 
        ///开始启用用户
        $user= new User();

        if($user->load(Yii::$app->request->post()))//判断是否是表单提交
        {
            
            $testid =$user->id;
            
            $user = User::findOne(["id"=>$user->id]);
            
            
            $user->isenable=0;
            $user->save();
            
            
            
            Yii::$app->session->setFlash('success','启用成功！');
            
        }
        
        ///结束启用用户
        
        
        $Sqlitem="select * from sm_user where isenable=1";
        
        $Sqlitem=$Sqlitem." order by createusertime DESC";
        //获取所有问题信息
        $items=\app\models\User::findBySql($Sqlitem)->all();
        
        return $this->render('banuser',['items'=>$items]);
    }


    
    
    
    /**
     * @禁止用户列表
     */
    public function actionBanuserlist($id){ 
        $user = User::findOne(["id"=>$id]);
        if(Yii::$app->request->isAjax){
            
            $user->isenable=0;
            $user->save();
            ////ajax请求删除
            //User::deleteAll(['id'=>$id]);
            return 0;
        }
    }
    
    
    public function actionUserinfo($id=-1)
    {
        
        if($id==-1)
        {
            $id=Yii::$app->user->getId();
            $model=User::findOne(['id'=>$id]);
            
            if($model->load(Yii::$app->request->post())//判断是否是表单提交
               //验证表单提交的内容正确性
                ){
                
                if($model->save()){
                    Yii::$app->session->setFlash('success','发送成功！');
                }else{
                    Yii::$app->session->setFlash('error','发送失败！');
                }
            }
           
            
            return $this->renderPartial('userinfo',['model'=>$model]);   
        }
        
        else
        {
            
            $model=User::findOne(['id'=>$id]);
            
            if($model->load(Yii::$app->request->post())//判断是否是表单提交
               //验证表单提交的内容正确性
                ){
                
                if($model->save()){
                    Yii::$app->session->setFlash('success','保存成功！');
                }else{
                    Yii::$app->session->setFlash('error','保存失败！');
                }
            }
       
            
            return $this->renderPartial('userinfo',['model'=>$model]);    
        }
        
       
    }
    
    
  
    
    /**
     * 统计
     */
    public function actionStatistics()
    {
        
    }
    


 

    

    
    
    

   
    




}
