<?php
namespace app\modules\admin\controllers;

use Yii;
use yii\web\session;
use yii\helpers\Url;
use yii\base\Action;
use yii\base\ActionFilter;
use yii\web\Response;
use yii\web\Controller;
use yii\filters\AccessControl;

use app\models\YiiUser;
/**
 * WxauthActionFilter short summary.
 *
 * WxauthActionFilter description.
 *
 * @version 1.0
 * @author yzhe
 */
class BsauthActionFilter extends ActionFilter   
{
    //在action之前运行，可用来过滤输入
    public function beforeAction($action) {
        if (\Yii::$app->user->isGuest == "1") {
            Yii::$app->response->redirect(Url::to(['/admin/index/login'],true));
            return false;
        }
        return true;//如果返回值为false,则action不会运行
    }
    //在action之后运行，可用来过滤输出
    public function afterAction($action, $result) {
        return $result;//可以对action输出的$result进行过滤，retun的内容会直接显示
    }
}
