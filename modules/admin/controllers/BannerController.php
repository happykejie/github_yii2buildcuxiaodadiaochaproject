<?php
/**
 * Created by PhpStorm.
 * App: Administrator
 * Date: 2015/1/31
 * Time: 17:33
 */

namespace app\modules\admin\controllers;
use app\models\Banner;
use Yii;
use yii\web\Controller;
use app\models\Follow;
use app\models\YiiUser;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

/**
 * RestController short summary.
 *
 * RestController description.
 *
 * @version 1.0
 * @author yzhe
 */
class BannerController extends Controller
{
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
        $model=Banner::find()->orderBy('order asc');
        $count=$model->count();
        $page=new Pagination(['defaultPageSize'=>15,'totalCount'=>$count]);
        $items=$model->offset($page->offset)->limit($page->limit)->all();
        return $this->render('index',['page'=>$page,'items'=>$items]);//,'search'=>$search
    }
    /**
     * @add
     */
    public function actionAdd(){
        
        $model=new Banner();
        
        if($model->load(Yii::$app->request->post())//判断是否是表单提交
            && $model->validate() //验证表单提交的内容正确性
            ){
            $headimgurl_files = UploadedFile::getInstance($model, 'bighead_file');
            if($headimgurl_files){
                $model->bannerimgpath = $model->fileInput($headimgurl_files);
            }
            
            $model->createtime=date("Y-m-d H:i:s", time());
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
        $model=Banner::findOne(['id'=>$id]);
        if($model->load(Yii::$app->request->post())//判断是否是表单提交
            && $model->validate() //验证表单提交的内容正确性
            ){

            $headimgurl_files = UploadedFile::getInstance($model, 'bighead_file');
            if($headimgurl_files){
                $model->bannerimgpath = $model->fileInput($headimgurl_files);
            }
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
            Banner::deleteAll(['id'=>$id]);
            return 0;
        }
    }
}
