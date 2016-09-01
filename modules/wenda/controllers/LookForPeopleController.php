<?php
namespace app\modules\wenda\controllers;
use app\models\Follow;
use app\models\User;
use app\models\Askproblem;
use app\models\Userattention;
use app\models\Category;
use app\models\Incomecost;
use app\models\Msg;
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

use app\models\wxJsSdk;



require_once "models/WxJsSdk.php";


//error_reporting(E_ERROR);
require_once "/vendor/WxpayAPI/lib/WxPay.Api.php";
require_once "/vendor/WxpayAPI/example/WxPay.JsApiPay.php";
require_once '/vendor/WxpayAPI/example/log.php';

class LookForPeopleController extends Controller{
    public $enableCsrfValidation = false;//yii默认表单csrf验证，如果post不带改参数会报错！
    public $layout  = 'layout';
    private $WX_APPID = WX_APPID; ///张杰开发测试账号wxf861f60fbb144cb9  //李朝先wxe474c6e60ea8f0c8
    private $WX_APPSECRET = WX_APPSECRET; //张杰开发测试账号2da66bd2cf0dccf0fb8d5db1e3ca6122  //李朝先33b1241f97a2803440b34bf30c33d57e
    private $_openid ,$_access_token,$_wxuser,$_user,$_users;
    private $timetamp,$countnum;
	private	$getteacherid;
	
	
	private $jinrunum =1;

    /**
     * accesscontrol
     */

    /**
     * @用户授权规则
     */
    //public function behaviors()
    //{
    //    return [
    //        'access' => [
    //            'class' => AccessControl::className(),
    //            'rules' => [
    //                [
    //                    'actions' => ['login','captcha'],
    //                    'allow' => true,
    //                    'roles' => ['?'],
    //                ],
    //                [
    //                    //'actions' => ['logout','edit','add','del','index','users','thumb','upload','cutpic','follow','nofollow'],
    //                    'allow' => true,
    //                    'roles' => ['@'],
    //                ],
    //            ],
    //        ],
    //    ];
    //}
	
	
    //微信自动验证
	
    public function actionIndex($id = -1,$code=null){


		if($id!=-1)
        {

			$cookteacherid ='teacherid';			
			$value=Yii::$app->cache->get($cookteacherid);         
            Yii::$app->cache->set($cookteacherid, $id, 7000);  

        }
		$cookteacherid ='teacherid';
        
		$value=Yii::$app->cache->get($cookteacherid);
		
		$this->getteacherid =$value;
        
        
        //返回首页
        // yii::$app->response->redirect(url::to(['/wenda/wenda/index'],true));
        // return;
        if(CURR_DOMAIN=='boshizhidao.com')
        {
            $urlexpert = "/wenda/lookforpeoplebszd/expert?id=".$this->getteacherid;
        }
        else
        {
            $urlexpert = "/wenda/lookforpeople/expert?id=".$this->getteacherid;
        }
        
		
        $this->_user = YiiUser::findOne(['id'=>Yii::$app->user->getId()]);
        if( $this->_user ){
            $this->_openid = $this->_user->openid;
            //返回提问老师页面
            // return $urlexpert;
            Yii::$app->response->redirect(Url::to([$urlexpert],true));
            return;
        }
        if($code){
            //return;
            if(!$this->_openid)
            {
                $this->_openid = $this->getWxUserOpenId($code);
            }
            //$this->_openid = "oTBP7vhBl8BNsAY-F5DmE1wdRbDw";
            if(empty($this->_openid)){
                die("No openid there! Can't add");
            } 
            $jssdk = new  \WxJsSdk(WX_APPID, WX_APPSECRET);

            $this->_access_token =  $jssdk->getAccessTokenfile();

            $this->_wxuser = $this->getWxUserinfo();
            $this->_user = YiiUser::find()->where(['openid'=>$this->_openid])->one();
            if($this->_user )
            {
                //设置登录成功
                Yii::$app->user->login($this->_user,3600*24*1);

            }else{
                //未找到绑定用户自动注册并登陆
                $this->_user=new YiiUser();
                $this->_user->openid =  $this->_openid;
                $this->_user->user =  $this->_openid;
                $this->_user->nickname = $this->_wxuser['nickname'];
                $this->_user->sex = $this->_wxuser['sex'];
                $this->_user->thumb = $this->_wxuser['headimgurl'];
                $this->_user->sex = $this->_wxuser['sex'];
                $this->_user->headimgurl = $this->_wxuser['headimgurl'];
                $this->_user->city = $this->_wxuser['city'];
                $this->_user->country = $this->_wxuser['country'];
                $this->_user->remark = $this->_wxuser['remark'];
				$this->_user->userstate =0;
                
                if($this->_user->save()){
                    //设置登录成功
                    Yii::$app->user->login($this->_user,3600*24*1);
					
					
					
					
                }else{
                    echo "登录失败";
                    die;
                }
            }
			
            //return $urlexpert;
            //返回首页
            Yii::$app->response->redirect(Url::to([$urlexpert],true));
        }else{
         
            $returl="http://".WWW."/wenda/lookforpeople/index";//Url::to(['/wx/wxapi/login'],true);
           
            Yii::$app->response->redirect('https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->WX_APPID.'&redirect_uri='.$returl.'&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect');
        }
    }

    //private Oauth 用户登录方法 
    //获取openid
    function getWxUserOpenId($code)
	{
		$appid =$this->WX_APPID;  
		$secret = $this->WX_APPSECRET;  
		//第一步:取得openid
		$oauth2Url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$secret&code=$code&grant_type=authorization_code";
		$oauth2 = $this->getJson($oauth2Url); 
        if(isset($oauth2['openid'])){
            return $oauth2['openid'];  
        }
        return null;
	}

    //获取用户信息
    function getWxUserinfo(){
        $get_user_info_url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$this->_access_token."&openid=".$this->_openid."&lang=zh_CN";
		$wxuserinfo =$this->getJson($get_user_info_url);

        return $wxuserinfo;
    }
    
    //字符串转对象
	function getJson($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		curl_close($ch);
		return json_decode($output, true);
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


    
    ///支付提问过后，添加新问题
    public function actionAddaskproblem($answerpersonid,$trade_no,$problemdescription,$problemprice,$isopenask)
    {

        $currentid =Yii::$app->user->getId();
        $t=time();
        $datetime = date("Y-m-d H:i:s",$t);
        $data = new Askproblem();
        $data->questiondescription=$problemdescription;
        $data->answerpersonid =$answerpersonid;
        $data->askpersonid =$currentid;
        $data->asktime =$datetime;
        $data->askfee =$problemprice;
        $data->isopenask = $isopenask;
        $data->trade_no =$trade_no;
        $result =  $data->save();
        if($result)
        {
            
            // 提问过后添加提问人支出记录（注意回答人要回答了问题才能获取改问题的金额进入自己的账号）

                $incomecost=new  Incomecost();
                $incomecost->incomecostnum=-$problemprice;
                $incomecost->dealtime=date("Y-m-d H:i:s", time());
                $incomecost->questionid=$data->id;/// 获取问题ID
                $incomecost->incomecosttype=4;
                $incomecost->userid=$currentid;
                $incomecost->trade_no=$data->trade_no;
                if($incomecost->save()){
                    Yii::$app->session->setFlash('success','发送成功！');
                }else{
                    Yii::$app->session->setFlash('error','发送失败！');
                }
            
           
            
            
            return $data->id;
        
        }
        return "0";
    }


	///ti wen success
    public function actionLookaskprobleminfo($id)
    {
        
        $item=Askproblem::findOne(['id'=>$id]);
      
        
		
        return $this->render('lookaskprobleminfo',['item'=>$item]);
        
    }
	
	///通知老师已经有人向他提问待回答
	public function actionSendmsgtoteacher($questionid)
	{
		$item=Askproblem::findOne(['id'=>$questionid]);
		if(isset($item))
		{
            
            $jssdk = new  \WxJsSdk(WX_APPID, WX_APPSECRET); 
            
            $askuser = $item->getUser();
            
            $ansuser = $item->getUseranswer();

            $touser =$ansuser->openid;
            $questionid =$item->id;
            $asknickname=$askuser->nickname;
            
            $content =$item->questiondescription;

            $jssdk->sendtplmsgtoteacher($touser,$questionid,$asknickname,$content);
			
			
			return "true";
			
			
        }
        
        else
        {
            return "error";
        }
	}
	
	
    /**
     * @根据用户Id 提问，判断是否登录
     */
    
    
    public function  actionAuthexpert($id = 1,$code=null)
    {
        //返回首页
        // yii::$app->response->redirect(url::to(['/wenda/wenda/index'],true));
        // return;
        
        if(CURR_DOMAIN=='boshizhidao.com')
        {
            $urlexpert = "/wenda/lookforpeoplebszd/expert?id=".$id;
        }
        else
        {
            $urlexpert = "/wenda/lookforpeople/expert?id=".$id;
        }
        
       
		$urlexpertwww ="http://".WWW."/wenda/lookforpeople/expert?id=".$id;	
        $this->_user = YiiUser::findOne(['id'=>Yii::$app->user->getId()]);
        if( $this->_user ){
            $this->_openid = $this->_user->openid;
            //返回提问老师页面
            
            if(CURR_DOMAIN=='boshizhidao.com')
            {
                $urlexpert = "/wenda/lookforpeoplebszd/expert?id=".$this->_user->id;
            }
            else
            {
                $urlexpert = "/wenda/lookforpeople/expert?id=".$this->_user->id;
            }
            
           
            Yii::$app->response->redirect(Url::to([$urlexpert],true));
            return;
        }
        if($code){
            //return;
            if(!$this->_openid)
            {
                $this->_openid = $this->getWxUserOpenId($code);
            }
            //$this->_openid = "oTBP7vhBl8BNsAY-F5DmE1wdRbDw";
            if(empty($this->_openid)){
                die("No openid there! Can't add");
            } 
            $jssdk = new  \WxJsSdk(WX_APPID, WX_APPSECRET);
            
            
            $this->_access_token =  $jssdk->getAccessTokenfile();
            //$this->_access_token =  $this->getWxAccessToken();

            $this->_wxuser = $this->getWxUserinfo();
            $this->_user = YiiUser::find()->where(['openid'=>$this->_openid])->one();
            if($this->_user )
            {
                //设置登录成功
                Yii::$app->user->login($this->_user,3600*24*1);
                if(CURR_DOMAIN=='boshizhidao.com')
                {
                    $urlexpert = "/wenda/lookforpeoplebszd/expert?id=".$this->_user->id;
                }
                else
                {
                    $urlexpert = "/wenda/lookforpeople/expert?id=".$this->_user->id;
                }
            }else{
                //未找到绑定用户自动注册并登陆
                $this->_user=new YiiUser();
                $this->_user->openid =  $this->_openid;
                $this->_user->user =  $this->_openid;
                $this->_user->nickname = $this->_wxuser['nickname'];
                //$this->_user->sex = $this->_wxuser['sex'];
                $this->_user->thumb = $this->_wxuser['headimgurl'];
                $this->_user->sex = $this->_wxuser['sex'];
                $this->_user->headimgurl = $this->_wxuser['headimgurl'];
                $this->_user->city = $this->_wxuser['city'];
                $this->_user->country = $this->_wxuser['country'];
                $this->_user->remark = $this->_wxuser['remark'];
				$this->_user->userstate =0;
                
                if($this->_user->save()){
                    //设置登录成功
                    Yii::$app->user->login($this->_user,3600*24*1);
                    if(CURR_DOMAIN=='boshizhidao.com')
                    {
                        $urlexpert = "/wenda/lookforpeoplebszd/expert?id=".$this->_user->id;
                    }
                    else
                    {
                        $urlexpert = "/wenda/lookforpeople/expert?id=".$this->_user->id;
                    }	

                }else{
                    echo "登录失败";
                    die;
                }
            }
			
			
            //返回首页
            Yii::$app->response->redirect(Url::to([$urlexpert],true));
        }else{
           
            
            $returl="http://".WWW."/wenda/lookforpeople/index";//Url::to(['/wx/wxapi/login'],true);
            
          
            Yii::$app->response->redirect('https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->WX_APPID.'&redirect_uri='.$returl.'&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect');
        }
    }
    


    /**
     * @根据用户Id 获取专家资料
     */

    public function actionExpert($id=1,$code=null)
	{
        $userid=Yii::$app->user->getId();
        if ($id!=$userid)
		{
            $expertitem =User::findOne(['userstate'=>1,'id'=>$id]);
            $body =ORDERBODY;
            $fee=(int)($expertitem->questionprice*100);
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
				$this->_openid = $tools->GetOpenid(Url::to(['/wenda/index'],true));
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
			
			$trade_no = $input->GetOut_trade_no();

			$jsApiParameters = $tools->GetJsApiParameters($order);

			//获取共享收货地址js函数参数
			$editAddress = $tools->GetEditAddressParameters();

			
            //获取当前登录用户的ID
            $userid=Yii::$app->user->getId();
            $userattention=new Userattention();
            $ttention=new Userattention();
            
            if($userattention->load(Yii::$app->request->post())//判断是否是表单提交
    ){
                //根据当前用户ID和老师的Id查询是否有关注数据
                $ttention=$ttention::findOne(["userid"=>$userid,'attentionuserid'=>$id]);
                //如果如果查询的数据得到非空 表示已经被关注了，那么删除该条关注信息
                if (isset($ttention))
                {
                    $userattention::deleteAll(['id'=>$ttention->id]);
                }else
                {
                    $userattention->userid=$userid;
                    $userattention->attentiontime=date("Y-m-d H:i:s", time());
                    $userattention->attentionuserid=$id;
                    if($userattention->save()){
                        Yii::$app->session->setFlash('success','发送成功！');
                    }else{
                        Yii::$app->session->setFlash('error','发送失败！');
                    }
                }
            }


			$userid=Yii::$app->user->getId();
			$userattention=$userattention::findOne(['userid'=>$userid,'attentionuserid'=>$id]);
			if (!isset($userattention))
			{
				$userattention=new Userattention();
				$userattention->id=0;
			}


			$items=new Askproblem();
			$model =User::findOne(['userstate'=>1,'id'=>$id]);
			if($items->load(Yii::$app->request->post())//判断是否是表单提交-提问内容提交
			  && $items->validate() //验证表单提交的内容正确性
			  ){
				$items->answerpersonid=$id;


				$items->askpersonid= Yii::$app->user->getId();
				$items->asktime=date("Y-m-d H:i:s", time());
				if (isset($model->questionprice))
				{
					$items->askfee=$model->questionprice;
				}else
				{
					$items->askfee=0;
				}

				if($items->save()){
					Yii::$app->session->setFlash('success','发送成功！');
				}else{
					Yii::$app->session->setFlash('error','发送失败！');
				}
			}

			if (isset($model)) //如果问题提问成功 则继续在老师详细页
			{
				return $this->render('expert',['model'=>$model,'items'=>$items,'userattention'=>$userattention,'order'=>$order,'trade_no'=>$trade_no,'jsApiParameters'=>$jsApiParameters,'editAddress'=>$editAddress]);
			}
			else //问题提问失败 则回到找人界面上
			{
				$players =User::find();
				$count=$players->count();
				$page=new Pagination(['defaultPageSize'=>5,'totalCount'=>$count]);
				$items=$players->where(['userstate'=>1])->orderBy('id desc')->offset($page->offset)->limit($page->limit)->all();
				return $this->render('lookforpeople',['page'=>$page,'items'=>$items,'userattention'=>$userattention,'order'=>$order,'trade_no'=>$trade_no,'jsApiParameters'=>$jsApiParameters,'editAddress'=>$editAddress]);
			}
            
            
        }
		else
        {
            
            $category=Category::find()->orderBy('id asc')->limit(4,4)->all();

            $items1=new Askproblem();
            $items2=new Askproblem();
            $items3=new Askproblem();
            $items4=new Askproblem();
            
            
            for ($i = 1; $i <= count($category); )
            {
                $id=	$category[$i-1]->id;
                $Sql="select * from sm_askproblem where questionstate=1 and askfee>0 and isopenask=1  and categoryid=".$id;
                
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
            $Sqlitem="select * from sm_askproblem where questionstate=1 and askfee>0 and isopenask=1 order by questionorder asc,asktime DESC";
            $items=Askproblem::findBySql($Sqlitem)->all();
            
            $askone=Askproblem::findOne(["askfee"=>0,'questionstate'=>1]);
			
            

            return $this->redirect('/wenda/wenda/index',['items'=>$items,'items1'=>$items1,'items2'=>$items2,'items3'=>$items3,'items4'=>$items4,'askone'=>$askone,'category'=>$category]);
        }
    }
    /**
     * @找人—显示所有老师
     */

    public function actionLookforpeople(){

        //获取当前登录用户的ID
        $userid=Yii::$app->user->getId();
        $userattention=new Userattention();
        $ttention=new Userattention();
        //获取组别信息
        $category=Category::find()->orderBy('id asc')->all();
        if($userattention->load(Yii::$app->request->post())//判断是否是表单提交
){
            //根据当前用户ID和老师的Id查询是否有关注数据
            $ttention=$ttention::findOne(["userid"=>$userid,'attentionuserid'=>$userattention->attentionuserid]);
            //如果如果查询的数据得到非空 表示已经被关注了，那么删除该条关注信息
            if (isset($ttention))
            {
                $userattention::deleteAll(['id'=>$ttention->id]);
            }else
            {
                $userattention->userid=$userid;
                $userattention->attentiontime=date("Y-m-d H:i:s", time());

                if($userattention->save()){
                    Yii::$app->session->setFlash('success','发送成功！');
                }else{
                    Yii::$app->session->setFlash('error','发送失败！');
                }
            }
        }

        $Sql='SELECT * FROM sm_user  where  userstate =1 and id <> '.$userid.' ';
        $search=new User();
        
        if ($search->load(Yii::$app->request->post())) {
            if (strlen($search->nickname))
            {
                $Sql= $Sql.' and nickname like "%'.($search->nickname).'%" or title like "%'.($search->nickname).'%" or description like "%'.($search->nickname).'%"';
            }
        }
        
        
        $Sql=$Sql.' ORDER BY id in(SELECT attentionuserid from sm_userattention WHERE userid='.$userid.') DESC';

        $items =User::findBySql($Sql)->all();
        
        
        if(CURR_DOMAIN=='boshizhidao.com')
        {
            return $this->render('lookforpeoplebszd',['items'=>$items,'search'=>$search,'category'=>$category]);
        }
        else  ///默认是她他好孕
        {
            return $this->render('lookforpeople',['items'=>$items,'search'=>$search]);
        }
        
     
    }

}
