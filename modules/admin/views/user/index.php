<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Alert;
use yii\widgets\linkPager;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>老师管理</title>
    <?=Html::cssFile('@web/web/css/bootstrap.min.css')?>
    <?=Html::cssFile('@web/web/css/site.css')?>
    <?=Html::jsFile('@web/web/Js/jquery.js')?>
    <?=Html::jsFile('@web/web/Js/bootstrap.js')?>
</head>
<body>
    <div class="contianer">
        <div class="row">
            <div class="col-md-12">
                <div class="main">
                    <h2>用户管理</h2>

                    <div class="row">
                        <?php $form=ActiveForm::begin(['id'=>'index','enableAjaxValidation'=>false]); ?>

                        <div class="col-md-2">
                            <?=$form->field($search,'nickname')->textinput();?>
                        </div>

                        <div class="col-md-1">
                            <?=Html::submitButton('搜索',['class'=>'btn btn-primary'])?>
                        </div>
                        <?php ActiveForm::end()?>
                    </div>
                    <table class="table table-hover">
                        <tr>
                            <th>用户名</th>
                            <th>头衔</th>
                            <th>所在地</th>
                            <th>总收入</th>
                            <th>总支出</th>
                            <th>排序</th>
                            <th>操作</th>
                        </tr>

                        <?php if(count($items)>0):?>

                        <?php foreach($items as $v):?>
                        <tr>

                            <td><?=$v->nickname?></td>
                            <td><?=$v->title?></td>
                            <td><?=$v->city?></td>

                            <td>
                                <?php  $sumincome=($v->incomecost2mnumber()->sum('incomecostnum'));
                                       if($sumincome>0):?>
                                <?=$sumincome?>
                                <?else:?>
                                0
                        <?endif?>
                                元

                            </td>

                            <td>
                                <?php  $sumincome=($v->incomecost1mnumber()->sum('incomecostnum'))+($v->incomecost4mnumber()->sum('incomecostnum'));
                                       if($sumincome>0):?>
                                <?=$sumincome?>
                                <?else:?>
                                0
                        <?endif?>
                                元
                            </td>
                            <td><?=$v->userorder?></td>
                            <td>
                                <button class="btn btn-sm btn-danger btn-edit" id="" action="<?=Yii::$app->urlManager->createUrl(['admin/user/edit','id'=>$v->id])?>">编辑</button>
                                <?php  $userstate=$v->userstate;
                                       if($userstate==2):?>

                                <button class="btn btn-sm btn-danger btn-examine"  action="<?=Yii::$app->urlManager->createUrl(['admin/user/examine','id'=>$v->id])?>">通过</button>
                                <button class="btn btn-sm btn-danger btn-examineno"  action="<?=Yii::$app->urlManager->createUrl(['admin/user/examineno','id'=>$v->id])?>">不通过</button>
                                <?endif?>

                             
                      
                                <button class="btn btn-sm btn-danger btn-banuser"  action="<?=Yii::$app->urlManager->createUrl(['admin/teacher/banuser','id'=>$v->id])?>">禁用</button>
                            



                                <!--资金冻结功能-->
                                <?php  $banfee=$v->banfee;
                                       if($banfee==0):?>
                                <button class="btn btn-sm btn-danger btn-banfee"  action="<?=Yii::$app->urlManager->createUrl(['admin/teacher/banfee','id'=>$v->id])?>">冻结</button>
                                <?else:?>
                                <button class="btn btn-sm btn-danger btn-startfee"  action="<?=Yii::$app->urlManager->createUrl(['admin/teacher/startfee','id'=>$v->id])?>">解冻</button>

                                <?endif?>
                            </td>
                        </tr>
                        <?endforeach?>
                        <?else:?>
                        <tr>
                            <td colspan="5">暂无内容！</td>
                        </tr>
                        <?endif?>
                    </table>
                    <div class="page">
                        <?= LinkPager::widget(['pagination' => $page]) ?>
                        <?php if(count($items)>0):?>
                        <? echo "共".count($items)."条数据" ?>
                        <?endif?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="frameModal" url='' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">关闭</span></button>
                    <h4 class="modal-title" id="myModalLabel">人员操作</h4>
                </div>
                <div class="modal-body">
                    <iframe frameborder="0" height="400px" width="550px"></iframe>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="frameexamine" url='' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">关闭</span></button>
                    <h4 class="modal-title" id="myframeexamine">用户审核</h4>
                </div>
                <div class="modal-body">
                    <iframe frameborder="0" height="400px" width="550px"></iframe>
                </div>
                <div class="modal-footer">

                    <button type="button" class="btn btn-default cancel" data-dismiss="modal">取消</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="banuserModal" url='' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">关闭</span></button>
                    <h4 class="modal-title" id="myModalLabel">禁用用户</h4>
                </div>
                <div class="modal-body">
                    确定禁用该用户吗？
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary sure">确定</button>
                    <button type="button" class="btn btn-default cancel" data-dismiss="modal">取消</button>
                </div>
            </div>
        </div>
    </div>


 


    <div class="modal fade" id="banfeeModal" url='' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">关闭</span></button>
                    <h4 class="modal-title" id="myModalLabel">冻结用户资金</h4>
                </div>
                <div class="modal-body">
                    确定冻结该用户的资金吗？
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary sure">确定</button>
                    <button type="button" class="btn btn-default cancel" data-dismiss="modal">取消</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="startfeeModal" url='' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">关闭</span></button>
                    <h4 class="modal-title" id="myModalLabel">解冻用户资金</h4>
                </div>
                <div class="modal-body">
                    确定解冻用户资金吗？
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary sure">确定</button>
                    <button type="button" class="btn btn-default cancel" data-dismiss="modal">取消</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <script>
        $(function () {

            //审核信息
            $(".btn-examineno").click(function (e) {
                $("#frameexamine iframe").attr("src", $(this).attr("action"));
                $('#frameexamine').modal('show');
            });

            //审核信息
            $(".btn-examine").click(function (e) {
                $("#frameexamine iframe").attr("src", $(this).attr("action"));
                $('#frameexamine').modal('show');
            });


            //编辑信息
            $(".btn-edit").click(function (e) {
                $("#frameModal iframe").attr("src", $(this).attr("action"));
                $('#frameModal').modal('show');
            });

            //修改密码
            $(".btn-pwd").click(function (e) {
                $("#frameModal iframe").attr("src", $(this).attr("action"));
                $('#frameModal').modal('show');
            });


            //编辑信息
            $("#btn_add").click(function (e) {
                $("#frameModal iframe").attr("src", $(this).attr("action"));
                $('#frameModal').modal('show');
            });

            $('#frameModal').on('hidden.bs.modal', function () {
                window.location.reload();
            });

            //弹出禁用用户确认框按钮
            $(".btn-banuser").click(function () {

                $("#banuserModal  .sure").attr("action", $(this).attr("action"));
                $('#banuserModal').modal('show');
            });

        

            //弹出禁用金额确认框按钮
            $(".btn-banfee").click(function () {
                $("#banfeeModal .sure").attr("action", $(this).attr("action"));
                $('#banfeeModal').modal('show');
            });

            //弹出启用金额确认框按钮
            $(".btn-startfee").click(function () {
                $("#startfeeModal .sure").attr("action", $(this).attr("action"));
                $('#startfeeModal').modal('show');
            });



            $("#banuserModal .sure").click(function () {
                //执行禁用用户
                $.ajax({
                    type: "GET",
                    url: $(this).attr("action"),
                    success: function (data) {
                        if (data == 0) {
                            window.location.reload();
                        } else {
                            alert(data);
                        }
                    }
                });
            });

          

            $("#banfeeModal .sure").click(function () {
                //执行冻结金额
                $.ajax({
                    type: "GET",
                    url: $(this).attr("action"),
                    success: function (data) {
                        if (data == 0) {
                            window.location.reload();
                        } else {
                            alert(data);
                        }
                    }
                });
            });

            $("#startfeeModal .sure").click(function () {
                //执行解冻金额
                $.ajax({
                    type: "GET",
                    url: $(this).attr("action"),
                    success: function (data) {
                        if (data == 0) {
                            window.location.reload();
                        } else {
                            alert(data);
                        }
                    }
                });
            });
        })
    </script>
</body>
</html>
