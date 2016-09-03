<?php
/**
 * Created by PhpStorm.
 * Userattention: Administrator
 * Date: 2015/1/31
 * Time: 17:33
 */

namespace app\modules\admin\controllers;
use app\models\Userattention;
use app\models\User;

use Yii;
use yii\web\Controller;
use app\models\Follow;
use app\models\YiiUser;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

class UserattentionController extends Controller{
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
        $Sql="select a.id , a.userid,a.attentionuserid,a.attentiontime from sm_userattention as a INNER JOIN sm_user as b INNER JOIN sm_user as c on a.userid=b.id and a.attentionuserid=c.id";
        $search=new User();
        
        if ($search->load(Yii::$app->request->post())) {
            if (isset($search))
            {
                $Sql.=" where b.nickname like '%".$search->nickname."%'"; 
                
                $Sql.="and c.nickname LIKE '%".$search->attentionname."%'";
            }
        } 
        
        $Sql.=" GROUP BY a.attentionuserid ";
        
        $userattentions=Userattention::findBySql($Sql);
        $count=count($userattentions->all());
        $page=new Pagination(['defaultPageSize'=>50,'totalCount'=>$count]);
        //分页
        $Sql.=" LIMIT ".$page->limit." OFFSET ".$page->offset."";
        $userattentions=Userattention::findBySql($Sql);
        $items=$userattentions->all();
        
        return $this->render('index',['page'=>$page,'items'=>$items,'search'=>$search]);
    }
    
    public function  actionFollowdetailed($id){
        $Sql="select a.id , a.userid,a.attentionuserid,a.attentiontime from sm_userattention as a INNER JOIN sm_user as b INNER JOIN sm_user as c on a.userid=b.id and a.attentionuserid=c.id where attentionuserid=".$id." ";
        $search=new User();
        
        if ($search->load(Yii::$app->request->post())) {
            if (isset($search))
            {
                $Sql.=" and b.nickname like '%".$search->nickname."%'"; 
                
                $Sql.=" and c.nickname LIKE '%".$search->attentionname."%'";
            }
        } 
        
        
        $userattentions=Userattention::findBySql($Sql);
        $count=count($userattentions->all());
        $page=new Pagination(['defaultPageSize'=>50,'totalCount'=>$count]);
        //分页
        $Sql.=" LIMIT ".$page->limit." OFFSET ".$page->offset."";
        $userattentions=Userattention::findBySql($Sql);
        $items=$userattentions->all();
        
        return $this->render('followdetailed',['page'=>$page,'items'=>$items,'search'=>$search]);
    }  
    /**
     * @add
     */
    public function actionAdd(){
        
        $model=new Userattention();
        if($model->load(Yii::$app->request->post())//判断是否是表单提交
            && $model->validate() //验证表单提交的内容正确性
            ){
            if($model->save()){
                Yii::$app->session->setFlash('success','新增成功！');
            }else{
                Yii::$app->session->setFlash('error','新增失败！');
            }
            //echo "<pre/>";print_r(Yii::$app->request->post());die();
        }
        return $this->render('add',['model'=>$model]);
    }
    
    
    public function actionEdit($id){
        
        $model=Userattention::findOne(['id'=>$id]);
        if($model->load(Yii::$app->request->post())//判断是否是表单提交
            && $model->validate() //验证表单提交的内容正确性
            ){
            if($model->save()){
                Yii::$app->session->setFlash('success','修改成功！');
            }else{
                Yii::$app->session->setFlash('error','修改失败！');
            }
        }
        return $this->render('edit',['model'=>$model]);
    }
    
    
    public function actionDelete($id){ 
        if(Yii::$app->request->isAjax){
            //ajax请求删除
            Userattention::deleteAll(['id'=>$id]);
            return 0;
        }
    }
    

} 