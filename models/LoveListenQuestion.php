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
class LoveListenQuestion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%lovelistenquestion}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
             [[ 'userid', 'questionid','buytime'], 'required'],
            [['userid'], 'integer'],
        ];
    }

    /**
     * 根据爱听问题的Id 获取问题详细信息
     */ 
    public function askproblem()
    {
        $model=  Askproblem ::findOne(['id'=>$this->questionid]);
        return $model;
    }

    //根据问题Id获取有多少人爱听
    public function lovenumber()
    {
        $count= LoveListenQuestion::find()->where(['questionid'=>$this->questionid])->count();
        return $count;
    }
    
        //根据爱听问题的Id 获取回答的详细信息
    public function getAnswerquestion()
    {
        $answerquestion= Answerquestion::findOne(['askquestionid'=>$this->questionid]);
        return $answerquestion;
    }
    
    
    
    /**
     * 根据问题ID获取问题详细信息
     */ 
    public function askproblemone()
    {
        $model=  Askproblem ::findOne(['id'=>$this->questionid]);
        return $model;
    }
    
    
    /**
     * @根据用户Id获取用户详细信息
     */
    public function getuser()
    {
        $model =User::findOne(['id'=>$this->userid]);
        return $model;
    }
    
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userid' => '用户ID',
            'question' => '问题Id',
            'buytime' => '提问时间',
        ];
    }
}
