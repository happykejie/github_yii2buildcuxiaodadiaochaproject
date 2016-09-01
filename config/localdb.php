<?php


if(CURR_DOMAIN=='tatahaoyun.com')
{
    return [
'class' => 'yii\db\Connection',
'dsn' => 'mysql:host=localhost;dbname=tatahaoyun',
'username' => 'root',
'password' => '123456',
'charset' => 'utf8',
'tablePrefix'=>'sm_'
    ];
}


if(CURR_DOMAIN=='boshizhidao.com')
{
    return [
   'class' => 'yii\db\Connection',
   'dsn' => 'mysql:host=localhost;dbname=boshizhidao',
   'username' => 'root',
   'password' => '123456',
   'charset' => 'utf8',
   'tablePrefix'=>'sm_'
   ];
}






