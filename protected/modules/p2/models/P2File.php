<?php
/**
 * Class File
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @link http://www.phundament.com/
 * @copyright Copyright &copy; 2005-2010 diemeisterei GmbH
 * @license http://www.phundament.com/license/
 */

/**
 * Model class
 *
 * Detailed info
 * <pre>
 * $var = code_example();
 * </pre>
 * {@link DefaultController}
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id: P2File.php 527 2010-03-28 19:32:03Z schmunk $
 * @package p2.models
 * @since 2.0
 */
class P2File extends P2ActiveRecord {
    /**
     *
     * The followings are the available columns in table 'P2File':
     * @var integer $id
     * @var string $name
     * @var string $filePath
     * @var string $fileType
     * @var integer $fileSize
     * @var string $fileOriginalName
     * @var string $fileMd5
     * @var string $fileInfo
     * @var integer $p2_infoId
     */
    const DEFAULT_PRESET = 'fckbrowse'; // rename to DEFAULT_IMAGE_PRESET 'ckbrowse' - project wide

    /**
     * Returns the static model of the specified AR class.
     * @return CActiveRecord the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return Yii::app()->params['p2.databaseName'].'p2_file';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('name','length','max'=>128),
            array('name', 'required'),
            #array('filePath', 'required'),
            array('p2_infoId', 'numerical', 'integerOnly'=>true),
        );
    }

    /**
     * @return array model behaviours
     */
    public function behaviors() {
        return CMap::mergeArray(parent::behaviors(), array(
            'FileUploadBehavior'=> array(
                'class' => 'application.modules.p2.behaviors.P2ActiveRecordFileUploadBehavior',
                'prefix' => 'file',
                'dataPath' => Yii::app()->params['protectedDataPath'].DS.'p2File',
                'enabled' => ($this->scenario == 'import')?false:true,
            ),
            )
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'Id',
            'name' => 'Name',
            'filePath' => 'File Path',
            'fileType' => 'File Type',
            'fileSize' => 'File Size',
            'fileOriginalName' => 'File Original Name',
            'fileMd5' => 'File Md5',
            'fileInfo' => 'File Info',
            'p2_infoId' => 'P2 Info',
        );
    }


    public function getFileList($type = null, $order = 'name ASC') {
        $criteria = new CDbCriteria();
        if ($type !== null) $criteria->condition = "fileType LIKE '".$type."'";
        $criteria->order = $order;
        $models = P2File::model()->with('P2Info')->findAll($criteria);
        return CHtml::listData($models, 'id', 'name');
    }

    public static function image($id = null, $presetName = self::DEFAULT_PRESET, $htmlOptions = array()) {
        Yii::import('p2.actions.P2FileImageAction');
        $preset = new CMap(Yii::app()->params['p2.file.imagePresets'][$presetName]);
        $result = P2FileImageAction::processMediaFile($id, $preset);
        $info = @getimagesize($result['data']);
        $alt = null; // FIXME

        if($info) {
            $htmlOptions['width'] = $info[0];
            $htmlOptions['height'] = $info[1];
        } else {
            if (!isset($htmlOptions['style'])) {
                $htmlOptions['style'] = "";
            }
            $htmlOptions['style'] .= ';max-width:'.$preset['commands']['resize'][0].'px; max-height: '.$preset['commands']['resize'][1].'px;';
        }

        switch ($result['type']) {
            case 'public':
                $src= $result['data'];
                break;
            case 'protected':
            default:
                $src=Yii::app()->controller->createUrl(
                    '/p2/p2File/image',
                    array('id'=>$id, 'preset'=>$presetName));
                break;
        }
        return CHtml::image($src, $alt, $htmlOptions);
    }
    public static function publish($id = null) {
        $model = self::model()->findByPk($id);
        if ($model !== null) {
            $file = Yii::app()->basePath.DS.$model->filePath;
            if (is_file($file)) {
                return Yii::app()->assetManager->publish($file);
            }
        }
        return false;
    }

}