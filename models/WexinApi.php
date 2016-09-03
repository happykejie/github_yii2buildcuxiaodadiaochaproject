<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%msg}}".
 *
 * @property integer $id
 * @property integer $fid
 * @property integer $tid
 * @property string $title
 * @property string $content
 * @property integer $status
 * @property integer $send_time
 * @property integer $replay
 */
class WeixinLogin extends \yii\db\ActiveRecord
{
    private $WX_APPID = WX_APPID; ///张杰开发测试账号wxf861f60fbb144cb9  //李朝先wxe474c6e60ea8f0c8
    private $WX_APPSECRET = WX_APPSECRET; //张杰开发测试账号2da66bd2cf0dccf0fb8d5db1e3ca6122  //李朝先33b1241f97a2803440b34bf30c33d57e
    private $_openid ,$_access_token,$_wxuser,$_user,$_users;
    
    public $accesstoken,$js_tiket;
    
    public function getWxUserOpenId()
    {
        $appid = WX_APPID;  
        $secret = WX_APPSECRET;  
        $code = $_GET["code"];
        //第一步:取得openid
        $oauth2Url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$secret&code=$code&grant_type=authorization_code";
        $oauth2 = $this->getJson($oauth2Url); 
        return $oauth2['openid'];  
    }
    
    /*
     * 获取微信accesstoken
     */ 
    public  function getWxAccessToken()
    {
        $appid = WX_APPID;  
        $secret = WX_APPSECRET; 
        $id ='accesstoken';
        $value=Yii::app()->cache->get($id);  
        if($value===false)  
        {  
            $oauth2Url = "https://api.weixin.qq.com/cgi-bin/token?appid=$appid&secret=$secret&grant_type=client_credential";
            $oauth2 = $this->getJson($oauth2Url); 
            $accesstoken =$oauth2["access_token"];
            Yii::app()->cache->set($id, $accesstoken, 7200);  
        } 
        return $value;
    }
    
    
    public function getSingwx()
    {
       // 签名，将jsapi_ticket、noncestr、timestamp、分享的url按字母顺序连接起来，进行sha1签名。
       // noncestr是你设置的任意字符串。
       // timestamp为时间戳。
            $timestamp = time();
            $wxnonceStr = "2nDgiWM7gCxhL8v0";//与js wx.comfig 配置文件一致。
            $wxticket = getJsapi_ticket();
            $wxOri = sprintf("jsapi_ticket=%s&noncestr=%s&timestamp=%s&url=%s",
                $wxticket, $wxnonceStr, $timestamp,
                'http://kejie.wipc.net/wxjssdk'
                );
           $signature = sha1($wxOri);
           
           return $signature;
    }
    
    
    
    /*
     * 获取微信jsapi_ticket
     */ 
    public  function getJsapi_ticket()
    {
        $a_token = getWxAccessToken(); ///得到accesstoken
        $id ='js_ticket';
        $value=Yii::app()->cache->get($id);  
        if($value===false)  
        {  
            $jsticket = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=$a_token&type=jsapi";
            $json_jsticket = $this->getJson($jsticket); 
            if($json_jsticket["errmsg"]=='ok')
            {
                $jstiket =$json_jsticket["ticket"];
                Yii::app()->cache->set($id, $jstiket, 7200);  
            }
        } 
        
        return $value;
        
    }

    public function getWxUserinfo($_access_token,$_openid){
        $get_user_info_url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$_access_token."&openid=".$_openid."&lang=zh_CN ";
        $wxuserinfo =$this->getJson($get_user_info_url);
        return $wxuserinfo;
    }
   public  function getJson($url){
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
