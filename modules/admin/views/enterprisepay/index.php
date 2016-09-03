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
                    <!--<div class="tool">
                        <button id="btn_add" class="btn btn-primary btn-sm" action="<?=Yii::$app->urlManager->createUrl('admin/users/add')?>">新增用户</button>
                    </div>-->
                    <div class="row">
                        <?php $form=ActiveForm::begin(['id'=>'index','enableAjaxValidation'=>false]); ?>

                        <div class="col-md-2">
                            <?=$form->field($search,'applyname')->textinput();?>
                        </div>
                        <div class="col-md-1">
                            <?=Html::submitButton('搜索',['class'=>'btn btn-primary primary'])?>
                        </div>
                        <?php ActiveForm::end()?>
                    </div>
                    <table class="table table-hover">
                        <tr>
                            <th>申请人姓名</th>
                            <th>申请人Openid</th>
                            <th>电话号码</th>
                            <th>金额</th>
                            <th>申请时间</th>
                            <th>审核时间</th>
                            <th>状态</th>
                            <th>审核人</th>
                            <th>操作</th>
                        </tr>

                        <?php if(count($items)>0):?>

                        <?php foreach($items as $v):?>
                        <tr>

                            <td><?=$v->applyname?></td>
                            <td><?=$v->applyopenid?></td>
                            <td><?=$v->phone?></td>

                            <td><?=$v->money?> </td>

                            <td><?=$v->applytime?> </td>
                            <td><?=$v->examinetime?></td>
                            <td><? $state=$v->state;
                                   if ($state==1)
                                   {
                                   	echo "审核通过";
                                   }else
                                   {
                                       echo $v->remark;
                                   }
                                ?></td>
                            <td><?=$v->examinename?></td>

                            <td>
                                <?php if($state==0):?>
                                <button class="btn btn-sm btn-danger btn-examine"  action="<?=Yii::$app->urlManager->createUrl(['admin/enterprisepay/examine','id'=>$v->id])?>">通过</button>
                                <button class="btn btn-sm btn-danger btn-examineno"  action="<?=Yii::$app->urlManager->createUrl(['admin/enterprisepay/examineno','id'=>$v->id])?>">不通过</button>
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
                    <h4 class="modal-title" id="myModalLabel">组别操作</h4>
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


    <div class="modal fade" id="deleteModal" url='' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">关闭</span></button>
                    <h4 class="modal-title" id="myModalLabel">删除组别</h4>
                </div>
                <div class="modal-body">
                    确定删除吗？
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

            //编辑信息
            $("#btn_add").click(function (e) {
                $("#frameModal iframe").attr("src", $(this).attr("action"));
                $('#frameModal').modal('show');
            });

            $('#frameModal').on('hidden.bs.modal', function () {
                window.location.reload();
            });

            //弹出删除确认框按钮
            $(".btn-delete").click(function () {
                $("#deleteModal .sure").attr("action", $(this).attr("action"));
                $('#deleteModal').modal('show');
            });



            $("#deleteModal .sure").click(function () {
                //执行删除

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
