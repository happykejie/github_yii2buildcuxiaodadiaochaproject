<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/1/31
 * Time: 17:33
 */

namespace app\modules\admin\controllers;
use app\models\User;
use Yii;
use yii\web\Controller;
use app\models\Follow;
use app\models\YiiUser;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

class UserdisableController extends Controller{
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
        $currentUser = YiiUser::findOne(['id'=>Yii::$app->user->getId()]);
        
        $model=User::find()->orderBy("userstate desc")->where(['isenable'=>1]);
        $search=new User();
        if ($search->load(Yii::$app->request->post())) {
            if (!isset($model->nickname))
            {
                
                $model->andWhere(['like', 'nickname',$search->nickname]);
            }
        }
        $count=$model->count();
        $page=new Pagination(['defaultPageSize'=>50,'totalCount'=>$count]);
        $items=$model->offset($page->offset)->limit($page->limit)->all();
        return $this->render('index',['page'=>$page,'items'=>$items,'search'=>$search,'currentUser'=>$currentUser]);
    }
    
    public function actionEnable($id){ 
        $user = User::findOne(["id"=>$id]);
        if(Yii::$app->request->isAjax){
            
            $user->isenable=0;
            $user->save();
            ////ajax请求删除
            //User::deleteAll(['id'=>$id]);
            return 0;
        }
    }
} 