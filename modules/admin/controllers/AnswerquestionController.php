<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/1/31
 * Time: 17:33
 */

namespace app\modules\admin\controllers;
use app\models\Answerquestion;
use app\models\Askproblem;

use Yii;
use yii\web\Controller;
use app\models\Follow;  
use app\models\YiiUser;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

class AnswerquestionController extends Controller{
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
     * @问题回答列表
     */
    public function  actionIndex(){
        
        $Sql="SELECT a.id,a.askquestionid,a.answertimelength from sm_answerquestion as a INNER JOIN sm_askproblem as b  on a.askquestionid=b.id";
        
        $search=new Askproblem();
        if ($search->load(Yii::$app->request->post())) {
            if (isset($search))
            {
                $Sql.= " where b.questiondescription LIKE '%".$search->questiondescription."%'";
                
            }
        }
        
        $Sql.=" group by askquestionid";
        
        $answerquestions= Answerquestion::findBySql($Sql);
        
        $count=count($answerquestions->all());
        $page=new Pagination(['defaultPageSize'=>50,'totalCount'=>$count]);
        //分页
        $Sql.=" LIMIT ".$page->limit." OFFSET ".$page->offset."";
        $answerquestions=Answerquestion::findBySql($Sql);
        $items=$answerquestions->all();
        
        return $this->render('index',['page'=>$page,'items'=>$items,'search'=>$search]);
    }

    
    public function  actionVoice($id){
        
        $answerquestions=new Answerquestion();
        $answerquestions=$answerquestions::find()->where(["askquestionid"=>$id]);
        $count=$answerquestions->count();
        $page=new Pagination(['defaultPageSize'=>5,'totalCount'=>$count]);
        $items=$answerquestions->offset($page->offset)->limit($page->limit)->all();
        return $this->render('voice',['page'=>$page,'items'=>$items]);
    }
    /**
     * @add
     */
    public function actionAdd(){
        
        $model=new Answerquestion();
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

        $model=Answerquestion::findOne(['id'=>$id]);
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
            Answerquestion::deleteAll(['id'=>$id]);
            return 0;
        }
    }

    public function actionDeletean($id){ 
            Answerquestion::deleteAll(['id'=>$id]);
            return 0;
    }
    
} 