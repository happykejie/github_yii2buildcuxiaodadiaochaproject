<?php
namespace app\modules\cxddc\controllers;
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
    private $WX_APPID = WX_APPID; 
    private $WX_APPSECRET = WX_APPSECRET; 
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
            
            $search=new Activity();
            
            if ($search->load(Yii::$app->request->post())) {
                if (strlen($search->name))
                {
                    $Sqlitem= $Sqlitem.' and intro like "%'.($search->name).'%"';
                }
            }
            $Sqlitem=$Sqlitem." order by ordernum asc,createtime DESC";
            //获取所有问题信息
            $items=\app\models\Activity::findBySql($Sqlitem)->all();

            return $this->render('cuxiaoindex',['items'=>$items,'category'=>$category,'banner'=>$banner,'search'=>$search,'cityname'=>$value]);
        }
        else
        {
            //返回登陆
            Yii::$app->response->redirect(Url::to(['/cxddc/index'],true));
            return false;			
        }
        
    }
    
    
    public function actionCuxiaoindex()
    {   
        
        $value=Yii::$app->cache->get('citynamenew'); 
        if($value===false) ///没有获取到所属城市
        {
            return $this->renderPartial('loadad');
        }

        $this->_user = YiiUser::findOne(['id'=>Yii::$app->user->getId()]);
        if( $this->_user ){
            //
            
            $Sqlitem="select b.* from sm_banner  as b where remark !='启动页' order by ordernum asc  LIMIT 5";
            $banner=Banner::findBySql($Sqlitem)->all();
            //获取组别信息
            $category=Category::find()->orderBy('id asc')->all();
            
            $Sqlitem="select a.* from sm_activity  as a inner join sm_category as b on a.group_id=b.id where a.belongarea ='$value'";
            
            $search=new Activity();
            
            if ($search->load(Yii::$app->request->post())) {
                if (strlen($search->name))
                {
                    $Sqlitem= $Sqlitem.' and intro like "%'.($search->name).'%"';
                }
            }
            $Sqlitem=$Sqlitem." order by ordernum asc,createtime DESC";
            //获取所有问题信息
            $items=\app\models\Activity::findBySql($Sqlitem)->all();
                        
            $currentuserid= Yii::$app->user->getId();  //获取当前用户ID
            return $this->render('cuxiaoindex',['items'=>$items,'category'=>$category,'banner'=>$banner,'search'=>$search,'cityname'=>$value,'currentuserid'=>$currentuserid]);
        }
        else
        {
            //返回登陆
            Yii::$app->response->redirect(Url::to(['/cxddc/index'],true));
            return false;			
        }
        
    }
    
    public function actionLoadad()
    {
        
        $Sqlitem="select b.* from sm_banner  as b  where b.remark ='启动页' order by ordernum asc limit 3";
        
        
       $banner=Banner::findBySql($Sqlitem)->all();
        
      return $this->renderPartial('loadad',['banner'=>$banner]);

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
   
   
   /**分享连接过来查看详情的
    * Summary of actionFxDetail
    */
   public function actionFxdetail($fxren=-1,$pid=-1,$code=null)
   {
       $urldetail = "/cxddc/cuxiao/detail?id=".$pid;
       
       
       $this->_user = YiiUser::findOne(['id'=>Yii::$app->user->getId()]);
       if( $this->_user ){
           $this->_openid = $this->_user->openid;
           //返回提问老师页面
           // return $urlexpert;
           Yii::$app->response->redirect(Url::to([$urldetail],true));
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
               $this->_user->city = $this->_wxuser['city'];
               $this->_user->country = $this->_wxuser['country'];
               $this->_user->remark = $this->_wxuser['remark'];
               $this->_user->userstate =0;
               $this->_user->createusertime= date('y-m-d h:i:s',time());
               
               if($this->_user->save()){
                   //设置登录成功
                   Yii::$app->user->login($this->_user,3600*24*1);
                   
                   
                   
                   
               }else{
                   echo "login error";
                   die;
               }
           }
           
           //return $urlexpert;
           //返回首页
           Yii::$app->response->redirect(Url::to([$urldetail],true));
       }else{
           
           $returl="http://".WWW."/cxddc/cuxiao/fxdetail";//Url::to(['/wx/wxapi/login'],true);
           
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
   
    
    
    
    
    

    
    public function actionDetail($id=1)
    {
        $item = Activity::findOne(['id'=>$id]);
        
        
        if(isset($item)) 
        {
        ///添加浏览次数：
        $currentviewnum = $item->viewcount;
        
       // $currentviewnum=$currentviewnum+range(1,10);
        
         $currentviewnum =$currentviewnum+1;
         
         $item->viewcount = $currentviewnum;
         
          $result = $item->save();
          
          ///结束添加浏览次数
          
     
        
          $currentuserid= Yii::$app->user->getId();  //获取当前用户ID
            
            $arryimg = $item->newspictures;

            return $this->render('detail',['item'=>$item,'arryimg'=>$arryimg,'currentuserid'=>$currentuserid]);
        }
        
       
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
    

   
}
