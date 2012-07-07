<?php
/**
 * Class File
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @link http://www.phundament.com/
 * @copyright Copyright &copy; 2005-2010 diemeisterei GmbH
 * @license http://www.phundament.com/license/
 */

Yii::import("application.modules.p2.components.cellmanager.*");
Yii::setPathOfAlias('P2GoogleFeedWidget', dirname(__FILE__));

/**
 * Widget, displaying a RSS feed by Google API
 *
 * {@link http://code.google.com/intl/de/apis/ajaxfeeds/documentation/}
 *
 * If you request the google.feeds.Feed.JSON_FORMAT result format,
 * the feed attribute will be present in the feed result. 
 * The feed attribute has the following structure:
 * 
 * feed
 * title       The feed title. Corresponds to the <title> element in Atom and the <title> element in RSS.
 * link        The URL for the HTML version of the feed. Corresponds to the <link> element in Atom and the <link> element in RSS.
 * description The feed description. Corresponds to the <subtitle> element in Atom and the <description> element in RSS.
 * author      The feed author. Corresponds to the <name> element for the author in Atom.
 * 
 * entries[]   A list of all of the entries in the feed. Corresponds to the <entry> element in Atom and the <item> element in RSS.
 * title           The entry title. Corresponds to the <title> element in Atom and the <title> element in RSS.
 * link            The URL for the HTML version of the entry. Corresponds to the <link> element in Atom and the <link> element in RSS.
 * content         The body of this entry, inlcuding HTML tags. Since this value can contain HTML tags, you should display this value using elem.innerHTML = entry.content (as opposed to using document.createTextNode). Corresponds to the <content> or <summary> elements in Atom and the <description> element in RSS.
 * contentSnippet  A snippet (< 120 characters) version of the content attribute. The snippet does not contain any HTML tags.
 * publishedDate   The string date on which the entry was published of the form "13 Apr 2007 12:40:07 -0700". You can parse the date with new Date(entry.publishedDate). Corresponds to the <published> element in Atom and the <pubDate> element in RSS.
 * categories[]    A list of string tags for the entry. Corresponds to the term attribute for the <category> element in Atom and the <category> element in RSS.
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id: P2SkeletonWidget.php 401 2010-02-14 04:03:52Z schmunk $
 * @package p2.cellmanager.widgets
 * @since 2.0

 */
class P2GoogleFeedWidget extends P2BaseWidget {

    public      $view = 'feed';
    public      $uniqid;
    protected   $modelName = 'P2GoogleFeedWidgetForm';
    

    public function getCreateView() {
        return 'P2GoogleFeedWidget.views._form';
    }
    public function getEditView() {
        return 'P2GoogleFeedWidget.views._form';
    }

    public function init(){

        CGoogleApi::init(Yii::app()->params['google.apiKey']);
        CGoogleApi::load("feeds");
        CGoogleApi::register('feeds', '1', array(), Yii::app()->params['google.apiKey']);
        $this->uniqid = uniqid("feed");

        Yii::app()->clientScript->registerCoreScript("jquery");
        
        $script = '
function registerFeed(url,uniqid,entries){

var feed = new google.feeds.Feed(url);
    feed.setNumEntries(entries);
    feed.load(function(result) {
  if (!result.error) {
    container = $("#"+uniqid);
    headerPanel = $(".header", container);
    entriesPanel = $(".entries", container);
    entryTemplate = $(".entryTemplate", container);

    $(".title",headerPanel).html(result.feed.title);
    $(".link",headerPanel).attr("href",result.feed.link);
    $(".description",headerPanel).html(result.feed.description);
    $(".author",headerPanel).html(result.feed.author);

    for (var i = 0; i < result.feed.entries.length; i++) {
      entryData = result.feed.entries[i];
      $(".title",entryTemplate).html(entryData.title);
      $(".link",entryTemplate).attr("href",entryData.link);
      $(".shortContent",entryTemplate).html(entryData.contentSnippet);
      $(".fullContent",entryTemplate).html(entryData.content);
      $(".date",entryTemplate).html(entryData.publishedDate);
      //$(".categories",entryTemplate).html(entryData.categories);

      entryPanel = entryTemplate.clone();
      entriesPanel.append(entryPanel);
    }
    entryTemplate.detach();

  } else {
    //alert(result.error);
  }
});

}
';
        Yii::app()->clientScript->registerScript('P2GoogleFeedWidget', $script, CClientScript::POS_BEGIN);
        Yii::app()->clientScript->registerScript(
            'P2GoogleFeedWidget.feed'.$this->uniqid,
            'registerFeed("'.$this->model->getUrl().'","'.$this->uniqid.'","'.$this->model->numEntries.'");',
            CClientScript::POS_END);


    }

    public function run() {
        $this->render('feed');
    }

}


/**
 * FormModel for P2SkeletonWidget
 *
 * see also {@link P2SkeletonWidget}
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id: P2SkeletonWidget.php 401 2010-02-14 04:03:52Z schmunk $
 * @package p2.cellmanager.widgets
 * @since 2.0
 */
class P2GoogleFeedWidgetForm extends CFormModel {

    public $url;
    public $numEntries = 5;
    public $displayAuthor = false;
    public $displayDescription = true;
    public $displayShortContent = true;
    public $displayFullContent = false;
    public $displayEntryDate = true;

    public $safeAttributeNames = array();

    function rules() {
        return array(
            array('url', 'safe'),
            array('numEntries', 'numerical', 'integerOnly' => true),
            array('displayAuthor,displayDescription,displayShortContent,displayFullContent,displayEntryDate', 'boolean'),
        );
    }

    public function getUrl(){
        return str_replace("feed://","http://",$this->url);
    }
}
?>
