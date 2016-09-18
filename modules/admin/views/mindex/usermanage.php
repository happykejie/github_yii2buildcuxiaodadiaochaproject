<?php
use yii\helpers\Html;
use app\assets\AppAsset;
use yii\widgets\ActiveForm;
?>

<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<title>用户管理</title>
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		 <?=Html::cssFile('@web/web/assets/mui/css/mui.min.css')?>
    
		<style>
			html,body {
				background-color: #efeff4;
			}
			.title{
				margin: 20px 15px 10px;
				color: #6d6d72;
				font-size: 15px;
			}
			 .oa-contact-cell.mui-table .mui-table-cell {
				padding: 11px 0;
				vertical-align: middle;
			}
			
			.oa-contact-cell {
				position: relative;
				margin: -11px 0;
			}
	
			.oa-contact-avatar {
				width: 75px;
			}
			.oa-contact-avatar img {
				border-radius: 50%;
			}
			.oa-contact-content {
				width: 100%;
			}
			.oa-contact-name {
				margin-right: 20px;
			}
			.oa-contact-name, oa-contact-position {
				float: left;
			}
            img{
                height:50px;
                width:50px;
            }
		</style>
	</head>

	<body>
		<div class="mui-content">
			<div class="title">
			用户管理
			</div>

			<ul class="mui-table-view mui-table-view-striped mui-table-view-condensed">


               <?php if(count($items)>0):?>
                <?php foreach($items as $v):?>


                <li class="mui-table-view-cell">
					<div class="mui-slider-cell">
						<div class="oa-contact-cell mui-table">
                      
							<div class="oa-contact-avatar mui-table-cell">
								<img src="<?=$v->headimgurl?>" />
							</div>
                           
							<div class="oa-contact-content mui-table-cell">
								<div class="mui-clearfix">
								<a href="userinfo?id=<?=$v->id?>">	<h4 class="oa-contact-name"> 账号：<?=$v->user?>&nbsp;&nbsp;</br>昵称：<?=$v->nickname?> </h4></a>
								</div>

                                <div style="float:left;margin-top:20px;">

                                    <a href="mypublished?id=<?=$v->id?>"  >发布数量:<?=count($v->getUserpublishcount())?></a>
                                </div>


                                 <div style="float:left;margin-top:20px; margin-left:10px">

                                   分享人数:<?=count($v->getUserfenxiangcount())?>
                                </div>


                                



                             

                                <div style="float:right">

                                           <?php $form=ActiveForm::begin(['id'=>'usermanage','enableAjaxValidation'=>false]); ?>


                                <input type="hidden" id="user-id" class="form-control" name="User[id]" value="<?=$v->id?>"/>

                                <?=Html::submitButton('禁用',['class'=>'mui-btn delete-button delete mui-btn-primary'])?>
                               
                                <?php ActiveForm::end()?>
                                </div>

                            

							</div>

                            
                     

						</div>
					</div>
				</li>



               




              

                <?endforeach?>
                <?endif?>

                        <?php if(count($items)<=0):?>
                        <li style="background-color: #efeff4;">
                            <div style="margin-top: 30px;">
                                <div class="face">
                                    <img src="/web/assets/mui/images/face.png" />
                                </div>
                                <p class="remind-text">您暂时还没有用户哦！</p>
                               
                            </div>
                        </li>
                        <?endif?>


				
				
			</ul>
		</div>
	</body>
	
    <?=Html::jsFile('@web/web/assets/mui/js/mui.min.js')?>
	<script>
	    mui.init({
	        swipeBack: true //启用右滑关闭功能
	    });
	</script>
</html>


