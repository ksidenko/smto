<?php

require_once(dirname(__FILE__)."/LithronException.php");
require_once(dirname(__FILE__)."/csstidy/class.csstidy.php");
require_once(dirname(__FILE__)."/PropertyDefinition.php");
require_once(dirname(__FILE__)."/Property.php");
require_once(dirname(__FILE__)."/Plugins.php");
require_once(dirname(__FILE__)."/LineBox.php");
require_once(dirname(__FILE__)."/RenderOperation.php");
require_once(dirname(__FILE__)."/LithronHelper.php");

$incPath = get_include_path();
set_include_path (dirname(__FILE__)."/workers:".$incPath);
#echo get_include_path();exit;

class Lithron
{
    const VERSION = "1.0.3";

    const INFINITY = 1000000;
    const EPSILON  = 0.1;

    const STYLESHEET_MODE_LITHRON = 0;
    const STYLESHEET_MODE_AUTHOR = 1;

    private $OutputPath = "output";
    private $TmpPath = "/tmp";

    private $IMagickPath = "/sw/bin";
    private $IMagickSearchPath = "/sw/bin;/usr/bin;/opt/local/bin";
    private $XMLString = null;
    private $DefaultEncoding = "unicode";

    public static $LogLevel = LOG_DEBUG;
    public static $LogBook = array();
    public static $Instance = null;

    public static $AllowCaching = false;

    public $PDFLibMode = null;
    public $Document = null;
    public $Tidy = null;
    public $State = null;
    public $XPath = null;
    public $Fonts = null;
    public $DummyPDF = null; // used for font measuring
    public $LicenseFile = null;
    public $JobId = null;
    public $JobIdPrefix = 'job';

    public $Wells = array();
    public static $ActiveSink = null;
    public static $ActiveFile = null;
    public $CurrentPageNum = 0;

    public $Files = array();
    public $Anchors = array();

    public $CleanedXML = null;

    public function __construct($xml = null, $loglevel = LOG_DEBUG)
    {
        //echo '<style type="text/css">pre { background-color:red;}</style>';
        self::$Instance = $this;
        self::$LogLevel = $loglevel;

        // get font properties
        $this->DummyPDF = new PDFlib();
        $this->DummyPDF->begin_document("", "compatibility={1.6}");

        // disable host fonts because of Leopard bug
        $this->DummyPDF->set_parameter("debug", "h");

        $this->DummyPDF->set_parameter("textformat", "utf8");
        $this->DummyPDF->set_parameter("charref", "true");

        if ($xml !== null)
        {
            $this->setXMLString($xml);
            $this->init();
        }
    }

    public function init()
    {

        $this->checkPdfLibMode();

        if ($this->PdfLibMode == "lite")
        {
            $this->DefaultEncoding = "iso8859-1";
            Lithron::log("PDFlib Lite mode: Changing DefaultEncoding to 'iso8859-1'", LOG_WARNING, "BlockImage");
            #exit;
        }

        Lithron::trace("Cleaning XML", "Lithron");
        $this->CleanedXML = $this->cleanXML($this->getXMLString());
        #file_put_contents("/tmp/last.xml", $this->CleanedXML);

        Lithron::trace("Loading XML into DOM-Document", "Lithron");
        $this->Document = new DOMDocument();
        if ($this->Document->loadXML($this->CleanedXML) === false)
        {
            throw new LithronException("Can not load XML");
        }
        //echo "Loaded XML: <pre>".htmlentities($this->Document->saveXML())."</pre><hr>";

        Lithron::trace("Initializing CSSTidy", "Lithron");
        $this->Tidy = new CssTidy();
        $this->Tidy->set_cfg('compress_colors', false);
        $this->Tidy->set_cfg('optimise_shorthands', 0);
        $this->Tidy->set_cfg('compress_font-weight', false);

        Lithron::trace("Initializing XPath", "Lithron");
        $this->XPath = new DOMXPath($this->Document);
        Lithron::trace("Initializing Property Definition", "Lithron");
        PropertyDefinition::init();
        Lithron::trace("Initializing Fonts", "Lithron");
        $this->initializeFonts();

        Lithron::trace("Validating element specified properties", "Lithron");
        $this->validateSpecifiedProperties();
        Lithron::trace("Applying Lithron Style", "Lithron");
        $this->applyLithronStylesheet();
        Lithron::trace("Applying Author Style", "Lithron");
        $this->applyAuthorStylesheet();
        //echo "Styled XML: <pre>".htmlentities($this->Document->saveXML())."</pre><hr>";

        Lithron::trace("Stripping specifity attributes", "Lithron");
        $this->stripNodes("//@*[starts-with(name(self::node()), \"".PropertyDefinition::SPECIFICITY_PREFIX."\")]");
        Lithron::trace("Stripping Style", "Lithron");
        $this->stripNodes("//style");
        Lithron::trace("Stripping display=none", "Lithron");
        $this->stripNodes("//*[@display=\"none\"]");
        Lithron::trace("Stripping irrelevant text nodes", "Lithron");
        $this->stripNodes("//text()[count(ancestor::page)=0 and count(ancestor::well)=0]");

        Lithron::trace("Invoking Transformers", "Lithron");
        $this->invokeTransformers();
        Lithron::trace("Stripping private attributes", "Lithron");
        $this->stripPrivateAttributes();
        //echo "Transformed XML: <pre>".htmlentities($this->Document->saveXML())."</pre><hr>";
    }

    public function  __get($name) {
        if (method_exists($this, 'get'.$name)) {
            return $this->{'get'.$name}();
        }
    }

    public function getOutputPath()
    {
        return $this->OutputPath;
    }

    public function setOutputPath($value)
    {
        $path = realpath($value);
        if ($path == false) {
            throw new Exception("Output path '".$value."' not valid!");
        } else {
            $this->OutputPath = $path;            
        }
    }

    public function getTmpPath()
    {
        return $this->TmpPath;
    }

    public function setTmpPath($value)
    {
        $this->TmpPath = $value;
    }

    public function getIMagickPath()
    {
        return $this->IMagickPath;
    }

    public function setIMagickPath($value)
    {
        $this->IMagickPath = $value;
    }

    public function getXMLString()
    {
        return $this->XMLString;
    }

    public function setXMLString($value)
    {
        $this->XMLString = $value;
    }

    public function getDefaultEncoding()
    {
        return $this->DefaultEncoding;
    }

    public function setDefaultEncoding($value)
    {
        $this->DefaultEncoding = $value;
    }

    public function getCleanedXML()
    {
        return $this->CleanedXML;
    }

    public function getEncodedXML()
    {
        $trans = array("<" => "&lt;", ">" => "&gt;");
        return wordwrap(strtr($this->CleanedXML, $trans),120);
    }




    public function work()
    {
        $this->JobId = $this->JobIdPrefix.uniqid();
        $dir = $this->getOutputPath()."/".$this->JobId;
        @mkdir($dir);
        if (!is_writable($dir))
        {
            Lithron::log("Output folder '".$this->getOutputPath()."' is not writable!", LOG_ERR, "Lithron");
            throw new Exception("Output folder '".$this->getOutputPath()."' is not writable!");
            return;
        }

        file_put_contents($dir.DIRECTORY_SEPARATOR.'job.xml', $this->CleanedXML);

        // checks moved to Lithron
        $paths= explode(";", $this->getIMagickPath());
        foreach ($paths as $path)
        {
            if (is_executable($path . "/identify"))
            {
                $this->setIMagickPath($path);
                break;
            }
        }


        self::$AllowCaching = true;
        Lithron::log("Generating Workers", LOG_INFO, "Lithron");
        $rootworker = $this->generateWorkers();
        Lithron::log("Working", LOG_INFO, "Lithron");
        $rootworker->work();
        Lithron::log("Done", LOG_INFO, "Lithron");

        file_put_contents($dir.DIRECTORY_SEPARATOR.'log.html', self::dumpLog());
    }

    protected function cleanXML($xml)
    {
        $trans = array(
            "&amp;" => "&amp;amp;",
            "&lt;" => "&amp;lt;",
            "&gt;" => "&amp;gt;",
        );
        $xml = html_entity_decode(strtr($xml, $trans), ENT_COMPAT, "UTF-8");
        $search = array("/&empty;/", "/&nbsp;/", "/<br[ ]*?>/", "/%[Cc][Bb]%/", "/%[Pp][Bb]%/");
        $replace = array("&#216;", "&amp;nbsp;", "<br/>", "<cbr/>", "<pbr/>");
        return preg_replace($search, $replace, $xml);
    }

    protected function initializeFonts()
    {
        $this->Fonts = PropertyDefinition::$DefaultFonts;

        // read all font nodes
        $q = '//font';
        $fontnodes = $this->XPath->query($q);
        foreach($fontnodes as $node)
        {
            $fam = $node->getAttribute("font-family");
            if ($fam == "") continue;
            $item = array();
            $styles = array("normal", "bold", "oblique", "italic", "boldoblique", "bolditalic");
            foreach($styles as $style)
            {
                if ($val = $node->getAttribute("file-".$style))
                $item["name"] = $val;
                if ($val = $node->getAttribute("embedding-".$style))
                $item["embedding"] = $val;
                if ($val = $node->getAttribute("encoding-".$style))
                $item["encoding"] = $val;
                $this->Fonts[$fam][$style] = $item;
            }
        }

        // remove the font nodes
        $this->stripNodes($q);



        //$this->DummyPDF->begin_page_ext(100, 100, "");
        foreach($this->Fonts as $family => $fam)
        foreach($fam as $subtype => $dummy)
        {
            $handle = $this->getFontHandle($this->DummyPDF, "dummyhandle", $family, $subtype);
            $checks = array("capheight", "ascender", "descender", "xheight");
            foreach($checks as $check)
            {
                $val = $this->DummyPDF->get_value("$check",  $handle);
                $this->Fonts[$family][$subtype][$check] = $val;
            }
            $this->Fonts[$family][$subtype]["spacewidth"] = $this->DummyPDF->stringwidth(" ", $handle, 1);
        }
    }

    public function getFontHandle($pdf, $handlekey, $family, $subtype)
    {
        $f = $this->Fonts[$family][$subtype];
        if (!isset($f[$handlekey]))
        {
            $emb = isset($f["embedding"]) ? $f["embedding"] : "false";
            $enc = isset($f["encoding"]) ? $f["encoding"] : $this->DefaultEncoding;
            $handle = $pdf->load_font($f["name"], $enc, "embedding={".$emb."}");
            $this->Fonts[$family][$subtype][$handlekey] = $handle;
        }
        else
        $handle = $f[$handlekey];
        return $handle;
    }


    public function getStringWidth($node, $string)
    {
        $family = Property::get($node, "font-family");
        $weight = Property::get($node, "font-weight");
        $style = Property::get($node, "font-style");
        $size = Property::get($node, "font-size");
        $sel = "";
        if ($weight != "normal") $sel .= $weight;
        if ($style != "normal") $sel .= $style;
        if ($sel == "") $sel = "normal";

        $handle = $this->getFontHandle($this->DummyPDF, "dummyhandle", $family, $sel);
        return $this->DummyPDF->stringwidth($string, $handle, $size);
    }


    public function getFontMetric($node, $metric)
    {
        $family = Property::get($node, "font-family");
        $weight = Property::get($node, "font-weight");
        $style = Property::get($node, "font-style");
        $size = Property::get($node, "font-size");
        $sel = "";
        if ($weight != "normal") $sel .= $weight;
        if ($style != "normal") $sel .= $style;
        if ($sel == "") $sel = "normal";
        #echo $family;exit;
        return $this->Fonts[$family][$sel][$metric] * $size;
    }

    public function applyCSSWrapper($context_node, $pcss, $mode) {

        $shorthands = array(
            "border-width" => "border-AXE-width",
            "border-color" => "border-AXE-color",
            "border-style" => "border-AXE-style",
            "margin" => "margin-AXE",
            "padding" => "padding-AXE");

        $axes = array("top", "right", "bottom", "left");
        
        if (!is_array($pcss)) {
            Lithron::log("Omitting empty style tag", LOG_NOTICE);
            return;
        }

        foreach ($pcss as $selector => $values)
        {
            foreach($values as $key => $value) {
                if (array_key_exists($key, $shorthands)) {
                    $toAdd = array();
                    foreach($axes as $axe) {
                        $parsedKey = str_replace("AXE", $axe, $shorthands[$key]);
                        $toAdd[$parsedKey] = $value;
                    }
                    $pcss[$selector] = array_merge($pcss[$selector], $toAdd);
                }
            }
        }
        $this->applyCSS($context_node, $pcss, $mode);
    }

    public function applyCSS($context_node, $pcss, $mode)
    {
        if (!isset($pcss)) return;

        foreach ($pcss as $selector => $values)
        foreach($values as $key => $value)
        if (!Property::validate($key, $value))
        unset($pcss[$selector][$key]);

        foreach ($pcss as $selector => $values)
        {
            $this->selectorToXPath($selector, $paths, $mode);
            //var_dump($selector, $paths);
            //echo "<hr>";
            foreach($paths as $item)
            {
                foreach($item as $key => $value) $$key = $value;
                //echo "sel: ".$sel."<br>path: $path<br>pseudo-element: $pseudo<br>spec: ".dechex($spec)."<br><hr>";
                $result = $this->XPath->query($path, $context_node);
                foreach($result as $qnode)
                {
                    if ($pseudo != "")
                    {
                        $q = "./child::".PropertyDefinition::ANON_NODE_NAME."[@".PropertyDefinition::PSEUDO_SELECTOR_NAME."=\"$pseudo\"]";
                        $result = $this->XPath->query($q, $qnode);
                        //echo "$q<br>SUBQUERY returned ".$result->length." nodes!<br>";

                        if (!$result->length)
                        {
                            $newnode = $this->Document->createElement(PropertyDefinition::ANON_NODE_NAME);
                            switch($pseudo)
                            {
                                case "before":
                                    if ($qnode->firstChild)
                                    $qnode->insertBefore($newnode, $qnode->firstChild);
                                    else
                                    $qnode->appendChild($newnode);
                                    break;
                                case "after":
                                    $qnode->appendChild($newnode);
                                    break;
                                default:
                                    echo "unknown pseudo $pseudo<br>";
                                }
                                $newattr = $this->Document->createAttribute(PropertyDefinition::PSEUDO_SELECTOR_NAME);
                                $newattr->value = $pseudo;
                                $newnode->appendChild($newattr);
                                $qnode = $newnode;
                            }
                            else
                            $qnode = $result->item(0);
                        }
                        foreach($values as $key => $value)
                        {
                            //echo $key.": ".$value." for ".$qnode->nodeName."<br>";
                            $pkey = PropertyDefinition::SPECIFICITY_PREFIX.$key;

                            $set = false;
                            if (!$qnode->hasAttribute($key))
                            $set = true;
                            else if (!$qnode->hasAttribute($pkey) && ($spec > ((self::STYLESHEET_MODE_AUTHOR << 24) | 0x010000))) // author stylesheet, a=1, b=0, c=0, according to spec
                            $set = true;
                            else if ($qnode->hasAttribute($pkey) && $spec >= $qnode->getAttributeNode($pkey)->value)
                            $set = true;

                            if ($set)
                            {
                                Property::set($qnode, $key, $value);
                                Property::set($qnode, $pkey, $spec);
                            }
                        }
                    }
                }
            }
        }

        protected function selectorToXPath($sel, &$paths, $mode)
        {
            $paths = array();
            switch ($mode)
            {
                case self::STYLESHEET_MODE_LITHRON:
                    $prefix = "//";
                    break;
                case self::STYLESHEET_MODE_AUTHOR:
                    $prefix = "./following-sibling::*/descendant-or-self::"; // this is NOT CSS2 conform, but nicer
                    break;
            }
            $arr = preg_split("/\s*,\s*/", $sel);
            //echo "<pre>"; var_dump($arr); echo "</pre><hr>";
            foreach($arr as $mselect)
            {
                $a = $b = $c = 0;
                $item = array("path" => $prefix, "sel" => $mselect, "pseudo" => "");
                $match = preg_split("/\s*([ >\+])\s*/", $mselect, -1, PREG_SPLIT_DELIM_CAPTURE);
                //echo "<pre>"; var_dump($match); echo "</pre><hr>";

                $close_next_match = "";
                for ($i = 0; $i < count($match); $i++)
                if ($i & 1)
                {
                    switch($match[$i])
                    {
                        case " ":
                            $item["path"] .= "//";
                            break;
                        case ">":
                            $item["path"] .= "/child::";
                            break;
                        case "+":
                            $item["path"] .= "/following::*[1][self::";
                            $close_next_match = "]";
                            break;
                    }
                }
                else
                {
                    $subsel = $match[$i];
                    $submatch = preg_split("/([\.#:])/", $subsel, -1, PREG_SPLIT_DELIM_CAPTURE);
                    //echo "<pre>"; var_dump($submatch); echo "</pre>";

                    if ($submatch[0] == "")
                    $submatch[0] = "*";

                    if ($submatch[0] != "*")
                    $c++;

                    $item["path"] .= array_shift($submatch).$close_next_match;
                    $close_next_match = "";

                    $submode = 0;
                    while (count($submatch))
                    switch($submode)
                    {
                        case 0:
                            $operator = array_shift($submatch);
                            $submode = 1;
                            break;
                        case 1:
                            $param = array_shift($submatch);
                            $submode = 0;
                            if ($operator == ".")
                            {
                                $item["path"] .= "[contains(concat(\" \", normalize-space(@class), \" \"), \" $param \")]";
                                $b++;
                            }
                            else if ($operator == "#")
                            {
                                $item["path"] .= "[@id=\"$param\"]";
                                $a++;
                            }
                            else if ($operator == ":")
                            {
                                if ($param == "first-child")
                                {
                                    $item["path"] .= "[../child::*[1]=self::node()]";
                                }
                                else
                                $item["pseudo"] = $param;
                            }
                            break;
                    }

                }
                $item["spec"] = ($mode << 24) | ($a << 16) | ($b << 8) | ($c);
                $paths[] = $item;
            }
        }


        public function stripPrivateAttributes($node = null)
        {
            $parts = array();
            foreach(PropertyDefinition::$PrivPrefixes as $prefix)
            $parts[] = 'starts-with(name(self::node()), "'.$prefix.'")';
            $q = "//@*[".implode(" or ", $parts)."]";
            $this->stripNodes($q, $node);
        }


        public function stripNodes($query, $node = null)
        {
            //echo $query."<br>";
            if ($node === null) $node = $this->Document->documentElement;
            $res = $this->XPath->query($query, $node);
            //echo "STRIP: $query, got ".$res->length." results<br>";
            foreach($res as $rnode)
            if ($rnode instanceof DOMAttr)
            {
                //echo "MATCH: ".$rnode->name."<br>";
                $rnode->ownerElement->removeAttributeNode($rnode);
            }
            else
            {
                //echo "removing ".$rnode->nodeName."<br>";
                $p = $rnode->parentNode;
                if ($p)
                $p->removeChild($rnode);
                else
                Lithron::log("BUG: stripNodes('$query')", LOG_DEBUG, "Lithron");
            }
        }


        protected function validateSpecifiedProperties($node = null)
        {
            if ($node === null) $node = $this->Document->documentElement;
            $q = '//@*';
            $attrs = $this->XPath->query($q, $node);
            foreach($attrs as $qnode)
            {
                // TODO: allow class to be validated normally
                if ($qnode->name == "class") continue;

                if (!Property::validate($qnode->name, $qnode->value, false))
                if (!Property::validate($qnode->name, "\"".$qnode->value."\""))
                $qnode->ownerElement->removeAttributeNode($qnode);
                else
                #$qnode->value = "\"".$qnode->value."\"";
                $qnode->value = "\"".str_replace("&", "&amp;", $qnode->value)."\"";
            }
        }


        protected function transformLinkTags()
        {
            $node = $this->Document->documentElement;
            $links = $this->XPath->query($q = '//link');
            foreach($links as $link)
            {
                switch($link->getAttribute("rel"))
                {
                    case "stylesheet":
                        $url = $link->getAttribute("rel");
                        break;
                }
            }
        }

        protected function applyLithronStylesheet($node = null)
        {
            if ($node === null) $node = $this->Document->documentElement;
            $defcss = file_get_contents(dirname(__FILE__)."/LithronStylesheet.css");
            $this->Tidy->parse($defcss);
            $pcss = array_shift($this->Tidy->css);
            $this->applyCSSWrapper($node, $pcss, self::STYLESHEET_MODE_LITHRON);
        }

        protected function applyAuthorStylesheet($node = null)
        {
            if ($node === null) $node = $this->Document->documentElement;
            $stylenodes = $this->XPath->query($q = '//style/text()');
            foreach($stylenodes as $node)
            {
                $this->Tidy->parse($node->wholeText);
                $pcss = array_shift($this->Tidy->css);
                $this->applyCSSWrapper($node->parentNode, $pcss, self::STYLESHEET_MODE_AUTHOR);
            }
        }

        protected function invokeTransformers()
        {
            $transformerList = array(
            "OldLists",
            "EvalCounters",
            "ComputeProps",
            "GenerateAnonymousElements",
            );
            $dir = dirname(__FILE__)."/transformers/";
            foreach($transformerList as $tf)
            {
                Lithron::trace("Invoking transformer $tf", "Plugin");
                require_once($dir.$tf.".php");
                $c = new $tf($this);
                $c->transform();
                $c = null;
            }
        }


        protected function generateWorkers($node = null, $level = 0)
        {
            if ($node === null) $node = $this->Document->documentElement;
            $parr = explode("-", Property::get($node, "display"));
            $class = "Worker";
            foreach($parr as $value) $class .= ucfirst($value);
            require_once(dirname(__FILE__)."/workers/".$class.".php");
            $w = new $class($this, $node, $level);
            $w->initializePre();
            $subnode = $node->firstChild;
            while($subnode)
            {
                if ($subnode->nodeType == XML_ELEMENT_NODE)
                {
                    $subw = $this->generateWorkers($subnode, $level+1);
                    $w->appendChild($subw);
                }
                $subnode = $subnode->nextSibling;
            }
            $w->initializePost();
            return $w;
        }


        public static function trace($msg, $category = 'Uncategorized', $nodelevel = 2)
        {
            #echo self::$LogLevel;
            //LOG_EMERG 0, LOG_ALERT 1, LOG_CRIT 2, LOG_ERR 3, LOG_WARNING 4, LOG_NOTICE 5, LOG_INFO 6, LOG_DEBUG 7
            if (self::$LogLevel <= LOG_INFO) return;
            #if (self::$LogLevel === LOG_DEBUG)
            #{
            $trace = debug_backtrace();
            if (isset($trace[0]['file']) && isset($trace[0]['line']))
            $msg .= " (line {$trace[0]['line']}, {$trace[0]['file']})";
            $level = LOG_DEBUG;
            #}
            #else
            #	$level = LOG_INFO;

            self::log($msg, $level, $category, $nodelevel);
        }

        public static function log($msg, $level = LOG_INFO, $category = 'Uncategorized', $nodelevel = 2)
        {
            if(self::$LogLevel >= $level)
            self::$LogBook[] = array('<span style="margin-left:'.(($nodelevel-2)*30).'px">'.$msg.'</span>', $level, $category, microtime(true));
        }

        private static function tformat($t)
        {
            if ($t > 1.0)
            {
                $f = 1.0;
                $e = "s";
            }
            else if ($t > 0.001)
            {
                $f = 1000.0;
                $e = "ms";
            }
            else
            {
                $f = 1000000.0;
                $e = "&micro;s";
            }
            return sprintf("%.02f&nbsp;$e", $t*$f);

        }

        public static function dumpLog()
        {
            if (count(self::$LogBook) == 0) return;

            $log = '<table id="LithronLogTable">';
            $log .= '<thead><tr><td>Level</td><td>Message</td><td>Category</td><td>Time</td></tr></thead>';
            $c = count(self::$LogBook);
            #var_dump(self::$LogBook);
            foreach(self::$LogBook as $num => $entry)
            {
                $class = "class='level".$entry[1]."'";
                $log .= "<tr>";
                $log .= "<td $class align=center>".$entry[1]."</td>";
                $log .= "<td>".$entry[0]."</td>";
                $log .= "<td>".$entry[2]."</td>";

                if ($num == 0) $first_time = $entry[3];

                if ($num != $c - 1)
                {
                    $next_time = self::$LogBook[$num+1][3];
                    $log .= "<td>".self::tformat($entry[3]-$first_time)."&nbsp;(".self::tformat($next_time - $entry[3]).")</td>";
                }
                else
                $log .= "<td>".self::tformat($entry[3]-$first_time)."</td>";

                $log .= "</tr>";
            }
            $log .= "</table>";
            $log .= "Levels: 
                <span class='level7'>DEBUG (".LOG_DEBUG.")</span>
                <span class='level6'>INFO (".LOG_INFO.")</span>
                <span class='level5'>NOTICE (".LOG_NOTICE.")</span>
                <span class='level4'>WARNING (".LOG_WARNING.")</span>
                <span class='level3'>ERR (".LOG_ERR.")</span>
                <span class='level2'>CRIT(".LOG_CRIT.")</span>
                <span class='level1'>ALERT (".LOG_ALERT.")</span>
                <span class='level0'>EMERG (".LOG_EMERG.")</span>

                <style type='text/css'>
#LithronLogTable {
border-width:0;
font-size:10px;
}
#LithronLogTable thead {
font-weight:bold;
}
#LithronLogTable td {
border:0 dashed black;
padding:2px;
}
#LithronLogTable tr {
-moz-background-clip:border;
-moz-background-inline-policy:continuous;
-moz-background-origin:padding;
background:#EEEEEE none repeat scroll 0 0;
}
.level7 {
-moz-background-clip:border;
-moz-background-inline-policy:continuous;
-moz-background-origin:padding;
background:#EEEEEE none repeat scroll 0 0;
color:black;
}
.level6 {
-moz-background-clip:border;
-moz-background-inline-policy:continuous;
-moz-background-origin:padding;
background:green none repeat scroll 0 0;
color:white;
}
.level5 {
-moz-background-clip:border;
-moz-background-inline-policy:continuous;
-moz-background-origin:padding;
background:blue none repeat scroll 0 0;
color:white;
}
.level4 {
-moz-background-clip:border;
-moz-background-inline-policy:continuous;
-moz-background-origin:padding;
background:yellow none repeat scroll 0 0;
}
.level3 {
-moz-background-clip:border;
-moz-background-inline-policy:continuous;
-moz-background-origin:padding;
background:orange none repeat scroll 0 0;
}
.level2 {
-moz-background-clip:border;
-moz-background-inline-policy:continuous;
-moz-background-origin:padding;
background:red none repeat scroll 0 0;
color:white;
}
.level1 {
-moz-background-clip:border;
-moz-background-inline-policy:continuous;
-moz-background-origin:padding;
background:magenta none repeat scroll 0 0;
color:white;
}
.level0 {
border:2px solid red;
color:red;
margin:-2px;
}
#SourcePanel {
-moz-background-clip:border;
-moz-background-inline-policy:continuous;
-moz-background-origin:padding;
background:#EEEEEE none repeat scroll 0 0;
font-family:monospace;
font-size:12px;
overflow:auto;
padding:1em;
}
</style>
                ";
            return $log;
        }

        public static function prepareTemplate($tpath)
        {
            #echo $tpath;exit;
            $service= Prado :: getApplication()->Service;
            #$realpath= $service->getBasePath() . DIRECTORY_SEPARATOR . strtr($tpath, '.', DIRECTORY_SEPARATOR);
            $realpath=Prado::getPathOfNamespace($tpath);
            //var_dump($realpath);
            //die();
            $page= Prado :: createComponent($tpath);
            #echo $realpath . TPageService :: PAGE_FILE_EXT;exit;
            $page->setTemplate($service->getTemplateManager()->getTemplateByFileName($realpath . TPageService :: PAGE_FILE_EXT));
            return $page;
        }

        public static function compileTemplate($template)
        {
            $textWriter= new TTextWriter();
            $htmlWriter= new THtmlWriter($textWriter);
            $template->run($htmlWriter);
            $xml = $textWriter->flush();
            return $xml;
        }

        public static function setLogLevel($level = LOG_NOTICE)
        {
            self::$LogLevel = $level;
        }

        private function checkPdfLibMode()
        {

            //PDFlib Lite
            try{
                $pdf = new PDFlib();
                $pdf->begin_document("", "compatibility={1.6}");
                $pdf->open_pdi(dirname(__FILE__)."/support/check.pdf", '', 0);
                $this->PdfLibMode = "full";
            }
            catch (Exception $e)
            {
                if (strchr($e->getMessage(), "PDFlib Lite"))
                {
                    $this->PdfLibMode = "lite";
                    Lithron::log("Switching to PDFlib Lite mode. No PDI available!", LOG_WARNING, "BlockImage");
                }
            }

        }


    }

    ?>
