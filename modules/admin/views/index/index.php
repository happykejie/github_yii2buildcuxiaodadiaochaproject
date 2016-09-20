<?php
use yii\helpers\Html;
use app\assets\AppAsset;
use yii\widgets\ActiveForm;
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>促销大调查后台管理系统</title>
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

        var banner = "<?= Yii::$app->urlManager->createUrl('admin/banner/index')?>";

        var enterprisepay = "<?= Yii::$app->urlManager->createUrl('admin/enterprisepay/index')?>";
        var useradmin = "<?= Yii::$app->urlManager->createUrl('admin/useradmin/index')?>";

        var userdisable = "<?= Yii::$app->urlManager->createUrl('admin/userdisable/index')?>";

        var uploadimg = "<?= Yii::$app->urlManager->createUrl('admin/tools/uploadimg')?>";

        var publishinfo = "<?= Yii::$app->urlManager->createUrl('admin/publishinfo/index')?>";

        var activity = "<?= Yii::$app->urlManager->createUrl('admin/activity/index')?>";





        BUI.use('common/main', function () {
            var config = [
                {
                    id: '1', homePage: "6", menu: [
                          //{
                          //    text: '系统管理', items: [
                          //          { id: '1', text: '朋友圈', href: moments },
                          //          { id: '2', text: '头像管理', href: thumb }
                          //    ]
                          //},
                          //{
                          //    text: '私信管理', items: [
                          //        { id: '3', text: '我的私信', href: msg },
                          //        { id: '4', text: '我发送的', href: mysend },
                          //        { id: '5', text: '发送私信', href: sendmsg }
                          //    ]
                          //}
                          //,
                          {
                              text: '后台管理', items: [
                                { id: '6', text: '活动管理', href: activity },
                                 { id: '7', text: '组别分类', href: category },
                                 
                                 { id: '10', text: '发布者列表', href: teacher },
                           
                                 { id: '12', text: '用户表', href: user },
                                 { id: '16', text: '管理员用户列表', href: useradmin },
                                 { id: '17', text: '禁用的用户', href: userdisable },
                                 { id: '14', text: '轮播图片', href: banner },
                                 { id: '15', text: '金额提现列表', href: enterprisepay },
                           

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
