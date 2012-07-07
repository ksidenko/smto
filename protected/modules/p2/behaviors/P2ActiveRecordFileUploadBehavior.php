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
 * Behavior, handles file uploads
 *
 * Detailed info
 * <pre>
 * $var = code_example();
 * </pre>
 * {@link DefaultController}
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id: P2ActiveRecordFileUploadBehavior.php 511 2010-03-24 00:41:52Z schmunk $
 * @package p2.behaviors
 * @since 2.0
 */
class P2ActiveRecordFileUploadBehavior extends CActiveRecordBehavior {

    const FILE_PREFIX = 'p2File_';
    const FILE_SEPARATOR = '_';
    const TRASH_FOLDER = 'trash';

    public $prefix;
    public $dataPath;

    public function beforeValidate($event) {

        if (!is_dir(Yii::app()->basePath.DS.$this->dataPath)) {
            mkdir(Yii::app()->basePath.DS.$this->dataPath);
        }

        // FIXME : Move file to trash when new file is uploaded
        $file = CUploadedFile::getInstanceByName($this->prefix.'Upload');
        if ($file instanceof CUploadedFile && $file->getError() == UPLOAD_ERR_OK) {
            $filePath = $this->dataPath.DS.uniqid(self::FILE_PREFIX).self::FILE_SEPARATOR.$file->getName();
            $savePath = Yii::app()->basePath.DS.$filePath;
            if ($file->saveAs($savePath)) {
                $this->Owner->filePath = $filePath;
                $this->Owner->fileType = $file->type;
                $this->Owner->fileSize = $file->size;
                $this->Owner->fileOriginalName = $file->name;
                $this->Owner->fileMd5 = md5($savePath);
            } else {
                $this->Owner->addError('filePath', 'File uploaded failed!');
            }
        } else {
            if ($this->Owner->isNewRecord) {
                $this->Owner->addError('filePath', 'No file uploaded!');
            }
        }
    }

    public function beforeSafe($event) {


        if ($this->Owner->isNewRecord) {
            Yii::log("File {$file->name} handled", LOG_DEBUG, "p2.db");
        }
        else {
            // nothing
        }
    }

    public function beforeDelete($event) {

        if (is_file(Yii::app()->basePath.DS.$this->Owner->filePath)) {
            $fileName = basename($this->Owner->filePath);
            $trashPath = Yii::app()->basePath.DS.$this->dataPath.DS.self::TRASH_FOLDER;
            if (!is_dir($trashPath)) {
                mkdir($trashPath);
            }
            if (!@rename(
            Yii::app()->basePath.DS.$this->Owner->filePath,
            $trashPath.DS.'id'.$this->Owner->id.'_'.$fileName)) {
                Yii::log("Error while moving file to trash", LOG_WARNING);
            }
        } else {
            Yii::log("Error file database mismatch!", LOG_WARNING);
        }
    }
}
?>
