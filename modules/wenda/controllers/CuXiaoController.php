<?php
namespace app\modules\wenda\controllers;
use app\models\Follow;
use app\models\Msg;
use app\models\Askproblem;
use app\models\Activity;

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
use yii\filters\pagecache;

require_once "models/WxJsSdk.php";
//error_reporting(E_ERROR);
require_once "/vendor/WxpayAPI/lib/WxPay.Api.php";
require_once "/vendor/WxpayAPI/example/WxPay.JsApiPay.php";
require_once '/vendor/WxpayAPI/example/log.php';



class CuXiaoController extends Controller{
    public $enableCsrfValidation = false;//yii默认表单csrf验证，如果post不带改参数会报错！
    public $layout  = 'layout';
    private $WX_APPID = WX_APPID; ///张杰开发测试账号wxf861f60fbb144cb9  //李朝先wxe474c6e60ea8f0c8
    private $WX_APPSECRET = WX_APPSECRET; //张杰开发测试账号2da66bd2cf0dccf0fb8d5db1e3ca6122  //李朝先33b1241f97a2803440b34bf30c33d57e
    private $_openid ,$_access_token,$_wxuser,$_user,$_users;
    private $timetamp;
    
    public $cityname;
    
    
    
    
    public function behaviors()
    {
        return [
            [
                'class' => 'yii\filters\PageCache',
                'only' => ['index'],
                'duration' => 60,
                'variations' => [
                    \Yii::$app->language,
                ],
                'dependency' => [
                    'class' => 'yii\caching\DbDependency',
                    'sql' => 'SELECT COUNT(*) FROM sm_activity',
                ],
            ],
        ];
    }
    
    

    /**
     * accesscontrol
     */

    public function actionIndex(){
        
        $value=Yii::$app->cache->get('citynamenew'); 
        if($value===false) ///没有获取到所属城市
        {
            return $this->renderPartial('loadad');
        }

     
            $this->_user = YiiUser::findOne(['id'=>Yii::$app->user->getId()]);
            if( $this->_user ){
                //
                $banner=Banner::find()->orderBy('order asc')->all();
                //获取组别信息
                $category=Category::find()->orderBy('id asc')->all();
                
                $Sqlitem="select a.* from sm_activity  as a inner join sm_category as b on a.group_id=b.id where a.belongarea ='$value'";
                
                $search=new Askproblem();
                
                if ($search->load(Yii::$app->request->post())) {
                    if (strlen($search->questiondescription))
                    {
                        $Sqlitem= $Sqlitem.' and intro like "%'.($search->questiondescription).'%" or b.categoryname like  "%'.($search->questiondescription).'%" ';
                    }
                }
                $Sqlitem=$Sqlitem." order by ordernum asc,createtime DESC";
                //获取所有问题信息
                $items=\app\models\Activity::findBySql($Sqlitem)->all();
                
               
                 
                
                return $this->render('index',['items'=>$items,'category'=>$category,'banner'=>$banner,'search'=>$search,'cityname'=>$value]);
            }
            else
            {
                //返回登陆
                Yii::$app->response->redirect(Url::to(['/wenda/index'],true));
                return false;			
            }
        
    }
    
    public function actionLoadad()
    {

      return $this->renderPartial('loadad');

    }
    
    
    /**
     * 通过百度地图获取到城市名称， 并设定城市缓存
     * @return string
     */
    public function  actionGetcityname($lng,$lat)
    {

        $q1="http://api.map.baidu.com/geocoder/v2/?ak=lmZLZ77R2a7dDznD114r5g813rXWhUSY&location=$lat,$lng&output=json&pois=1";

        $result1 = json_decode(file_get_contents($q1));

        $city = $result1->result->addressComponent->city;

        $this->cityname = $city;

         //表达式依赖
        //$dependency = new \yii\caching\ExpressionDependency(
        //    ['expression' => '\Yii::$app->request->get("cityname")']
        //);
        
        
         $bool1 = Yii::$app->cache->set('citynamenew',$this->cityname,7200);
         
         $value=Yii::$app->cache->get('citynamenew'); 

        if(isset($this->cityname))
        {

            return $city;      
        }
        
        return $this->renderPartial('loadad');
    }
    
    
   public  function getcitynamebyjw($lng,$lat)
    {

        $q1="http://api.map.baidu.com/geocoder/v2/?ak=lmZLZ77R2a7dDznD114r5g813rXWhUSY&location=30.548397,104.04701&output=json&pois=1";

        $result1 = json_decode(file_get_contents($q1));

        $city = $result1->result->addressComponent->city;


        return $city;
        

    }
    
    
    
    
    
    /**
     * @我是老师 详细页
     */

    public function actionTeacher(){
        return $this->render('teacher');
    }
    
    public function actionDetail($id=1)
    {
        $item = Activity::findOne(['id'=>$id]);
        
        if(isset($item)) 
        {
            
            $arryimg = $item->newspictures;
            
             
            
            return $this->render('detail',['item'=>$item,'arryimg'=>$arryimg]);
        }
        
       
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
