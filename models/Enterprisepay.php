<?php

namespace app\models;
use Yii;


class Enterprisepay extends \yii\db\ActiveRecord
{
    public  $attentionname;
    //public $id;
    //public $username;
    //public $password;
    //public $authKey;
    //public $accessToken;

    /**
     * @inheritdoc 建立模型表
     */
    public static function tableName()
    {
        return '{{%enterprisepay}}';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        
            [['phone','state','id'], 'integer'],
            [['applyname','examinename','remark','applyopenid','applytime','examinetime'], 'string'],
            [['money'],'double'], 
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '用户Id',
            'applyname' => '申请人姓名',
            'applyopenid' => '申请人Openid',
            'phone' => '电话号码',
            'money' => '金额',
            'applytime' => '申请时间',
            'examinetime'=>'审核时间',
            'state' => '状态',
            'examinename' => '审核人',
            'remark' => '备注',
           
        ];
    }
}
