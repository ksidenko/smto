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
 * Action, displays rendered images
 *
 * Config Options:
 * - p2.file.imagePresets
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id: P2FileImageAction.php 521 2010-03-24 13:20:13Z schmunk $
 * @package p2.actions
 * @since 2.0
 */
class P2FileImageAction extends CAction {

    public function run() {

        // check preset
        if (isset($_GET['preset']) && isset(Yii::app()->params['p2.file.imagePresets'][$_GET['preset']])) {
            $preset = new CMap(Yii::app()->params['p2.file.imagePresets'][$_GET['preset']]);
        } else {
            throw new Exception('No preset specified!');
        }

        if (is_numeric($_GET['id'])) {
            $result = self::processMediaFile($_GET['id'], $preset);
            switch($result['type']) {
                case 'public':
                    header('location: '.$result['data']);
                    break;
                case 'protected':
                    $model = self::findModel($_GET['id']);
                    self::sendImage($result['data'], $model, $preset);
                    break;
                default:
                    self::sendErrorImage();

            }
        } else {
            throw new Exception('No id specified!');
        }

        exit;
    }

    /**
     * Renders an image from P2File specified by id and preset
     *
     * @param integer $id
     * @param string $preset
     * @return mixed Rendering result, false if an error occured, otherwise an array with 'type' and 'data'
     */
    public static function processMediaFile($id, $preset) {
        
        Yii::trace('Processing media file #'.$id.' ...', 'p2.file');

        // get file from db
        $model = self::findModel($id);
        if(!$model) return false;

        $inFile = Yii::app()->basePath.DS.$model->filePath;
        $path = self::prepareRenderPath($preset['savePublic']);

        // debug - use missing.png if fiile not found
        if (!is_file($inFile)) {
            $inFile = P2Helper::findModule()->basePath.DS.'assets'.DS.'p2File'.DS.'missing.png';
            #exit;
        }

        if (is_file($inFile)) {

            if ($preset['originalFile'] === true) {
                // return original file and exit
                self::sendImage($inFile, $model, $preset);
            }

            $hash = self::generateHash($model, $preset);
            $outFile = $path.DS.$hash;

            if (is_file($outFile)) {
                // file exists
                #Yii::trace('found existing file.', 'p2.file');
            } else {
                Yii::log('Creating image from '.$inFile, CLogger::LEVEL_INFO, 'p2.file');
                if (!self::generateImage($inFile, $outFile, $preset)) {
                    Yii::log('Error while rendering '.$inFile, CLogger::LEVEL_INFO, 'p2.file');
                    $mimeImageDir = P2Helper::findModule()->basePath.DS.'assets'.DS.'p2File'.DS.'mimetypes'.DS;
                    $mimeImageFile = $mimeImageDir.CFileHelper::getMimeTypeByExtension($inFile).'.png';
                    if(!is_file($mimeImageFile)) {
                        Yii::log('Missing mime type image '.$mimeImageFile, CLogger::LEVEL_WARNING, 'p2.file');
                        $mimeImageFile = $mimeImageDir."mime-empty.png";
                    }
                    self::generateImage($mimeImageFile, $outFile, $preset);
                }
            }
        } else {
            self::sendErrorImage();
            Yii::log("File #{$id} missing!", LOG_WARNING);
        }

        // output
        if ($preset['savePublic'] === true) {
            return array('type'=>'public','data'=>Yii::app()->baseUrl.Yii::app()->params['publicRuntimeUrl'].'/p2File/'.$hash);
        } else {
            return array('type'=>'protected','data'=>$outFile);

        }

    }

    private static function prepareRenderPath($public = false) {
        $basePath = Yii::app()->basePath;

        // set render path
        if ($public === true) {
            $path = $basePath.DS.Yii::app()->params['publicRuntimePath'].DS.'p2File';
        } else {
            $path = $basePath.DS.Yii::app()->params['protectedRuntimePath'].DS.'p2File';
        }
        if (!is_dir($path)) {
            Yii::log('Creating render path in '.$path, CLogger::LEVEL_INFO, 'p2.file');
            mkdir($path, 0777, true);
        }

        if (!is_writable($path)) {
            throw new Exception('Runtime data path '.$path.' not writable');
        }

        return $path;

    }

    private static function findModel($id) {
        return P2File::model()->checkAccess()->with('P2Info')->findByPk($id); // TODO?

    }


    private static function generateHash($model, $preset) {
        $pathInfo = pathinfo($model->filePath);
        $hash = md5($model->fileMd5.CJSON::encode($preset->toArray()));
        if (isset($preset['type'])) {
            $hash = $hash.'.'.$preset['type'];
        } else {
            $hash = $hash.'.'.$pathInfo['extension'];
        }
        return $hash;
    }

    private static function generateImage($src, $dest, $preset) {
        try {
            $image = Yii::app()->image->load($src);
        } catch (Exception $e) {
            Yii::log($e->getMessage().' '.$src, CLogger::LEVEL_ERROR, "p2.file");
            return false;
        }
        if (isset($preset['commands'])) {
            $commands = $preset['commands'];
            foreach($commands as $command => $value) { // FIXME refelction, see API
                if ($command == 'savePublic') {
                    continue;
                }
                $count = count($value);
                switch ($count) {
                    case '2':
                        $image->$command($value[0],$value[1]);
                        break;
                    case '3':
                        $image->$command($value[0],$value[1],$value[2]);
                        break;
                    case '4':
                        $image->$command($value[0],$value[1],$value[2],$value[3]);
                        break;
                    default:
                        $image->$command($value);
                        break;
                }
            }
        }
        try {
            $image->save($dest);
            return true;
        } catch (Exception $e) {
            Yii::log($e->getMessage(), CLogger::LEVEL_ERROR, "p2.file");
            return false;
        }
    }

    private static function sendImage($image, $model, $preset) {
        header('Content-Type: '.CFileHelper::getMimeTypeByExtension($image)); // FIXME mimetype
        #header("Content-Length: {$this->_model->fileSize};\n"); // bugs with FF + Flash + ImageLoading
        header("Last-Modified: " . gmdate("D, d M Y H:i:s", strtotime($model->P2Info->modifiedAt)) . " GMT");

        if ($preset['contentDisposition'] === 'attachment') {
            header("Content-Disposition: attachment; filename=\"".basename($model->fileOriginalName)."\";\n\n");
        } else {
            header("Content-Disposition: inline; filename=\"".basename($model->fileOriginalName)."\";\n\n");
        }

        // FIX ME move to sendFile() function ...
        if ($preset['noCache'] === true) {
            header ("Cache-Control: no-store,max-age=0,must-revalidate");
            header ("Pragma: public");
            header ("Pragma: public_no_cache");
        }

        readfile($image);
        exit;

    }

    private static function sendErrorImage() {
        header('Content-Type: png');
        readfile(P2Helper::findModule()->basePath.DS.'assets'.DS.'p2File'.DS.'missing.png');
        exit();
    }
}
?>
