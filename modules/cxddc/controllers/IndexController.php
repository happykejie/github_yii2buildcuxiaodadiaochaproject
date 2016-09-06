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
    

    public $enableCsrfValidation = false;//yiiĬ�ϱ�csrf��֤�����post�����Ĳ����ᱨ��
    public $layout  = 'layout';
    private $WX_APPID = WX_APPID; ///�Žܿ��������˺�wxf861f60fbb144cb9  //���wxe474c6e60ea8f0c8
    private $WX_APPSECRET = WX_APPSECRET; //�Žܿ��������˺�2da66bd2cf0dccf0fb8d5db1e3ca6122  //���33b1241f97a2803440b34bf30c33d57e
    private $_openid,$_access_token,$_wxuser,$_user,$_users;

    /**
     * 
     * ����php��$_SERVER['HTTP_USER_AGENT'] �и������������ʱ����������������ض����ַ������ж�������PC�����ƶ���
     * @author           discuz3x
     * @lastmodify    2014-04-09
     * @return  BOOL
     */
    function checkmobile() {
        global $_G;
        $mobile = array();
        //���������������$_SERVER['HTTP_USER_AGENT']���������ַ�������
        static $touchbrowser_list =array('iphone', 'android', 'phone', 'mobile', 'wap', 'netfront', 'java', 'opera mobi', 'opera mini',
        'ucweb', 'windows ce', 'symbian', 'series', 'webos', 'sony', 'blackberry', 'dopod', 'nokia', 'samsung',
        'palmsource', 'xda', 'pieplus', 'meizu', 'midp', 'cldc', 'motorola', 'foma', 'docomo', 'up.browser',
        'up.link', 'blazer', 'helio', 'hosin', 'huawei', 'novarra', 'coolpad', 'webos', 'techfaith', 'palmsource',
        'alcatel', 'amoi', 'ktouch', 'nexian', 'ericsson', 'philips', 'sagem', 'wellcom', 'bunjalloo', 'maui', 'smartphone',
        'iemobile', 'spice', 'bird', 'zte-', 'longcos', 'pantech', 'gionee', 'portalmmm', 'jig browser', 'hiptop',
        'benq', 'haier', '^lct', '320x320', '240x320', '176x220');
        //window�ֻ���������顾�µġ�
        static $mobilebrowser_list =array('windows phone');
        //wap�������$_SERVER['HTTP_USER_AGENT']���������ַ�������
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
            return '3'; //wml��
        }
        $brower = array('mozilla', 'chrome', 'safari', 'opera', 'm3gate', 'winwap', 'openwave', 'myop');
        if($this->dstrpos($useragent, $brower)) return false;
        $_G['mobile'] = 'unknown';
        //����δ֪���͵��������ͨ��$_GET['mobile']�����������Ƿ����ֻ������
        if(isset($_G['mobiletpl'][$_GET['mobile']])) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * �ж�$arr��Ԫ���ַ����Ƿ��г�����$string��
     * @param  $string     $_SERVER['HTTP_USER_AGENT'] 
     * @param  $arr          ���������$_SERVER['HTTP_USER_AGENT']�бض���������ַ���
     * @param  $returnvalue ������������ƻ��Ƿ��ز���ֵ��trueΪ������������ƣ�falseΪ���ز���ֵ��Ĭ�ϡ�
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

    
    
	
    
    //΢���Զ���֤
    public function actionIndex($id = 1,$code=null){
		
        //$ismobile =  $this->checkmobile();
        //$user_agent = $_SERVER['HTTP_USER_AGENT'];
        //if (strpos($user_agent, 'MicroMessenger') === false) {
        //    // ��΢���������ֹ���
        //    $ismobile = false;
        //} else {
        //    // ΢����������������
        //    $ismobile = true;

        //}

        //if(!$ismobile)
        //{
        //    //���غ�̨��¼ҳ��
        //    Yii::$app->response->redirect(Url::to(['/admin/index'],true));
        //    return;
        //}
        
        //������ҳ
        // yii::$app->response->redirect(url::to(['/cxddc/cxddc/index'],true));
        // return;
        $this->_user = YiiUser::findOne(['id'=>Yii::$app->user->getId()]);
        if( $this->_user ){
            $this->_openid = $this->_user->openid;
            //������ҳ
            
            if($this->_user->isenable==1)
            {
                //���ش����¼ҳ��
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
                    //���ش����¼ҳ��
                    Yii::$app->response->redirect(Url::to(['/cxddc/index/errorlogin'],true));
                    
                    return;
                }
                
                //���õ�¼�ɹ�
                Yii::$app->user->login($this->_user,3600*24*1);
            }else{
                //δ�ҵ����û��Զ�ע�Ტ��½
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
                    //���õ�¼�ɹ�
                    Yii::$app->user->login($this->_user,3600*24*1);
					
					
                }else{
                    echo "��¼ʧ��";
                    die;
                }
            }
            //������ҳ
            Yii::$app->response->redirect(Url::to(['/cxddc/cuxiao/loadad'],true));
        }else{
            $returl="http://".WWW."/cxddc/index";//Url::to(['/wx/wxapi/login'],true);
            Yii::$app->response->redirect('https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->WX_APPID.'&redirect_uri='.$returl.'&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect');
        }
    }
	
	
	
    
    //private Oauth �û���¼���� 
    //��ȡopenid
    function getWxUserOpenId($code)
	{
		$appid =$this->WX_APPID;  
		$secret = $this->WX_APPSECRET;  
		//��һ��:ȡ��openid
		$oauth2Url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$secret&code=$code&grant_type=authorization_code";
		$oauth2 = $this->getJson($oauth2Url); 
        if(isset($oauth2['openid'])){
            return $oauth2['openid'];  
        }
        return null;
	}
    
    
    
    
	
	
	

    //��ȡ�û���Ϣ
    function getWxUserinfo(){

        $get_user_info_url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$this->_access_token."&openid=".$this->_openid."&lang=zh_CN";
		$wxuserinfo =$this->getJson($get_user_info_url);

        return $wxuserinfo;
    }
    
    //�ַ���ת����
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
     *  ����Ա����
     * 
     * 
     */
    public function actionErrorlogin()
    {
        return $this->renderPartial('errorlogin');
    }
	
    


    /**
     * @return string ��ȡ�û��б�
     */
    public function actionUsers(){
        $uid=Yii::$app->user->getId();
        //�ҵķ�˿
        $follow=Follow::find()->where(array("fid"=>$uid))->all();
        //$follow=Follow::findBySql("select uid from {{%follow}} where fid=".Yii::$app->user->getId())->all();
        //echo '<pre/>';print_r($follow);
        $ids=array();
        foreach($follow as $v){
            array_push($ids,$v->uid);
        }

        //��ȡ�ҵķ�˿��Ϣ
        $fensi=YiiUser::findAll($ids);
        //echo '<pre/>';print_r($fensi);

        //��ȡ�ҹ�ע���ˡ��ҵĺ��ѡ�
        $careids=Follow::find()->where(["uid"=>$uid])->all();
        $cids=array();
        foreach($careids as $v){
            array_push($cids,$v->fid);
        }
        $cares=YiiUser::findAll($cids);

        //��ȡ��û�й�ע���û����ӹ�ע���ˡ�
        array_push($cids,$uid);//���ҵ�idҲ���뵽�ų��б�
        //$users=YiiUser::find()->where(['in','id',$ids])->all();//id��һ�����鷶Χ��
        $users=YiiUser::find()->where(['not in','id',$cids])->all();

        return $this->render('users',array('users'=>$users,'fensi'=>$fensi,'cares'=>$cares,'cids'=>$cids));
    }


    /**
     * @��ӹ�ע����
     */
    public function actionFollow($id){
        //��ȡ��ѯ����
        $fid=intval($id);
        $uid=Yii::$app->user->getId();

        //����Ƿ��Ѿ���ע��
        $obj=new Follow();
        $num=$obj->find()->andWhere(['uid'=>$uid,'fid'=>$fid])->count();
        if($num==0){
            $obj->uid=$uid;
            $obj->fid=$fid;
            $obj->save();
            Yii::$app->session->setFlash('success','��ע�ɹ���');
        }else{
            Yii::$app->session->setFlash('error','��עʧ�ܣ�');
        }
        return $this->redirect(['index/users']);
    }

    /**
     * @ȡ����ע
     */
    public function actionNofollow($id){
        $fid=intval($id);
        $uid=Yii::$app->user->getId();
        //����Ƿ��Ѿ���ע��
        $user=Follow::find()->andWhere(['uid'=>$uid,'fid'=>$fid])->one();

        if(count($user)>0){
            //$user->delete() ʧ�ܣ���ʾû�ж�������
            $user->deleteAll('uid=:uid and fid=:fid',[':uid'=>$uid,':fid'=>$fid]);
            Yii::$app->session->setFlash('success','ȡ����ע�ɹ���');
        }else{
            Yii::$app->session->setFlash('error','ȡ����עʧ�ܣ�');
        }
        return $this->redirect(['index/users']);
    }

    

    
    
    


    /**
     * @return string|\yii\web\Response �û���¼
     */

    public function actionLogin(){
        $model=new UserForm();

        if($model->load(Yii::$app->request->post())){

            if($model->login()){
                //��ѯδ����Ϣ
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
     * @��̨�˳�ҳ��
     */
    public function actionLogout(){
        Yii::$app->user->logout();
        return $this->goHome();

    }


    /**
     * @�û�ͷ���ϴ�
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

            //�õ��ϴ�����ʱ�ļ���
            $tempFile = $_FILES['myfile']['tmp_name'];

            //������ļ���׺
            $fileTypes = array('jpg','jpeg','gif','png');

            //�õ��ļ�ԭ��
            $fileName = iconv("UTF-8","GB2312",$_FILES["myfile"]["name"]);
            $fileParts = pathinfo($_FILES['myfile']['name']);



            //��󱣴��������ַ
            if(!is_dir($path)){
                mkdir($path);
            }

            if (move_uploaded_file($tempFile, $path.$fileName)){
                $info= $tmpath.$fileName;
                $status=1;
                $data=array('path'=>Yii::$app->basePath,'file'=> $path.$fileName);
            }else{
                $info=$fileName."�ϴ�ʧ�ܣ�";
                $status=0;
                $data='';
            }
            echo $info;
        }

    }

    /**
     * @�ü�ͷ��
     */

    public function actionCutpic(){
        if(Yii::$app->request->isAjax){
            $path="/avatar/";
            $targ_w = $targ_h = 150;
            $jpeg_quality = 100;
            $src =Yii::$app->request->post('f');
            $src=Yii::$app->basePath.'/web'.$src;//��ʵ��ͼƬ·��

            $img_r = imagecreatefromjpeg($src);
            $ext=$path.time().".jpg";//���ɵ�����·��
            $dst_r = ImageCreateTrueColor( $targ_w, $targ_h );

            imagecopyresampled($dst_r,$img_r,0,0,Yii::$app->request->post('x'),Yii::$app->request->post('y'),
                $targ_w,$targ_h,Yii::$app->request->post('w'),Yii::$app->request->post('h'));

            $img=Yii::$app->basePath.'/web/'.$ext;//��ʵ��ͼƬ·��

            if(imagejpeg($dst_r,$img,$jpeg_quality)){
                //�����û�ͷ��
                $user=YiiUser::findOne(Yii::$app->user->getId());
                $user->thumb=$ext;
                $user->save();
                $arr['status']=1;
                $arr['data']=$ext;
                $arr['info']='�ü��ɹ���';
                echo json_encode($arr);

            }else{
                $arr['status']=0;
                echo json_encode($arr);
            }
            exit;
        }
    }
}
