<?php

namespace app\modules\cxddc\controllers;
use app\models\Follow;
use app\models\User;
use app\models\Userattention;
use app\models\Askproblem;
use app\models\Enterprisepay;
use app\models\Category;

use app\models\LoveListenQuestion;
use yii\data\Pagination;
use Yii;
use yii\web\Controller;
use app\models\YiiUser;
use yii\web\session;
use app\models\UserForm;
use yii\filters\AccessControl;

use app\models\Activity;

use yii\web\NotFoundHttpException;

use common\widgets\payment\Weixinjspi;
use common\widgets\payment\Notifyurl;
use yii\helpers\Url;
use yii\app;
use yii\web\Response;

use yii\web\UploadedFile;
use app\models\UploadForm;

require_once "models/phpqrcode.php";

require_once "models/WxJsSdk.php";
//error_reporting(E_ERROR);
require_once "/vendor/WxpayAPI/lib/WxPay.Api.php";
require_once "/vendor/WxpayAPI/example/WxPay.JsApiPay.php";
require_once '/vendor/WxpayAPI/example/log.php';

class MyCuXiaoController extends Controller{
    public $enableCsrfValidation = false;//yii默认表单csrf验证，如果post不带改参数会报错！
    public $layout  = 'layout';
	private $_openid ,$_access_token,$_wxuser,$_user,$_users;

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
                        //'actions' => ['logout','edit','add','del','index','users','thumb','upload','cutpic','follow','nofollow'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
    
    /**
     * 默认主页
     */
    public function actionIndex()
    {
        
        //判断当前用户是否关注，如果没有关注跳转让用户关注
        $currentuserid= Yii::$app->user->getId();  //获取当前用户ID
        $items =User::findOne(['id'=>$currentuserid]);
        
		
		if($items->subscribe==0) //如果用户没用关注，跳转用户关注
        {
			Yii::$app->session->setFlash('notattention','还没有关注，请先关注');

            //return $this->render('infoercode',['currentuserid'=>$currentuserid]);
            
			return $this->render('commonuser',['items'=>$items,'currentuserid'=>$currentuserid]);
        }
        ///跳转关注结束
        
        
        if ($items->userstate==1)
        {
            return $this->render('publishuser',['items'=>$items,'currentuserid'=>$currentuserid]);
        }else
        {
            return $this->render('commonuser',['items'=>$items,'currentuserid'=>$currentuserid]);
        }   
    }
    
    /**
     * 意见反馈
     */
    public function actionFeedback()
    {
        $currentuserid= Yii::$app->user->getId();  //获取当前用户ID
        return $this->render('feedback',['currentuserid'=>$currentuserid]);
    }
    
    /**
     * 发布信息用户
     */
    public function actionPublishuser()
    {
       
        
        $currentuserid= Yii::$app->user->getId();  //获取当前用户ID
        return $this->render('publishuser',['currentuserid'=>$currentuserid]);
    }
    
    /**
     * 普通用户
     */
    public function actionCommonuser()
    {
        
        $currentuserid= Yii::$app->user->getId();  //获取当前用户ID
        return $this->render('commonuser',['currentuserid'=>$currentuserid]);
       
    }
    
    
    /**
     * 发布申明
     */
    public function actionPublishdeclare()
    {
        $currentuserid= Yii::$app->user->getId();  //获取当前用户ID
        return $this->render('publishdeclare',['currentuserid'=>$currentuserid]);

    }
    
    
    
    /**
     * 我的发布
     */
    public function actionMypublished()
    {             
        $this->_user = YiiUser::findOne(['id'=>Yii::$app->user->getId()]);
        $userid =Yii::$app->user->getId();
        if( $this->_user ){
           
            
            $Sqlitem="select a.* from sm_activity  as a inner join sm_category as b on a.group_id=b.id where a.publishpeople ='$userid'";
          
            $Sqlitem=$Sqlitem." order by ordernum asc,createtime DESC";
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
     * 我的资料
     */
    public function actionMyinfo()
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
        $currentuserid= Yii::$app->user->getId();  //获取当前用户ID
        
        return $this->render('myinfo',['model'=>$model,'currentuserid'=>$currentuserid]);    
    }
    
    
    /**
     * 平台合作
     */
    public function actionCooperation()
    {
        $currentuserid= Yii::$app->user->getId();  //获取当前用户ID
        
        return $this->render('cooperation',['currentuserid'=>$currentuserid]);
    }
    
    /**
     * 平台二维码展示
     */
    
    public function actionInfoercode()
    {
        $currentuserid= Yii::$app->user->getId();  //获取当前用户ID
        
        return $this->render('infoercode',['currentuserid'=>$currentuserid]);
        
    }
    
    
    
    /**
     * 没支付或者支付失败的订单
     */
    
    public function actionNopayorder()
    {
        
        $this->_user = YiiUser::findOne(['id'=>Yii::$app->user->getId()]);
        $userid =Yii::$app->user->getId();
        if( $this->_user ){

            $Sqlitem="select a.* from sm_activity  as a inner join sm_category as b on a.group_id=b.id where a.publishpeople ='$userid' and a.ispay='否'";
            
            $Sqlitem=$Sqlitem." order by ordernum asc,createtime DESC";
            //获取所有问题信息
            $mypublishitems=\app\models\Activity::findBySql($Sqlitem)->all();
            
            $currentuserid= Yii::$app->user->getId();  //获取当前用户ID
            

            return $this->render('nopayorder',['mypublishitems'=>$mypublishitems,'currentuserid'=>$currentuserid]);
        }
        else
        {
            //返回登陆
            Yii::$app->response->redirect(Url::to(['/cxddc/index'],true));
            return false;			
        }
        
       
        
    }
    
    
    
    
    /**
     * 确认订单支付界面
     */
    public function actionComfirmorder($id)
    {
        

         $item = Activity::findOne(['id'=>$id]);
        
        
        if(isset($item)) 
        {
            
            ///开始生产订单信息
            
            
            $body =ORDERBODY;
            $fee=(int)($item->paynum);
            $goods_tag=ORDERTAG;
            $attach =ORDERATTACH;
            $tools = new \JsApiPay();
            
            // $body ='aiwenaida';
            // $fee=1;
            // $goods_tag='aiwenaida';
            // $attach ='this is aiwenaida of test program';
            // $tools = new \JsApiPay();

            $this->_user = User::findOne(['id'=>Yii::$app->user->getId()]);
            if( $this->_user ){
                $this->_openid = $this->_user->openid;
            }
            //$this->_openid = 'oTBP7vhBl8BNsAY-F5DmE1wdRbDw';//测试使用用户 微信账号:khjl12345
            if(empty($this->_openid)){
                //①、获取用户openid
                $this->_openid = $tools->GetOpenid(Url::to(['/cxddc/index'],true));
            }
            //商户订单号
            $out_trade_no=MCHID.date("YmdHis");
            //②、统一下单
            $input = new \WxPayUnifiedOrder();
            $input->SetBody($body);
            $input->SetAttach($attach);
            //必填
            $input->SetOut_trade_no($out_trade_no);
            $input->SetTotal_fee($fee);
            $input->SetTime_start(date("YmdHis"));
            $input->SetGoods_tag($goods_tag);
            $input->SetNotify_url(Url::to(['/cxddc/cxddc/paynotify']));
            $input->SetTrade_type("JSAPI");
            $input->SetOpenid($this->_openid);
            
            $order = \WxPayApi::unifiedOrder($input);
            
            $trade_no = $input->GetOut_trade_no();

            $jsApiParameters = $tools->GetJsApiParameters($order);

            //获取共享收货地址js函数参数
            $editAddress = $tools->GetEditAddressParameters();
            
            ///结束生产订单信息


            $arryimg = $item->newspictures;
            
            $currentuserid= Yii::$app->user->getId();  //获取当前用户ID
            return $this->render('comfirmorder',['item'=>$item,'arryimg'=>$arryimg,'order'=>$order,'trade_no'=>$trade_no,'jsApiParameters'=>$jsApiParameters,'editAddress'=>$editAddress,'currentuserid'=>$currentuserid]);
        }
        
        return null;
       
    }
    
    
    
    
    
    /**
     * 支付成功修改发布信息状态
     */
    public function actionPaysuccess()
    {
        $publishid = Yii::$app->request->post('publishid');
        $model=Activity::findOne(['id'=>$publishid]);
        
        $model->ispay='是';
        
        $result = $model->save();
        
        if($result)
        {
            return '1';
        }
        
        return false;
    }
    
    
    /**
     * 成为发布者
     */
    public function actionBecomepublisher($id)
    {
      
        $model=User::findOne(['id'=>$id]);
        if($model->load(Yii::$app->request->post())//判断是否是表单提交
            
            
         
           //验证表单提交的内容正确性
            ){
            //如果自己本身不是老师,状态变更为审核状态
             if ($model->userstate<>1)
            {
                $model->userstate=2;
            }
            if($model->save()){
                Yii::$app->session->setFlash('success','发送成功！');
                $items =$model;
                return $this->render('commonuser',['items'=>$items]);    
            }else{
                Yii::$app->session->setFlash('error','发送失败！');
            }
        }
        
       
         
        $currentuserid= Yii::$app->user->getId();  //获取当前用户ID
                                          
        
                                 
        return $this->render('becomepublisher',['model'=>$model,'currentuserid'=>$currentuserid]);    
    }

    
    
    /**
     * 免费发布促销信息
     * @param mixed $id 
     * @return string
     */
    public function actionPublishinfofree()
    {
        $currentuserid= Yii::$app->user->getId();  //获取当前用户ID
        ///判断当前用户是否发送过了
       
         $userinfo=User::findOne(['id'=>$currentuserid]);
         $currenttime =date('y-m-d',time());
         if($userinfo)
         {
            $isfp =  $userinfo->isfreepublished;
            
            $fptime =  $userinfo->freepublishtime;
            
          
            
            if($fptime==null)
            {
               $userinfo->freepublishtime= $currenttime; //发布成功了才添加免费发布时间和次数
            }
            else{
                
                //获取上一次免费发布的年月
               $oldy = date('y', strtotime($fptime));
               $oldm= date('m', strtotime($fptime));
                
                //获取当前发布消息的年月
               
             $newy =  date('y', strtotime($currenttime));
               
             $newm =  date('m', strtotime($currenttime));
             
             
             if($oldy==$newy&&$oldm==$newm)
             {
                 if($isfp==1) ///如果当月以及发送了。
                 {
                     Yii::$app->session->setFlash('error','注意：每月只能免费发一次。');
                     
                     return $this->render('publishdeclare',['currentuserid'=>$currentuserid]);
                 }
  
             }
   
            }

         }

        ///结束判断
      
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
            $userid=Yii::$app->user->getId();  //获取当前用户；
            $model->publishpeople=$userid;
            $model->ispay='免费';
            $model->paynum=0;
            
            if( $model->validate()){
                if($model->save()){
                    
                    $userinfo->freepublishtime=$currenttime;
                    $userinfo->isfreepublished =1;
                    
                  $usresult =  $userinfo->save();
                  
                  if($usresult)
                  {
                      Yii::$app->response->redirect("/cxddc/mycuxiao/index");
                  }
                  else//免费发布失败
                  {
                      Activity::deleteAll(['id'=>$model->id]);
                      Yii::$app->session->setFlash('error','添加失败！');
                  }

                }else{
                    Yii::$app->session->setFlash('error','添加失败！');
                }
            }
        }
       
        
        return $this->render('publishinfofree',['model'=>$model,'to'=>$to,'currentuserid'=>$currentuserid]);
    }
    
    
    /**付费发布促销信息
     * @add
     */
    public function actionPublishinfopay(){

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
           
            
            if( $model->validate()){
                if($model->save()){
                    Yii::$app->response->redirect("/cxddc/mycuxiao/comfirmorder?id=$model->id");
                }else{
                    Yii::$app->session->setFlash('error','添加失败！');
                }
            }
        }
        
        $currentuserid= Yii::$app->user->getId();  //获取当前用户ID
        
        return $this->render('publishinfopay',['model'=>$model,'to'=>$to,'currentuserid'=>$currentuserid]);
    }
    

  
    

   
    
    
    
    
    
    
}
