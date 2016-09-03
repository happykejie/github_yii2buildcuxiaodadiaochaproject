<?php
use yii\helpers\Html;
use app\assets\AppAsset;
use yii\widgets\ActiveForm;
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>后台管理系统</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=9" />
    <?=Html::cssFile('@web/web/assets/css/dpl-min.css')?>
    <?=Html::cssFile('@web/web/assets/css/bui-min.css')?>
    <?=Html::cssFile('@web/web/assets/css/main-min.css')?>
    <?=Html::cssFile('@web/web/css/site.css')?>
    <?=Html::jsFile('@web/web/assets/js/jquery-1.8.1.min.js')?>
    <?=Html::jsFile('@web/web/assets/js/bui-min.js')?>
    <?=Html::jsFile('@web/web/assets/js/common/main-min.js')?>
    <?=Html::jsFile('@web/web/assets/js/config-min.js')?>
    <!--[if lt IE 9]>
      <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script>
        $(function () {

        })
    </script>
</head>
<body>

    <div class="header">

        <div class="dl-title">
            <!--<img src="/chinapost/Public/assets/img/top.png">-->
        </div>

        <div class="dl-log">
            欢迎您，<span class="dl-log-user" id="<?= Yii::$app->user->getId()?>">
                <?= Yii::$app->user->identity->nickname?>(<?= Yii::$app->user->identity->user?>)</span>
            <span class="glyphicon glyphicon-envelope"></span>
            <span class="badge" id="msgnum">
                <?php if(Yii::$app->session->has('msg')):?> <?=Yii::$app->session->get('msg')?>
                <?else:?>0<?endif?>
            </span>
            <a href="<?=Yii::$app->urlManager->createUrl(['admin/index/logout'])?>" title="退出系统" class="dl-log-quit">[退出]</a>
        </div>
    </div>
    <div class="content">
        <div class="dl-main-nav">
            <div class="dl-inform">
                <div class="dl-inform-title"><s class="dl-inform-icon dl-up"></s></div>
            </div>
            <ul id="J_Nav" class="nav-list ks-clear">
                <li class="nav-item dl-selected">
                    <div class="nav-item-inner nav-home">系统管理</div>
                </li>
            </ul>
        </div>
        <ul id="J_NavContent" class="dl-tab-conten">
        </ul>
    </div>


    <script>
        var moments = "<?= Yii::$app->urlManager->createUrl('admin/index/users')?>";
        var thumb = "<?= Yii::$app->urlManager->createUrl('admin/index/thumb')?>";
        var sendmsg = "<?= Yii::$app->urlManager->createUrl('admin/msg/sendmsg')?>";
        var msg = "<?= Yii::$app->urlManager->createUrl('admin/msg/msg')?>";
        var mysend = "<?= Yii::$app->urlManager->createUrl('admin/msg/mysend')?>";

        var askproblem = "<?= Yii::$app->urlManager->createUrl('admin/askproblem/index')?>";

        var answerquestion = "<?= Yii::$app->urlManager->createUrl('admin/answerquestion/index')?>";
        var category = "<?= Yii::$app->urlManager->createUrl('admin/category/index')?>";


        var lovelistenquestion = "<?= Yii::$app->urlManager->createUrl('admin/lovelistenquestion/index')?>";
        var statistics = "<?= Yii::$app->urlManager->createUrl('admin/statistics/index')?>";
        var teacher = "<?= Yii::$app->urlManager->createUrl('admin/teacher/index')?>";
        var userattention = "<?= Yii::$app->urlManager->createUrl('admin/userattention/index')?>";

        var user = "<?= Yii::$app->urlManager->createUrl('admin/user/index')?>";
        var enterprisepay = "<?= Yii::$app->urlManager->createUrl('admin/enterprisepay/index')?>";

        BUI.use('common/main', function () {
            var config = [
                {
                    id: '1', homePage: "6", menu: [
                          {
                              text: '后台管理', items: [
                                 { id: '6', text: '问题列表', href: askproblem },
                                 { id: '7', text: '组别分类', href: category },
                                 { id: '8', text: '爱听列表', href: lovelistenquestion },
                                 { id: '9', text: '统计页面', href: statistics },
                                 { id: '10', text: '老师列表', href: teacher },
                                 { id: '11', text: '用户关注列表', href: userattention },
                                 { id: '12', text: '用户表', href: user },
                                 { id: '13', text: '问题回答列表', href: answerquestion },
                                 { id: '14', text: '金额提现列表', href: enterprisepay }
                              ]
                          }
                    ]
                }
            ];
            new PageUtil.MainPage({
                modulesConfig: config
            });
        });
    </script>
</body>
</html>
