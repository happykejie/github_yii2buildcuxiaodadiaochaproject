<?php
/**
 * Created by PhpStorm.
 * Askproblem: Administrator
 * Date: 2015/1/31
 * Time: 17:33
 */

namespace app\modules\admin\controllers;
use app\models\Askproblem;
use app\models\Category;
use app\models\Search;


use Yii;
use yii\web\Controller;
use app\models\Follow;
use app\models\YiiUser;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

class AskproblemController extends Controller{
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
        
        $connection = Yii::$app->db; //连接
        
        $model=Askproblem::find();
        
        $Sql="select a.* from  sm_askproblem as a INNER JOIN sm_user as b ON a.askpersonid=b.id  INNER JOIN sm_user as c ON a.answerpersonid=c.id";
        

        $search=new Search();
        if ($search->load(Yii::$app->request->post())) {
            if (isset($search))
            {
                $Sql.=" where b.nickname like '%".$search->nickname."%'"; 
                
                $Sql.=" and c.nickname LIKE '%".$search->answernname."%'";
                
                $Sql.=" and a.questiondescription LIKE '%".$search->questiondescription."%'";
            }
        }
        $Sql.=" order by questionorder asc";
        $askproblems = Askproblem::findBySql($Sql);
        
        $count=count($askproblems->all());
        $page=new Pagination(['defaultPageSize'=>50,'totalCount'=>$count]);
        //分页
        $Sql.=" LIMIT ".$page->limit." OFFSET ".$page->offset."";
        $askproblems=Askproblem::findBySql($Sql);
        $items=$askproblems->all();
        
        return $this->render('index',['page'=>$page,'items'=>$items,'search'=>$search]);
    }
    /**
     * @add
     */
    public function actionAdd(){
        
        $model=new Askproblem();
        
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
    
    
    /**
     * @问题置顶
     */
    public function actionTop($id){
        
        $category= Category::find()->all();
        $to=array();
        foreach($category as $v){
            $to[$v->id]=$v->categoryname;
        }
        
        $model=new Askproblem();
        $model=$model::findOne(['id'=>$id]);
        if($model->load(Yii::$app->request->post())//判断是否是表单提交
            && $model->validate() //验证表单提交的内容正确性
            ){
            if ($model->isfree=1)
            {
                $askproblem=Askproblem::findOne(['isfree'=>1]);
                if (isset($askproblem))
                {
                    $askproblem->isfree=0;
                    $askproblem->save();
                }
                
            }
            
            if($model->save()){
                Yii::$app->session->setFlash('success','新增成功！');
            }else{
                Yii::$app->session->setFlash('error','新增失败！');
            }
            //echo "<pre/>";print_r(Yii::$app->request->post());die();
        }
        return $this->render('top',['model'=>$model,'to'=>$to]);
    }
    
    //编辑问题
    public function actionEdit($id){
        
        $model=Askproblem::findOne(['id'=>$id]);
        if($model->load(Yii::$app->request->post())//判断是否是表单提交
            && $model->validate() //验证表单提交的内容正确性
            ){
            if ($model->isfree=1)
            {
                
                $askproblem=Askproblem::findOne(['isfree'=>1]);
                if (isset($askproblem))
                {
                    $askproblem->isfree=0;
                    $askproblem->save();
                }
            	
            }
            
            
            
            $model->modifytime=date("Y-m-d H:i:s", time());
            
            if($model->save()){
                Yii::$app->session->setFlash('success','修改成功！');
            }else{
                Yii::$app->session->setFlash('error','修改失败！');
            }
        }
        return $this->render('edit',['model'=>$model]);
    }
    
    //审核问题
    public function actionExamine($id){
        
        $model=Askproblem::findOne(['id'=>$id]);
        
        if($model->load(Yii::$app->request->post())//判断是否是表单提交
            && $model->validate() //验证表单提交的内容正确性
            ){
            $model->questionstate=1;
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
            Askproblem::deleteAll(['id'=>$id]);
            return 0;
        }
    }
    

} 