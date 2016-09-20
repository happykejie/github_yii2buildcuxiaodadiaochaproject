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
use \app\models\fxandbfx;

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
    
    public $cityname, $urldetail;
    
    
    
    
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
		
		
		      
     
        

        $Sqlitem="select b.* from sm_banner  as b  where b.remark ='启动页' order by ordernum asc limit 3";
        
        
        $banner=Banner::findBySql($Sqlitem)->all();

        $value=Yii::$app->cache->get('citynamenew'); 
        if($value===false) ///没有获取到所属城市
        {
            return $this->renderPartial('loadad',['banner'=>$banner]);
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
	
	
	
           //判断当前用户是否关注，如果没有关注跳转让用户关注
        $currentuserid= Yii::$app->user->getId();  //获取当前用户ID
        $items =User::findOne(['id'=>$currentuserid]);
        
		

		
		if($items->subscribe==0) //如果用户没用关注，跳转用户关注
        {
			Yii::$app->session->setFlash('notattention','还没有关注，请先关注');
        }
        ///跳转关注结束

		//获取当前用户定位城市
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
		
		///结束获取城市

        $this->_user = YiiUser::findOne(['id'=>Yii::$app->user->getId()]);
        if( $this->_user ){
            //
            
            $Sqlitem="select b.* from sm_banner  as b where remark !='启动页' order by ordernum asc  LIMIT 5";
            $banner=Banner::findBySql($Sqlitem)->all();
            //获取组别信息
            $category=Category::find()->orderBy('id asc')->all();
            
            $Sqlitem="select a.* from sm_activity  as a inner join sm_category as b on a.group_id=b.id where a.belongarea ='$getcity'";
            
            $search=new Activity();
            
            if ($search->load(Yii::$app->request->post())) {
                if (strlen($search->name))
                {
                    $Sqlitem= $Sqlitem.' and intro like "%'.($search->name).'%"';
                }
            }
            $Sqlitem=$Sqlitem." order by paynum DESC,ordernum asc";
            //获取所有问题信息
            $items=\app\models\Activity::findBySql($Sqlitem)->all();
                        
            $currentuserid= Yii::$app->user->getId();  //获取当前用户ID
            return $this->render('cuxiaoindex',['items'=>$items,'category'=>$category,'banner'=>$banner,'search'=>$search,'cityname'=>$getcity,'currentuserid'=>$currentuserid]);
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

        // $currentuserid= Yii::$app->user->getId();  //获取当前用户ID
        //$item =User::findOne(['id'=>$currentuserid]);
		
        //if($item->locationcity==$city)
        //{
        //    //如果获取用户城市和当前一样就不更新数据库
        //}
		
        //else{ //如果不一样就更新数据库
        //    if($city)
        //    {
        //        $item->locationcity=$city;
				
        //        $item->save();
        //    }
			
        //}
        
        return $city;
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

	 // return $fxren.'ewewe'.$pid;
	  
	  if($fxren!=-1&&$pid!=-1)
	  {
		   $this->urldetail = "/cxddc/cuxiao/detail?id=".$pid;
		   
		    
	  }
      $ismobile =  false;
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($user_agent, 'MicroMessenger') === false) {
            // 非微信浏览器禁止浏览
           $ismobile = false;
        } else {
            // 微信浏览器，允许访问
            $ismobile = true;

        }

       if(!$ismobile)
        {
           //返回后台登录页面
            Yii::$app->response->redirect(Url::to(['/admin/index'],true));
            return;
        }
        
        //返回首页
        // yii::$app->response->redirect(url::to(['/cxddc/cxddc/index'],true));
        // return;
        $this->_user = YiiUser::findOne(['id'=>Yii::$app->user->getId()]);
		
	
        if( $this->_user ){
            $this->_openid = $this->_user->openid;
            //返回首页
            
            if($this->_user->isenable==1)
            {
                //返回错误登录页面
                Yii::$app->response->redirect(Url::to(['/cxddc/index/errorlogin'],true));
                
                return;
            }
			
			  if(UPSYS=='yes') //判断是否进入维护状态
            {

                if($this->_user->isdevelop==1)
                {
                    
                    Yii::$app->response->redirect(Url::to([$this->urldetail],true));
                    return;
                }
                
                else
                {
                    Yii::$app->response->redirect(Url::to(['/cxddc/index/updatesystem'],true));
                    return;
                }
            }
            
			Yii::$app->response->redirect(Url::to([$this->urldetail],true));

            return ;
        }
		
	
        if($code){
            //return;
            if(!$this->_openid)
            {
                $this->_openid = $this->getWxUserOpenId($code);
            }
            
            if(empty($this->_openid)){
                die("No openid there! Can't add");
            } 
            $jssdk = new  \WxJsSdk(WX_APPID, WX_APPSECRET);

            $this->_access_token =  $jssdk->getAccessTokenfile();

            $this->_wxuser = $this->getWxUserinfo();
			
            //return \yii\helpers\Json::encode($this->_wxuser); 
			
            $this->_user = YiiUser::find()->where(['openid'=>$this->_openid])->one();
             if($this->_user&&$this->_user->subscribe==1) //用户存在，并且用户已经关注获取了信息
            {
                
                if($this->_user->isenable==1)
                {
                    //返回错误登录页面
                    Yii::$app->response->redirect(Url::to(['/cxddc/index/errorlogin'],true));
                    return;
                }
                
                //设置登录成功
                Yii::$app->user->login($this->_user,3600*24*1);
            }else
			{
               if($this->_wxuser['subscribe']==0)
				{
                    //Yii::$app->response->redirect(Url::to(['https://mp.weixin.qq.com/mp/profile_ext?action=home&__biz=MzIyNzE1NDMwMQ==&scene=110#wechat_redirect'],true));
					 //未找到绑定用户自动注册并登陆
					$this->_user=new YiiUser();
					$this->_user->openid =  $this->_openid;
					$this->_user->user =  $this->_openid;
                    $this->_user->subscribe =$this->_wxuser['subscribe'];
					$this->_user->nickname = "未关注(请立即关注)";
					$this->_user->thumb ='/web/images/cxddcgetheadimg.jpg';
					$this->_user->headimgurl = '/web/images/cxddcgetheadimg.jpg';
					$this->_user->remark = '未关注';
					$this->_user->userstate =0;
					$this->_user->createusertime= date('y-m-d h:i:s',time());	

					if($this->_user->save()){
						//设置登录成功
						Yii::$app->user->login($this->_user,3600*24*1);
						
						
						///添加分享人与被分享人关系表。
						
						if($fxren!=-1)
						{
							
						
						 $model=fxandbfx::findOne(['bfxopenid'=>$this->_user->openid]);
                                
                                if(!$model)//如果没有添加分享记录
                                {
                                    $fxitem = new  \app\models\fxandbfx();
                                    $fxitem->fxrenid=$fxren;
                                    $fxitem->bfxrenid =$this->_user->id;
                                    $fxitem->createtime =date('y-m-d h:i:s',time());	
                                    $fxitem->remark ='remark log';
                                    $fxitem->bfxopenid =$this->_user->openid;
                                    
                                    $fxresult =  $fxitem->save();
									
								                              }
						
						}
                        ///结束添加分享人

					}
					else{
						echo "user save fail";
						die;
					}
				}
				
				if($this->_wxuser['subscribe']==1)
				{
					
					 $this->_user = YiiUser::find()->where(['openid'=>$this->_openid])->one();
                    
                    if($this->_user)  //如果用户存在只需要更新用户信息
                    {
                        
                        
                        $this->_user->subscribe =$this->_wxuser['subscribe'];
                        $this->_user->nickname = $this->_wxuser['nickname'];
                        $this->_user->sex = $this->_wxuser['sex'];
                        $this->_user->thumb = $this->_wxuser['headimgurl'];
                        $this->_user->headimgurl = $this->_wxuser['headimgurl'];
                        $this->_user->city = $this->_wxuser['city'];
                        $this->_user->country = $this->_wxuser['country'];
                        $this->_user->remark = $this->_wxuser['remark'];
                        $this->_user->userstate =0;
                        $this->_user->createusertime= date('y-m-d h:i:s',time());
                        

                        if($this->_user->save()){
                            //设置登录成功
                            Yii::$app->user->login($this->_user,3600*24*1);
                            
                            
                        }
                        else{
                            echo "user save fail";
                            die;
                        }
                    }
                    else
                    {
                        //未找到绑定用户自动注册并登陆
                        $this->_user=new YiiUser();
                        $this->_user->openid =  $this->_openid;
                        $this->_user->user =  $this->_openid;
                        $this->_user->subscribe =$this->_wxuser['subscribe'];
                        $this->_user->nickname = $this->_wxuser['nickname'];
                        $this->_user->sex = $this->_wxuser['sex'];
                        $this->_user->thumb = $this->_wxuser['headimgurl'];
                        $this->_user->headimgurl = $this->_wxuser['headimgurl'];
                        $this->_user->city = $this->_wxuser['city'];
                        $this->_user->country = $this->_wxuser['country'];
                        $this->_user->remark = $this->_wxuser['remark'];
                        $this->_user->userstate =0;
                        $this->_user->createusertime= date('y-m-d h:i:s',time());
                        

                        if($this->_user->save()){
                            //设置登录成功
                            Yii::$app->user->login($this->_user,3600*24*1);
                            
                            
                        }
                        else{
                            echo "user save fail";
                            die;
                        }
                    }
					
				}
           }
		   
		    if(UPSYS=='yes') //判断是否进入维护状态
            {
                
                
                if($this->_user->isdevelop==1)
                {
                    
                    Yii::$app->response->redirect(Url::to(['/cxddc/cuxiao/cuxiaoindex'],true));
                    return false;
                }
                
                else
                {
                    Yii::$app->response->redirect(Url::to(['/cxddc/index/updatesystem'],true));
                    return false;
                }
            }
		   
           
          // return 'testt1232';
           //返回首页
            Yii::$app->response->redirect(Url::to([$this->urldetail],true));
             return;
       }else{
           
           $returl="http://".WWW."/cxddc/cuxiao/fxdetail?fxren=$fxren";//Url::to(['/wx/wxapi/login'],true);
           
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
