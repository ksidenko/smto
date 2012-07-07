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
 * @version $Id: P2Html.php 527 2010-03-28 19:32:03Z schmunk $
 * @package p2.models
 * @since 2.0
 */
class P2Html extends P2ActiveRecord {
    /**
     * The followings are the available columns in table 'P2Html':
     * @var integer $id
     * @var string $name
     * @var string $html
     * @var integer $p2_infoId
     */

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
        return Yii::app()->params['p2.databaseName'].'p2_html';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('name','length','max'=>64),
            array('name', 'required'),
            array('name', 'unique'),
            array('name', 'match', 'pattern' => '/^[a-zA-Z0-9-_.]+$/', 'message' => Yii::t('P2Module.p2','__VALIDATION_ERROR_REGEXP_ALPHANUMERIC__', array('{var}'=>'Name'))),
            array('html', 'length', 'min'=>0), //FIXME
            array('p2_infoId', 'numerical', 'integerOnly'=>true),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'Id',
            'name' => 'Name',
            'html' => 'Html',
            'p2_infoId' => 'P2 Info',
        );
    }

    public function splitHtml($marker, $before = true) {
        if ($before)
            return   preg_replace("/".$marker."(.*)/ms", "", $this->html);
        else
            return   preg_replace("/(.*)".$marker."/ms", $this->html);
    }

    public function getHtml($selector = null, $asXml = false) {
        if ($selector) {
            $doc = new DOMDocument('1.0', 'utf-8');
            #$html = strtr(html_entity_decode($this->html,ENT_COMPAT,'UTF-8'),array('&'=>'&amp;'));
            $html = P2Helper::html_convert_entities($this->html);
            $doc->loadXML("<?xml version='1.0' encoding='utf-8'?><html>".$html."</html>");
            $xpath = new DOMXPath($doc);
            $result = $xpath->query($selector);


            #if ($result->item(1))
            #    throw new Exception("More than one node selected");

            if ($result->item(0)) {
                $return = "";
                if ($asXml) {
                    echo "ijijoj";
                    for($i=0; $i<$result->length; $i++) {
                        $return .= $doc->saveXML($result->item($i));
                    }
                    return $return;
                }
                else {
                    #echo "ijijoj";
                    for($i=0; $i<$result->length; $i++) {
                        $return .= $result->item($i)->textContent;
                    }
                    return $return;
                }
            }
            else
                return false;
            break;
        } else
            return $this->html;

        /*
                switch($mode) {
            case '0': // default
                return $this->html;
                break;
            case '1': // headline only

                $doc = new DOMDocument();
                $doc->loadHTML("<html>".$this->html."</html>");
                $xpath = new DOMXPath($doc);
                $headline = $xpath->query($selector);
                if ($headline->item(0)) {
                    return trim($headline->item(0)->nodeValue);
                }
                else
                    return false;
                break;
            case '2': // text only
                $doc = new DOMDocument();
                $doc->loadHTML("<html>".$this->html."</html>");
                $xpath = new DOMXPath($doc);
                $headline = $xpath->query($selector);
                if ($headline->item(0)) {
                    $return = str_replace($headline->item(0)->nodeValue,"",$this->html);
                    return $return;
                }
                else
                    return $this->html;
                break;*/
    }



    public static function extractHeadline($html, $selector = "//h1[1]") {
        #return 'CVBN';

    }
}
