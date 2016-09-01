<?php

namespace app\models;
use Yii;
use yii\db\ActiveRecord;
use \yii\web\IdentityInterface;

class Banner extends \yii\db\ActiveRecord


{
    //public $id;
    //public $username;
    //public $password;
    //public $authKey;
    //public $accessToken;
    public $bighead_file;
    public $layerrewardid;
    public $img=[];
    
    /**
     * @inheritdoc 建立模型表
     */
    public static function tableName()
    {
        return '{{%banner}}';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order'], 'integer'],
            [['bannertitle','bannerimgpath','linkurl','remark','createtime'], 'string'],
            [['bighead_file'], 'file', 'skipOnEmpty' => true],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bannertitle' => '图片标题',
            'bannerimgpath' => '图片存储路径',
            'linkurl' => '图片链接地址',
            'order' => '排序',
            'createtime' => '创建时间',
            'remark' => '备注',
            'bighead_file'=>'上传图片',
        ];
    }
    
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