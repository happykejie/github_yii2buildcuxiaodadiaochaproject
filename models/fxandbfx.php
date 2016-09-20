<?php

namespace app\models;
use yii\web\UploadedFile;
use Yii;

/**
 * This is the model class for table "{{%activity}}".
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
class fxandbfx extends \yii\db\ActiveRecord
{
    public $homepictures=[];
    public $newspictures=[];
    public $surface_file;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%fxandbfx}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
           
          
           
         
        ];
    }
    
    
   

    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fxrenid' => '分享人ID',
            'bfxrenid' => '被分享人ID',
            'createtime' => '创建时间',
            'remark' => '备注',

        ];
    }
    
}
