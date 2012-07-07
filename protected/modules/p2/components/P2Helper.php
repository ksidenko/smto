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
 * Helper class
 *
 * Detailed info
 * <pre>
 * $var = code_example();
 * </pre>
 * {@link DefaultController}
 *
 * @author Tobias Munk <schmunk@usrbin.de>
 * @version $Id: P2Helper.php 547 2010-03-31 22:54:57Z schmunk $
 * @package p2.components
 * @since 2.0
 */
class P2Helper {

    const MODULE_NAME = 'p2';
    const MODULE_VERSION = '2.1-b8';

    public static function return_url($encode = false) {
        // fixme? REQUEST, GET or POST?
        if (isset($_REQUEST['return_url'])) {
            if ($encode)
                return $_REQUEST['return_url'];
            else
                return base64_decode(urldecode($_REQUEST['return_url']));
        }
        else {
            return false;
        }
    }

    /**
     * Return the current requestUri
     *
     * @return string
     */
    public static function uri() {
        return urlencode(base64_encode(Yii::app()->request->requestUri));
    }

    public static function xmlspecialchars($text) {
        return str_replace('&#039;', '&apos;', htmlspecialchars($text, ENT_QUOTES));
    }

    public static function numeric_entities($string){
        $mapping_hex = array();
        $mapping_dec = array();

        foreach (get_html_translation_table(HTML_ENTITIES, ENT_QUOTES) as $char => $entity){
            $mapping_hex[html_entity_decode($entity,ENT_QUOTES,"UTF-8")] = '&#x' . strtoupper(dechex(ord(html_entity_decode($entity,ENT_QUOTES)))) . ';';
            $mapping_dec[html_entity_decode($entity,ENT_QUOTES,"UTF-8")] = '&#' . ord(html_entity_decode($entity,ENT_QUOTES)) . ';';
        }
        $string = str_replace(array_values($mapping_hex),array_keys($mapping_hex) , $string);
        $string = str_replace(array_values($mapping_dec),array_keys($mapping_dec) , $string);
        return $string;
    }
    /**
     * Returns a clearfloat HTML snip
     *
     * @return string
     */
    public static function clearfloat() {
        Yii::app()->clientScript->registerCss('P2Helper.clearfloat','.clearfloat{    clear:both;    height:0;    font-size: 1px;    line-height: 0px; }');
        return "<div class='clearfloat'></div>";
    }

    /**
     * Return HTML markup for a key value pair array.
     *
     * @param <type> $data
     * @return string
     */
    public static function table($data) {
        if (!is_array($data)) {
            return 'Empty table.';
        }
        $return = '<table class="simpleTable">';
        foreach($data AS $key => $value) {
            $return .= "<tr>";
            $return .= "<th>".$key."</th>";
            $return .= "<td>".$value."</td>";
            $return .= "</tr>";
        }
        $return .= "</table>";
        return $return;
    }

    public static function juiButtonCssClass($icon = false) {
        ob_start();
        Yii::app()->controller->widget("P2JuiTheme");
        ob_end_clean();
        $return = 'button ui-state-default ui-corner-all ';
        $return .= ($icon)?' ui-icon-container':'';
        return $return;
    }
    public static function juiIcon($icon, $applyStyle = true) {
        ob_start();
        Yii::app()->controller->widget("P2JuiTheme");
        ob_end_clean();
        $style = ($applyStyle)?'style="margin-right: 0.5em; display: inline-block; float:left"':'style=" float:left; display: inline-block;"';
        return '<span class="ui-icon ui-icon-'.$icon.'" '.$style.'>&nbsp;</span>';
    }

    public static function icon($key, $type = null) {
        if ($type == 'flag')
            return Yii::app()->theme->baseUrl."/flags/".$key.".gif";
        else
            return Yii::app()->theme->baseUrl."/icons/".$key.".png";
    }

    public static function language($type = null) {
        if($type == 'UC_ALL') {
            return str_replace(' ','',ucwords(str_replace('_',' ',Yii::app()->language)));
        } else {
            return Yii::app()->language;
        }
    }

    /**
     * A wrapper for Yii::t(), you may use this method for special translations, like dynamic values.
     *
     * @param <type> $category
     * @param <type> $message
     * @param <type> $params
     * @param <type> $source
     * @param <type> $language
     * @return <type>
     */
    public static function t($category, $message, $params = array(), $source = null, $language = null) {
        return Yii::t($category, $message, $params, $source, $language);
    }

    public static function widget($className, $properties){
        ob_start();
        Yii::app()->controller->widget($className, $properties);
        return ob_get_clean();
    }

    // FIXME
    public static function relation($relation, $property){
        if ($relation !== null)
            return CHtml::link($relation->$property,array(P2Helper::lcfirst(get_class($relation))."/show","id"=>$relation->id));
        else 
            return "";
    }

    // FIXME
    public static function hasMany($relation){
        if(!isset($relation[0])) 
            return;
        else 
            $controller = P2Helper::lcfirst(get_class($relation[0]));
        $return = "";
        foreach(CHtml::listData($relation,'id','title') AS $id => $name){
            $return .= CHtml::link($name, array($controller.'/update', 'id'=>$id)).', ';
        };
        return $return;
    }

    /**
     *
     * @return <type>
     */
    public static function powered() {
        return 'Powered by <a class="poweredByPhundament" href="http://phundament.com">phundament</a>.';
    }

    /**
     *
     * @param <type> $relativePath
     * @return <type>
     */
    public static function publishAsset($relativePath = null) {
        if ($relativePath === null) {
            Yii::log('Relative path can not be null', LOG_WARNING, "p2.helper");
        }
        else {
            return Yii::app()->assetManager->publish(Yii::app()->basePath.DS.$relativePath);
        }
    }

    public static function registerJsExtension($path = null) {

        if ($path === null) {
            throw new Exception('No plugin path specified!');
        }
        $script = self::findModule()->basePath.DS."extensions".DS.$path;
        $url = Yii::app()->assetManager->publish($script);
        Yii::app()->clientScript->registerScriptFile($url);
    }

    /**
     * Addiditional config string parsing
     *
     * @param <type> $key
     * @return <type>
     */
    public static function configure($key) {
        $args = func_get_args();
        $data = null;
        switch($args[0]) {
            case 'jquery:loadingStart':
                $data = '$("'.$args[1].'").fadeTo("fast", 0.5);';
                break;
            case 'jquery:loadingEnd':
                $data = '$("'.$args[1].'").fadeTo("normal", 1);';
                break;
            case 'cTabView:cssFile':
                if (Yii::app()->theme) {
                    $url = Yii::app()->theme->baseUrl.DS.'css'.DS.'jquery.yiitab.css';
                    $path = Yii::app()->theme->basePath.DS.'css'.DS.'jquery.yiitab.css';
                    $data = (is_file($path))?$url:null;
                }
                break;
            case 'ckeditor:config':
                // FIXME: ckeditor-3.0.1 no baseUrl, bodyId
                if(is_array($args[1]['contentsCss'])) {
                    foreach($args[1]['contentsCss'] AS $css) {
                        $parse['contentsCss'][] = "http://".$_SERVER['SERVER_NAME']."/".Yii::app()->baseUrl."/".$css."";
                    }
                } else {
                    $parse['contentsCss'] = "http://".$_SERVER['SERVER_NAME']."/".Yii::app()->baseUrl."/".$args[1]['contentsCss'];
                }
                
                #exit;

                // parse assets
                $parse['stylesCombo_stylesSet'] = 'default:'.Yii::app()->assetManager->publish(Yii::app()->basePath.DS.$args[1]['stylesCombo_stylesSet']);
                $parse['templates'] = 'default';
                $parse['templates_files'] = array(Yii::app()->assetManager->publish(Yii::app()->basePath.DS.$args[1]['templates_files']));

                // parse URLs
                $parse['filebrowserBrowseUrl'] = Yii::app()->controller->createUrl($args[1]['filebrowserBrowseUrl']);
                $parse['filebrowserImageBrowseUrl'] = Yii::app()->controller->createUrl($args[1]['filebrowserImageBrowseUrl']);
                $parse['filebrowserFlashBrowseUrl'] = Yii::app()->controller->createUrl($args[1]['filebrowserFlashBrowseUrl']);

                $data = array_merge($args[1], $parse);
                break;
            default:
                throw new Exception('Config key not found!');
        }

        return $data;
    }



    /**
     * Finds a module by id, returns 'p2' if no id given
     *
     * @param string $id
     * @return CModule
     */
    public static function findModule($id = null) {
        if ($id === null) $id = self::MODULE_NAME;
        return Yii::app()->findModule($id);
    }

    // BIG TODO here - how to check classes ?
    public static function classExists($classPath) {
        $class = Yii::import($classPath);
        #echo $class; exit;
        if (class_exists($class) == true) {
            return $class;
        } else {
            return false;
            throw new CException();
        }
    }

    /**
     * Lowercases only the first character of a string
     *
     * @param <type> $string
     * @return string
     */
    public static function lcfirst($string) {
        return strtolower(substr($string,0,1)).substr($string,1);
    }

    /**
     * Retrieves $_POST variable by array values
     *
     * @param array $array "the key-chain"
     * @return string
     */
    public static function getPostVar($array) {
        switch(count($array)) {
            case 0:
                $msg = "Unable to determine POST vars!";
                throw new Exception($msg);
                break;
            case 1:
                $return = $_POST[$array[0]];
                break;
            case 2:
                $return = $_POST[$array[0]][$array[1]];
                break;
            case 3:
                $return = $_POST[$array[0]][$array[1]][$array[2]];
                break;
            default:
                $msg = "Too many POST fields specified!";
                throw new Exception($msg);
                break;
        }
        return $return;

    }

    public static function hash($input) {
        return md5($input);
    }

    public static function getClassProperties($classPath) {
        $class = self::classExists($classPath);
        if ($class) {
            return get_class_vars($class);
        }
    }

    public static function getModules() {
        $dir = Yii::app()->basePath;
        foreach(scandir($dir.DS."modules") AS $module) {
            if((($module != ".") && ($module != "..")) && (is_dir($dir.DS."modules".DS.$module) && strstr($module,".") === false) ) {
                Yii::import("application.modules.".$module.".controllers.*");
                $return[] = $module;
            }
        }
        return $return;
    }

    public static function getControllers($module = null) {
        $return = array();
        if ($module === null) {
            $dir = Yii::app()->basePath.DS."controllers";
        }
        else {
            $dir = Yii::app()->basePath.DS."modules".DS.$module.DS."controllers";
        }

        foreach(scandir($dir) AS $controller) {
            if(!is_dir($dir.DS.$controller)) {
                if (substr($controller,0,1) == ".") continue;
                Yii::import("application.controllers.*");
                #self::getActions(str_replace(".php","",$controller));

                $trans = array("Controller.php" => "", "\\"=>"/");
                if ($module !== null)
                    $return[strtr($controller,$trans)] = $module."/".P2Helper::lcfirst(strtr($controller,$trans));
                else
                    $return[strtr($controller,$trans)] = P2Helper::lcfirst(strtr($controller,$trans));
            }
        }
        return $return;
    }

    public static function getActions($controller) {
        $return = array();
        Yii::trace("Scanning actions for $controller ...");
        $methods = get_class_methods(ucfirst(basename($controller)."Controller"));
        foreach($methods AS $method) {
            if ($method == "actions") continue;
            if ($method == "actionDelete") continue;
            if ($method == "actionShow") continue;
            if ($method == "actionUpdate") continue;

            if (substr($method,0,6) == "action") {
                $key = P2Helper::lcfirst(str_replace("action","",$method));
                $trans = array("action"=>"", "\\"=>"/");
                $value = strtr(Yii::app()->createUrl($controller.DS.$method),$trans);
                $return[$key] = $value;
            }

        }
        return $return;
    }



public static function html_convert_entities($string) {
  return preg_replace_callback('/&([a-zA-Z][a-zA-Z0-9]+);/',
                               'P2Helper::convert_entity', $string);
}

    /**
     *
     * from http://inanimatt.com/php-convert-entities.php
     *
     * @staticvar <type> $table
     * @param <type> $matches
     * @return <type>
     */
    public static function convert_entity($matches) {
  static $table = array('quot'    => '&#34;',
                        'amp'      => '&#38;',
                        'lt'       => '&#60;',
                        'gt'       => '&#62;',
                        'OElig'    => '&#338;',
                        'oelig'    => '&#339;',
                        'Scaron'   => '&#352;',
                        'scaron'   => '&#353;',
                        'Yuml'     => '&#376;',
                        'circ'     => '&#710;',
                        'tilde'    => '&#732;',
                        'ensp'     => '&#8194;',
                        'emsp'     => '&#8195;',
                        'thinsp'   => '&#8201;',
                        'zwnj'     => '&#8204;',
                        'zwj'      => '&#8205;',
                        'lrm'      => '&#8206;',
                        'rlm'      => '&#8207;',
                        'ndash'    => '&#8211;',
                        'mdash'    => '&#8212;',
                        'lsquo'    => '&#8216;',
                        'rsquo'    => '&#8217;',
                        'sbquo'    => '&#8218;',
                        'ldquo'    => '&#8220;',
                        'rdquo'    => '&#8221;',
                        'bdquo'    => '&#8222;',
                        'dagger'   => '&#8224;',
                        'Dagger'   => '&#8225;',
                        'permil'   => '&#8240;',
                        'lsaquo'   => '&#8249;',
                        'rsaquo'   => '&#8250;',
                        'euro'     => '&#8364;',
                        'fnof'     => '&#402;',
                        'Alpha'    => '&#913;',
                        'Beta'     => '&#914;',
                        'Gamma'    => '&#915;',
                        'Delta'    => '&#916;',
                        'Epsilon'  => '&#917;',
                        'Zeta'     => '&#918;',
                        'Eta'      => '&#919;',
                        'Theta'    => '&#920;',
                        'Iota'     => '&#921;',
                        'Kappa'    => '&#922;',
                        'Lambda'   => '&#923;',
                        'Mu'       => '&#924;',
                        'Nu'       => '&#925;',
                        'Xi'       => '&#926;',
                        'Omicron'  => '&#927;',
                        'Pi'       => '&#928;',
                        'Rho'      => '&#929;',
                        'Sigma'    => '&#931;',
                        'Tau'      => '&#932;',
                        'Upsilon'  => '&#933;',
                        'Phi'      => '&#934;',
                        'Chi'      => '&#935;',
                        'Psi'      => '&#936;',
                        'Omega'    => '&#937;',
                        'alpha'    => '&#945;',
                        'beta'     => '&#946;',
                        'gamma'    => '&#947;',
                        'delta'    => '&#948;',
                        'epsilon'  => '&#949;',
                        'zeta'     => '&#950;',
                        'eta'      => '&#951;',
                        'theta'    => '&#952;',
                        'iota'     => '&#953;',
                        'kappa'    => '&#954;',
                        'lambda'   => '&#955;',
                        'mu'       => '&#956;',
                        'nu'       => '&#957;',
                        'xi'       => '&#958;',
                        'omicron'  => '&#959;',
                        'pi'       => '&#960;',
                        'rho'      => '&#961;',
                        'sigmaf'   => '&#962;',
                        'sigma'    => '&#963;',
                        'tau'      => '&#964;',
                        'upsilon'  => '&#965;',
                        'phi'      => '&#966;',
                        'chi'      => '&#967;',
                        'psi'      => '&#968;',
                        'omega'    => '&#969;',
                        'thetasym' => '&#977;',
                        'upsih'    => '&#978;',
                        'piv'      => '&#982;',
                        'bull'     => '&#8226;',
                        'hellip'   => '&#8230;',
                        'prime'    => '&#8242;',
                        'Prime'    => '&#8243;',
                        'oline'    => '&#8254;',
                        'frasl'    => '&#8260;',
                        'weierp'   => '&#8472;',
                        'image'    => '&#8465;',
                        'real'     => '&#8476;',
                        'trade'    => '&#8482;',
                        'alefsym'  => '&#8501;',
                        'larr'     => '&#8592;',
                        'uarr'     => '&#8593;',
                        'rarr'     => '&#8594;',
                        'darr'     => '&#8595;',
                        'harr'     => '&#8596;',
                        'crarr'    => '&#8629;',
                        'lArr'     => '&#8656;',
                        'uArr'     => '&#8657;',
                        'rArr'     => '&#8658;',
                        'dArr'     => '&#8659;',
                        'hArr'     => '&#8660;',
                        'forall'   => '&#8704;',
                        'part'     => '&#8706;',
                        'exist'    => '&#8707;',
                        'empty'    => '&#8709;',
                        'nabla'    => '&#8711;',
                        'isin'     => '&#8712;',
                        'notin'    => '&#8713;',
                        'ni'       => '&#8715;',
                        'prod'     => '&#8719;',
                        'sum'      => '&#8721;',
                        'minus'    => '&#8722;',
                        'lowast'   => '&#8727;',
                        'radic'    => '&#8730;',
                        'prop'     => '&#8733;',
                        'infin'    => '&#8734;',
                        'ang'      => '&#8736;',
                        'and'      => '&#8743;',
                        'or'       => '&#8744;',
                        'cap'      => '&#8745;',
                        'cup'      => '&#8746;',
                        'int'      => '&#8747;',
                        'there4'   => '&#8756;',
                        'sim'      => '&#8764;',
                        'cong'     => '&#8773;',
                        'asymp'    => '&#8776;',
                        'ne'       => '&#8800;',
                        'equiv'    => '&#8801;',
                        'le'       => '&#8804;',
                        'ge'       => '&#8805;',
                        'sub'      => '&#8834;',
                        'sup'      => '&#8835;',
                        'nsub'     => '&#8836;',
                        'sube'     => '&#8838;',
                        'supe'     => '&#8839;',
                        'oplus'    => '&#8853;',
                        'otimes'   => '&#8855;',
                        'perp'     => '&#8869;',
                        'sdot'     => '&#8901;',
                        'lceil'    => '&#8968;',
                        'rceil'    => '&#8969;',
                        'lfloor'   => '&#8970;',
                        'rfloor'   => '&#8971;',
                        'lang'     => '&#9001;',
                        'rang'     => '&#9002;',
                        'loz'      => '&#9674;',
                        'spades'   => '&#9824;',
                        'clubs'    => '&#9827;',
                        'hearts'   => '&#9829;',
                        'diams'    => '&#9830;',
                        'nbsp'     => '&#160;',
                        'iexcl'    => '&#161;',
                        'cent'     => '&#162;',
                        'pound'    => '&#163;',
                        'curren'   => '&#164;',
                        'yen'      => '&#165;',
                        'brvbar'   => '&#166;',
                        'sect'     => '&#167;',
                        'uml'      => '&#168;',
                        'copy'     => '&#169;',
                        'ordf'     => '&#170;',
                        'laquo'    => '&#171;',
                        'not'      => '&#172;',
                        'shy'      => '&#173;',
                        'reg'      => '&#174;',
                        'macr'     => '&#175;',
                        'deg'      => '&#176;',
                        'plusmn'   => '&#177;',
                        'sup2'     => '&#178;',
                        'sup3'     => '&#179;',
                        'acute'    => '&#180;',
                        'micro'    => '&#181;',
                        'para'     => '&#182;',
                        'middot'   => '&#183;',
                        'cedil'    => '&#184;',
                        'sup1'     => '&#185;',
                        'ordm'     => '&#186;',
                        'raquo'    => '&#187;',
                        'frac14'   => '&#188;',
                        'frac12'   => '&#189;',
                        'frac34'   => '&#190;',
                        'iquest'   => '&#191;',
                        'Agrave'   => '&#192;',
                        'Aacute'   => '&#193;',
                        'Acirc'    => '&#194;',
                        'Atilde'   => '&#195;',
                        'Auml'     => '&#196;',
                        'Aring'    => '&#197;',
                        'AElig'    => '&#198;',
                        'Ccedil'   => '&#199;',
                        'Egrave'   => '&#200;',
                        'Eacute'   => '&#201;',
                        'Ecirc'    => '&#202;',
                        'Euml'     => '&#203;',
                        'Igrave'   => '&#204;',
                        'Iacute'   => '&#205;',
                        'Icirc'    => '&#206;',
                        'Iuml'     => '&#207;',
                        'ETH'      => '&#208;',
                        'Ntilde'   => '&#209;',
                        'Ograve'   => '&#210;',
                        'Oacute'   => '&#211;',
                        'Ocirc'    => '&#212;',
                        'Otilde'   => '&#213;',
                        'Ouml'     => '&#214;',
                        'times'    => '&#215;',
                        'Oslash'   => '&#216;',
                        'Ugrave'   => '&#217;',
                        'Uacute'   => '&#218;',
                        'Ucirc'    => '&#219;',
                        'Uuml'     => '&#220;',
                        'Yacute'   => '&#221;',
                        'THORN'    => '&#222;',
                        'szlig'    => '&#223;',
                        'agrave'   => '&#224;',
                        'aacute'   => '&#225;',
                        'acirc'    => '&#226;',
                        'atilde'   => '&#227;',
                        'auml'     => '&#228;',
                        'aring'    => '&#229;',
                        'aelig'    => '&#230;',
                        'ccedil'   => '&#231;',
                        'egrave'   => '&#232;',
                        'eacute'   => '&#233;',
                        'ecirc'    => '&#234;',
                        'euml'     => '&#235;',
                        'igrave'   => '&#236;',
                        'iacute'   => '&#237;',
                        'icirc'    => '&#238;',
                        'iuml'     => '&#239;',
                        'eth'      => '&#240;',
                        'ntilde'   => '&#241;',
                        'ograve'   => '&#242;',
                        'oacute'   => '&#243;',
                        'ocirc'    => '&#244;',
                        'otilde'   => '&#245;',
                        'ouml'     => '&#246;',
                        'divide'   => '&#247;',
                        'oslash'   => '&#248;',
                        'ugrave'   => '&#249;',
                        'uacute'   => '&#250;',
                        'ucirc'    => '&#251;',
                        'uuml'     => '&#252;',
                        'yacute'   => '&#253;',
                        'thorn'    => '&#254;',
                        'yuml'     => '&#255;'

                        );
  // Entity not found? Destroy it.
  return isset($table[$matches[1]]) ? $table[$matches[1]] : '';
}


}


?>