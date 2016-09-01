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
    <title>后台用户管理</title>
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
                    <h2>后台用户管理</h2>

                    <?php $authkey=$currentUser->authkey;
                          if($authkey ='administrator'):?>
                    <div class="tool">
                        <button id="btn_add" class="btn btn-primary btn-sm" action="<?=Yii::$app->urlManager->createUrl('admin/useradmin/add')?>">新增用户</button>
                    </div>
                    <?endif?>
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
                             <th>用户账号</th>
                            <th>排序</th>
                            <th>操作</th>
                        </tr>

                        <?php if(count($items)>0):?>
                        <?php foreach($items as $v):?>
                        <tr>
                            <td><?=$v->nickname?></td>
                            <td><?=$v->user?></td>
                            <td><?=$v->userorder?></td>
                            <td>
                                <button class="btn btn-sm btn-danger btn-edit" id="" action="<?=Yii::$app->urlManager->createUrl(['admin/useradmin/edit','id'=>$v->id])?>">编辑</button>
                                <?if($authkey ='administrator'):?>
                                <button class="btn btn-sm btn-danger btn-pwd"  action="<?=Yii::$app->urlManager->createUrl(['admin/useradmin/pwd','id'=>$v->id])?>">修改密码</button>
                                <?endif?>
                                <button class="btn btn-sm btn-danger btn-delete"  action="<?=Yii::$app->urlManager->createUrl(['admin/useradmin/delete','id'=>$v->id])?>">删除</button>
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
