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
class Category extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'categoryname', 'createtime'], 'required'],
            [['remark','modifytime'], 'string'],
            [['remark'], 'string', 'max' => 225]
        ];
    }

    /*cate与news关联，获取关联的news信息[这里onCondition条件，相当于and]*/
    public function getName(){
       // return $this->hasOne(YiiUser::className(),["id"=>"tid"]);
        return $this->hasOne(YiiUser::className(),['id'=>'fid']);
    }

    public function getToname(){
        return $this->hasOne(YiiUser::className(),['id'=>'tid']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'categoryname' => '分类名称',
            'remark' => '备注：',
            'createtime' => '创建时间',
            'modifytime' => '修改时间',
        ];
    }
}
