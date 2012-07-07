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
 * Controller, handles feeds
 *
 * Detailed info
 * <pre>
 * $var = code_example();
 * </pre>
 * {@link DefaultController}
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id: DefaultController.php 510 2010-03-24 00:39:06Z schmunk $
 * @package p2.controllers
 * @since 2.0
 */
class FeedController extends CController {

    private $_types;
    private $_typeTitle;
    private $_urlMap;
    private $_mode = 'normal'; // local|normal|international

    public function beforeAction($event) {

        $types = Yii::app()->params['p2.info.types']['P2Html'];

        if(isset($_GET['type']) && array_key_exists($_GET['type'],$types)) {
            $this->_typeTitle = " - ".$types[$_GET['type']];
            $this->_types = array($_GET['type'] => $types[$_GET['type']]);
        } else {
            $this->_typeTitle = "";
            $this->_types = $types;
        }

        if(isset($_GET['mode'])) {
            $this->_mode = $_GET['mode'];
        }

        return true;
    }

    public function actionIndex() {
        $this->render('index',array('feeds'=>self::getFeeds()));
    }

    public function actionAtom() {
        $params['p2.feed.title'] = Yii::app()->name." ".$this->_typeTitle." - Atom ".(($this->_mode == "international")?$this->_mode:Yii::app()->params['languages'][Yii::app()->language].' '.$this->_mode);
        $params['p2.feed.entry.urlMap'] = $this->getUrlMap();
        $models = $this->getModels();
        $builder = new P2FeedBuilder('atom', $models, $params);
        $builder->build();
        $builder->send();
        exit; // no log output here
    }

    public function actionRss() {
        $params['p2.feed.title'] = Yii::app()->name.$this->_typeTitle." - RSS ".(($this->_mode == "international")?$this->_mode:Yii::app()->params['languages'][Yii::app()->language].' '.$this->_mode);
        $params['p2.feed.entry.urlMap'] = $this->getUrlMap();
        $models = $this->getModels();
        $builder = new P2FeedBuilder('rss', $models, $params);
        $builder->build();
        $builder->send();
        exit; // no log output here
    }

    public static function getFeeds() {
        $feeds[] = array('name' => 'Feed '.Yii::app()->name.' All RSS', 'url' => Yii::app()->controller->createAbsoluteUrl('/p2/feed/rss', array('mode'=>'international')));
        $feeds[] = array('name' => 'Feed '.Yii::app()->name.' All Atom', 'url' => Yii::app()->controller->createAbsoluteUrl('/p2/feed/atom', array('mode'=>'international')));
        foreach(Yii::app()->params->languages AS $code => $language) {
            $feeds[] = array('name' => 'Feed '.Yii::app()->name.' '.$language.' RSS', 'url' => Yii::app()->controller->createAbsoluteUrl('/p2/feed/rss', array('mode'=>'local','lang'=>$code)));
            $feeds[] = array('name' => 'Feed '.Yii::app()->name.' '.$language.' Atom', 'url' => Yii::app()->controller->createAbsoluteUrl('/p2/feed/atom', array('mode'=>'local','lang'=>$code)));
            $feeds[] = array('name' => 'Feed '.Yii::app()->name.' '.$language.'/International RSS', 'url' => Yii::app()->controller->createAbsoluteUrl('/p2/feed/rss', array('mode'=>'normal','lang'=>$code)));
            $feeds[] = array('name' => 'Feed '.Yii::app()->name.' '.$language.'/International Atom', 'url' => Yii::app()->controller->createAbsoluteUrl('/p2/feed/atom', array('mode'=>'normal','lang'=>$code)));
        }
        return $feeds;
    }


    private function getModels() {
        $entries = array();
        $criteria = new CDbCriteria;
        $criteria->with = array('P2Info');
        $criteria->condition = "";
        foreach($this->_types AS $i => $type) {
            $criteria->condition .= "P2Info.type = :type".md5($i)." OR ";
            $criteria->params[":type".md5($i)] = $i;
        }
        $criteria->condition .= " 0 = 1 ";
        $criteria->order = "P2Info.begin DESC, P2Info.end DESC";

        switch($this->_mode) {
            case 'international':
                return P2Html::model()->checkAccess()->active()->ongoing()->findAll($criteria);
                break;
            case 'local':
                return P2Html::model()->default()->localizedStrict()->ongoing()->findAll($criteria);
                break;
            default:
                return P2Html::model()->default()->ongoing()->findAll($criteria);
                break;
        }
    }

    private function getUrlMap() {
        $criteria = new CDbCriteria;
        $config = "piiBlogWidget.urlMap:";
        $criteria->condition = "customData LIKE '".$config."%'";
        $models = P2Page::model()->with('P2Info')->findAll($criteria);
        $urlMap = array();
        foreach ($models AS $model) {
            $lang = ($model->P2Info->language)?$model->P2Info->language:'NULL';
            $type = ($model->P2Info->customData)?str_replace($config,"",$model->P2Info->customData):'NULL';
            $urlMap[$lang][$type] = $model;
        }
        return $urlMap;
    }
}
