<?php

/**
 * boshizhidaoconfig short summary.
 *
 * boshizhidaoconfig description.
 *
 * @version 1.0
 * @author Administrator
 */
define('Wx_Title','博士知道');
define('WX_APPID','wx9d87a8766d67046b'); //博士知道 wx9d87a8766d67046b   
define('WX_APPSECRET','dbe970c0d68d338693a5eb836689d83f');  ///博士知道dbe970c0d68d338693a5eb836689d83f   


define("CURR_DOMAIN",'boshizhidao.com');//配置当前域名

define("DOMAITDESC",'博士知道');

define("YHTWTZ",'KwpE1b0IyukwmfBLd18M5UxzM4pEnhq1dXqE2cOi91o'); // 博士知道  用户提问通知

define("SDHFTZ",'vfVV009pVO4ZkXwM4C1LiJvJ1ot7HFe4x32ISvfwW7k'); // 博士知道  收到回复通知

define("TKTZ",'xTBEu1vh5rZxRlSzL_tO90gsSai-z4HHl2ASPLeyU3k'); // 博士知道  退款通知

define("JJGQTX",'Ik8CJmg69xjVrdduWIvemsFsLG1Lm77aljMAIyEQMCE'); // 博士知道  即将过期提醒


///微信分享配置

define("WXFX_TITLE",'博士知道'); // 博士知道  微信分享——标题

define("WXFX_DESC",'博士知道：专业的平台,专业的问题,专业的回答'); // 博士知道  微信分享——分享描述

define("WXFX_LINK",'http://www.boshizhidao.com'); // 博士知道  微信分享详细地址

define("WXFX_IMGPATH",'http://www.boshizhidao.com/web/images/bszdgetheadimg.jpg'); // 博士知道  微信分享图地址片

//默认官网
define("HTTPWWW",'http://www.boshizhidao.com'); // 博士知道  



//默认官网
define("WWW",'www.boshizhidao.com'); // 博士知道  




//////////////////////////微信支付////////////////////////////////////////////////////////////



//=======【基本信息设置】=====================================
//
/**
 * TODO: 修改这里配置为您自己申请的商户信息
 * 微信公众号信息配置
 * 
 * APPID：绑定支付的APPID（必须配置，开户邮件中可查看）
 * 
 * MCHID：商户号（必须配置，开户邮件中可查看）
 * 
 * KEY：商户支付密钥，参考开户邮件设置（必须配置，登录商户平台自行设置）
 * 设置地址：https://pay.weixin.qq.com/index.php/account/api_cert
 * 
 * APPSECRET：公众帐号secert（仅JSAPI支付的时候需要配置， 登录公众平台，进入开发者中心可设置），
 * 获取地址：https://mp.weixin.qq.com/advanced/advanced?action=dev&t=advanced/dev&token=2005451881&lang=zh_CN
 * @var string
 */


define('APPID','wx9d87a8766d67046b');
define('MCHID','1359663202');
define('KEY','0f20d16e0ace80ea1b0bf0ef89207a96');
define('APPSECRET','dbe970c0d68d338693a5eb836689d83f');


//=======【证书路径设置】=====================================
/**
 * TODO：设置商户证书路径
 * 证书路径,注意应该填写绝对路径（仅退款、撤销订单时需要，可登录商户平台下载，
 * API证书下载地址：https://pay.weixin.qq.com/index.php/account/api_cert，下载之前需要安装商户操作证书）
 * @var path
 */

define('SSLCERT_PATH','../bszdcert/apiclient_cert.pem');
define('SSLKEY_PATH','../bszdcert/apiclient_key.pem');
define('SSLCERTP12_PATH','../bszdcert/apiclient_cer.p12');
define('SSLROOTCA','../bszdcert/rootca.pem');

////证书完整路径

define('SSLCERT_PATHALL','/vendor/wxpayapi/bszdcert/apiclient_cert.pem');
define('SSLKEY_PATHALL','/vendor/wxpayapi/bszdcert/apiclient_key.pem');
define('SSLCERTP12_PATHALL','/vendor/wxpayapi/bszdcert/apiclient_cer.p12');
define('SSLROOTCAALL','/vendor/wxpayapi/bszdcert/rootca.pem');

//=======【curl代理设置】===================================
/**
 * TODO：这里设置代理机器，只有需要代理的时候才设置，不需要代理，请设置为0.0.0.0和0
 * 本例程通过curl使用HTTP POST方法，此处可修改代理服务器，
 * 默认CURL_PROXY_HOST=0.0.0.0和CURL_PROXY_PORT=0，此时不开启代理（如有需要才设置）
 * @var unknown_type
 */
const CURL_PROXY_HOST = "0.0.0.0";//"10.152.18.220";
const CURL_PROXY_PORT = 0;//8080;

//=======【上报信息配置】===================================
/**
 * TODO：接口调用上报等级，默认紧错误上报（注意：上报超时间为【1s】，上报无论成败【永不抛出异常】，
 * 不会影响接口调用流程），开启上报之后，方便微信监控请求调用的质量，建议至少
 * 开启错误上报。
 * 上报等级，0.关闭上报; 1.仅错误出错上报; 2.全量上报
 * @var int
 */
const REPORT_LEVENL = 1;//=======【基本信息设置】=====================================




////////////////////////////////支付订单////////////////////////////////////////

define('ORDERBODY','博士知道');
define('ORDERTAG','博士知道');
define('ORDERATTACH','博士知道');










