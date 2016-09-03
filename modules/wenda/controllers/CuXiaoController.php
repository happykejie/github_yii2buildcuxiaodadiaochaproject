<?php
namespace app\modules\wenda\controllers;
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
    private $WX_APPID = WX_APPID; ///张杰开发测试账号wxf861f60fbb144cb9  //李朝先wxe474c6e60ea8f0c8
    private $WX_APPSECRET = WX_APPSECRET; //张杰开发测试账号2da66bd2cf0dccf0fb8d5db1e3ca6122  //李朝先33b1241f97a2803440b34bf30c33d57e
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
            Yii::$app->response->redirect(Url::to(['/wenda/index'],true));
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
            Yii::$app->response->redirect(Url::to(['/wenda/index'],true));
            return false;			
        }
        
    }
    
    public function actionLoadad()
    {

      return $this->renderPartial('loadad');

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
