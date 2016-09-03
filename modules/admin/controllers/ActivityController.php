<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/1/31
 * Time: 17:33
 */

namespace app\modules\admin\controllers;
use app\models\Activity;
use app\models\Category;
use Yii;
use yii\web\Controller;
use app\models\Follow;
use app\models\YiiUser;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use app\models\UploadForm;
use yii\web\Response;
class ActivityController extends Controller{
    public $enableCsrfValidation = false;
    public $layout  = 'layout';
    /**
     * @用户授权规则
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => BsauthActionFilter::className(),
            ],
        ];
    }
    /**
     * @msg消息列表
     */

    public function  actionIndex(){
        $model=new Activity();
        
        $count=$model->find()->count();
        $page=new Pagination(['defaultPageSize'=>20,'totalCount'=>$count]);
        $items=$model->find()->orderBy('name asc')->offset($page->offset)->limit($page->limit)->all();

        return $this->render('index',['page'=>$page,'items'=>$items]);


    }

    /**
     * @add
     */
    public function actionAdd(){
        
        $group= Category::find()->all();
        $to=array();
        foreach($group as $v){
            $to[$v->id]=$v->categoryname;
        }
        $model=new Activity();
        
        if($model->load(Yii::$app->request->post())//判断是否是表单提交
            ){
            
            if (Yii::$app->request->isPost) {
                $surface_files = UploadedFile::getInstance($model, 'surface_file');
                if($surface_files){
                    $model->surface = $model->fileInput($surface_files);
                    $model->setAttr("surface",$model->surface);
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
            
            if( $model->validate()){
                if($model->save()){
                    Yii::$app->response->redirect("/admin/activity/index");
                }else{
                    Yii::$app->session->setFlash('error','添加失败！');
                }
            }
        }
        return $this->render('add',['model'=>$model,"to"=>$to]);
    }
    
    public function actionEdit($id){
        $group= Category::find()->all();
        $to=array();
        foreach($group as $v){
            $to[$v->id]=$v->categoryname;
        }
        
        $model=Activity::findOne(['id'=>$id]);
        
        if($model->load(Yii::$app->request->post())//判断是否是表单提交
            ){
            $surface_files = UploadedFile::getInstance($model, 'surface_file');
            if($surface_files){
                $model->surface = $model->fileInput($surface_files);
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
            
            if( $model->validate()){
                if($model->save()){
                    Yii::$app->response->redirect("/admin/activity/index");
                }else{
                    Yii::$app->session->setFlash('error','修改失败！');
                }
            }
        }
        return $this->render('edit',['model'=>$model,"to"=>$to]);
    }
    
    public function actionDelete($id){ 
        if(Yii::$app->request->isAjax){
            //ajax请求删除
            
            ///先获取该活动关联图片
            $model = Activity::findOne(['id'=>$id]);
            
            $surfaceimg = $model->surface; //封面图片
            
            
            $newspicturesarraryimg = $model->newspictures;  // 展示图片
        
          $resultint =  Activity::deleteAll(['id'=>$id]);
          
          if($resultint>0)
          {
              $app_root =APP_ROOT;

              $del=unlink($app_root.$surfaceimg);
              
              foreach($newspicturesarraryimg as  $item)
              {
                  if(strlen($item)>5)
                  {
                      $del=unlink($app_root.$item);
                      
                  }
                  
                  
              }
          }
            return 0;
        }
    }
    
    public function actionUpload(){  
        Yii::$app->response->format = Response::FORMAT_JSON;   
        $model=new Activity();
        
        if (Yii::$app->request->isPost) {  
            $homepictures_files = UploadedFile::getInstances($model, 'homepictures');
            if($homepictures_files)
            {
                $model->homepictures=$model->fileInput($homepictures_files);
                return  implode('-',$model->homepictures).'-';
            }
            
            $newspictures_files = UploadedFile::getInstances($model, 'newspictures');
            if($newspictures_files)
            {
                $model->newspictures=$model->fileInput($newspictures_files);
                return  implode('-',$model->newspictures).'-';
            }
        }
        return false;
    }
    
    public function actionDeleteupload(){  
        Yii::$app->response->format = Response::FORMAT_JSON; 
        $key=  Yii::$app->request->post("key");
        if(empty($key)){
            $file = Yii::$app->basePath.$key;
            if(file_exists($file)){
                unlink($file);
            }
        }
        return true;
    }
} 