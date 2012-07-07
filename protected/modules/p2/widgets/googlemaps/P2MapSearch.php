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
 * Widget which displays a Google Maps search
 *
 * Detailed info
 * <pre>
 * $var = code_example();
 * </pre>
 * You have to create an API key here: {@link http://code.google.com/intl/de/apis/maps/signup.html}
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id: P2MapSearch.php 505 2010-03-24 00:19:06Z schmunk $
 * @package p2.cellmanager.widgets
 * @since 2.0
 */
class P2MapSearch extends CWidget {

    public $cc;
    public $headline;

    /**
     * Initializes the widget
     */
    public function init()
    {
        $this->registerClientScripts();
    }

    public function run()
    {
        $cc = self::getCC();

        echo "<div class='p2widget'>";
       
        echo '
         <div id="MapSearchWrapper">
         <h1>'.$this->headline.'</h1>
             <form>

                <table>
                    <tr class="formRow">
                        <td>Address:</td>
                        <td id="addressContainer"><input id="inputAddress" value="" /></td>
                        <td id="addressButtonContainer"><input class="submit" type="button" onclick="searchLocations(\'address\',\''. $cc .'\')" value="Search dealers by address"/></td>
                        <td id="radiusLabel">Radius in km:</td>
                        <td id="radiusContainer"><input id="inputRadius" value="50" /></td>
                    	
                    </tr>

                    <tr class="formRow">
                        <td>Name:</td>
                        <td ><input id="inputName" value="" /></td>
                        <td id="nameButtonContainer"><input class="submit" type="button" onclick="searchLocations(\'name\',\''. $cc .'\')" value="Search dealers by name"/></td>
                   		
                    </tr>

                    <tr>
                        <td id="MapContainer" colspan="5">
                            <div id="map"></div>
                        </td>
                        <td>
                            <div id="sidebar"></div>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        ';
        
        echo "</div>";

        // TODO
    }

    public function getCC()
    {
        return $this->cc;
        
        /*$country_code_raw = strstr(Yii::app()->language, '_');
        $country_code = str_replace("_", "", $country_code_raw);
        $country_code_upper = strtoupper($country_code);
        
        return "DE";*/
    }

    /**
     * Registers custom stylesheet and javascript files
     */
    public function registerClientScripts()
    {
        Yii::app()->clientScript->registerScriptFile("http://maps.google.com/maps?file=api&v=2&key=".Yii::app()->params['google.apiKey']);
        $script = dirname(__FILE__).DS."/googleMaps.js";
        $url = Yii::app()->assetManager->publish($script);
        Yii::app()->clientScript->registerScriptFile($url);

        $script = "load('images/icon_merida_map_no_shadow.png','images/shadow_merida_map.png');";
        Yii::app()->clientScript->registerScript('p2MapSearchLoad',$script,CClientScript::POS_LOAD);

        #Yii::app()->clientScript->registerScript('p2MapSearchInit',"$('#addressButtonContainer input').click()",CClientScript::POS_END);


    }
}
?>
