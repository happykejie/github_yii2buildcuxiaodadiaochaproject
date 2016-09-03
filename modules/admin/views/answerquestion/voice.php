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
    <title>组别管理</title>
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
                    <h2>语音列表</h2>
                    <!--<div class="tool">
                        <button id="btn_add" class="btn btn-primary btn-sm" action="<?=Yii::$app->urlManager->createUrl('admin/answerquestion/add')?>">新增组别</button>
                    </div>-->
                    <div class="row">
                        <?php $form=ActiveForm::begin(['id'=>'index','enableAjaxValidation'=>false]); ?>
                        <?php ActiveForm::end()?>
                    </div>
                    <table class="table table-hover">
                        <tr>
                            <th>语音</th>
                            <th>操作</th>

                        </tr>
                        <?php if(count($items)>0):?>
                        <?php foreach($items as $v):?>
                        <tr>
                            <td>
                            <button type="button"  id="<?=$v->answercontent?>"  onclick="playvoiceout('<?=$v->answercontent?>')" value="<?=$v->answercontent?>" class="mui-btn love-button ta mui-btn-primary" style="">
                                <img src="/web/assets/mui/images/yuy.png" />
                                点击播放
											<audio id="<?=$v->answercontent?>audio" autoplay >
                                                <source id="<?=$v->answercontent?>source" src="" type="audio/mpeg" />
                                            </audio>
                            </button>
                            <button type="button"  id="<?=$v->answercontent?>stop" onclick="stopVoiceout()" style="display:none;" class="mui-btn love-button ta mui-btn-primary" style="">
                                <img src="/web/assets/mui/images/strk.gif" />
                                暂停播放
                            </button>
                            <span id="<?=$v->answercontent?>audioLength" class="love-span1"><?=$v->answertimelength?>"</span>
                            <td>
                                <button class="btn btn-sm btn-danger btn-delete"  action="<?=Yii::$app->urlManager->createUrl(['admin/answerquestion/delete','id'=>$v->id])?>">删除</button>
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
		  var currentplayid = "";
		var at = 0;
		var len = null ;
           var audio;

           function playvoiceout(id) {
				len =  document.getElementById(id+"audioLength").innerHTML;
					len = parseInt(len);
					timedCount();
               if (currentplayid == "") ///first run 
               {
                   playvoice(id);
               }
               if (id == currentplayid) {
                   playvoice(id);
               }

               if (currentplayid.length>10) {
				 
                   if (id != currentplayid) {
                       ///停止当前音频播放
                       stopVoiceout();

                       if (id != currentplayid) {
                           playvoice(id);
                       }
                   }
               }
           }

           function stopVoiceout() {

               document.getElementById(currentplayid + "stop").style.display = "none";
               document.getElementById(currentplayid).style.display = "initial";
               document.getElementById(currentplayid).src = " ";
               audio.pause();//暂停播放 
           }

           function playvoice(id) {
               audio = document.getElementById(id + "audio");
               currentplayid = id;
               document.getElementById(id).style.display = "none";
               document.getElementById(id + "stop").style.display = "initial";
               audio.play();//播放
               document.getElementById(id + "source").src = "/../mediafile/" + id + "";
               audio.load();
           }
		   
		   		function timedCount() {
			if(at == len){
				stopCount();
				at = 0;
				document.getElementById(currentplayid+"stop").style.display = "none";
        	document.getElementById(currentplayid).style.display = "initial";
        	document.getElementById(currentplayid).src = " "; 
					return;
			}
				at++;
				t=setTimeout("timedCount()",1000) ;	
			} 
			function stopCount() 
			{ 
				clearTimeout(t) 
			} 
    </script>
     <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
       <script>


         

    </script>
</body>
</html>
