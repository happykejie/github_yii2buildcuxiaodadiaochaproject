<?php
namespace app\modules\wenda\controllers;
use app\models\Follow;
use app\models\Msg;
use Yii;
use yii\web\Controller;
use app\models\YiiUser;
use app\models\User;
use app\models\Askproblem;
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

class WxapiController extends Controller{
    
    public $enableCsrfValidation = false;//yii默认表单csrf验证，如果post不带改参数会报错！
    public $layout  = 'layout';
    private $WX_APPID = WX_APPID; ///张杰开发测试账号wxf861f60fbb144cb9  //李朝先wxe474c6e60ea8f0c8
    private $WX_APPSECRET =WX_APPSECRET; //张杰开发测试账号2da66bd2cf0dccf0fb8d5db1e3ca6122  //李朝先33b1241f97a2803440b34bf30c33d57e
    private $_openid ,$_access_token,$_wxuser,$_user,$_users;
    private $timetamp;
    
    //微信自动验证
    public function actionLogin($id = 1,$code=null){
        $this->_user = YiiUser::findOne(['id'=>Yii::$app->user->getId()]);
        if( $this->_user ){
            $this->_openid = $this->_user->openid;
        }
        if($code){
            return;
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
			
           // $this->_access_token =  $this->getWxAccessToken();
            
            $this->_wxuser = $this->getWxUserinfo();
            $this->_user = YiiUser::find()->where(['openid'=>$this->_openid])->one();
            if($this->_user )
            {
                //设置登录成功
                Yii::$app->user->login($this->_user,3600*24*1);
            }else{
                //未找到绑定用户自动注册并登陆
                $this->_user=new User();
                $this->_user->openid =  $this->_openid;
                $this->_user->user =  $this->_openid;
                $this->_user->nickname = $this->_wxuser['nickname'];
                //$this->_user->sex = $this->_wxuser['sex'];
                $this->_user->thumb = $this->_wxuser['headimgurl'];
                
                if($this->_user->save()){
                    //设置登录成功
                    Yii::$app->user->login($this->_user,3600*24*1);
                }else{
                    echo "登录失败";
                    die;
                }
            }
            //返回首页
            Yii::$app->response->redirect(Url::to(['/wenda/index'],true));
        }else{
            $returl='http://'.WWW.'/wenda/wxapi/login';//Url::to(['/wx/wxapi/login'],true);
            Yii::$app->response->redirect('https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->WX_APPID.'&redirect_uri='.$returl.'&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect');
        }
    }
	
	//begion 企业付款
	  function getRandChar($length){
        $str = null;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol)-1;

        for($i=0;$i<$length;$i++){
            $str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }

        return $str;
    }
    
    
    //数组转XML
    function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key=>$val)
        {
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml;
    }

    //将XML转为array
    function xmlToArray($xml)
    {    
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);        
        return $values;
    }  

    
    
    
    
   

    
    
    
    /**
     * 以post方式提交xml到对应的接口url
     * 
     * @param string $xml  需要post的xml数据
     * @param string $url  url
     * @param bool $useCert 是否需要证书，默认不需要
     * @param int $second   url执行超时时间，默认30s
     * @throws WxPayException
     */
	public  function postXmlCurl($xml, $url, $useCert = false, $second = 30)
	{		
		$ch = curl_init();
		//设置超时
		curl_setopt($ch, CURLOPT_TIMEOUT, $second);
		
		//如果有配置代理这里就设置代理
		if(CURL_PROXY_HOST != "0.0.0.0" 
			&& CURL_PROXY_PORT != 0){
			curl_setopt($ch,CURLOPT_PROXY, CURL_PROXY_HOST);
			curl_setopt($ch,CURLOPT_PROXYPORT, CURL_PROXY_PORT);
		}
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);//严格校验
        curl_setopt($ch,CURLOPT_SSLCERT,getcwd().SSLCERT_PATHALL);
        curl_setopt($ch,CURLOPT_SSLKEY,getcwd().SSLKEY_PATHALL);
        curl_setopt($ch,CURLOPT_CAINFO,getcwd().SSLROOTCAALL); 
		//设置header
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		//要求结果为字符串且输出到屏幕上
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        //if($useCert == true){
        //    //设置证书
        //    //使用证书：cert 与 key 分别属于两个.pem文件
        //    curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
        //    curl_setopt($ch,CURLOPT_SSLCERT, \WxPayConfig::SSLCERT_PATH);
        //    curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
        //    curl_setopt($ch,CURLOPT_SSLKEY, \WxPayConfig::SSLKEY_PATH);
        //}
		//post提交方式
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
		//运行curl
		$data = curl_exec($ch);
		//返回结果
		if($data){
			curl_close($ch);
			return $data;
		} else { 
			$error = curl_errno($ch);
            echo $url .":". $error;
			curl_close($ch);
            return false;
			//throw new WxPayException("curl出错，错误码:$error");
		}
	}
	
	
	
	
	
	//endbegion 企业付款
    
    
    
    
    /*开发发起退款业务**/
    
    ///发起退款业务：当提问人提问超过3天没有回答。就发起退款流程。
    
    
    public  function actionPayrefund()
    {

        ///查询哪些超过三天没有回答的，如果没有回答，就发起退款服务。
        $Sql="SELECT a.* FROM sm_askproblem as a  where a.asktime <  date_sub(curdate(),interval 3 day) and a.questionstate=0";

        $askproblems=Askproblem::findBySql($Sql);
        $items=$askproblems->all();
        
        foreach($items as $v){
           
            $this->refundtrade($v->trade_no,$v->askfee);
        }

    }
    
    
    
   public function refundtrade($tradeno,$askfee)
   {
       $trade_no = $tradeno;
       
       $fee=(int)($askfee*100);
       
       $nonce_str =$this->getRandChar(32);

       $stringA="appid=".APPID."&mch_id=".MCHID."&nonce_str=".$nonce_str."&op_user_id=".MCHID."&out_refund_no=".$trade_no."&out_trade_no=".$trade_no."&refund_fee=".$fee."&total_fee=".$fee.""; 
       
       $stringSignTemp=$stringA."&key=".KEY; 
       $signstr = md5($stringSignTemp);
       
       $sign = strtoupper($signstr);
       
       $sendmgs=array(
         'appid'=>APPID, //公众账号appid
        'mch_id'=>MCHID, //商户号
        'nonce_str'=>$nonce_str, //随机字符串
        'op_user_id'=> MCHID,  ///操作员
        'out_refund_no'=>$trade_no, //商户退款单号
        'out_trade_no'=>$trade_no, //商户订单号
        'refund_fee'=>$fee, //退款金额
        'total_fee'=>$fee, //订单总金额
      
        'sign'=>$sign,

        );

       $url="https://api.mch.weixin.qq.com/secapi/pay/refund";
       $xmldata =  $this->arrayToXml($sendmgs);
       $test =  $this->postXmlCurl($xmldata,$url,true);
       return $test;
   }
    
    
    
   
    
    
	

	////end  结束退款业务
    
    
	///回答问题后ajax 提交到这里改变问题状态为1， 并通知提问用户已经回答问题
	public function actionSubrecord()
	{
				$id = Yii::$app->request->post("id");
                $categoryid =   Yii::$app->request->post("categoryid");

				$askproblem = Askproblem::findOne(["id"=>$id]);
                $askproblem->questionstate=1;
                $askproblem->categoryid =$categoryid;
                $result = $askproblem->save();

                //回答完毕，把提问的钱汇入回答人账号：
                $currentuserid =  Yii::$app->user->getId();
                
                $incomecost= new \app\models\Incomecost();
                $incomecost->incomecostnum=$askproblem->askfee;
                $incomecost->dealtime=date("Y-m-d H:i:s", time());
                $incomecost->questionid=$askproblem->id;/// 获取问题ID
                $incomecost->incomecosttype=5;
                $incomecost->userid=$currentuserid;
                $incomecost->trade_no =$askproblem->trade_no;
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
					
					return true;
				}
				 else
				{
					 return false;
				}
		 
	
	}
    
    
    
	
    
    ///上传微信录音音频到服务器
    public function actionUploadvoice()
    {
		$serverId = Yii::$app->request->post("serverId");
		$askid= Yii::$app->request->post("askid");
		$alength= Yii::$app->request->post("alength");
		 
		 $jssdk = new  \WxJsSdk(WX_APPID, WX_APPSECRET);

		  $access_token =  $jssdk->getAccessTokenfile();
		  
		  
		try 
		{
	
		  //下载音频
		   $mediaid = $serverId;
			$url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=$access_token&media_id=$mediaid";
			$fileInfo =$this->downloadWeixinFile($url);
			  $resultdata =  $fileInfo["body"]; 
			$filename = BASE_PATH.'mediafile/'.$mediaid.'down_voice.amr';
			$realfilename = $mediaid.'down_voice.amr';

			$result = $this->saveWeixinFile($filename, $fileInfo["body"]);
		   //暂时不下载音频，音频转码遇到问题，后面处理。
		   
		   /// ffempeg 转码
		   
			$cmd="C:/ffmpeg/bin/ffmpeg.exe -i ".BASE_PATH."mediafile/".$realfilename." ".BASE_PATH."mediafile/".$mediaid.".MP3";  
			system($cmd);
		} 
		catch(Exception $e) 
			{ 
				 $testdata =[];
		
				return \yii\helpers\Json::encode($testdata); 
			}

			  ///录制三条语音解决方案
        $answercontent =$mediaid.".MP3";
         $Answerquestion=  new \app\models\Answerquestion();
        
        $Answerquestion->answercontent=$answercontent;
			$Answerquestion->answertimelength=$alength;
            $t=time(); 
            $datetime = date("Y-m-d H:i:s",$t); 
            $Answerquestion->answertime=$datetime;
            $Answerquestion->askquestionid=$askid;
			 $Answerquestion->answerpeosonid=Yii::$app->user->getId();
            $result =  $Answerquestion->save();
            
            if($result)
            {
				 Yii::$app->response->format = Response::FORMAT_JSON;
				 
               /// return true;
				   return \yii\helpers\Json::encode($Answerquestion); 
                
                //重新调用刷新recordings 页面
      
            }
            else
            {
                $testdata =[];
		
				return \yii\helpers\Json::encode($testdata); 
            }
    }
    

    
    function downloadWeixinFile($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);    
        curl_setopt($ch, CURLOPT_NOBODY, 0);    //只取body头
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $package = curl_exec($ch);
        $httpinfo = curl_getinfo($ch);
        curl_close($ch);
        $imageAll = array_merge(array('header' => $httpinfo), array('body' => $package)); 
        return $imageAll;
    }
    
    function saveWeixinFile($filename, $filecontent)
    {
		try
		{
				 $local_file = fopen($filename, 'w');
			if (false !== $local_file){
				if (false !== fwrite($local_file, $filecontent)) {
					fclose($local_file);
				}
			}
			return  $filename;
		}
		catch(Exception $e) 
			{ 
				 return $e; 
			}
		
       
    }
    

   
    
    
    /*
     * 获取微信jsapi_ticket
     */ 
    public  function getJsapi_ticket()
    {
		
		 $jssdk = new  \WxJsSdk(WX_APPID, WX_APPSECRET);

		  $a_token =  $jssdk->getAccessTokenfile();
		
        $id ='js_ticket';
        $value=Yii::$app->cache->get($id);  
        if($value===false)  
        {  
            $jsticket = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=$a_token&type=jsapi";
            $json_jsticket = $this->getJson($jsticket); 
            if($json_jsticket["errmsg"]=='ok')
            {
                $jstiket =$json_jsticket["ticket"];
                Yii::$app->cache->set($id, $jstiket, 7200);  
  
                $this->$value =$jstiket;
            }
        } 
        
        return $value;
        
    }

    //支付更新状态回调
    public function actionPaynotify()
    {
        return $this->render('paynotify');
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
    //获取token
    function getWxAccessToken()
	{
		$appid = $this->WX_APPID;  
		$secret = $this->WX_APPSECRET; 
        //第二步:取得access_token
        $oauth2Url = "https://api.weixin.qq.com/cgi-bin/token?appid=$appid&secret=$secret&grant_type=client_credential";
		$oauth2 = $this->getJson($oauth2Url); 
        return $oauth2["access_token"]; 
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
    
}
