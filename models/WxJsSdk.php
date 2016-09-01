<?php


require_once "/vendor/WxpayAPI/lib/WxPay.Api.php";
require_once "/vendor/WxpayAPI/example/WxPay.JsApiPay.php";
require_once '/vendor/WxpayAPI/example/log.php';

class WxJsSdk  {
    
    private $appId;
    private $appSecret;
    public function __construct($appId, $appSecret) {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
    }

    public function getSignPackage() {
        $jsapiTicket = $this->getJsApiTicket();
        $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $timestamp = time();
        $nonceStr = $this->createNonceStr();

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
        $signature = sha1($string);

        $signPackage = array(
          "appId"     => $this->appId,
          "nonceStr"  => $nonceStr,
          "timestamp" => $timestamp,
          "url"       => $url,
          "signature" => $signature,
          "rawString" => $string
        );
        return $signPackage; 

    }
	
	
	//构造一个请求函数
    function http_request($url,$data=array()){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        // 我们在POST数据哦！
        curl_setopt($ch, CURLOPT_POST, 1);
        // 把post的变量加上
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
	

	
	
	
    
    
    ///xmldata send
          function xmlCurlPost($xmlData, $url, $timeoutMs=30000)
        {
            $ch = curl_init();
            $header[] = "Content-type: text/xml";//定义content-type为xml
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlData);
            curl_setopt($ch, CURLOPT_NOSIGNAL, 1);    //注意，毫秒超时一定要设置这个
            curl_setopt($ch, CURLOPT_TIMEOUT_MS, $timeoutMs);  //超时毫秒，cURL 7.16.2中被加入。从PHP 5.2.3起可使用
            $sContent = curl_exec($ch);
            $aStatus = curl_getinfo($ch);
            curl_close($ch);
            if(intval($aStatus["http_code"])==200){
                return trim($sContent);
            }else{
                return false;
            }
        }
          
          
          

       

    
    
   
	
	
	
	//有人老师审核与否通知用户消息模板
    public function sendapproveresulttouser($touser,$issuccess,$reason){
		
		
        
        if($issuccess)
        {
            $first = "你好,你在".DOMAITDESC."上申请成为老师已经通过";
            $keyword1 = DOMAITDESC;
            $t=time(); 
            $datetime = date("Y-m-d H:i:s",$t); 
            $keyword2 =$datetime;
            $keyword3 ="尊敬的用户，感谢你使用".DOMAITDESC."平台。你申请成为老师已经通过审核";
        }
        
        else
        {
            $first = "你好,你在".DOMAITDESC."上申请成为老师未通过";
            $keyword1 = "".DOMAITDESC."";
            $t=time(); 
            $datetime = date("Y-m-d H:i:s",$t); 
            $keyword2 =$datetime;           
            $keyword3 ="尊敬的用户，感谢你使用".DOMAITDESC."平台。你申请成为老师未通过审核，具体原因：".$reason;
        }
        
		
        
		$url = HTTPWWW;

        //模板消息	
        $template=array(
       'touser'=>$touser,//提问人
       'template_id'=>SDHFTZ,  //收到回复通知模板ID
       'url'=>$url,
       'topcolor'=>"#7B68EE",
       'data'=>array(
       'first'=>array('value'=>urlencode($first),'color'=>"#743A3A"),
       'keyword1'=>array('value'=>urlencode($keyword1),'color'=>'#743A3A'),
       'keyword2'=>array('value'=>urlencode($keyword2),'color'=>'#743A3A'),
       'keyword3'=>array('value'=>urlencode($keyword3),'color'=>'#743A3A'),

       'remark'=>array('value'=>urlencode('此消息有'.DOMAITDESC.'提供'),'color'=>'#743A3A'),
               )
               );
        //  $json_template=json_encode($template);
		
        
        $accessToken = $this->getAccessTokenfile();
        $url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$accessToken;
		
		
        $res= $this->http_request($url,urldecode(json_encode($template)));
		
        
		
	    return $res;
		


    }
    
    
    
    
    
    //提现审核不通过，通知用户原因
    public function sendTiXianNoPass($touser,$reason){

            $first = "你好,你在".DOMAITDESC."上申请提现未通过";
            $keyword1 = "".DOMAITDESC."";
            $t=time(); 
            $datetime = date("Y-m-d H:i:s",$t); 
            $keyword2 =$datetime;           
            $keyword3 ="尊敬的用户，感谢你使用".DOMAITDESC."平台。你申请提现未通过审核，具体原因：".$reason;

		$url = HTTPWWW;

        //模板消息	
        $template=array(
       'touser'=>$touser,//提问人
       'template_id'=>YHTWTZ,  // 用户提问通知
       'url'=>$url,
       'topcolor'=>"#7B68EE",
       'data'=>array(
       'first'=>array('value'=>urlencode($first),'color'=>"#743A3A"),
       'keyword1'=>array('value'=>urlencode($keyword1),'color'=>'#743A3A'),
       'keyword2'=>array('value'=>urlencode($keyword2),'color'=>'#743A3A'),
       'keyword3'=>array('value'=>urlencode($keyword3),'color'=>'#743A3A'),

       'remark'=>array('value'=>urlencode('此消息有'.DOMAITDESC.'提供'),'color'=>'#743A3A'),
               )
               );
        //  $json_template=json_encode($template);

        $accessToken = $this->getAccessTokenfile();
        $url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$accessToken;

        $res= $this->http_request($url,urldecode(json_encode($template)));

	    return $res;
		


    }
	
	

	

	
	//有人提问通知老师回答
    public function sendtplmsgtoteacher($touser,$questionid,$asknickname,$content){
		
		
		$accessToken = $this->getAccessTokenfile();
		$first = "仰慕您的:".$asknickname."向您提出问题";
		$keyword1 =$content;
		$keyword2 ="公开";
		$t=time(); 
        $datetime = date("Y-m-d H:i:s",$t); 
		$keyword3 =$datetime;
		$url = 'http://'.WWW.'/wenda/wenda/recordings?id='.$questionid;

        //模板消息	
        $template=array(
       'touser'=>$touser,//提问人
       'template_id'=>YHTWTZ,  // 用户提问通知
       'url'=>$url,
       'topcolor'=>"#7B68EE",
       'data'=>array(
       'first'=>array('value'=>urlencode($first),'color'=>"#743A3A"),
       'keyword1'=>array('value'=>urlencode($keyword1),'color'=>'#743A3A'),
       'keyword2'=>array('value'=>urlencode($keyword2),'color'=>'#743A3A'),
       'keyword3'=>array('value'=>urlencode($keyword3),'color'=>'#743A3A'),

       'remark'=>array('value'=>urlencode('此消息有'.DOMAITDESC.'提供'),'color'=>'#743A3A'),
               )
               );
      //  $json_template=json_encode($template);
		
        
        
        $url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$accessToken;
		
		
        $res= $this->http_request($url,urldecode(json_encode($template)));
		
	
		
	    return $res;
		
	
		
		
		
       


    }
	
	
	
	//老师回答问题通知提问人

    public function sendtplmsgtoaskperson($touser,$questionid,$answerlength,$ansnickname,$content){

        $accessToken = $this->getAccessTokenfile();
		$first = "老师:".$ansnickname."回答了你提出的问题:".$content;
		$keyword1 =$ansnickname;
		$t=time(); 
        $datetime = date("Y-m-d H:i:s",$t); 
		$keyword2 =$datetime;
		$keyword3 =$answerlength;
		
		$url = 'http://'.WWW.'/wenda/wenda/paywenda?id='.$questionid;

        //模板消息	
        $template=array(
       'touser'=>$touser,//提问人
       'template_id'=>SDHFTZ,  //收到回复通知
       'url'=>$url,
       'topcolor'=>"#7B68EE",
       'data'=>array(
       'first'=>array('value'=>urlencode($first),'color'=>"#743A3A"),
       'keyword1'=>array('value'=>urlencode($keyword1),'color'=>'#743A3A'),
       'keyword2'=>array('value'=>urlencode($keyword2),'color'=>'#743A3A'),
       'keyword3'=>array('value'=>urlencode($keyword3),'color'=>'#743A3A'),
       'remark'=>array('value'=>urlencode('此消息有'.DOMAITDESC.'提供'),'color'=>'#743A3A'),
               )
               );
      //  $json_template=json_encode($template);
		
        
        
        $url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$accessToken;
		
		
        $res= $this->http_request($url,urldecode(json_encode($template)));
		
	    return $res;


    }
	
	

    private function createNonceStr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    private function getJsApiTicket() {
        // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
        $data = json_decode(file_get_contents("jsapi_ticket.json"));
        if ($data->expire_time < time()) {
            $accessToken = $this->getAccessTokenfile();
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
            $jsonticket = $this->getJson($url); 
            $ticket =$jsonticket["ticket"];
            if ($ticket) {
                $data->expire_time = time() + 7000;
                $data->jsapi_ticket = $ticket;
                $fp = fopen("jsapi_ticket.json", "w");
                fwrite($fp, json_encode($data));
                fclose($fp);
            }
        } else {
            $ticket = $data->jsapi_ticket;
        }

        return $ticket;
    }

    
    

    
    
    public function getAccessTokenfile() {
		
		
        // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
        $data = json_decode(file_get_contents("access_token.json"));
        
        if(!isset($data))
        {
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
            $a_token = $this->getJson($url); 
            $access_token =$a_token["access_token"];
            
            if ($access_token) {
                
                $data->expire_time = time() + 7000;
                $data->access_token = $access_token;
                $fp = fopen("access_token.json", "w");
                fwrite($fp, json_encode($data));
                fclose($fp);
            }
        }
        
        else
        {
            
            if ($data->expire_time < time()) {
                $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
                $a_token = $this->getJson($url); 
                $access_token =$a_token["access_token"];
                if ($access_token) {
                    $data->expire_time = time() + 7000;
                    $data->access_token = $access_token;
                    $fp = fopen("access_token.json", "w");
                    fwrite($fp, json_encode($data));
                    fclose($fp);
                }
            } else {
                $access_token = $data->access_token;
            }
        }
        
        
      
        return $access_token;
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

    
    
    ///公司支付
    public  function Companypay($openid,$fee)
    {

        // $jssdk = new  \WxJsSdk(WX_APPID, WX_APPSECRET);
        
        $trade_no = MCHID.date("YmdHis");
        
        $nonce_str =$this->getRandChar(32);

        $stringA="amount=".$fee."&check_name=NO_CHECK&desc=".DOMAITDESC."提现打款,提现已存入零钱,请注意查收.&mch_appid=".APPID."&mchid=".MCHID."&nonce_str=".$nonce_str."&openid=".$openid."&partner_trade_no=".$trade_no."&spbill_create_ip=0.0.0.0"; 
        
        $stringSignTemp=$stringA."&key=".KEY; 
        $signstr = md5($stringSignTemp);
        $sign = strtoupper($signstr);
        
        $sendmgs=array(
      'mch_appid'=>APPID, //公众账号appid
     'mchid'=>MCHID, //商户号
     'nonce_str'=>$nonce_str, //随机字符串
     'partner_trade_no'=> $trade_no,  ///商户订单号
     'openid'=>$openid, //用户openid
     'check_name'=>"NO_CHECK", //校验用户姓名选项
     'amount'=>$fee, //金额
     'desc'=>"".DOMAITDESC."提现打款,提现已存入零钱,请注意查收.", //企业付款描述信息
     'spbill_create_ip'=>"0.0.0.0", //Ip地址 调用接口的机器Ip地址
     'sign'=>$sign,

         );

        $url="https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers";
        
        $xmldata =  $this->arrayToXml($sendmgs);
        
        $test =  $this->postXmlCurl($xmldata,$url,true);

        return $test;
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
			curl_setopt($ch,CURLOPT_PROXYPORT,CURL_PROXY_PORT);
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
    

    private function httpGet($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_URL, $url);

        $res = curl_exec($curl);
        curl_close($curl);

        return $res;
    }
    
    
    
    
    
}