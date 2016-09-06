<?php

namespace app\modules\cxddc\controllers;
use app\models\Follow;
use app\models\Msg;
use \app\models\Users;
use Yii;
use yii\web\Controller;
use app\models\YiiUser;
use yii\web\session;
use app\models\UserForm;
use yii\filters\AccessControl;
use yii\helpers\Url;

require_once "models/WxJsSdk.php";

class IndexController extends Controller{
    

    public $enableCsrfValidation = false;//yii默认表单csrf验证，如果post不带改参数会报错！
    public $layout  = 'layout';
    private $WX_APPID = WX_APPID; ///张杰开发测试账号wxf861f60fbb144cb9  //李朝先wxe474c6e60ea8f0c8
    private $WX_APPSECRET = WX_APPSECRET; //张杰开发测试账号2da66bd2cf0dccf0fb8d5db1e3ca6122  //李朝先33b1241f97a2803440b34bf30c33d57e
    private $_openid,$_access_token,$_wxuser,$_user,$_users;

    /**
     * 
     * 根据php的$_SERVER['HTTP_USER_AGENT'] 中各种浏览器访问时所包含各个浏览器特定的字符串来判断是属于PC还是移动端
     * @author           discuz3x
     * @lastmodify    2014-04-09
     * @return  BOOL
     */
    function checkmobile() {
        global $_G;
        $mobile = array();
        //各个触控浏览器中$_SERVER['HTTP_USER_AGENT']所包含的字符串数组
        static $touchbrowser_list =array('iphone', 'android', 'phone', 'mobile', 'wap', 'netfront', 'java', 'opera mobi', 'opera mini',
        'ucweb', 'windows ce', 'symbian', 'series', 'webos', 'sony', 'blackberry', 'dopod', 'nokia', 'samsung',
        'palmsource', 'xda', 'pieplus', 'meizu', 'midp', 'cldc', 'motorola', 'foma', 'docomo', 'up.browser',
        'up.link', 'blazer', 'helio', 'hosin', 'huawei', 'novarra', 'coolpad', 'webos', 'techfaith', 'palmsource',
        'alcatel', 'amoi', 'ktouch', 'nexian', 'ericsson', 'philips', 'sagem', 'wellcom', 'bunjalloo', 'maui', 'smartphone',
        'iemobile', 'spice', 'bird', 'zte-', 'longcos', 'pantech', 'gionee', 'portalmmm', 'jig browser', 'hiptop',
        'benq', 'haier', '^lct', '320x320', '240x320', '176x220');
        //window手机浏览器数组【猜的】
        static $mobilebrowser_list =array('windows phone');
        //wap浏览器中$_SERVER['HTTP_USER_AGENT']所包含的字符串数组
        static $wmlbrowser_list = array('cect', 'compal', 'ctl', 'lg', 'nec', 'tcl', 'alcatel', 'ericsson', 'bird', 'daxian', 'dbtel', 'eastcom',
        'pantech', 'dopod', 'philips', 'haier', 'konka', 'kejian', 'lenovo', 'benq', 'mot', 'soutec', 'nokia', 'sagem', 'sgh',
        'sed', 'capitel', 'panasonic', 'sonyericsson', 'sharp', 'amoi', 'panda', 'zte');
        $pad_list = array('pad', 'gt-p1000');
        $useragent = strtolower($_SERVER['HTTP_USER_AGENT']);
        if($this->dstrpos($useragent, $pad_list)) {
            return false;
        }
        if(($v = $this->dstrpos($useragent, $mobilebrowser_list, true))){
            $_G['mobile'] = $v;
            return '1';
        }
        if(($v = $this->dstrpos($useragent, $touchbrowser_list, true))){
            $_G['mobile'] = $v;
            return '2';
        }
        if(($v = $this->dstrpos($useragent, $wmlbrowser_list))) {
            $_G['mobile'] = $v;
            return '3'; //wml版
        }
        $brower = array('mozilla', 'chrome', 'safari', 'opera', 'm3gate', 'winwap', 'openwave', 'myop');
        if($this->dstrpos($useragent, $brower)) return false;
        $_G['mobile'] = 'unknown';
        //对于未知类型的浏览器，通过$_GET['mobile']参数来决定是否是手机浏览器
        if(isset($_G['mobiletpl'][$_GET['mobile']])) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * 判断$arr中元素字符串是否有出现在$string中
     * @param  $string     $_SERVER['HTTP_USER_AGENT'] 
     * @param  $arr          各中浏览器$_SERVER['HTTP_USER_AGENT']中必定会包含的字符串
     * @param  $returnvalue 返回浏览器名称还是返回布尔值，true为返回浏览器名称，false为返回布尔值【默认】
     * @author           discuz3x
     * @lastmodify    2014-04-09
     */
    function dstrpos($string, $arr, $returnvalue = false) {
        if(empty($string)) return false;
        foreach((array)$arr as $v) {
            if(strpos($string, $v) !== false) {
                $return = $returnvalue ? $v : true;
                return $return;
            }
        }
        return false;
    }

    
    
	
    
    //微信自动验证
    public function actionIndex($id = 1,$code=null){
		
        //$ismobile =  $this->checkmobile();
        //$user_agent = $_SERVER['HTTP_USER_AGENT'];
        //if (strpos($user_agent, 'MicroMessenger') === false) {
        //    // 非微信浏览器禁止浏览
        //    $ismobile = false;
        //} else {
        //    // 微信浏览器，允许访问
        //    $ismobile = true;

        //}

        //if(!$ismobile)
        //{
        //    //返回后台登录页面
        //    Yii::$app->response->redirect(Url::to(['/admin/index'],true));
        //    return;
        //}
        
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
            
			Yii::$app->response->redirect(Url::to(['/cxddc/cuxiao/loadad'],true));

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
			
            
			
            $this->_user = YiiUser::find()->where(['openid'=>$this->_openid])->one();
            if($this->_user )
            {
                
                if($this->_user->isenable==1)
                {
                    //返回错误登录页面
                    Yii::$app->response->redirect(Url::to(['/cxddc/index/errorlogin'],true));
                    
                    return;
                }
                
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
            //返回首页
            Yii::$app->response->redirect(Url::to(['/cxddc/cuxiao/loadad'],true));
        }else{
            $returl="http://".WWW."/cxddc/index";//Url::to(['/wx/wxapi/login'],true);
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
    
    
    
    /**
     *  管理员禁用
     * 
     * 
     */
    public function actionErrorlogin()
    {
        return $this->renderPartial('errorlogin');
    }
	
    


    /**
     * @return string 读取用户列表
     */
    public function actionUsers(){
        $uid=Yii::$app->user->getId();
        //我的粉丝
        $follow=Follow::find()->where(array("fid"=>$uid))->all();
        //$follow=Follow::findBySql("select uid from {{%follow}} where fid=".Yii::$app->user->getId())->all();
        //echo '<pre/>';print_r($follow);
        $ids=array();
        foreach($follow as $v){
            array_push($ids,$v->uid);
        }

        //获取我的粉丝信息
        $fensi=YiiUser::findAll($ids);
        //echo '<pre/>';print_r($fensi);

        //获取我关注的人【我的好友】
        $careids=Follow::find()->where(["uid"=>$uid])->all();
        $cids=array();
        foreach($careids as $v){
            array_push($cids,$v->fid);
        }
        $cares=YiiUser::findAll($cids);

        //获取我没有关注的用户【加关注的人】
        array_push($cids,$uid);//将我的id也加入到排除列表
        //$users=YiiUser::find()->where(['in','id',$ids])->all();//id在一个数组范围内
        $users=YiiUser::find()->where(['not in','id',$cids])->all();

        return $this->render('users',array('users'=>$users,'fensi'=>$fensi,'cares'=>$cares,'cids'=>$cids));
    }


    /**
     * @添加关注动作
     */
    public function actionFollow($id){
        //获取查询条件
        $fid=intval($id);
        $uid=Yii::$app->user->getId();

        //检查是否已经关注了
        $obj=new Follow();
        $num=$obj->find()->andWhere(['uid'=>$uid,'fid'=>$fid])->count();
        if($num==0){
            $obj->uid=$uid;
            $obj->fid=$fid;
            $obj->save();
            Yii::$app->session->setFlash('success','关注成功！');
        }else{
            Yii::$app->session->setFlash('error','关注失败！');
        }
        return $this->redirect(['index/users']);
    }

    /**
     * @取消关注
     */
    public function actionNofollow($id){
        $fid=intval($id);
        $uid=Yii::$app->user->getId();
        //检查是否已经关注了
        $user=Follow::find()->andWhere(['uid'=>$uid,'fid'=>$fid])->one();

        if(count($user)>0){
            //$user->delete() 失败，提示没有定义主键
            $user->deleteAll('uid=:uid and fid=:fid',[':uid'=>$uid,':fid'=>$fid]);
            Yii::$app->session->setFlash('success','取消关注成功！');
        }else{
            Yii::$app->session->setFlash('error','取消关注失败！');
        }
        return $this->redirect(['index/users']);
    }

    

    
    
    


    /**
     * @return string|\yii\web\Response 用户登录
     */

    public function actionLogin(){
        $model=new UserForm();

        if($model->load(Yii::$app->request->post())){

            if($model->login()){
                //查询未读消息
                $count=Msg::find()->andwhere(['tid'=>Yii::$app->user->getId(),'status'=>0])->count();
                $session=Yii::$app->session;
                $session->set('msg',$count);

                return $this->redirect(['index/index']);
            }else{
                return $this->render('login',['model'=>$model]);
            }
        }

        return $this->render('login',['model'=>$model]);
    }



    /**
     * @后台退出页面
     */
    public function actionLogout(){
        Yii::$app->user->logout();
        return $this->goHome();

    }


    /**
     * @用户头像上传
     */
    public function  actionThumb(){
        $user=YiiUser::findOne(Yii::$app->user->getId());
        return $this->render('thumb',array('user'=>$user));
    }

    /**
     * @
     */
    public  function  actionUpload(){

        $path = Yii::$app->basePath."/web/avatar/";
        $tmpath="/avatar/";
        if(!empty($_FILES)){

            //得到上传的临时文件流
            $tempFile = $_FILES['myfile']['tmp_name'];

            //允许的文件后缀
            $fileTypes = array('jpg','jpeg','gif','png');

            //得到文件原名
            $fileName = iconv("UTF-8","GB2312",$_FILES["myfile"]["name"]);
            $fileParts = pathinfo($_FILES['myfile']['name']);



            //最后保存服务器地址
            if(!is_dir($path)){
                mkdir($path);
            }

            if (move_uploaded_file($tempFile, $path.$fileName)){
                $info= $tmpath.$fileName;
                $status=1;
                $data=array('path'=>Yii::$app->basePath,'file'=> $path.$fileName);
            }else{
                $info=$fileName."上传失败！";
                $status=0;
                $data='';
            }
            echo $info;
        }

    }

    /**
     * @裁剪头像
     */

    public function actionCutpic(){
        if(Yii::$app->request->isAjax){
            $path="/avatar/";
            $targ_w = $targ_h = 150;
            $jpeg_quality = 100;
            $src =Yii::$app->request->post('f');
            $src=Yii::$app->basePath.'/web'.$src;//真实的图片路径

            $img_r = imagecreatefromjpeg($src);
            $ext=$path.time().".jpg";//生成的引用路径
            $dst_r = ImageCreateTrueColor( $targ_w, $targ_h );

            imagecopyresampled($dst_r,$img_r,0,0,Yii::$app->request->post('x'),Yii::$app->request->post('y'),
                $targ_w,$targ_h,Yii::$app->request->post('w'),Yii::$app->request->post('h'));

            $img=Yii::$app->basePath.'/web/'.$ext;//真实的图片路径

            if(imagejpeg($dst_r,$img,$jpeg_quality)){
                //更新用户头像
                $user=YiiUser::findOne(Yii::$app->user->getId());
                $user->thumb=$ext;
                $user->save();
                $arr['status']=1;
                $arr['data']=$ext;
                $arr['info']='裁剪成功！';
                echo json_encode($arr);

            }else{
                $arr['status']=0;
                echo json_encode($arr);
            }
            exit;
        }
    }
}
