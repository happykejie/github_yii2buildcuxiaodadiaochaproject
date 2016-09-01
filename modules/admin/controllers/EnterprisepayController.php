<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/1/31
 * Time: 17:33
 */

namespace app\modules\admin\controllers;
use app\models\User;
use app\models\Enterprisepay;

use Yii;
use yii\web\Controller;
use app\models\Follow;
use app\models\YiiUser;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

require_once "models/WxJsSdk.php";
class EnterprisepayController extends Controller{
    public $layout  = 'layout';
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
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @msg消息列表
     */

    public function  actionIndex(){
        $model=Enterprisepay::find();
        $search=new Enterprisepay();
        if ($search->load(Yii::$app->request->post())) {
            if (!isset($model->nickname))
            {
                $model->andWhere(['like', 'applyname',$search->applyname]);
            }
        }
        
        $count=$model->count();
        $page=new Pagination(['defaultPageSize'=>50,'totalCount'=>$count]);
        $items=$model->offset($page->offset)->limit($page->limit)->all();
        return $this->render('index',['page'=>$page,'items'=>$items,'search'=>$search]);
    }
    
    public function actionDelete($id){ 
        if(Yii::$app->request->isAjax){
            //ajax请求删除
            Enterprisepay::deleteAll(['id'=>$id]);
            return 0;
        }
    }
    
    //用户提现通过
    public function actionExamine($id){
        
        $userid = Yii::$app->user->getId();
        $items =User::findOne(['id'=>$userid]);
        $model=Enterprisepay::findOne(['id'=>$id]);
        if($model->load(Yii::$app->request->post())//判断是否是表单提交
            
            ){
            $model->state=1;
            $model->applytime=date("Y-m-d H:i:s", time());
            $model->examinetime=date("Y-m-d H:i:s", time());
            $model->examinename=$items->nickname;
            $model->remark="提现成功";
            if($model->save()){
                
                
                
                ///提现审核通过 ，提现操作 
                $jssdk = new  \WxJsSdk(WX_APPID, WX_APPSECRET); 
                $touser =  $model->applyopenid;
                $money = $model->money;

                $expandnum = $money*100;  ///扩大支付额度100部
                if($expandnum>100) ///如果提现大于等1元钱。 平台分成10%
                {                  
                    $paymoney = $expandnum*0.9; 
                    //调用企业支付方法。
                    $jssdk->Companypay($touser,$paymoney); 
                }
                if($expandnum==100) //一元钱不收取平台费用
                {
                    // 支付小于1元的零钱         
                    $paymoney = $expandnum;    
                    $jssdk->Companypay($touser,$paymoney);
                }

                //end
                
                
                
                
                
                Yii::$app->session->setFlash('success','审核成功！');                
            }else{
                Yii::$app->session->setFlash('error','审核失败！');
            }
        }
        return $this->render('examine',['model'=>$model]);
    }
    
    
    //审核用户不通过
    public function actionExamineno($id){
        
        
        $userid = Yii::$app->user->getId();
        $items =User::findOne(['id'=>$userid]);
        $model=Enterprisepay::findOne(['id'=>$id]);
        
        if($model->load(Yii::$app->request->post())//判断是否是表单提交
             && $model->validate() //验证表单提交的内容正确性
            ){
            $model->state=2;
            $model->examinetime=date("Y-m-d H:i:s", time());
            $model->examinename=$items->nickname;
            $model->remark=$model->remark;
            if($model->save()){
                
                
                //不通过要删除incomecost 提现记录通过订单号删除
                
                \app\models\Incomecost::deleteAll(['trade_no'=>$model->trade_no]);
                
                
                
                ///提现审核不通过，微信通知用户原因
                $jssdk = new  \WxJsSdk(WX_APPID, WX_APPSECRET); 
                $touser =  $model->applyopenid;
                $jssdk->sendTiXianNoPass($touser,$model->remark);
                
                //end
                
                Yii::$app->session->setFlash('success','审核成功！');                
            }else{
                Yii::$app->session->setFlash('error','审核失败！');
            }
        }
        return $this->render('examineno' ,['model'=>$model]);
    }
    
} 

