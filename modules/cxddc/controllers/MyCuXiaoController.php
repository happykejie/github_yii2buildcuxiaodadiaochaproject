<?php

namespace app\modules\cxddc\controllers;
use app\models\Follow;
use app\models\User;
use app\models\Userattention;
use app\models\Askproblem;
use app\models\Enterprisepay;
use app\models\Category;

use app\models\LoveListenQuestion;
use yii\data\Pagination;
use Yii;
use yii\web\Controller;
use app\models\YiiUser;
use yii\web\session;
use app\models\UserForm;
use yii\filters\AccessControl;

use app\models\Activity;

use yii\web\NotFoundHttpException;

use common\widgets\payment\Weixinjspi;
use common\widgets\payment\Notifyurl;
use yii\helpers\Url;
use yii\app;
use yii\web\Response;

use yii\web\UploadedFile;
use app\models\UploadForm;

require_once "models/phpqrcode.php";

require_once "models/WxJsSdk.php";
//error_reporting(E_ERROR);
require_once "/vendor/WxpayAPI/lib/WxPay.Api.php";
require_once "/vendor/WxpayAPI/example/WxPay.JsApiPay.php";
require_once '/vendor/WxpayAPI/example/log.php';

class MyCuXiaoController extends Controller{
    public $enableCsrfValidation = false;//yii默认表单csrf验证，如果post不带改参数会报错！
    public $layout  = 'layout';
	private $_openid ,$_access_token,$_wxuser,$_user,$_users;

    /**
     * accesscontrol
     */
    /**
     * @用户授权规则
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login','captcha'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        //'actions' => ['logout','edit','add','del','index','users','thumb','upload','cutpic','follow','nofollow'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
    
    /**
     * 默认主页
     */
    public function actionIndex()
    {
        $id = Yii::$app->user->getId();
        $items =User::findOne(['id'=>$id]);
        if ($items->userstate==1)
        {
            return $this->render('publishuser',['items'=>$items]);
        }else
        {
            return $this->render('commonuser',['items'=>$items]);
        }   
    }
    
    /**
     * 意见反馈
     */
    public function actionFeedback()
    {
        return $this->render('feedback');
    }
    
    /**
     * 发布信息用户
     */
    public function actionPublishuser()
    {
        return $this->render('publishuser');
    }
    
    /**
     * 普通用户
     */
    public function actionCommonuser()
    {
        return $this->render('commonuser');
    }
    
    
    /**
     * 发布申明
     */
    public function actionPublishdeclare()
    {
        
      
        return $this->render('publishdeclare');
    }
    
    
    
    /**
     * 我的发布
     */
    public function actionMypublished()
    {             
        $this->_user = YiiUser::findOne(['id'=>Yii::$app->user->getId()]);
        $userid =Yii::$app->user->getId();
        if( $this->_user ){
           
            
            $Sqlitem="select a.* from sm_activity  as a inner join sm_category as b on a.group_id=b.id where a.publishpeople ='$userid'";
          
            $Sqlitem=$Sqlitem." order by ordernum asc,createtime DESC";
            //获取所有问题信息
            $mypublishitems=\app\models\Activity::findBySql($Sqlitem)->all();

            return $this->render('mypublished',['mypublishitems'=>$mypublishitems]);
        }
        else
        {
            //返回登陆
            Yii::$app->response->redirect(Url::to(['/cxddc/index'],true));
            return false;			
        }
    }
    
    
    /**
     * 我的资料
     */
    public function actionMyinfo()
    {
        $id=Yii::$app->user->getId();
        $model=User::findOne(['id'=>$id]);
        
        if($model->load(Yii::$app->request->post())//判断是否是表单提交
           //验证表单提交的内容正确性
            ){
          
            if($model->save()){
                Yii::$app->session->setFlash('success','发送成功！');
            }else{
                Yii::$app->session->setFlash('error','发送失败！');
            }
        }
        return $this->render('myinfo',['model'=>$model]);    
    }
    
    
    /**
     * 平台合作
     */
    public function actionCooperation()
    {
        return $this->render('cooperation');
    }
    
    /**
     * 平台二维码展示
     */
    
    public function actionInfoercode()
    {
        return $this->render('infoercode');
        
    }
    
    
    
    /**
     * 没支付或者支付失败的订单
     */
    
    public function actionNopayorder()
    {
        
        $this->_user = YiiUser::findOne(['id'=>Yii::$app->user->getId()]);
        $userid =Yii::$app->user->getId();
        if( $this->_user ){

            $Sqlitem="select a.* from sm_activity  as a inner join sm_category as b on a.group_id=b.id where a.publishpeople ='$userid' and a.ispay='否'";
            
            $Sqlitem=$Sqlitem." order by ordernum asc,createtime DESC";
            //获取所有问题信息
            $mypublishitems=\app\models\Activity::findBySql($Sqlitem)->all();

            return $this->render('nopayorder',['mypublishitems'=>$mypublishitems]);
        }
        else
        {
            //返回登陆
            Yii::$app->response->redirect(Url::to(['/cxddc/index'],true));
            return false;			
        }
        
       
        
    }
    
    
    
    
    /**
     * 确认订单支付界面
     */
    public function actionComfirmorder($id)
    {
        

         $item = Activity::findOne(['id'=>$id]);
        
        
        if(isset($item)) 
        {
            
            ///开始生产订单信息
            
            
            $body =ORDERBODY;
            $fee=(int)($item->paynum);
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
                $this->_openid = $tools->GetOpenid(Url::to(['/cxddc/index'],true));
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
            $input->SetNotify_url(Url::to(['/cxddc/cxddc/paynotify']));
            $input->SetTrade_type("JSAPI");
            $input->SetOpenid($this->_openid);
            
            $order = \WxPayApi::unifiedOrder($input);
            
            $trade_no = $input->GetOut_trade_no();

            $jsApiParameters = $tools->GetJsApiParameters($order);

            //获取共享收货地址js函数参数
            $editAddress = $tools->GetEditAddressParameters();
            
            ///结束生产订单信息


            $arryimg = $item->newspictures;

            return $this->render('comfirmorder',['item'=>$item,'arryimg'=>$arryimg,'order'=>$order,'trade_no'=>$trade_no,'jsApiParameters'=>$jsApiParameters,'editAddress'=>$editAddress]);
        }
        
        return null;
       
    }
    
    
    
    
    
    /**
     * 支付成功修改发布信息状态
     */
    public function actionPaysuccess()
    {
        $publishid = Yii::$app->request->post('publishid');
        $model=Activity::findOne(['id'=>$publishid]);
        
        $model->ispay='是';
        
        $result = $model->save();
        
        if($result)
        {
            return '1';
        }
        
        return false;
    }
    
    
    /**
     * 成为发布者
     */
    public function actionBecomepublisher($id)
    {
      
        $model=User::findOne(['id'=>$id]);
        if($model->load(Yii::$app->request->post())//判断是否是表单提交
            
            
         
           //验证表单提交的内容正确性
            ){
            //如果自己本身不是老师,状态变更为审核状态
             if ($model->userstate<>1)
            {
                $model->userstate=2;
            }
            if($model->save()){
                Yii::$app->session->setFlash('success','发送成功！');
                $items =$model;
                return $this->render('commonuser',['items'=>$items]);    
            }else{
                Yii::$app->session->setFlash('error','发送失败！');
            }
        }
    
        return $this->render('becomepublisher',['model'=>$model]);    
    }

    
    
    /**
     * 免费发布促销信息
     * @param mixed $id 
     * @return string
     */
    public function actionPublishinfofree()
    {
      
        $group= Category::find()->all();
        $to=array();
        foreach($group as $v){
            $to[$v->id]=$v->categoryname;
        }
        $model=new Activity();
        
        if($model->load(Yii::$app->request->post())//判断是否是表单提交s
            ){
            
            if (Yii::$app->request->isPost) {
                $surface_files = UploadedFile::getInstance($model, 'surface_file');
                if($surface_files){
                    $model->surface = $model->fileInput($surface_files);
                    $model->setAttr("surface",$model->surface);
                }
                $homepictures_val=  Yii::$app->request->post("homepictures_val");
                if($homepictures_val){
                    $model->homepictures= explode('-',$homepictures_val);
                    $model->setAttr("homepictures",$homepictures_val);
                }else{
                    $homepictures_files = UploadedFile::getInstances($model, 'homepictures');
                    if($homepictures_files)
                    {
                        $model->homepictures=$model->fileInput($homepictures_files);
                        $model->setAttr("homepictures",$model->homepictures);
                    }
                }
                $newspictures_val=  Yii::$app->request->post("newspictures_val");
                if($newspictures_val){
                    $model->newspictures= explode('-',$newspictures_val);
                    $model->setAttr("newspictures",$newspictures_val);
                }else{
                    $newspictures_files = UploadedFile::getInstances($model, 'newspictures');
                    if($newspictures_files)
                    {
                        $model->newspictures=$model->fileInput($newspictures_files);
                        $model->setAttr("newspictures",$model->newspictures);
                    }
                }
            }
            $userid=Yii::$app->user->getId();
            $model->publishpeople=$userid;
            $model->ispay='免费';
            $model->paynum=0;
            
            if( $model->validate()){
                if($model->save()){
                    Yii::$app->response->redirect("/cxddc/mycuxiao/index");
                }else{
                    Yii::$app->session->setFlash('error','添加失败！');
                }
            }
        }
        return $this->render('publishinfofree',['model'=>$model,"to"=>$to]);
    }
    
    
    /**付费发布促销信息
     * @add
     */
    public function actionPublishinfopay(){

        $group= Category::find()->all();
        $to=array();
        foreach($group as $v){
            $to[$v->id]=$v->categoryname;
        }
        $model=new Activity();
        
        if($model->load(Yii::$app->request->post())//判断是否是表单提交s
            ){
            
            if (Yii::$app->request->isPost) {
                $surface_files = UploadedFile::getInstance($model, 'surface_file');
                if($surface_files){
                    $model->surface = $model->fileInput($surface_files);
                    $model->setAttr("surface",$model->surface);
                }
                $homepictures_val=  Yii::$app->request->post("homepictures_val");
                if($homepictures_val){
                    $model->homepictures= explode('-',$homepictures_val);
                    $model->setAttr("homepictures",$homepictures_val);
                }else{
                    $homepictures_files = UploadedFile::getInstances($model, 'homepictures');
                    if($homepictures_files)
                    {
                        $model->homepictures=$model->fileInput($homepictures_files);
                        $model->setAttr("homepictures",$model->homepictures);
                    }
                }
                $newspictures_val=  Yii::$app->request->post("newspictures_val");
                if($newspictures_val){
                    $model->newspictures= explode('-',$newspictures_val);
                    $model->setAttr("newspictures",$newspictures_val);
                }else{
                    $newspictures_files = UploadedFile::getInstances($model, 'newspictures');
                    if($newspictures_files)
                    {
                        $model->newspictures=$model->fileInput($newspictures_files);
                        $model->setAttr("newspictures",$model->newspictures);
                    }
                }
            }
            
      
            
            $userid=Yii::$app->user->getId();
            $model->publishpeople=$userid;
            $model->ispay='否';
           
            
            if( $model->validate()){
                if($model->save()){
                    Yii::$app->response->redirect("/cxddc/mycuxiao/comfirmorder?id=$model->id");
                }else{
                    Yii::$app->session->setFlash('error','添加失败！');
                }
            }
        }
        return $this->render('publishinfopay',['model'=>$model,"to"=>$to]);
    }
    
    public function actionGetlocal()
    {
        return $this->render('getlocal');
    }
    
    
    public function actionSelectarea()
    {
        return $this->render('selectarea');
    }
    

    /**
     * @我的关注
     */
    public function actionFollow($id){
        //获取当前登录用户的ID
        $userid=Yii::$app->user->getId();
        $userattention=new Userattention();
        $ttention=new Userattention();
        
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
        
        $Sql='select a.id,a.title,a.nickname,a.headimgurl,b.attentionuserid  from sm_user as a  inner JOIN sm_userattention as b on a.id=b.attentionuserid WHERE b.userid='.$id;
        
        $search=new User();
        
        if ($search->load(Yii::$app->request->post())) {
            if (isset($search->nickname))
            {
                $Sql= $Sql.' and nickname like "%'.($search->nickname).'%"';
            }
        }
        
        
        $Sql=$Sql.' order BY b.attentiontime DESC';
        
        $items =User::findBySql($Sql)->all();
        return $this->render('follow',['items'=>$items,'search'=>$search]);
    }

    /**
     * @我的二维码
     */

    public function actionInfo($id=-1){
		
        

		if($id==-1)
		{
            
            $this->_user = YiiUser::findOne(['id'=>Yii::$app->user->getId()]);
            if( $this->_user ){
                
                $value = 'http://'.WWW.'/cxddc/lookforpeople/index?id='.$this->_user->id; //二维码内容   
                $errorCorrectionLevel = 'L';//容错级别   
                $matrixPointSize = 6;//生成图片大小   
                //生成二维码图片
                
                $fileyuantu = BASE_PATH.'web/images/'.$this->_user->openid.'qrcode.png';
                
                \QRcode::png($value, $fileyuantu, $errorCorrectionLevel, $matrixPointSize, 2);
				
                
                $imgurl = '/web/images/'.$this->_user->openid.'qrcode.png';
                $id = Yii::$app->user->getId();
                $items =YiiUser::findOne(['id'=>$id]);
                return $this->render('info',['items'=>$items,'url'=>$imgurl]);
                
            }
            
            
		}
		
		else
		{
            $this->_user = YiiUser::findOne(['id'=>$id]);

			
			
            if( $this->_user ){
                $value = 'http:/'.WWW.'/cxddc/lookforpeople/index?id='.$this->_user->id; //二维码内容   
                $errorCorrectionLevel = 'L';//容错级别   
                $matrixPointSize = 6;//生成图片大小   
                //生成二维码图片
                
                $fileyuantu = BASE_PATH.'web/images/'.$this->_user->openid.'qrcode.png';
                
                \QRcode::png($value, $fileyuantu, $errorCorrectionLevel, $matrixPointSize, 2);   
                
                
                $imgurl = '/web/images/'.$this->_user->openid.'qrcode.png';
                
                $items =YiiUser::findOne(['id'=>$id]);
                return $this->renderPartial('info',['items'=>$items,'url'=>$imgurl]);
            }

            
		}
        
    }
    
    /**
     * @获取我的爱听
     */

    public function actionLove($id){
        
        //$id = Yii::$app->user->getId();
        $Sql="";
        $search =new Askproblem();       
        if ($search->load(Yii::$app->request->post())) {

            if(isset($search->questiondescription))
            {
                $Sql="select a.questionid,a.userid,a.id,a.buytime from sm_lovelistenquestion as a inner join  sm_user as b inner JOIN sm_askproblem as c where a.questionid=c.id and b.id=c.answerpersonid and c.questiondescription like '%".$search->questiondescription."%' and a.userid=".$id;
            }
            
        }  else
        {
            $Sql="select a.questionid,a.userid,a.id,a.buytime from sm_lovelistenquestion as a inner join  sm_user as b inner JOIN sm_askproblem as c where a.questionid=c.id and b.id=c.answerpersonid and a.userid=".$id;
        }

        $items=LoveListenQuestion::findBySql($Sql)->all();

        return $this->render('love',['items'=>$items,'search'=>$search]);
    }
    
    /**
     * @我的问题
     */

    public function actionMyquestion($id){
		
        //获取我已经回答的问题集合
        // $itemsOK=$itemsOK->where(['answerpersonid'=>$id])->all();
        $sql='select * from sm_askproblem where questionstate<>0 and askpersonid='.$id.' ORDER BY asktime DESC';
        
        $itemsok =Askproblem::findBySql($sql)->all();
        
        //获取我还没有回答的问题
        $itemsno =Askproblem::find()->where(['askpersonid'=>$id,'questionstate'=>0])->orderBy('asktime DESC')->all();
        
        return $this->render('myquestion',['itemsok'=>$itemsok,'itemsno'=>$itemsno]);
    }
    
    
    /**
     * @我的回答
     */

    public function actionMyanswer($id){
        
        //获取我的提问集合
        // $itemsOK=$itemsOK->where(['answerpersonid'=>$id])->all();
        $sql='select * from sm_askproblem where questionstate<>0 and answerpersonid='.$id.' ORDER BY asktime DESC';
        
        $itemsok =Askproblem::findBySql($sql)->all();
        
        //获取我还没有回答的问题
        $itemsno =Askproblem::find()->where(['answerpersonid'=>$id,'questionstate'=>0])->orderBy('asktime DESC')->all();
        
        return $this->render('myanswer',['itemsok'=>$itemsok,'itemsno'=>$itemsno]);
    }

    /**
     * @我的详细页
     */

    public function actionMy(){
        $id =  Yii::$app->user->getId();
        
        $items =User::findOne(['id'=>$id]);
        
        if ($items->userstate==1)
        {
            return $this->render('teacher',['items'=>$items]);
            
        }else
        {
            return $this->render('my',['items'=>$items]);
        }   
    }
    
    
    public function  actionTest()
    {
        echo 'stese';
        
        return null;
    }
    
    
    public function actionCurrentme(){
        $id = Yii::$app->user->getId();
        $items =User::findOne(['id'=>$id]);
        if ($items->userstate==1)
        {
            return $this->render('teacher',['items'=>$items]);
        }else
        {
            return $this->render('currentme',['items'=>$items]);
        }   
    }
    
    
    public function  actionTeacheranswer()
    {
        $this->render('teacheranswer');
        // echo 'ssds';
    }
    

	/**
     *用户提现
     *
     */
    public function actionWithdarawal()
    {
        $id = Yii::$app->user->getId();
        $items =User::findOne(['id'=>$id]);
		
		$Enterprisepay=new Enterprisepay();
		
        $incomecost = new \app\models\Incomecost();
        
        $incomecounum =0;

        if ($Enterprisepay->load(Yii::$app->request->post())) {

            //商户订单号
            $out_trade_no=MCHID.date("YmdHis");
            
            $Enterprisepay->applyname=$items->nickname;
            $Enterprisepay->applyopenid=$items->openid;
            $Enterprisepay->phone=$items->phone;
            $Enterprisepay->trade_no = $out_trade_no;
            
            $sumexpenditure=($items->incomecost3mnumber()->sum('incomecostnum'))+($items->incomecost2mnumber()->sum('incomecostnum'))+($items->incomecost5mnumber()->sum('incomecostnum'))+($items->incomecost6mnumber()->sum('incomecostnum'));
            $format_number = number_format($sumexpenditure, 2, '.', '');/// float 四舍五入
            $money=$format_number; 
            if(!isset($money)){
                $money= 0;
            }
            
        	$Enterprisepay->money=$money;
            
            $incomecounum = $money;
            
            
            
            $Enterprisepay->applytime=date("Y-m-d H:i:s", time());
            $Enterprisepay->phone=$items->phone;
            $Enterprisepay->state=0;
            
            if ($Enterprisepay->save())
            {
                
                //添加一条提现记录到收入支付表
                
                $incomecost->userid=$id;
                $incomecost->incomecosttype=6;
                $incomecost->incomecostnum= -$incomecounum;       
                $incomecost->dealtime =date("Y-m-d H:i:s", time());
                $incomecost->trade_no =$out_trade_no;
                $incomecost->questionid =0;
                
                if($incomecost->save())
                {
                    $id = Yii::$app->user->getId();
                    $items =User::findOne(['id'=>$id]);
                    if ($items->userstate==1)
                    {
                        return $this->render('teacher',['items'=>$items]);
                    }else
                    {
                        return $this->render('currentme',['items'=>$items]);
                    }   
                    
                }
                
                
            }else
            {
                $this->render('recording');
            }
            
        }
        return $this->render('withdarawal',['items'=>$items]);
        
    }
    
	
	
    public function actionTestpage()
    {
        $this->render('testpage');
    }
    
    
    public function actionRecording()
    {
        $this->render('recording');
    }
    
    
    
    
    
    
}