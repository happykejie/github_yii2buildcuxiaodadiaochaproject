<?php
namespace app\modules\wenda\controllers;
use app\models\Follow;
use app\models\Msg;
use app\models\Askproblem;
use app\models\Search;
use app\models\Category;
use app\models\Answerquestion;
use app\models\Incomecost;
use app\models\User;
use app\models\Banner;

use yii\data\Pagination;
use Yii;
use yii\web\Controller;
use app\models\YiiUser;
use yii\web\session;
use app\models\UserForm;
use yii\filters\AccessControl;

use common\widgets\payment\Weixinjspi;
use common\widgets\payment\Notifyurl;
use yii\helpers\Url;
use yii\app;
use yii\web\Response;

require_once "models/WxJsSdk.php";
//error_reporting(E_ERROR);
require_once "/vendor/WxpayAPI/lib/WxPay.Api.php";
require_once "/vendor/WxpayAPI/example/WxPay.JsApiPay.php";
require_once '/vendor/WxpayAPI/example/log.php';

class WenDaController extends Controller{
    public $enableCsrfValidation = false;//yii默认表单csrf验证，如果post不带改参数会报错！
    public $layout  = 'layout';
    private $WX_APPID = WX_APPID; ///张杰开发测试账号wxf861f60fbb144cb9  //李朝先wxe474c6e60ea8f0c8
    private $WX_APPSECRET = WX_APPSECRET; //张杰开发测试账号2da66bd2cf0dccf0fb8d5db1e3ca6122  //李朝先33b1241f97a2803440b34bf30c33d57e
    private $_openid ,$_access_token,$_wxuser,$_user,$_users;
    private $timetamp;

    /**
     * accesscontrol
     */

    public function actionIndex(){
        

        if(CURR_DOMAIN=='boshizhidao.com')
        {
            $this->_user = YiiUser::findOne(['id'=>Yii::$app->user->getId()]);
            if( $this->_user ){
                //
                $banner=Banner::find()->orderBy('order asc')->all();
                //获取组别信息
                $category=Category::find()->orderBy('id asc')->all();
                
                $Sqlitem="select a.* from sm_askproblem  as a inner join sm_category as b on a.categoryid=b.id  where questionstate=1 and isfree=0 and isopenask=1 ";
                
                $search=new Askproblem();
                
                if ($search->load(Yii::$app->request->post())) {
                    if (strlen($search->questiondescription))
                    {
                        $Sqlitem= $Sqlitem.' and questiondescription like "%'.($search->questiondescription).'%" or b.categoryname like  "%'.($search->questiondescription).'%" ';
                    }
                }
                
                $Sqlitem=$Sqlitem." order by questionorder asc,asktime DESC";
                //获取所有问题信息
                $items=Askproblem::findBySql($Sqlitem)->all();
                //获取免费听问题的信息
                $askone=Askproblem::findOne(['isfree'=>1,'questionstate'=>1]);
                return $this->render('indexbszd',['items'=>$items,'askone'=>$askone,'category'=>$category,'banner'=>$banner,'search'=>$search]);
            }
            else
            {
                //返回登陆
                Yii::$app->response->redirect(Url::to(['/wenda/index'],true));
                return false;			
            }
        }
        
        
        else //默认她他好孕
        {
            $this->_user = YiiUser::findOne(['id'=>Yii::$app->user->getId()]);
            if( $this->_user ){
                
                $category=Category::find()->orderBy('id asc')->limit(4,4)->all();

                $items1=new Askproblem();
                $items2=new Askproblem();
                $items3=new Askproblem();
                $items4=new Askproblem();
                
                
                for ($i = 1; $i <= count($category); )
                {
                    $id=	$category[$i-1]->id;
                    $Sql="select * from sm_askproblem where questionstate=1 and isfree<>1 and isopenask=1  and categoryid=".$id;
                    
                    $Sql=$Sql.' order by questionorder asc,asktime DESC';
                    if ($i==1)
                    {
                        $items1=Askproblem::findBySql($Sql)->all();
                    }
                    
                    if ($i==2)
                    {
                        $items2=Askproblem::findBySql($Sql)->all();
                    }
                    
                    if ($i==3)
                    {
                        $items3=Askproblem::findBySql($Sql)->all();
                    }
                    
                    if($i==4)
                    
                    {
                        $items4=Askproblem::findBySql($Sql)->all();
                    }
                    $i++;
                }
                $Sqlitem="select * from sm_askproblem where questionstate=1 and isfree=0 and isopenask=1 order by questionorder asc,asktime DESC";
                $items=Askproblem::findBySql($Sqlitem)->all();
                
                $askone=Askproblem::findOne(['isfree'=>1,'questionstate'=>1]);
                
                return $this->render('index',['items'=>$items,'items1'=>$items1,'items2'=>$items2,'items3'=>$items3,'items4'=>$items4,'askone'=>$askone,'category'=>$category]);
                
            }
            
            else
            {
                
                //返回登陆
                Yii::$app->response->redirect(Url::to(['/wenda/index'],true));
                return;			
            }
        }
        

    }
    
    /**
     * @我是老师 详细页
     */

    public function actionTeacher(){
        return $this->render('teacher');
    }
    
    
    
    
    
    public function actionPaywenda($id=1)
    {
		
		
        $body =ORDERBODY;
        $fee=1;
        $goods_tag=ORDERTAG;
        $attach =ORDERATTACH;
        $tools = new \JsApiPay();
        
        $currentid =Yii::$app->user->getId();
        
        $this->_user = User::findOne(['id'=>Yii::$app->user->getId()]);
        if( $this->_user ){
            $this->_openid = $this->_user->openid;
        }
        //$this->_openid = 'oTBP7vhBl8BNsAY-F5DmE1wdRbDw';//测试使用用户 微信账号:khjl12345
        if(empty($this->_openid)){
            //①、获取用户openid
            $this->_openid = $tools->GetOpenid(Url::to(['/wenda/wenda/paywenda'],true));
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
        $input->SetNotify_url(Url::to(['/wenda/wenda/paynotify']));
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($this->_openid);

        $order = \WxPayApi::unifiedOrder($input);
        
        $jsApiParameters = $tools->GetJsApiParameters($order);

        //获取共享收货地址js函数参数
        $editAddress = $tools->GetEditAddressParameters();
        
        $items = new Askproblem();
        $item= $items::findOne(['id'=>$id]);
		
		
    return $this->render('paywenda',['item'=>$item,'order'=>$order,'jsApiParameters'=>$jsApiParameters,'editAddress'=>$editAddress]);
      /// return $this->render('paywenda');
        
        
    }
    
    //支付更新状态回调
    public function actionPaynotify()
    {
        return $this->render('paynotify');
    }
	
	
	  //回答问题luyin submint
    public function actionSubrecord()
    {
				
            $Answerquestion= new Answerquestion();
        if ($Answerquestion->load(Yii::$app->request->post()))
			{
				$id = $Answerquestion->askquestionid;
			
				$askproblem = Askproblem::findOne(["id"=>$id]);
                $askproblem->questionstate=1;
                $result = $askproblem->save();
				
				//回答完毕，消息模板提醒提问用户收听
				if($result)
				{
            		if(isset($askproblem))
            		{
						 $jssdk = new  \WxJsSdk(WX_APPID, WX_APPSECRET); 
						 $askuser = $askproblem->getUser();
						 $ansuser = $askproblem->getUseranswer();
						 
						 $touser =$askuser->openid;
            			 $questionid =$askproblem->id;
            			 $ansnickname=$ansuser->nickname;           			 
            			 $content =$askproblem->questiondescription;           			  
            			 $answerlist =$askproblem->getAnswerquestions();            			  
            			 $answertotallength =0;  			  
            			    foreach($answerlist as $v)
            				{
                             $oneanswerlength=$v->answertimelength;
            				 $answertotallength =$answertotallength+$oneanswerlength;
            				}
            			 $jssdk->sendtplmsgtoaskperson($touser,$questionid,$answertotallength,$ansnickname,$content);
					}
					$this->redirect(array('/wenda/wenda/paywenda','id'=>$id));
				}
				 else
				{
					$this->redirect(array('/wenda/wenda/paywenda','id'=>$id));
				}
		 }
   
    }

    //回答问题录音
    public function actionRecordings($id=1)
    {
   
		 
        $Answerquestion= new Answerquestion();
		
	
        if ($Answerquestion->load(Yii::$app->request->post()))
			{
				
				$id = $Answerquestion->askquestionid;
				
				$askproblem = Askproblem::findOne(["id"=>$id]);
                $askproblem->questionstate=1;
                $result = $askproblem->save();
                
                
                //回答完毕，把提问的钱汇入回答人账号：
               $currentuserid =  Yii::$app->user->getId();
                
                $incomecost=new  Incomecost();
                $incomecost->incomecostnum=$askproblem->askfee;
                $incomecost->dealtime=date("Y-m-d H:i:s", time());
                $incomecost->questionid=$askproblem->id;/// 获取问题ID
                $incomecost->incomecosttype=5;
                $incomecost->userid=$currentuserid;
                $incomecost->save();

				//回答完毕，消息模板提醒提问用户收听
				if($result)
				{
            		if(isset($askproblem))
            		{
						 $jssdk = new  \WxJsSdk(WX_APPID, WX_APPSECRET); 
						 $askuser = $askproblem->getUser();
						 $ansuser = $askproblem->getUseranswer();
						 
						 $touser =$askuser->openid;
            			 $questionid =$askproblem->id;
            			 $ansnickname=$ansuser->nickname;           			 
            			 $content =$askproblem->questiondescription;           			  
            			 $answerlist =$askproblem->getAnswerquestions();            			  
            			 $answertotallength =0;  			  
            			    foreach($answerlist as $v)
            				{
                             $oneanswerlength=$v->answertimelength;
            				 $answertotallength =$answertotallength+$oneanswerlength;
            				}
            			 $jssdk->sendtplmsgtoaskperson($touser,$questionid,$answertotallength,$ansnickname,$content);
					}
					$this->redirect(array('/wenda/wenda/paywenda','id'=>$id));
				}
				 else
				{
					$this->redirect(array('/wenda/wenda/paywenda','id'=>$id));
				}
		 }
		 else
		 {
             //获取组别信息
             $categorys=Category::find()->orderBy('id asc')->all();
             
             $to=array();
             foreach($categorys as $v){
                 $to[$v->id]=$v->categoryname;
             }
            
			$items =   new Askproblem();
			$item= $items::findOne(['id'=>$id]);
			return $this->render('recordings',['item'=>$item,'to'=>$to]);
		 }
        
       return null;
    }
    
    
    ///添加爱听
    public function actionAddlovelisten()
    {
		
		
        $questionid =  Yii::$app->request->post("questionid");
        $currentid =Yii::$app->user->getId();
        $t=time(); 
        $datetime = date("Y-m-d H:i:s",$t); 
        $lovelisten = new \app\models\LoveListenQuestion();
        $lovelisten->userid=$currentid;
        $lovelisten->questionid =$questionid;
        $lovelisten->buytime =$datetime;
       
        if($lovelisten->save())
        {
			$items =   new Askproblem();
			$item= $items::findOne(['id'=>$questionid]);
			 if (isset($item))
			{            
				//获取问题提问者Id
				if (strlen($item->askpersonid))
				{
					$incomecost=new  Incomecost();
					$incomecost->incomecostnum=0.5;
					$incomecost->dealtime=date("Y-m-d H:i:s", time());
					$incomecost->questionid=$questionid;
					$incomecost->incomecosttype=2;
					$incomecost->userid=$item->askpersonid;
					if($incomecost->save()){
						Yii::$app->session->setFlash('success','发送成功！');
					}else{
						Yii::$app->session->setFlash('error','发送失败！');
					}
				}
				
				//获取问题回答人的Id
				if (strlen($item->answerpersonid))
				{
					$incomecost=new  Incomecost();
					$incomecost->incomecostnum=0.5;
					$incomecost->dealtime=date("Y-m-d H:i:s", time());
					$incomecost->questionid=$questionid;
					$incomecost->incomecosttype=3;
					$incomecost->userid=$item->answerpersonid;
					if($incomecost->save()){
						Yii::$app->session->setFlash('success','发送成功！');
					}else{
						Yii::$app->session->setFlash('error','发送失败！');
					}
				}

				//获取爱听用户的ID
				if (strlen(Yii::$app->user->getId()))
				{
					$incomecost=new  Incomecost();
					$incomecost->dealtime=date("Y-m-d H:i:s", time());
					$incomecost->questionid=$questionid;
					$incomecost->incomecostnum=-1;
					$incomecost->incomecosttype=1;
					$incomecost->userid=Yii::$app->user->getId();
					
					if($incomecost->save()){
						Yii::$app->session->setFlash('success','发送成功！');
					}else{
						Yii::$app->session->setFlash('error','发送失败！');
					}
				}
            
        }
  
         return '1';
        }
        return "0";
    }
    
    
    public function actionSearch()
    {
        $userid= Yii::$app->user->getId();
        $user = new User();
        $askproblem= new Askproblem();
        $search = new Search();
        if ($search->load(Yii::$app->request->post())) {
            
            if(isset($search->nickname))
            {
                $Sql='select * from sm_user where userstate=1 and  nickname LIKE "%'.$search->nickname.'%" and id<>"'.$userid.'"';
                $user= $user::findBySql($Sql)->all();
                $askSql='select * from sm_askproblem where questionstate=1 and  questiondescription LIKE "%'.$search->nickname.'%"';
                $askproblem= $askproblem::findBySql($askSql)->all();
            }
            
        } 
        //进来的时候默认没有值
        else
        {
            $Sql='select * from sm_user where 1=0';
            $user= $user::findBySql($Sql)->all();
            $askSql='select * from sm_askproblem where 1=0';
            $askproblem= $askproblem::findBySql($askSql)->all();
        }
        
        
        return $this->render('search',['user'=>$user,'askproblem'=>$askproblem,'search'=>$search]);
    }
    

    public function addproblem($userid)
    {
        $model=new Askproblem();
        if($model->load(Yii::$app->request->post())//判断是否是表单提交
           && $model->validate() //验证表单提交的内容正确性
           ){
            
            if($model->save()){
                Yii::$app->session->setFlash('success','发送成功！');
            }else{
                Yii::$app->session->setFlash('error','发送失败！');
            }
            //echo "<pre/>";print_r(Yii::$app->request->post());die();
        }
        return $this->render('addht',['model'=>$model]);
    }
}
