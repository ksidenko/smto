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
 * @version $Id: P2Page.php 529 2010-03-28 22:52:26Z schmunk $
 * @package p2.models
 * @since 2.0
 */
class P2Page extends P2ActiveRecord {
    
    const PAGE_ID_KEY = 'pageId';
    const PAGE_NAME_KEY = 'pageName';
    
/**
 * The followings are the available columns in table 'P2Page':
 * @var integer $id
 * @var string $name
 * @var integer $parentId
 * @var string $descriptiveName
 * @var string $link
 * @var string $replaceHook
 * @var integer $rank
 * @var integer $p2_infoId
 */
    public $childs = null;

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
        return Yii::app()->params['p2.databaseName'].'p2_page';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
        array('name', 'required'),
        array('name','length','max'=>128),
        array('name', 'match', 'pattern' => '/^[a-zA-Z0-9-_.]+$/', 'message' => Yii::t('P2Module.p2','__VALIDATION_ERROR_REGEXP_ALPHANUMERIC__', array('{var}'=>'Name'))),
        array('descriptiveName','length','max'=>200),
        array('descriptiveName','required'),
        array('view','length','max'=>128),
        array('layout','length','max'=>128),
        array('url','length','max'=>256),
        array('replaceMethod','length','max'=>128),
        array('parentId, rank, p2_infoId', 'numerical', 'integerOnly'=>true),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
    // NOTE: you may need to adjust the relation name and the related
    // class name for the relations automatically generated below.
        return CMap::mergeArray(parent::relations(), array(
            'Parent' => array(self::BELONGS_TO, 'P2Page', 'parentId')
        ));
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
        'id' => 'Id',
        'name' => 'Name',
        'parentId' => 'Parent',
        'descriptiveName' => 'Descriptive Name',
        'link' => 'Link',
        'replaceHook' => 'Replace Hook',
        'rank' => 'Rank',
        'p2_infoId' => 'P2 Info',
        );
    }

    public static function getLayouts(){
        $return = Yii::app()->params['p2.page.availableLayouts'];
        if (!is_array($return))
                throw new Exception(Yii::t('P2Module.p2',"Application parameter [p2Page][availableLayouts] not specified in config!"));
        else
            return $return;
    }

    public static function getViews(){
        $return = Yii::app()->params['p2.page.availableViews'];
        if (!is_array($return))
                throw new Exception(Yii::t('P2Module.p2',"Application parameter [p2Page][availableViews] not specified in config!"));
        else
            return $return;
    }


    public function getUrlString($params = array(), $absolute = false) {

        if ($this->id == 1) {
            return Yii::app()->homeUrl;
        } elseif ($this->replaceMethod) {
            return null;
        } elseif (CJSON::decode($this->url) !== null) {
            $link = CJSON::decode($this->url);
        } elseif($this->url) {
            return $this->url;
        } else {
            $link['route'] = '/p2/p2Page/view';
            $link['params'] = CMap::mergeArray($params,array(P2Page::PAGE_ID_KEY=>$this->id,P2Page::PAGE_NAME_KEY=>$this->name));
        }

        if(isset($link['route'])) {
            $params = (isset($link['params']))?$link['params']:array();
            if ($absolute === true)
                return Yii::app()->controller->createAbsoluteUrl($link['route'], $params);
            else
                return Yii::app()->controller->createUrl($link['route'], $params);
        } else {
            throw new Exception('Could not determine URL string.');
        }
    }

    /**
     * Renders a UL,LI sitemap tree by default
     *
     * @param <type> $rootId
     * @param <type> $maxLevel
     * @param <type> $scope
     * @param <type> $mode
     * @param <type> $enclosures
     * @param <type> $translate
     * @return <type>
     */
    public static function renderTree($rootId = 1, $maxLevel = null, $scope = 'default', $mode = 'normal', $enclosures=array("<ul>","<li>","</li>","</ul>"), $translate = false) {
        return self::renderTreeRecursive(self::getTree($rootId,$scope),$maxLevel,$mode, $enclosures, $translate);
    }

    private static function renderTreeRecursive($root, $maxLevel = null, $mode='normal', $enclosures=array("<ul>","<li>","</li>","</ul>"), $translate, $currentLevel = 0) {
        $currentLevel++;
        $code = $enclosures[0];
        foreach($root AS $model) {

            /**
            * Basic functionality to mark items as active -- FIX ME
            */
            if ((self::getActivePage() !== null) && (self::getActivePage()->id == $model->id || self::getActivePage()->parentId == $model->id))
            {
                $active_enclosure = str_replace(">"," class='activeItem'>",$enclosures[1]);
                $code .= $active_enclosure;
            }
            else
            {
                $code .= $enclosures[1];
            }

            $name = ($model->descriptiveName)?$model->descriptiveName:$model->name;
            if ($translate !== false) {
                $name = P2Helper::t($translate, $name);
            }

            if ($mode == 'normal') {
                $code .= CHtml::link($name, $model->getUrlString());
            } else {
                $admin = $model->rank.' ';
                $admin .= " #".$model->id." ".CHtml::link($name, Yii::app()->controller->createUrl('/p2/p2Page/update',array('id'=>$model->id)));
                $admin .= " ".CHtml::link('[^]', $model->getUrlString(), array('target'=>'_blank'));
                $admin .= " <small>".$model->url.$model->view.$model->replaceMethod." (".$model->P2Info->language.")</small>";
                $code .= $admin;
            }
            #echo ">>>".$currentLevel ."-".$model->name."-". $maxLevel."<br/>";
            if (is_array($model->childs) && (($currentLevel <= $maxLevel) || $maxLevel === null)) {
                $code .= self::renderTreeRecursive($model->childs, $maxLevel, $mode, $enclosures, $translate, $currentLevel);
            }
            $code .= $enclosures[2];
        }
        $code .= $enclosures[3];
        return $code;
    }


    /**
     * Returns tree structrue of the sitemap specified with scope.
     * 
     * Access child nodes with $node->childs
     * 
     * @param <type> $rootId
     * @param <type> $scope
     * @return <type> 
     */
    public static function getTree($rootId = 1, $scope = null) {
        $cacheKey = 'P2Page:'.$rootId.':'.$scope.':'.Yii::app()->language.':'.Yii::app()->user->name;
        $mapByParentId=Yii::app()->cache->get($cacheKey);


        if($mapByParentId===false) {
        // regenerate $value because it is not found in cache
        // and save it in cache for later use:
        // get pages
            $criteria = new CDbCriteria();
            $criteria->order = "rank ASC";
            if ($scope != null) {
                $models = P2Page::model()->$scope()->findAll($criteria);
            } else {
                $models = P2Page::model()->findAll($criteria);
            }
            

            // build map, "group by" parentId; remove root
            $mapByParentId = array();
            foreach($models AS $key => $model) {
                if ($model->id != $rootId) {
                    $mapByParentId[$model->parentId][] = $model;
                } else {
                #$root[0] = clone($model);
                    unset($models[$key]);
                }
            }

            // reference childs, if found in map {
            foreach($models AS $model) {
                if (isset($mapByParentId[$model->id]) && $model->id !== $model->parentId) {
                    $model->childs = $mapByParentId[$model->id];
                }
            }

            // check for remaining elements, loops
            # FIXME

            $dependency = new CDbCacheDependency("SELECT MAX(modifiedAt) FROM p2_page LEFT OUTER JOIN p2_info ON p2_info.modelId = p2_page.id WHERE p2_info.model = 'P2Page'");
            Yii::app()->cache->set($cacheKey, $mapByParentId, 0, $dependency);

        }

        // attach map to root
            if (!isset($mapByParentId[$rootId])) {
                return array();
            } else {
                return $mapByParentId[$rootId];
            }

    }

    public static function getTreeByName($name, $scope = null) {
         if ($scope) {
        $model = P2Page::model()->$scope()->findByAttributes(array('name'=>$name));
            } else {
        $model = P2Page::model()->findByAttributes(array('name'=>$name));
            }


        if ($model instanceof P2Page) {
            return self::getTree($model->id, $scope);
        } else {
            return array();
        }
        
    }

    public static function getActivePage() {
        static $page;

        if(isset($page)) {
            return $page;
        }elseif(isset($_GET[P2Page::PAGE_ID_KEY])) {
            return $page = P2Page::model()->findByPk($_GET[P2Page::PAGE_ID_KEY]);
        } elseif (isset($_GET[P2Page::PAGE_NAME_KEY])) {            
            return $page = P2Page::model()->findByAttributes(array('name'=>$_GET[P2Page::PAGE_NAME_KEY]));
        } else {
            return null;
        }
    }

    public static function getPageChildren($id) {
            return P2Page::model()->findByAttributes(array('parentId'=>$id));
    }

   public static function getBreadCrumbs() {
        $page =  P2Page::model()->getActivePage();

        while ($page && $page->id != 1)
        {
            $breadcrumbs[$page->descriptiveName] = array('/cms/'.$page->id.'/'.$page->name);
            $page = $page->Parent;
        }

        return array_reverse($breadcrumbs);
    }

}
