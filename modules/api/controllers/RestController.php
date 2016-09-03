<?php
namespace app\modules\api\controllers;
use Yii;
use yii\web\Controller;
use yii\web\Response;

use app\models\LoveListenQuestion;
use app\models\Userattention;
use app\models\Users;
use app\models\Answerquestion;
use app\models\Askproblem;
use app\models\Division;
use app\models\Player;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;

/**
 * RestController short summary.
 *
 * RestController description.
 *
 * @version 1.0
 * @author yzhe
 */
class RestController extends Controller
{
    public $enableCsrfValidation = false;
    /**
     * @用户授权规则
     */
    public function behaviors()
    {
        return [
            'access' => [
                'only'=>['Askproblem','Activity','Division','Players'],
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
            ],
        ];
    }

    
    public function init()
    {
        parent::init();
        Yii::$app->response->format = Response::FORMAT_JSON;   
    }
    
    //获取所有已回答问题  加入Id 为 获取我提的并且已经回答的问题
    //api/rest/activity/id
    public function actionAskproblem($id = 0){
        if(isset($id)){
            return [Askproblem::find()->where(['questionstate'=>2,'askpersonid'=>$id])->orderBy('questionorder desc')->all()];
        }else{
            return [Askproblem::find()->where(['questionstate'=>2])->orderBy('questionorder desc')->all()];
        }
    }
    
    
    //根据问题Id获取爱听数量  没有id则为获取全部爱听数量
    //api/rest/lovenumber/id
    public function actionLovenumber($id=0){
        
        if(isset($id)){
            return [LoveListenQuestion::find()->where(['question'=>$id])->count()];
        }else{
            return [LoveListenQuestion::find()->count()];
        }
    }
    
    //根据分类Id获取所有已经回答的问题,不输入ID则显示为 所有已回答的问题
    //api/rest/askproblemcategory/id
    public function actionAskproblemcategory($id = null){
        if(isset($id)){
            return [Askproblem::find()->where(['questionstate'=>2,'categoryid'=>$id])->orderBy('questionorder desc')->all()];
        }else{
            return [Askproblem::find()->where(['questionstate'=>2])->orderBy('questionorder desc')->all()];
        }
    }
    
    //根据问题ID获取问题详细信息，并且获得问题回答详细信息
    //api/rest/askproblemdetailed/id
    public function actionAskproblemdetailed ($id = null){
        return  [Askproblem::findOne(['id'=>$id]),Answerquestion::findOne(['askquestionid'=>$id])];
       
    }
    
    
    //根据Id获取用户详细信息
    //api/rest/users/id
    public function actionUsers ($id = null){
        
        return  [Users::findOne(['id'=>$id])];
    }
    
    
      //根据被关注用户Id 获取被关注数量
    //api/rest/atttentioncount/id
    public function actionAtttentioncount ($id = null){
        
        return  [Userattention::find()->where(['attentionuserid'=>$id])->count()];
    }
    
    
    //当传入Id时 获取老师详细信息、没有传入Id的时候 获取老师列表
    //api/rest/teacher/id
    public function actionTeacher ($id = null){
        if (isset($id))
        {
            return  [Users::findOne(['userstate'=>1,'id'=>$id])];
        }else
        {
            return  [Users::find()->where(['userstate'=>1])->orderBy('userorder asc')->all()];
        }
        
    }
    
    
    //当传入Id时 获取老师详细信息、没有传入Id的时候 获取老师列表
    //api/rest/ansteacher/id
    public function actionAnsteacher ($id = null){

        if (isset($id))
        {
            return  [answerquestion ::findOne(['userstate'=>1,'id'=>$id])];
        }else
        {
            return  [answerquestion ::find()->where(['userstate'=>1])->orderBy('userorder asc')->all()];
        }   
    }
}
