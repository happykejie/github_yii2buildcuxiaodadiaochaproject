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
class Incomecost extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%incomecost}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'userid', 'incomecosttype', 'questionid','incomecostnum','dealtime'], 'required'],
            [['userid', 'questionid'], 'integer'],
           
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userid' => '参与人Id',
            'incomecosttype' => '收支类型',
            'questionid' => '问题Id',
            'incomecostnum' => '金额',
            'dealtime' => '交易时间',
        ];
    }
}
