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
 * Creates RSS and ATOM feed (ALPHA!)
 *
 * from {@link P2Html}
 *
 * Config Options:
 * - p2.feed.title
 * - p2.feed.description
 * - p2.feed.author.name
 * - p2.feed.author.email
 * - p2.feed.author.uri
 * - p2.feed.entry.urlMap
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id$
 * @package p2.components
 * @since 2.1
 */

class P2FeedBuilder {

    private $_type;
    private $_models;
    private $_params;
    private $_xml;
    
    public function __construct($type, $models, $params){
        $this->_type   = $type;
        $this->_models = $models;
        $this->_params = CMap::mergeArray(Yii::app()->params, $params);
    }

    public function build() {
        $feed = new Zend_Feed_Writer_Feed;

        if ($this->_params['p2.feed.title'])
            $title = $this->_params['p2.feed.title'];
        else 
            $title = Yii::app()->name.' '.Yii::app()->language;

        $feed->setEncoding("utf-8");
        $feed->setTitle($title);
        $feed->setDescription(($this->_params['p2.feed.description'])?$this->_params['p2.feed.description']:Yii::app()->name." ".$this->_type." feed");
        $feed->setLink(Yii::app()->controller->createAbsoluteUrl("/"));
        $feed->setFeedLink(Yii::app()->controller->createAbsoluteUrl(""), $this->_type);

        if($this->_params['p2.feed.description']) $feed->setDescription($this->_params['p2.feed.description']);
        if($this->_params['p2.feed.author.name']) $feed->addAuthor(array(
                'name'  => $this->_params['p2.feed.author.name'],
                'email' => $this->_params['p2.feed.author.email'],
                'uri'   => $this->_params['p2.feed.author.uri'],
            ));
        $feed->setDateModified(time());

        foreach($this->_models AS $model) {
            // only 1st h1
            $title = $model->getHtml("//h1[1]");
            if (!$title) {
                $title = strip_tags(substr($model->getHtml(),0,stripos($model->getHtml()," ",20)))." ...";
            }
            // only 1st paragraph
            $description = strip_tags($model->getHtml("//p[1]"));
            if (!$description) {
                $description = strip_tags($model->getHtml());
            }
            // strip out h1-headline
            $content = trim(preg_replace("/<h1>.*<\/h1>/ms","",$model->getHtml()));
            if (!$content) {
                $content = $model->getHtml();
            }

            $entry = $feed->createEntry();
            $entry->setTitle($title);
            $entry->setDateModified(new Zend_Date($model->P2Info->modifiedAt,Zend_Date::ISO_8601));
            $entry->setDateCreated(new Zend_Date($model->P2Info->begin,Zend_Date::ISO_8601));
            $entry->addCategory(array('term'=>$model->P2Info->type));
            $entry->setDescription($description);

            // prepare link
            $linkParams = array(
                'postId'=>$model->id,
                'postName'=>$model->name,
                'lang'=>$model->P2Info->language,
            );
            if(isset($this->_params['p2.feed.entry.urlMap'][Yii::app()->language][$model->P2Info->type])) {
                $pageModel = $this->_params['p2.feed.entry.urlMap'][Yii::app()->language][$model->P2Info->type];
                $entry->setLink($pageModel->getUrlString($linkParams, true));
            }  else {
                #$this->_params['p2.feed.entry.url'][Yii::app()->language][$model->P2Info->type]
                $mapRoute = $this->_params['p2.feed.entry.url']['route'];
                $mapParams = CMap::mergeArray($linkParams,(isset($this->_params['p2.feed.entry.url']['params']))?$this->_params['p2.feed.entry.url']['params']:array());
                $entry->setLink(Yii::app()->controller->createAbsoluteUrl($mapRoute, $mapParams));
            }
            
            // need for atom
            $entry->addAuthor(array(
                'name'  => $model->P2Info->Creator->name,
                'email' => $model->P2Info->Creator->eMail,
                #'name'  => $model->P2Info->Creator->firstName.' '.$model->P2Info->Creator->lastName,
                #'uri'   => 'http://www.example.com',
            ));

            // different ATOM and RSS handling
            if ($this->_type == 'rss') {
                // Zend Feed Bug? no modified date available
                $entry->setDateModified(new Zend_Date($model->P2Info->begin,Zend_Date::ISO_8601));
                $entry->setContent($content);
            }
            else {
                // XML works best with numeric entities ... char will be kicked out if not found in table
                $entry->setContent(P2Helper::html_convert_entities($content));
            }
            $feed->addEntry($entry);
        }

        if ($this->_type == 'rss') {
            return $this->_xml = $feed->export($this->_type);
        }
        else {
            $trans = array("xhtml:"=>""); // w3c validator recommends this
            return $this->_xml = strtr($feed->export($this->_type),$trans);
        }
    }

    public function send(){
        #echo "<pre>".$this->_xml;exit;
        
        if ($this->_type == 'rss') {
            $feed = new Zend_Feed_Rss(null,$this->_xml);
        }
        else {
            $feed = new Zend_Feed_Atom(null,$this->_xml);
        }
        $feed->send();
    }


}
?>
