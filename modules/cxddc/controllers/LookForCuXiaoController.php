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

require_once "models/WxJsSdk.php";
//error_reporting(E_ERROR);
require_once "/vendor/WxpayAPI/lib/WxPay.Api.php";
require_once "/vendor/WxpayAPI/example/WxPay.JsApiPay.php";
require_once '/vendor/WxpayAPI/example/log.php';

class LookForCuXiaoController extends Controller{
    public $enableCsrfValidation = false;//yii默认表单csrf验证，如果post不带改参数会报错！
    public $layout  = 'layout';
    private $WX_APPID = WX_APPID; ///张杰开发测试账号wxf861f60fbb144cb9  //李朝先wxe474c6e60ea8f0c8
    private $WX_APPSECRET = WX_APPSECRET; //张杰开发测试账号2da66bd2cf0dccf0fb8d5db1e3ca6122  //李朝先33b1241f97a2803440b34bf30c33d57e
    private $_openid ,$_access_token,$_wxuser,$_user,$_users;
    private $timetamp;

    /**
     * accesscontrol
     */

    public function actionIndex(){
        
        
        //判断当前用户是否关注，如果没有关注跳转让用户关注
        $currentuserid= Yii::$app->user->getId();  //获取当前用户ID
        $items =User::findOne(['id'=>$currentuserid]);
        
		
		if($items->subscribe==0) //如果用户没用关注，跳转用户关注
        {
			Yii::$app->session->setFlash('notattention','还没有关注，请先关注');

          
        }
        ///跳转关注结束
        
        
        
        
        
        $value=Yii::$app->cache->get('citynamenew'); 
        if($value===false) ///没有获取到所属城市
        {
            return $this->renderPartial('loadad');
        }
        

        $this->_user = YiiUser::findOne(['id'=>Yii::$app->user->getId()]);
        if( $this->_user ){
            
            ///////////////////////////////////////今日
            
            //获取当前服务器时间
            $time= date('Y-m-d H:i:s',time());
            
            $Sqlitem="select a.* from sm_activity  as a inner join sm_category as b on a.group_id=b.id  where  belongarea='$value' and start_time<'$time' and end_time>'$time' ";
            $Sqlitem=$Sqlitem." order by ordernum asc,createtime DESC";
            //获取所有问题信息
            $todayitems=\app\models\Activity::findBySql($Sqlitem)->all();
            
            ///////////////////////////////////////明日
            $time1= date('Y-m-d',strtotime("+1 day"));
            
            $strtime1   =$time1.' '.'00:00:00';
            
            
           
            
            
          
         
            
            $Sqlitem1="select a.* from sm_activity  as a inner join sm_category as b on a.group_id=b.id  where belongarea='$value' and start_time<'$strtime1' and end_time>'$strtime1' ";
            $Sqlitem1=$Sqlitem1." order by ordernum asc,createtime DESC";
            //获取所有问题信息
            $tomorrowitems=\app\models\Activity::findBySql($Sqlitem1)->all();
            
            ////////////////////////////////////预告
            $time2= date('Y-m-d',strtotime("+2 day"));
            
            $strtime2   =$time2.' '.'00:00:00';
            
        
            
            $Sqlitem2="select a.* from sm_activity  as a inner join sm_category as b on a.group_id=b.id  where belongarea='$value' and start_time>'$strtime2'";
            $Sqlitem2=$Sqlitem2." order by ordernum asc,createtime DESC";
            //获取所有问题信息
            $prevueitems=\app\models\Activity::findBySql($Sqlitem2)->all();
            
            
            /////////////////////////////////////////////////////////////热门

            //获取当前服务器时间
        
            
            $Sqlitem3="select  a.* from sm_activity  as a inner join sm_category as b on a.group_id=b.id  where belongarea='$value' ";
            $Sqlitem3=$Sqlitem3." order by viewcount DESC limit 10";
            //获取所有问题信息
            $hotitems=\app\models\Activity::findBySql($Sqlitem3)->all();
            
            
            //大型活动
            
            $Sqlitem4="select a.* from sm_activity  as a inner join sm_category as b on a.group_id=b.id  where belongarea='$value' and lableremark='大型活动'";
            $Sqlitem4=$Sqlitem4." order by viewcount DESC limit 10";
            //获取所有问题信息
            $bigitems=\app\models\Activity::findBySql($Sqlitem4)->all();
            
            
            $currentuserid= Yii::$app->user->getId();  //获取当前用户ID

            
            return $this->render('index',['todayitems'=>$todayitems,'tomorrowitems'=>$tomorrowitems,'prevueitems'=>$prevueitems,'hotitems'=>$hotitems,'bigitems'=>$bigitems,'currentuserid'=>$currentuserid]);
        }
        else
        {
            //返回登陆
            Yii::$app->response->redirect(Url::to(['/cxddc/index'],true));
            return false;			
        }
        
        
        
        
        

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
        
      
            
            $arryimg = $item->newspictures;
            
            
            
            return $this->render('detail',['item'=>$item,'arryimg'=>$arryimg]);
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
