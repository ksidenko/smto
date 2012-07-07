<?php
/**
 * EJqueryFlash class file.
 *
 * @author MetaYii
 * @version 0.2 beta
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2009 MetaYii
 * @license dual GPL (3.0 or later) and MIT, at your choice.
 * @license http://www.opensource.org/licenses/mit-license.php
 * @license http://www.opensource.org/licenses/gpl-3.0.php
 *
 * The MIT license:
 *
 * Copyright (c) 2009 MetaYii
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * The GPL license:
 *
 * Copyright (C) 2009 MetaYii
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Do you want to check your user parameters against the valid ones?
 */
define('_CHECK_JS_PARAMETERS_', true);

/**
 * EJqueryFlash is a widget which inserts Flash (swf) objects in a web page. It
 * relies on the jQuery Flash plugin. You should take a look into the plugin's
 * website, since there are many useful examples and advanced tips, which can't
 * be done with this widget as they're too complex and need custom javascript
 * coding.
 *
 * @author MetaYii
 * @version 0.1 beta
 * @since 1.0.4
 * @package extensions.flash
 * @link http://jquery.lukelutman.com/plugins/flash/
 */
class EJqueryFlash extends CInputWidget
{
   //***************************************************************************
   // Configuration
   //***************************************************************************

   /**
    * Alternate text in case the user doesn't have the Flash Plugin or is in
    * text mode. If there's a widget's body then it overrides this property.
    *
    * @var string
    */
   protected $text = '';

   /**
    * Inherits $htmlOptions: Options for the embed/object tag.
    */

   /**
    * Options for detecting/updating the Flash plugin (optional).
    *
    * @var array
    */
   protected $pluginOptions = array();

   /**
    * Callback functions.
    *
    * @var array
    */
   protected $callbacks = array();

   //***************************************************************************
   // Internal properties
   //***************************************************************************

   /**
    * Valid callbacks.
    *
    * @var array
    */
   protected $validCallbacks = array('replace', 'update');

   /**
    * Valid pluginOptions.
    *
    * @var array
    */
   protected $validPluginOptions = array(
                                         'expressInstall'=>array('type'=>'boolean'), // boolean
                                         'update'=>array('type'=>'boolean'),         // boolean
                                         'version'=>array('type'=>'string')          // string
                                        );

   /**
    * Valid htmlOptions.
    *
    * @var array
    */
   protected $validHtmlOptions = array(
                                       'id'=>array('type'=>'string'), // string, object-tag (id) embed-tag (name)
                                       'allowFullScreen'=>array('type'=>'boolean'), // integer, default: 240
                                       'height'=>array('type'=>'integer'), // integer, default: 240
                                       'width'=>array('type'=>'integer'), // integer, default: 320
                                       'flashvars'=>array('type'=>'array'), // array
                                       'pluginspage'=>array('type'=>'string'), // string, default: 'http://www.adobe.com/go/getflashplayer'
                                       'src'=>array('type'=>'string'), // string, default: '#'
                                       'type'=>array('type'=>'string'), // string, default: 'application/x-shockwave-flash'
                                       'wmode'=>array('type'=>'string'), // string, default: transparent
                                       'bgcolor'=>array('type'=>'string'), // string, default: #ffffff
                                       'quality'=>array('type'=>'string'), // string: 'high', 'medium', 'low', default: 'high'
                                      );

   /**
    * Base assets' URL.
    *
    * @var string
    */
   private $_baseUrl = '';

   /**
    * Client script object
    *
    * @var object
    */
   private $_clientScript = null;

   //***************************************************************************
   // Setters and getters
   //***************************************************************************

   /**
    * Setter
    *
    * @param string $value text
    */
   public function setText($value)
   {
      $this->text = strval($value);
   }

   /**
    * Getter
    *
    * @return string
    */
   public function getText()
   {
      return $this->text;
   }

   /**
    * Setter
    *
    * @param array $value htmlOptions
    */
   public function setHtmlOptions($value)
   {
      if (!is_array($value))
         throw new CException(Yii::t('EJqueryFlash', 'htmlOptions must be an array'));
      if (_CHECK_JS_PARAMETERS_) self::checkOptions($value, $this->validHtmlOptions);
      $this->htmlOptions = $value;
   }

   /**
    * Getter
    *
    * @return array
    */
   public function getHtmlOptions()
   {
      return $this->htmlOptions;
   }

   /**
    * Setter
    *
    * @param array $value pluginOptions
    */
   public function setPluginOptions($value)
   {
      if (!is_array($value))
         throw new CException(Yii::t('EJqueryFlash', 'pluginOptions must be an array'));
      if (_CHECK_JS_PARAMETERS_) self::checkOptions($value, $this->validPluginOptions);
      $this->pluginOptions = $value;
   }

   /**
    * Getter
    *
    * @return array
    */
   public function getPluginOptions()
   {
      return $this->pluginOptions;
   }

   /**
    * Setter
    *
    * @param array $value callbacks
    */
   public function setCallbacks($value)
   {
      if (!is_array($value))
         throw new CException(Yii::t('EJqueryFlash', 'callbacks must be an array'));
      if (_CHECK_JS_PARAMETERS_) self::checkCallbacks($value, $this->validCallbacks);
      $this->callbacks = $value;
   }

   /**
    * Getter
    *
    * @return array
    */
   public function getCallbacks()
   {
      return $this->callbacks;
   }

   //***************************************************************************
   // Utilities
   //***************************************************************************

   /**
    * Check the options against the valid ones
    *
    * @param array $value user's options
    * @param array $validOptions valid options
    */
   protected static function checkOptions($value, $validOptions)
   {
      if (!empty($validOptions)) {
         foreach ($value as $key=>$val) {
            if (!array_key_exists($key, $validOptions)) {
               throw new CException(Yii::t('EJqueryFlash', '{k} is not a valid option', array('{k}'=>$key)));
            }
            $type = gettype($val);
            if ((!is_array($validOptions[$key]['type']) && ($type != $validOptions[$key]['type'])) || (is_array($validOptions[$key]['type']) && !in_array($type, $validOptions[$key]['type']))) {
               throw new CException(Yii::t('EJqueryFlash', '{k} must be of type {t}', array('{k}'=>$key, '{t}'=>implode(',', (is_array($validOptions[$key]['type']))?implode(', ', $validOptions[$key]['type']):$validOptions[$key]['type']))));
            }
            if (array_key_exists('possibleValues', $validOptions[$key])) {
               if (!in_array($val, $validOptions[$key]['possibleValues'])) {
                  throw new CException(Yii::t('EJqueryFlash', '{k} must be one of: {v}', array('{k}'=>$key, '{v}'=>implode(', ', $validOptions[$key]['possibleValues']))));
               }
            }
            if (($type == 'array') && array_key_exists('elements', $validOptions[$key])) {
               self::checkOptions($val, $validOptions[$key]['elements']);
            }
         }
      }
   }

   /**
    * Check callbacks against the valid ones
    *
    * @param array $value user's callbacks
    * @param array $validCallbacks valid callbacks
    */
   protected static function checkCallbacks($value, $validCallbacks)
   {
      if (!empty($validCallbacks)) {
         foreach ($value as $key=>$val) {
            if (!in_array($key, $validCallbacks)) {
               throw new CException(Yii::t('EJqueryFlash', '{k} must be one of: {c}', array('{k}'=>$key, '{c}'=>implode(', ', $validCallbacks))));
            }
         }
      }
   }

   /**
    * Publishes the assets
    */
   protected function publishAssets()
   {
      $dir = dirname(__FILE__).DIRECTORY_SEPARATOR.'jquery';
      $this->_baseUrl = Yii::app()->getAssetManager()->publish($dir);
   }

   /**
    * Registers the external javascript files
    */
   protected function registerClientScripts()
   {
      if ($this->_baseUrl === '')
         throw new CException(Yii::t('EJqueryFlash', 'baseUrl must be set. This is done automatically by calling publishAssets()'));
      $this->_clientScript = Yii::app()->getClientScript();
      $this->_clientScript->registerCoreScript('jquery');
      $this->_clientScript->registerScriptFile($this->_baseUrl.'/jquery.flash.js');
   }

   /**
    * Make the options javascript string.
    *
    * @return string
    */
   protected function makeOptions($opt)
   {
      if (empty($opt)) return 'null';

      $encodedOptions = CJavaScript::encode($opt);
      return $encodedOptions;
   }

   /**
    * Generate the javascript code.
    *
    * @param string $id id
    * @return string
    */
   protected function jsCode($id)
   {
      $htmlOptions = $this->makeOptions($this->htmlOptions);
      $pluginOptions = $this->makeOptions($this->pluginOptions);

      $c1 = $c2 = 'null';
      if (isset($this->callbacks['replace']))
         $c1 = strval($this->callbacks['replace']);
      if (isset($this->callbacks['update']))
         $c2 = strval($this->callbacks['update']);

      $script =<<<EOP
$('#{$id}').flash({$htmlOptions}, {$pluginOptions}, {$c1}, {$c2});
EOP;
      
      return $script;
   }

   /**
    * Make the HTML code
    *
    * @param string $id id
    * @return string
    */
   protected function htmlCode($id)
   {
      $html = CHtml::tag('div', array('id'=>$id), $this->text, true);
      return $html;
   }

   //***************************************************************************
   // Run Lola Run
   //***************************************************************************

   /**
    * Get the output buffer.
    */
   public function init()
   {
      ob_start();
   }

   /**
    * Draw the widget
    */
   public function run()
   {
      if (empty($this->text)) {
         $this->text = ob_get_contents();
      }
      ob_end_clean();

      list($name, $id) = $this->resolveNameID();

      $this->publishAssets();
      $this->registerClientScripts();

      $js = $this->jsCode($id);
      $html = $this->htmlCode($id);

      $this->_clientScript->registerScript('Yii.'.get_class($this).'#'.$id, $js, CClientScript::POS_READY);
      
      echo $html;
   }
}