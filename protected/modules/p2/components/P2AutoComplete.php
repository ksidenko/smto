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
 * Widget which shows an auto-complete input field for p2 models
 *
 * Detailed info
 * <pre>
 * $var = code_example();
 * </pre>
 * {@link DefaultController}
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id$
 * @package p2.widgets
 * @since 2.0
 */

class P2AutoComplete extends CWidget {

    const MODE_SELECT = 0;
    const MODE_UPDATE = 1;

    public $name = null;
    public $model = null;
    public $attribute = null;
    public $htmlOptions = array();

    public $class;
    public $inputClass;

    public $searchModel;

    private $baseSkin = array(
        #'name' => md5(rand()),
        'url' => array('/p2/ajax/search'),
        'formatResult'=>"function(select,pos,items,term){
                    return select[1];}",
        'formatItem'=>"function(select,pos,items,term){
                    return '#'+select[0]+' '+select[1]+' <i>'+select[2]+'</i>';}",
        'width' => '600px',
        'scrollHeight' => '400px',
        'max' => 25,
        #'value' => 'Search ...' // add clear string javascript
    );

    private $modelSkins = array(
        'P2File' => array(
                'extraParams' => array(
                        'model' => 'P2File',
                        'with[0]' => 'P2Info', // FIXME: -> array()
                        'search[0]' => 't.id' ,
                        'search[1]' => 'name',
                        'search[2]' => 'fileOriginalName',
                        'search[3]' => 'keywords',
                        'select[0]' => 'P2File.id' ,
                        'select[1]' => 'name',
                        'select[2]' => 'filePath',
                //'select[3]' => 'keywords', FIX ME: alias & relatiion?!? '<br/><i>Keywords</i>'+select[3]
                ),
                'formatItem'=>"function(select,pos,items,term){
                    return '<img style=\"max-width: 150px; max-height: 150px\" src=\"/p2/p2File/image/preset/fckbrowse/id/'+select[0]+'\" /><br/> #'+select[0]+' '+select[1];}",
        ),
        'P2Page' => array(
                'extraParams' => array(
                        'model' => 'P2Page',
                        'with[0]' => 'P2Info', // FIXME: -> array()
                        'scope[0]' => 'localized' ,
                        'search[0]' => 't.id' ,
                        'search[1]' => 'name',
                        'search[2]' => 'descriptiveName',
                        'search[3]' => 'keywords',
                        'select[0]' => 'P2File.id' ,
                        'select[1]' => 'name',
                        'select[2]' => 'descriptiveName',
                //'select[3]' => 'keywords', FIX ME: alias & relatiion?!? '<br/><i>Keywords</i>'+select[3]
                ),
        ),
        'P2Html' => array(
                'extraParams' => array(
                        'model' => 'P2Html',
                        'with[0]' => 'P2Info', // FIXME: -> array()
                        'scope[0]' => 'localized' ,
                        'search[0]' => 't.id' ,
                        'search[1]' => 'name',
                        'search[2]' => 'html',
                        'search[3]' => 'keywords',
                        'select[0]' => 't.id' ,
                        'select[1]' => 'name',
                        'select[2]' => 'html',
                //'select[3]' => 'keywords', FIX ME: alias & relatiion?!? '<br/><i>Keywords</i>'+select[3]
                ),
                'formatItem'=>"function(select,pos,items,term){
                        return '#'+select[0]+' <h3>'+select[1]+'</h3><br/>'+select[2].substr(0,300)+' '+select[3];}",

        ),
        'P2User' => array(
                'extraParams' => array(
                        'model' => 'P2User',
                        'search[0]' => 't.id' ,
                        'search[1]' => 'name',
                        'search[2]' => 'firstName',
                        'search[3]' => 'lastName',
                        'search[4]' => 'eMail',
                        'select[0]' => 't.id' ,
                        'select[1]' => 'name',
                        'select[2]' => 'firstName',
                        'select[3]' => 'lastName',
                ),
                'formatItem'=>"function(select,pos,items,term){
                    return '#'+select[0]+' '+select[1]+' '+select[2]+' '+select[3];}",
        ),
        'P2Cell' => array(
                'extraParams' => array(
                        'model' => 'P2Cell',
                        'search[0]' => 't.id' ,
                        'search[1]' => 'classPath',
                        'search[2]' => 'moduleId',
                        'search[3]' => 'controllerId',
                        'search[4]' => 'actionName',
                        'select[0]' => 't.id' ,
                        'select[1]' => 'classPath',
                        'select[2]' => 'controllerId',
                        'select[3]' => 'actionName',
                ),
                'formatItem'=>"function(select,pos,items,term){
                    return '#'+select[0]+' '+select[1]+' '+select[2]+' '+select[3];}",
        ),
    );

    public $mode;

    public function run() {

        if ($this->model !== null && $this->attribute !== null) {
            $this->name   = get_class($this->model)."[".$this->attribute."]";
            $valueField = CHtml::activeTextField(
            $this->model,
            $this->attribute,
            array('size'=>'4')
            );
        }
        elseif ($this->name != null) {
            $valueField = CHtml::textField(
            $this->name,
            null,
            array(
            #'class' => 'p2CellManagerClassProps',
            'size'=>'4',
            'style' => ($this->mode == self::MODE_SELECT)?'':'display: none',
            'disabled'=>null)
            );
        } else {
            throw new Exception('Please specify a name or model and attribute.');
        }

        $modelSkin = $this->modelSkins[$this->searchModel];
        $modelSkin['name'] = uniqid('p2AutoComplete_');
        $modelSkin['inputClass'] = $this->inputClass;

        // apply method
        switch($this->mode) {
            case self::MODE_SELECT:
                $modelSkin['methodChain'] = ".result(function(event,select){
                    \$(\"[name=".$this->name."]\").val(select[0]);})";
                break;
            case self::MODE_UPDATE:
                $modelSkin['methodChain'] = ".result(function(event,select){
                    url = '".$this->controller->createUrl("/p2/".$this->searchModel."/update", array('id'=>'_ID_'))."';
                    window.location.href = url.replace('_ID_',select[0]);
                    \$(\"#id\").val(select[0]);})";
                break;
        }

        echo "<span class='".$this->class."'>";

        $this->widget(
            'CAutoComplete',
            CMap::mergeArray($this->baseSkin, $modelSkin)
        );
        echo $valueField;

        echo "</span>";
    }
}
?>
