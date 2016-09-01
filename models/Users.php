<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%msg}}".
 *
 * @property integer $id
 * @property integer $fid
 * @property integer $tid
 * @property string $title
 * @property string $content
 * @property integer $status
 * @property integer $send_time
 * @property integer $replay
 */
class Users extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%users}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        
            [['userorder','phone'], 'integer'],
            [['description'], 'string'],
            [['title'], 'string', 'max' =>225]
        ];
    }
    
    /**
     * 获取当前用户回答了多少个问题
     */ 
    public function askproblemnumber()
    {
        $model=Users::findOne(['id'=>$this->id,'userstate'=>1]);
        if (isset($model))
        {
            $model=  Askproblem ::find()->where(['answerpersonid'=>$this->id,'questionstate'=>1]);
            return $model;
        }else
        {
            return  1111;
        }
    }
    
    /**
     * 获取当前用户获取被关注次数
     */ 
    public function attentionnumber()
    {
        $model=Users::findOne(['id'=>$this->id,'userstate'=>1]);
        if (isset($model))
        {
            $model=  Userattention ::find()->where(['attentionuserid'=>$this->id]);
            return $model;
        }else
        {
            return  1111;
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '用户Id',
            'username' => '用户姓名',
            'usercreatetime' => '用户注册时间',
            'useraccount' => '用户帐号',
            'userrole' => '用户角色',
            'title' => '老师的头衔',
            'userstate'=>'用户状态',
            'description' => '老师的介绍',
            'phone' => '老师的电话',
            'questionprice' => '老师提问的价格',
            'createteachertime' => '成为老师的时间',
            'userorder' => '排序',
            'isenable'=>'是否启用',
            'openid'=>'Openid',
            'scope'=>'scope',
            'nickname'=>'昵称',
            'sex'=>'性别',
            'city'=>'城市',
            'country'=>'国家',
            'headimgurl'=>'头像',
            'remark'=>'备注',
        ];
    }
}
