<?php
/**
 * Created by PhpStorm.
 * LoveListenQuestion: Administrator
 * Date: 2015/1/31
 * Time: 17:33
 */

namespace app\modules\admin\controllers;
use app\models\LoveListenQuestion;
use app\models\Askproblem;

use Yii;
use yii\web\Controller;
use app\models\Follow;
use app\models\YiiUser;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

class LoveListenQuestionController extends Controller{
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
        $id = Yii::$app->user->getId();
        $Sql="";
        $search =new Askproblem();
        
        if ($search->load(Yii::$app->request->post())) {
            
             if(isset($search))
            {
                $Sql="select a.questionid,a.userid,a.id from sm_lovelistenquestion as a inner join  sm_user as b inner JOIN sm_askproblem as c where a.questionid=c.id and b.id=c.answerpersonid and c.questiondescription like '%".$search->questiondescription."%'";
            }
            
        }  else
        {
            $Sql="select a.questionid,a.userid,a.id,a.buytime from sm_lovelistenquestion as a inner join  sm_user as b inner JOIN sm_askproblem as c where a.questionid=c.id and b.id=c.answerpersonid";
        }
        
        
        
        $lovelistenquestion = LoveListenQuestion::findBySql($Sql);
        
        $count=count($lovelistenquestion->all());
        $page=new Pagination(['defaultPageSize'=>50,'totalCount'=>$count]);
        //分页
        $Sql.=" LIMIT ".$page->limit." OFFSET ".$page->offset."";
        $lovelistenquestion=LoveListenQuestion::findBySql($Sql);
        $items=$lovelistenquestion->all();
        
        return $this->render('index',['page'=>$page,'items'=>$items,'search'=>$search]);
    }
    /**
     * @add
     */
    public function actionAdd(){
        
        $model=new LoveListenQuestion();
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
        
        $model=LoveListenQuestion::findOne(['id'=>$id]);
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
            LoveListenQuestion::deleteAll(['id'=>$id]);
            return 0;
        }
    }
    

} 