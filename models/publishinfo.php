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
class PublishInfo extends \yii\db\ActiveRecord
{
    
    public $headimg_file;
    public $backimg_file;
    public $layerrewardid;
    public $img=[];
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%publishinfo}}';
    }
    
   
    
    
    
    /**
     * 根据问题ID获取问题详细信息
     */ 
    public function askproblemone()
    {
       
    }
    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[  'title', 'headimg'], 'required'],
            [['id','order'], 'integer'],
            [['description','remark'], 'string'],
            [['remark'], 'string', 'max' =>225],
            [['headimg_file'], 'file', 'skipOnEmpty' => true],
            [['backimg_file'], 'file', 'skipOnEmpty' => true],
            
            
        ];
    }
    
    
    

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '发布信息ID',
            'title' => '标题',
            'description' => '简述',
            'detail' => '详情',
            'starttime' => '开始时间',
            'endtime' => '结束时间',
            'address' => '促销地址',
            'order' => '排序',
            'remark' => '备注',
            'headimg_file' => '图片1',            
            'backimg_file' => '图片2',

        ];
    }
    
    /**
     * Summary of fileInput
     * @param mixed $file 
     * @return array|string
     */
    public function fileInput($file)
    {
        $files=[];
        if(is_array($file)){
            foreach ($file as $f) {
                $image =  $f;
                $ext = $image->getExtension();
                $randName = time() . rand(1000, 9999) . "." . $ext;
                $rootPath = 'upload/';
                if (!file_exists($rootPath)) {
                    mkdir($rootPath,true);
                }
                $image->saveAs($rootPath . $randName); 
                $path=  '/'.$rootPath.$randName;
                array_push($files,$path);
            }
            return $files;
        }else{
            $image =  $file;
            $ext = $image->getExtension();
            $randName = time() . rand(1000, 9999) . "." . $ext;
            $rootPath = 'upload/';
            if (!file_exists($rootPath)) {
                mkdir($rootPath,true);
            }
            $image->saveAs($rootPath . $randName);
            $path=  '/'.$rootPath.$randName;
            array_push($files,$path);
            return $path;
        }
        return $files;
    }
}
