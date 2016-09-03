<?php
namespace app\modules\admin\controllers;
use Yii;
use yii\web\Controller;
use yii\web\Response;

use app\models\Activity;
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
                'only'=>['Player','Activity','Division','Players'],
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
    
    //活动基本信息、轮播图片
    //api/rest/activity/id
    public function actionActivity($id = null){
        if($id){
            return [Activity::findOne(['id'=>$id])];
        }else{
            return Activity::find()->all();
        }
    }
    
    
    //获取赛区列表
    //web/admin/rest/division/id
    public function actionDivision($id = null){
        if($id){
            return [Division::findOne(['id'=>$id])];
        }else{
            return Division::find()->all();
        }
    }
    
    //分页显示选手
    //web/admin/rest/players?key=张&pageindex=1&pagesize=2
    public function actionPlayers($key = null,$pageindex = null,$pagesize = null){
        if($pagesize==null){
            $pagesize = 15;
        }
        $players = null;
        if($key){
            $players = Player::find()->andWhere(['like', 'name', $key] );
        }else{
            $players = Player::find();
        }
        if($pageindex){
            $pages = new Pagination([
                'defaultPageSize' => $pagesize,
                'totalCount' =>$players->count()
                ]);
            $pages->setPage($pageindex - 1);
            $players= $players->offset($pages->offset)->limit($pages->limit);
        }
        $data = [];
        foreach($players->all() as $player)
        {
            array_push($data,[
                 'id'=>$player->id,
                  //头像大图
                 'bighead'=>$player->bighead,
                  //头像小图
                 'littlehead'=>$player->littlehead,
                 //名字
                 'name'=>$player->name,
                 //昵称
                 'nickname'=>$player->nickname,
                 //年龄
                 'age'=>$player->age,
                 //赛区
                 'division' => [$player->getdivision()],
                 //总排名
                 'ranking' => $player->GetRanking(),
                 //投票数
                 'vote' =>  $player->GetVote()
                ]
            );
        }
        
        return $data;
        
    }
    //成人报名-POST
    public function actionCR_Signup(){
        
    }
    
    //海豚报名-POST
    public function actionHT_Signup(){
        
    }
    //小孩报名-POST
    public function actionXH_Signup(){
        
    }
    
    //选手详情
    //web/admin/rest/player/1
    public function actionPlayer($id){
        $player = Player::findOne(['id'=>$id]);
        return [
                 'id'=>$player->id,
                  //头像大图
                 'bighead'=>$player->bighead,
                  //头像小图
                 'littlehead'=>$player->littlehead,
                 //名字
                 'name'=>$player->name,
                 //昵称
                 'nickname'=>$player->nickname,
                 //年龄
                 'age'=>$player->age,
                 //三围：胸围、腰围、臀围
                 'circumference' =>$player->circumference,
                 'waistline' =>$player->waistline,
                 'hipline' =>$player->hipline,
                //个人介绍
                  'profile' =>$player->profile,
                //介绍视频连接['http://youku.com/xxx'];
                  'video' =>$player->video,
                //介绍图片多张['http://youku.com/xxx','xx']
                  'img' =>$player->img,
                 //赛区
                 'division' => [$player->getdivision()],
                 //总排名
                 'ranking' => $player->GetRanking(),
                 //投票数
                 'vote' =>  $player->GetVote(),
                 
                 'lastcontribution'=>$player->GetLastContribution(),
                 'higcontribution'=>$player->GetHigContribution(),
                ];
        //最近贡献记录[贡献者昵称、贡献道具、贡献数量、提升排名]，根据时间排序
        //贡献排名前三名[头像、排名、昵称、总贡献值]
        
    }

    //新增接口：
    
    //造星工厂介绍，规则【前端可以写死】
    //造星工厂用户详情页:用户头像、用户昵称、贡献值、总排名、是否为参赛选手标识、我的关注列表（分页加载）【选手ID、头像、向该选手的贡献值】
    //造星工厂选手详情页:选手头像、选手名称、人气值、总排名、我的粉丝列表（分页加载）【用户ID、头像、贡献值】
    //私信读取接口（分页加载）【发送ID、头像、昵称、内容】
    //私信发送接口
    //用户富豪榜列表（分页加载）[用户头像、用户昵称、用户总贡献值、名次]
    //人气排行榜（分页加载）[选手头像、选手姓名、选手人气值、名次]
    
    //赠送道具接口
    
    //线下报名第一步【姓名、身份证】
    //线下报名第二步【头像大图、选手姓名、参赛赛区】绑定
    //抽票弹窗接口【票详情】
    //抽票使用接口
}
