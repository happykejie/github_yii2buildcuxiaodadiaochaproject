<?php

namespace app\modules\wenda\controllers;
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
require_once "models/phpqrcode.php";

require_once "models/WxJsSdk.php";
//error_reporting(E_ERROR);
require_once "/vendor/WxpayAPI/lib/WxPay.Api.php";
require_once "/vendor/WxpayAPI/example/WxPay.JsApiPay.php";
require_once '/vendor/WxpayAPI/example/log.php';

class MeController extends Controller{
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
	*
	*
	*/
    
    /**
     * @申请成为老师
     * 传入用户Id
     */
    public function actionBecometeacher($id){
        $model=User::findOne(['id'=>$id]);
        $category=Category::find()->all();
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
            }else{
                Yii::$app->session->setFlash('error','发送失败！');
            }
        }
        return $this->render('becometeacher',['model'=>$model,'category'=>$category]);
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
                
                $value = 'http://'.WWW.'/wenda/lookforpeople/index?id='.$this->_user->id; //二维码内容   
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
                $value = 'http:/'.WWW.'/wenda/lookforpeople/index?id='.$this->_user->id; //二维码内容   
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
