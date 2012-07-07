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
 * Widget, wraps jQuery tweet
 *
 * Detailed info
 * <pre>
 * $var = code_example();
 * </pre>
 * {@link DefaultController}
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id: ETweet.php 508 2010-03-24 00:35:21Z schmunk $
 * @package extensions.tweet
 * @since 2.0
 */
class ETweet extends CWidget {

    public $username;
    public $count = 5;
    public $loadingText = 'loading tweets...';
    public $autoJoinTextDefault = "he said,";
    public $autoJoinTextEd = "he";
    public $autoJoinTextIng = "he was";
    public $autoJoinTextReply = "he replied";
    public $autoJoinTextUrl = "he was checking out";

    public function run() {
        if($this->username === null) {
           throw new Exception('Twitter username not set!');
        }

        $file = Yii::app()->assetManager->publish(dirname(__FILE__).DS.'jquery.tweet.js');
        Yii::app()->clientScript->registerScriptFile($file);

        $script = 'jQuery(document).ready(function() { $("#'.$this->id.'").tweet({
          join_text: "auto",
          username: "'.$this->username.'",
          avatar_size: 48,
          count: '.$this->count.',
          auto_join_text_default: "'.$this-> autoJoinTextDefault.'",
          auto_join_text_ed: "'.$this->autoJoinTextEd.'",
          auto_join_text_ing: "'.$this->autoJoinTextIng.'",
          auto_join_text_reply: "'.$this->autoJoinTextReply.'",
          auto_join_text_url: "'.$this->autoJoinTextUrl.'",
          loading_text: "'.$this->loadingText.'"
        }); })';
        Yii::app()->clientScript->registerScript($this->id, $script);
        echo "<h2><a href='http://twitter.com/".$this->username."'>".$this->username." tweets</a></h2>";
        echo "<div id='".$this->id."' class='tweet'></div>";
    }
}
?>
