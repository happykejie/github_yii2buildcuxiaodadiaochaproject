<?php
use yii\helpers\Html;
use app\assets\AppAsset;
use yii\widgets\ActiveForm;
?>

<?php
require_once "models/WxJsSdk.php";
$jssdk = new WxJsSdk(WX_APPID, WX_APPSECRET);
$signPackage = $jssdk->GetSignPackage();
?>

<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
	<meta http-equiv="pragma" content="no-cache">
	<meta http-equiv="cache-control" content="no-cache">
	<meta http-equiv="expires" content="0">   
    <title>回答提问</title>
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <?=Html::cssFile('@web/web/assets/mui/css/mui.min.css')?>
    <!--App自定义的css-->
    <?=Html::cssFile('@web/web/assets/mui/css/css/recordings.css')?>
	


</head>
<body>
    <div class="mui-content" style="position:relative;">
        <input type="hidden" id="answercontent" name="answercontent" value="" />
        <ul class="mui-table-view mui-table-view-striped mui-table-view-condensed">
            <li class="mui-table-view-cell">
                <div class="mui-slider-cell">
                    <div class="oa-contact-cell mui-table">
                        <div class="oa-contact-avatar mui-table-cell">
                            <img src="<?php echo $item->getUser()->headimgurl?>" />
                        </div>
                        <div class="oa-contact-content mui-table-cell">
                            <div class="mui-clearfix">
                                <h4 class="oa-contact-name" style="position: relative;top: 15px;font-size: 16px;width:75%;">
									<?php echo $item->getUser()->nickname?>
								</h4>
                                <span class="recording-span">&yen; <?php echo $item->askfee?></span>
                            </div>

                        </div>
                    </div>
					   <input  type="hidden" id="askid" value="<?php echo $item->id?>" />
                    <h4 class="recording-h4"><?php echo $item->questiondescription?>?</h4>
                    <p style=" font-size: 14px;color: #999999;">
					 <?php $hour=ceil((time()-strtotime($item->asktime))/3600);
                              if($hour>48):?>
                        <? echo ceil((time()-strtotime($item->asktime))/3600/24)?>天前

                                         <?endif?>

                        <?php  if ($hour<48&&1<$hour):?>

                        <? echo $hour ?> 小时前
                                        <?endif?>

                        <?php   $minute=ceil((time()-strtotime($item->asktime))/60); 
                                if ($minute<=59):?>

                        <? echo $minute ?> 分钟前
                                        <?endif?>
                      
					</p>
                      	<div style="position: relative;">
						 		
						    <div>
                                  <?php $form=ActiveForm::begin(['id'=>'recordings','enableAjaxValidation'=>false]); ?>
                             <?=$form->field($item,'categoryid')->dropDownList($to)?>
                                  <?php ActiveForm::end()?>
                        </div>
                    
                    </div>
                </div>
            </li>
			 <li id="recording-li" class="mui-table-view-cell" style="min-height: 100px;" >
			 <div class="mui-slider-cell love-top ">
                            <div  id="imgw"class="oa-contact-cell mui-table" >
							
                                <div id="answermanimg" style="display:none" class="oa-contact-avatar mui-table-cell" >
                                    <img src="<?php echo $item->getUseranswer()->headimgurl?>" />
                                </div>
								 <?php
										  $nswerquestions=$item->getAnswerquestions();
										 $answercount  =count($nswerquestions);
										 $answercounthidden="<input hidden=\"hidden\" id=\"isexitdata\"  value=\"$answercount\"></input>";
									 echo $answercounthidden;
								?>

                                <div id="recordlist" class="oa-contact-content mui-table-cell" style="padding:13px;">
									 <?php
                                        if (isset($nswerquestions))
                                        {
                                            foreach ($nswerquestions as $v)
                                            {
												$i=1;
												$id =$v->id;
												$idshowly =$id."showly";
                                                $content = $v->answercontent;
												$contentstop =$content."stop";
													$contentaudio = $content."audio";
												$contentsource = $content."source";
												$timelength = $v->answertimelength;
													$showly2 ="<div id=\"$idshowly\" class=\"love-right\">";
													$showly3 = "<button type=\"button\"  id=\"$content\"  onclick=\"playvoiceout('$content')\" value=\"$content\" class=\"mui-btn-primary love-backcolor love-backcolor2\">";
													$showly4 ="<img src=\"/web/assets/mui/images/yuy.png\" />点击播放";
													 $showly5 ="<audio id=\"$contentaudio\" autoplay ><source id=\"$contentsource\" src=\"\" type=\"audio/mpeg\" /></audio>";
													 $showly6 = "<span class=\"love-span1\">$timelength\"</span>";
													 $showly7 = "</button>";
													 $showly8 = "<button type=\"button\" id=\"$contentstop\"  onclick=\"stopVoiceout()\" style=\"display:none;\" class=\"mui-btn-primary love-backcolor love-backcolor2\">";
													 $showly9 =  "<img src=\"/web/assets/mui/images/strk.gif\" />暂停播放";
													 $showly10 = "<span class=\"love-span1\">\"$timelength\"</span></button>";     
													$showly11 = "<span  onclick=\"deleterecord($id)\" class=\"mui-icon mui-icon-close\"> </span>";
													 $showly12 = "</div>";
													 echo $showly2.$showly3.$showly4.$showly5.$showly6.$showly7.$showly8.$showly9.$showly10.$showly11.$showly12;
                                            }	
                                        }
                                    ?> 
                                </div>
                            </div>
                        </div>
						
					
						
							     <div id="footer-botton" class="footer-botton" style="display:none;">
             <!--   <button type="submit" class="mui-btn-primary  recording-ti3">
                </button>-->
          
			
			
			 <input type="hidden" id="Answerquestion-askquestionid" class="form-control" name="Answerquestion[askquestionid]" value="<?php echo $item->id?>">
            <input type="hidden" id="Answerquestion-answercontent" class="form-control" name="Answerquestion[answercontent]" value="4">
			 <input type="hidden" id="Answerquestion-answertimelength" class="form-control" name="Answerquestion[answertimelength]" value="4">
			
			    <button type="submit"    class ="mui-btn-primary  recording-ti3" id="sub" onclick="ajaxsub()" />确认提交</button>
				 
				  

            </div>
						  </li>
			
        </ul>
		 <input style="display:none;" name="checkbox" value="Item 2" type="checkbox" checked="">
        <div class="recording-footer">
            <div class="mui-checkbox">
			   <p class="tongy">
				<?php				
					$str ="<span class=\"open\">公开</span>提问公开回答，答案每被人爱听1次，你就将获得0.5元";
					if($item->isopenask==0)
					{
						
					}else{
						echo $str;
					}
				?>
  
                </p>
            </div>
			
		
        
             <button class="btn btn_primary" id="uploadtoserver" style="display:none;">uploadtoserver</button>
            <div id="ly" class="footer-cent">
        		<p id="recording-p" class="recording-p">点击一下开始录音，每段录音时长不超过60秒，最多可以录制3段语音</p>
        		<div id="recordingon">
        		<div class="recording-button">
					<!--录音前-->
        		<div id="luy" class="luy"  >
        			
        				<img id="lyd" src="/web/assets/mui/images/icon2.png" alt="">
        			
        		</div>
        		<!--录音中-->
      		<div id="lyz" class="lyz" style="display: none;">
        			<div class="lyz-boder">
        			    <span class="lyz-a">录音中</span>
                        <img src="/web/assets/mui/images/lysty.gif" alt="Alternate Text" />
                    </div>
	
            </div>

				<span id="recording-time"  class="recording-time"></span>
        		</div>
        	</div>
        		</div>
       
        </div>
    </div>
	<input style="display: none;" id='totalrecord' value=' <?php echo (count($nswerquestions))?>'/>
   <?=Html::jsFile('@web/web/assets/mui/js/mui.min.js')?>
	<?=Html::jsFile('@web/web/Js/jquery.js')?>
	
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	
	
	<script type="text/javascript">

	  
	    ///ajax 确认录音提交

	    function ajaxsub() {

	        var askid = document.getElementById("askid").value; //问题id

	        var cgid = document.getElementById("askproblem-categoryid").value; //分类ID
	        alert(cgid);
	        var url = '/wenda/wxapi/subrecord';

	        //alert(url);

	        $.ajax({
	            url: url,
	            type: 'post',
	            data: {
	                id: askid,
	                categoryid: cgid
	            },
	            cache: false,
	            async: false,

	            success: function (data) {

	                //alert(data);

	                if (data) {
	                    //alert(" success");
	                    window.location.href = '/wenda/wenda/paywenda?id=' + askid + '';
	                }

	            },
	            error: function (xhr, errorType, error) {


	                $.ajax({
	                    url: url,
	                    type: 'post',
	                    data: {
	                        id: askid,
	                        categoryid: cgid
	                    },
	                    cache: false,
	                    async: false,

	                    success: function (data) {

	                        //alert(data);

	                        if (data) {
	                            //alert("again success");
	                            window.location.href = '/wenda/wenda/paywenda?id=' + askid + '';
	                        }

	                    },
	                    error: function (xhr, errorType, error) {
	                        alert('回答提交出错,请联系管理员');

	                    }
	                });
	            }
	        });
	    }

	    function againajaxsub() {

	    }



	    var totalrecord = document.getElementById('totalrecord').value;

	    function Trim(str, is_global) {
	        var result;
	        result = str.replace(/(^\s+)|(\s+$)/g, "");
	        if (is_global.toLowerCase() == "g")
	            result = result.replace(/\s/g, "");
	        return result;
	    }

	    $(function () {


	        var isexit = $("#isexitdata").val();

	        if (isexit > 0) {

	        }

	        var htmlstr = $("#recordlist").html();


	        var resulthtml = Trim(htmlstr, "g") //remove empty 

	        if (resulthtml == "") {

	            document.getElementById("footer-botton").style.display = "none";
	            document.getElementById("answermanimg").style.display = "none";
	        }
	        else {

	            document.getElementById("footer-botton").style.display = "block";
	            document.getElementById("answermanimg").style.display = "block";
	        }

	    })

	    function deleterecord(answerid) {

	        // alert("deleterecord"+answerid);

	        $.ajax({
	            url: '/admin/answerquestion/deletean',
	            type: 'get',
	            data: { 'id': answerid },
	            dataType: "json",
	            success: function (data) {

	                if (data == 0) {
	                    $("#" + answerid + "showly").remove();
	                    var htmlstr = $("#recordlist").html();

	                    var resulthtml = Trim(htmlstr, "g") //remove empty 

	                    if (resulthtml == "") {

	                        document.getElementById("answermanimg").style.display = "none";
	                        document.getElementById("footer-botton").style.display = "none";
	                    }
	                    if (totalrecord <= 3) {
	                        totalrecord--;
	                    }
	                }
	            },
	            error: function (xhr, errorType, error) {
	                console.log(error);
	            }
	        });
	    }







	    mui.init({
	        gestureConfig: {
	            tap: true, //默认为true
	            doubletap: true, //默认为false
	            longtap: true, //默认为false
	            swipe: true, //默认为true
	            drag: true, //默认为true
	            hold: false,//默认为false，不监听
	            release: false//默认为false，不监听
	        }
	    });






	    // 注意：所有的JS接口只能在公众号绑定的域名下调用，公众号开发者需要先登录微信公众平台进入“公众号设置”的“功能设置”里填写“JS接口安全域名”。 
	    // 如果发现在 Android 不能分享自定义内容，请到官网下载最新的包覆盖安装，Android 自定义分享接口需升级至 6.0.2.58 版本及以上。
	    // 完整 JS-SDK 文档地址：http://mp.weixin.qq.com/wiki/7/aaa137b55fb2e0456bf8dd9148dd613f.html

	    wx.config({
	        appId: '<?php echo $signPackage["appId"];?>',
		    timestamp: '<?php echo $signPackage["timestamp"];?>',
            nonceStr: '<?php echo $signPackage["nonceStr"];?>',
		    signature: '<?php echo $signPackage["signature"] ?>',
		    jsApiList: [
              // 所有要调用的 API 都要加到这个列表中
               'translateVoice',
                'startRecord',
                'stopRecord',
                'onRecordEnd',
                'playVoice',
                'pauseVoice',
                'stopVoice',
                'uploadVoice',
                'downloadVoice'
		    ]
		});



		/*秒数 */
		var c = 0;
		var t;
		var clicknum = 0;

		wx.ready(function () {
		    // 在这里调用 API
		    // 4 音频接口
		    // 4.2 开始录音
		    var voice = {
		        localId: '',
		        serverId: ''
		    };
		    document.querySelector('#luy').onclick = function () {
		        if (totalrecord == 3) {
		            c = null;
		            document.getElementById("luy").style.display = "block";
		            alert("已经录制3条");
		            return false;
		        } else {
		            c = 0;
		            document.getElementById("sub").disabled = true;
		            document.getElementById("luy").style.display = "none";
		            document.getElementById("lyz").style.display = "block";
		            document.getElementById("recording-time").innerHTML = c;
		            var p = document.getElementById("recording-p");

		            p.innerHTML = "语音录制中，再次点击停止录音";

		            timedCount();

		        }
		        wx.startRecord({
		            cancel: function () {
		                alert('用户拒绝授权录音');
		            }
		        });
		    };





		    // 4.3 停止录音
		    document.querySelector('#lyz').onclick = function () {
		        //alert("2");

		        document.getElementById("lyz").style.display = "none";
		        document.getElementById("luy").style.display = "block";

		        document.getElementById("sub").disabled = false;
		        document.getElementById("footer-botton").style.display = "block";
		        document.getElementById("answermanimg").style.display = "block"
		        var p = document.getElementById("recording-p");

		        p.innerHTML = "点击一下开始录音，每段录音时长不超过60秒，最多可以录制3段语音";
		        //document.getElementById("lywctime").innerHTML=c;

		        document.getElementById("Answerquestion-answertimelength").value = c;
		        stopCount();

		        document.getElementById("recording-time").innerHTML = "";



		        wx.stopRecord({
		            success: function (res) {
		                voice.localId = res.localId;

		                //alert(voice.localId);
		                uploadVoice(res);
		            },
		            fail: function (res) {
		                // alert(JSON.stringify(res));
		            }
		        });
		    };

		    // 4.4 监听录音自动停止
		    wx.onVoiceRecordEnd({
		        complete: function (res) {

		            voice.localId = res.localId;
		            alert('录音时间已超过60秒，请再次录音。');
		            c = 60;
		            document.getElementById("recording-time").innerHTML = c;
		            document.getElementById("Answerquestion-answertimelength").value = c;
		            //上传录音
		            uploadVoice(res);


		            document.getElementById("sub").disabled = false;
		            document.getElementById("lyz").style.display = "none";
		            document.getElementById("luy").style.display = "block";
		            document.getElementById("footer-botton").style.display = "block";
		            document.getElementById("answermanimg").style.display = "block"
		            document.getElementById("Answerquestion-answertimelength").value = c;
		            // document.getElementById("footer-botton").style.display = "none";
		            //document.getElementById("imgw").style.display= "none"
		            var p = document.getElementById("recording-p");
		            p.innerHTML = "点击一下开始录音，每段录音时长不超过60秒，最多可以录制3段语音";
		            stopCount();
		            document.getElementById("recording-time").innerHTML = "";

		        }
		    });

		    // 4.5 播放音频
		    document.querySelector('#lywc').onclick = function () {

		        document.getElementById("luy").style.display = "none";
		        document.getElementById("lyz").style.display = "none";
		        document.getElementById("lywc").style.display = "none";
		        document.getElementById("pauseVoice").style.display = "block";
		        var p = document.getElementById("recording-p");

		        p.innerHTML = "暂停播放";
		        document.getElementById("recording-time").innerHTML = c;

		        if (voice.localId == '') {
		            alert('请先使用 startRecord 接口录制一段声音');
		            return;
		        }
		        wx.playVoice({
		            localId: voice.localId
		        });
		    };

		    // 4.6 暂停播放音频
		    document.querySelector('#pauseVoice').onclick = function () {

		        document.getElementById("luy").style.display = "none";
		        document.getElementById("lyz").style.display = "none";
		        document.getElementById("pauseVoice").style.display = "none";
		        document.getElementById("lywc").style.display = "block";
		        var p = document.getElementById("recording-p");

		        p.innerHTML = "点击播放";
		        wx.pauseVoice({
		            localId: voice.localId
		        });
		    };

		    // 4.7 停止播放音频
		    document.querySelector('#stopVoice').onclick = function () {
		        document.getElementById("lyz").style.display = "none";
		        document.getElementById("lywc").style.display = "none";
		        document.getElementById("pauseVoice").style.display = "none";
		        document.getElementById("luy").style.display = "block";
		        var p = document.getElementById("recording-p");
		        p.innerHTML = "点击开始录音，录音时长不超过60秒";
		        document.getElementById("recording-time").innerHTML = "";
		        wx.stopVoice({
		            localId: voice.localId
		        });
		    };

		    // 4.8 监听录音播放停止
		    wx.onVoicePlayEnd({
		        complete: function (res) {
		            alert('录音（' + res.localId + '）播放结束');
		        }
		    });


		    //上传录音
		    function uploadVoice(res) {
		        //调用微信的上传录音接口把本地录音先上传到微信的服务器
		        //不过，微信只保留3天，而我们需要长期保存，我们需要把资源从微信服务器下载到自己的服务器
		        //alert(res.localId);
		        //alert(voice.localId);
		        wx.uploadVoice({
		            localId: res.localId, // 需要上传的音频的本地ID，由stopRecord接口获得
		            //isShowProgressTips: 1, // 默认为1，显示进度提示
		            success: function (res) {
		                //把录音在微信服务器上的id（res.serverId）发送到自己的服务器供下载。

		                // alert(res.serverId);
		                //  $("#Answerquestion-answercontent").val(res.serverId);
		                uploadVoicetoserver(res);




		            }
		        });
		    }
		    // 4.9 下载语音
		    document.querySelector('#downloadVoice').onclick = function () {

		        if (voice.serverId == '') {
		            alert('请先使用 uploadVoice 上传声音');
		            return;
		        }
		        wx.downloadVoice({
		            serverId: voice.serverId,
		            success: function (res) {
		                alert('下载语音成功，localId 为' + res.localId);
		                voice.localId = res.localId;
		            }
		        });
		    };


		    //上传语音到服务器
		    document.querySelector('#uploadtoserver').onclick = function () {
		        $.ajax({
		            url: 'http://www.cnlync.com/wenda/wxapi/uploadvoice',
		            type: 'get',
		            data: { 'serverId': 'thisisw2323', 'errMsg': 'errormsg' },
		            dataType: "json",
		            success: function (data) {
		                alert('文件已经保存到七牛的服务器');//这回，我使用七牛存储
		            },
		            error: function (xhr, errorType, error) {
		                console.log(error);
		            }
		        });
		    }


		});


		document.querySelector('#uploadtoserver').onclick = function () {

		    // alert('uploadtoserver');
		    $.ajax({
		        url: 'http://www.cnlync.com/wenda/wxapi/uploadvoice',
		        type: 'get',
		        data: { 'serverId': 'thisisw2323', 'errMsg': 'errormsg' },
		        dataType: "json",
		        success: function (data) {
		            alert('文件已经保存到七牛的服务器');//这回，我使用七牛存储
		        },
		        error: function (xhr, errorType, error) {
		            console.log(error);
		        }
		    });
		};




		function timedCount() {

		    document.getElementById('recording-time').innerHTML = c;

		    c++;
		    t = setTimeout("timedCount()", 1000);

		}

		function stopCount() {
		    clearTimeout(t)
		}





		//发送ServerId 到后台从服务器下载音频
		function uploadVoicetoserver(res) {
		    var askid = document.getElementById("askid").value; //问题id


		    var alength = document.getElementById("Answerquestion-answertimelength").value; //问题回答时长

		    var againurl = '/wenda/wxapi/uploadvoice?serverId=' + res.serverId + '&askid=' + askid + '&alength=' + alength;

		    var url = '/wenda/wxapi/uploadvoice';

		    $.ajax({
		        url: url,
		        type: 'post',
		        data: {
		            serverId: res.serverId,
		            askid: askid,
		            alength: alength
		        },
		        success: function (data) {
		            var jsonobj = eval('(' + data + ')');

		            var showly = "";

		            showly = "<div id=\"" + jsonobj.id + "showly\" class=\"love-right\">";
		            showly += "<button type=\"button\"  id=\"" + jsonobj.answercontent + "\"  onclick=\"playvoiceout('" + jsonobj.answercontent + "')\" value=\"" + jsonobj.answercontent + "\" class=\"mui-btn-primary love-backcolor love-backcolor2\">";
		            showly += " <img src=\"/web/assets/mui/images/yuy.png\" />点击播放";
		            showly += "<audio id=\"" + jsonobj.answercontent + "audio\" autoplay ><source id=\"" + jsonobj.answercontent + "source\" src=\"\" type=\"audio/mpeg\" /></audio>";
		            showly += "<span class=\"love-span1\">" + jsonobj.answertimelength + "\"</span>";
		            showly += "</button>";
		            showly += "<button type=\"button\"  id=\"" + jsonobj.answercontent + "stop\" onclick=\"stopVoiceout()\" style=\"display:none;\" class=\"mui-btn-primary love-backcolor love-backcolor2\" style=\"\">";
		            showly += "<img src=\"/web/assets/mui/images/strk.gif\" />暂停播放";
		            showly += "<span class=\"love-span1\">" + jsonobj.answertimelength + "\"</span></button>";
		            showly += "<span id=\"sr\" onclick=\"deleterecord('" + jsonobj.id + "')\" class=\"mui-icon mui-icon-close \"> </span>";
		            showly += "</div>";

		            // //document.getElementById("recordlist").appendChild(showly); 

		            $("#recordlist").append(showly);
		            if (totalrecord < 3) {
		                totalrecord++;
		            }
		        },
		        error: function (xhr, errorType, error) {

		            //alert(res.serverId);
		            //alert(xhr+error+errorType+'上传音频失败');
		            $.ajax({
		                url: url,
		                type: 'post',
		                data: {
		                    serverId: res.serverId,
		                    askid: askid,
		                    alength: alength
		                },
		                success: function (data) {



		                    var jsonobj = eval('(' + data + ')');
		                    //alert(jsonobj)
		                    //alert(jsonobj.id);
		                    var showly = "";
		                    showly = "<div id=\"" + jsonobj.id + "showly\" class=\"love-right\">";
		                    showly += "<button type=\"button\"  id=\"" + jsonobj.answercontent + "\"  onclick=\"playvoiceout('" + jsonobj.answercontent + "')\" value=\"" + jsonobj.answercontent + "\" class=\"mui-btn-primary love-backcolor love-backcolor2\">";
		                    showly += " <img src=\"/web/assets/mui/images/yuy.png\" />点击播放";
		                    showly += "<audio id=\"" + jsonobj.answercontent + "audio\" autoplay ><source id=\"" + jsonobj.answercontent + "source\" src=\"\" type=\"audio/mpeg\" /></audio>";
		                    showly += "<span class=\"love-span1\">" + jsonobj.answertimelength + "\"</span>";
		                    showly += "</button>";
		                    showly += "<button type=\"button\"  id=\"" + jsonobj.answercontent + "stop\" onclick=\"stopVoiceout()\" style=\"display:none;\" class=\"mui-btn-primary love-backcolor love-backcolor2\" style=\"\">";
		                    showly += "<img src=\"/web/assets/mui/images/strk.gif\" />暂停播放";
		                    showly += "<span class=\"love-span1\">" + jsonobj.answertimelength + "\"</span></button>";
		                    showly += "<span  onclick=\"deleterecord('" + jsonobj.id + "')\" class=\"mui-icon mui-icon-close\"> </span>";
		                    showly += "</div>";

		                    // //document.getElementById("recordlist").appendChild(showly); 
		                    $("#recordlist").append(showly);
		                    if (totalrecord < 3) {
		                        totalrecord++;
		                    }
		                },
		                error: function (xhr, errorType, error) {

		                    alert('上传音频失败');
		                }
		            });
		        }
		    });
		}

		function againajax(url) {

		}

		var currentplayid = "";

		var audio;

		function playvoiceout(id) {

		    if (currentplayid == "") ///first run 
		    {
		        playvoice(id);

		    }
		    if (id == currentplayid) {
		        playvoice(id);

		    }

		    if (currentplayid.length > 10) {
		        if (id != currentplayid) {
		            ///停止当前音频播放
		            stopVoiceout();
		            alert(1);
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
		    document.getElementById(id + "source").src = "../../mediafile/" + id + "";
		    audio.load();
		}



    </script>
	



</body>

</html>
