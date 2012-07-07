<?php


class PropertyDefinition
{
	const SPECIFICITY_PREFIX = "_spec-";
	const COUNTER_PREFIX = "_count-";
	const AKKU_PREFIX = "_akku-";
	const TEMP_PREFIX = "_temp-";
	
	public static $PrivPrefixes = array(
		self::SPECIFICITY_PREFIX,
		self::COUNTER_PREFIX,
		self::AKKU_PREFIX,
		self::TEMP_PREFIX,
	);

	const ANON_NODE_NAME       = "anon";
	const PSEUDO_SELECTOR_NAME = "_temp-selector";

	public static $DefaultFonts = array(
		"courier" => array(
			"normal" => array("name" => "Courier"),
			"bold" => array("name" => "Courier-Bold"),
			"italic" => array("name" => "Courier-Oblique"),
			"oblique" => array("name" => "Courier-Oblique"),
			"boldoblique" => array("name" => "Courier-BoldOblique"),
                        "bolditalic" => array("name" => "Courier-BoldOblique"),
		),
		"helvetica" => array(
			"normal" => array("name" => "Helvetica"),
			"bold" => array("name" => "Helvetica-Bold"),
			"italic" => array("name" => "Helvetica-Oblique"),
			"oblique" => array("name" => "Helvetica-Oblique"),
			"boldoblique" => array("name" => "Helvetica-BoldOblique"),
                        "bolditalic" => array("name" => "Helvetica-BoldOblique"),
		),
		"times" => array(
			"normal" => array("name" => "Times-Roman"),
			"bold" => array("name" => "Times-Bold"),
			"italic" => array("name" => "Times-Italic"),
                        "boldoblique" => array("name" => "Times-BoldItalic"),
			"bolditalic" => array("name" => "Times-BoldItalic"),
		),
		"zapf" => array(
			"normal" => array("name" => "ZapfDingbats"),
			"bold" => array("name" => "ZapfDingbats"),
			"italic" => array("name" => "ZapfDingbats"),
                    "boldoblique" => array("name" => "ZapfDingbats"),
			"bolditalic" => array("name" => "ZapfDingbats"),
		),
	);

	private static $keywords = array();
	protected static $def = array(
	
		// ---------------------------
		// ANCHOR
		// ---------------------------
		"anchor-set" => array(
			"allowed"   => "<string>|none", 
			"initial"   => "none",
		),
		"anchor-get" => array(
			"allowed"   => "<string>|none",
			"initial"   => "none",
		),
		
		// ---------------------------
		// REPEATER
		// ---------------------------
		"modulo" => array(
			"allowed"   => "<length>", // TODO: Bug in Tidy - can't supply integer'
			"initial"   => "1",
		),
		"modulo-result" => array(
			"allowed"   => "<string>|none", // TODO: Bug in Tidy - can't supply integer'
			"initial"   => "none",
		),
		"modulo-mode" => array(
			"allowed"   => "equal|different", 
			"initial"   => "equal",
		),
		

		// ---------------------------
		// FILE
		// ---------------------------
		"name" => array(
			"allowed"   => "<string>",
			"initial"   => "uniquename('.pdf')",
		),
		"title" => array(
			"allowed"   => "<string>",
			"initial"   => "untitled",
		),
		"author" => array(
			"allowed"   => "<string>",
			"initial"   => "unknown",
		),
		"creator" => array(
			"allowed"   => "<string>",
			"initial"   => "lithron PDF-Renderer",
		),
		"compatibility" => array(
			"allowed"   => "<string>",
			"initial"   => "1.5",
		),
		"permissions" => array(
			"allowed"   => "<string>",
			"initial"   => "",
		),

		"href" => array(
			"allowed"   => "<string>",
			"initial"   => "",
		),

		"class" => array(
			"allowed"   => "<string>",
			"initial"   => "",
		),

		"breakable" => array(
			"allowed"   => "yes|no",
			"initial"   => "yes",
		),

		"well-id" => array(
			"allowed"   => "<string>",
			"initial"   => "",
		),


		// IMAGE
		"src" => array(
			"allowed"   => "<string>",
			"initial"   => "",
		),
		"src-low" => array(
			"allowed"   => "<string>",
			"initial"   => "",
		),

		"img-scale" => array(
			"allowed"   => "<string>|<number>|none", // TODO: may not be string!!!
			"initial"   => '1',
		),

		"img-position-mode" => array(
			"allowed"   => "meet|clip",
			"initial"   => 'meet',
		),

		"img-position-x" => array(
			"allowed"   => "<number>|<string>|<length>",// TODO: may not be string!!!(tidy appends px!)
			"initial"   => 0,
		),

		"img-position-y" => array(
			"allowed"   => "<number>|<string>|<length>",// TODO: may not be string!!! (tidy appends px!)
			"initial"   => 0,
		),

		"img-resolution" => array(
			"allowed"   => "low|high",
			"initial"   => 'high',
			"inherited" => true,
		),


		// ---------------------------
		// PSEUDO
		// ---------------------------
		"selector" => array(
			"allowed"   => "before|after",
			"initial"   => "none",
		),

		// ---------------------------
		// COUNTERS
		// ---------------------------
		"content" => array(
			"allowed"   => "((<string>|<uri>|<counter>|<counters>|pagenum|open-quote|close-quote|no-open-quote|no-close-quote) ?)+|inherit",
			"initial"   => "",
		),
		"counter-reset" => array(
			"allowed"   => "((( )?<identifier>)( <integer>)?)+|none|inherit",
			"initial"   => "none",
		),
		"counter-increment" => array(
			"allowed"   => "((( )?<identifier>)( <integer>)?)+|none|inherit",
			"initial"   => "none",
		),

		// ---------------------------
		// LISTS
		// ---------------------------
		"list-style-image" => array(
			"allowed"   => "<uri>|none|inherit",
			"initial"   => "none",
			"inherited" => true,
		),
		"list-style-position" => array(
			"allowed"   => "inside|outside|inherit",
			"initial"   => "outside",
			"inherited" => true,
		),
		"list-style-type" => array(
			"allowed"   => "disc|circle|square|decimal|decimal-leading-zero|lower-roman|upper-roman|none|inherit",
			"initial"   => "disc",
			"inherited" => true,
		),

		// ---------------------------
		// FONTS
		// ---------------------------
		/*
		"font" => array(
			"allowed"   => "(<'font-style'> )?(<'font-variant'> )?(<'font-weight'> )?<'font-size'>( \/ <'line-height'> )? <'font-family'>",
			"shorthand" => true,
		),
		*/
		"font-family" => array(
			"allowed"   => "<identifier>|inherit",
			"initial"   => "times",
			"inherited" => true,
		),
		"font-style" => array(
			"allowed"   => "normal|italic|oblique|inherit",
			"initial"   => "normal",
			"inherited" => true,
		),
		"font-weight" => array(
			"allowed"   => "<integer>|normal|bold|inherit",
			"initial"   => "normal",
			"inherited" => true,
		),
		"font-variant" => array(
			"allowed"   => "normal|small-caps|inherit",
			"initial"   => "normal",
			"inherited" => true,
		),
		"font-size" => array(
			"allowed"   => "<length>|<percentage>|inherit",
			"initial"   => "12pt",
			"inherited" => true,
		),
		"line-height" => array(
			"allowed"   => "normal|<number>|<length>|<percentage>|inherit",
			"initial"   => "normal",
			"inherited" => true,
		),

		"white-space" => array(
			"allowed"   => "normal|pre|nowrap|inherit",
			"initial"   => "normal",
			"inherited" => true,
		),
		"text-transform" => array(
			"allowed"   => "capitalize|uppercase|uppercase_sz|lowercase|none|inherit",
			"initial"   => "none",
			"inherited" => true,
		),
		"text-decoration" => array(
			"allowed"   => "underline|none|inherit",
			"initial"   => "none",
		),
		"text-align" => array(
			"allowed"   => "left|right|center|justify|inherit",
			"initial"   => "left",
			"inherited" => true,
		),
		"word-spacing" => array(
			"allowed"   => "normal|<length>|inherit",
			"initial"   => "normal",
			"inherited" => true,
		),
		"letter-spacing" => array(
			"allowed"   => "normal|<length>|inherit",
			"initial"   => "normal",
			"inherited" => true,
		),

		// ---------------------------
		// LAYOUT
		// ---------------------------
		"display" => array(
			"allowed"   => "lithron|file|page|inline|block|sink|well|marker|list-item|block-image|pdf-lib-command|repeater|none|inherit",
			"initial"   => "inline",
		),
		"position" => array(
			"allowed"   => "static|relative|absolute|fixed|inherit",
			"initial"   => "static",
		),
		"width" => array(
			"allowed"   => "<length>|<percentage>|auto|inherit",
			"initial"   => "auto",
		),
		"min-width" => array(
			"allowed"   => "<length>|<percentage>|inherit",
			"initial"   => "0",
		),
		"max-width" => array(
			"allowed"   => "<length>|<percentage>|none|inherit",
			"initial"   => "none",
		),
		"height" => array(
			"allowed"   => "<length>|<percentage>|auto|inherit",
			"initial"   => "auto",
		),
		"min-height" => array(
			"allowed"   => "<length>|<percentage>|inherit",
			"initial"   => "0",
		),
		"max-height" => array(
			"allowed"   => "<length>|<percentage>|none|inherit",
			"initial"   => "none",
		),
		"top" => array(
			"allowed"   => "<length>|<percentage>|auto|inherit",
			"initial"   => "auto",
		),
		"right" => array(
			"allowed"   => "<length>|<percentage>|auto|inherit",
			"initial"   => "auto",
		),
		"bottom" => array(
			"allowed"   => "<length>|<percentage>|auto|inherit",
			"initial"   => "auto",
		),
		"left" => array(
			"allowed"   => "<length>|<percentage>|auto|inherit",
			"initial"   => "auto",
		),
		"rotation" => array(
			"allowed"   => "<length>",
			"initial"   => "0",
		),
		"margin" => array(
			"allowed"   => "<length>|<percentage>|inherit",
			"initial"   => "0",
            "shorthand" => true,
		),
		"margin-top" => array(
			"allowed"   => "<length>|<percentage>|auto|inherit",
			"initial"   => "0",
		),
		"margin-right" => array(
			"allowed"   => "<length>|<percentage>|auto|inherit",
			"initial"   => "0",
		),
		"margin-bottom" => array(
			"allowed"   => "<length>|<percentage>|auto|inherit",
			"initial"   => "0",
		),
		"margin-left" => array(
			"allowed"   => "<length>|<percentage>|auto|inherit",
			"initial"   => "0",
		),
		"padding" => array(
			"allowed"   => "<length>|<percentage>|inherit",
			"initial"   => "0",
            "shorthand" => true,
		),
		"padding-top" => array(
			"allowed"   => "<length>|<percentage>|inherit",
			"initial"   => "0",
		),
		"padding-right" => array(
			"allowed"   => "<length>|<percentage>|inherit",
			"initial"   => "0",
		),
		"padding-bottom" => array(
			"allowed"   => "<length>|<percentage>|inherit",
			"initial"   => "0",
		),
		"padding-left" => array(
			"allowed"   => "<length>|<percentage>|inherit",
			"initial"   => "0",
		),
		"border-width" => array(
			"allowed"   => "<length>|inherit",
			"initial"   => "1",
            "shorthand" => true,
		),
		"border-top-width" => array(
			"allowed"   => "<length>|inherit",
			"initial"   => "1",
		),
		"border-right-width" => array(
			"allowed"   => "<length>|inherit",
			"initial"   => "1",
		),
		"border-bottom-width" => array(
			"allowed"   => "<length>|inherit",
			"initial"   => "1",
		),
		"border-left-width" => array(
			"allowed"   => "<length>|inherit",
			"initial"   => "1",
		),
		"border-color" => array(
			"allowed"   => "<cmyk>|colorprop|tansparent|inherit",
			"initial"   => "colorprop",
            "shorthand" => true,
		),
		"border-top-color" => array(
			"allowed"   => "<cmyk>|colorprop|tansparent|inherit",
			"initial"   => "colorprop",
		),
		"border-right-color" => array(
			"allowed"   => "<cmyk>|colorprop|tansparent|inherit",
			"initial"   => "colorprop",
		),
		"border-bottom-color" => array(
			"allowed"   => "<cmyk>|colorprop|tansparent|inherit",
			"initial"   => "colorprop",
		),
		"border-left-color" => array(
			"allowed"   => "<cmyk>|colorprop|tansparent|inherit",
			"initial"   => "colorprop",
		),
		"border-style" => array(
			"allowed"   => "none|hidden|dotted|dashed|solid|double|groove|ridge|inset|outset|inherit",
			"initial"   => "none",
            "shorthand" => true,
		),
		"border-top-style" => array(
			"allowed"   => "none|hidden|dotted|dashed|solid|double|groove|ridge|inset|outset|inherit",
			"initial"   => "none",
		),
		"border-right-style" => array(
			"allowed"   => "none|hidden|dotted|dashed|solid|double|groove|ridge|inset|outset|inherit",
			"initial"   => "none",
		),
		"border-bottom-style" => array(
			"allowed"   => "none|hidden|dotted|dashed|solid|double|groove|ridge|inset|outset|inherit",
			"initial"   => "none",
		),
		"border-left-style" => array(
			"allowed"   => "none|hidden|dotted|dashed|solid|double|groove|ridge|inset|outset|inherit",
			"initial"   => "none",
		),
		"border-stroke-mode" => array(
			"allowed"   => "single|multi",
			"initial"   => "multi",
		),
		"border-stroke-color" => array(
			"allowed"   => "<cmyk>",
			"initial"   => "cmyk|0|0|0|1",
		),
		"border-stroke-width" => array(
			"allowed"   => "<length>",
			"initial"   => "0",
		),
		"border-stroke-cap" => array(
			"allowed"   => "<integer>",
			"initial"   => 0,
		),
		"border-stroke-join" => array(
			"allowed"   => "<string>",// TODO: may not be string!!!(tidy appends px!)
			"initial"   => 0,
		),
		"border-stroke-pattern" => array(
			"allowed"   => "<string>",
			"initial"   => "none",
		),
        "color" => array(
			"allowed"   => "<cmyk>|inherit",
			"initial"   => "cmyk|0|0|0|1",
			"inherited" => true,
		),
		"background-color" => array(
			"allowed"   => "<cmyk>|transparent|inherit",
			"initial"   => "transparent",
		),
	);
	
	public static function init()
	{
		self::$keywords = array();
		foreach (self::$def as $d)
		{
			$parts = preg_split("/([|()+? ]+|<.*?>)/", $d["allowed"], -1, PREG_SPLIT_NO_EMPTY);
			self::$keywords = array_merge(self::$keywords, $parts);
		}
		self::$keywords = array_unique(self::$keywords);
		//echo "<pre>"; echo htmlentities(var_export(self::$keywords, true)); echo "</pre>";
	}

	public static function has($attribute)
	{
		return isset(self::$def[$attribute]);
	}

	public static function allowed($attribute)
	{
		if (!isset(self::$def[$attribute])) return null;
		//return str_replace("<identifier>", "[a-z]+", self::$def[$attribute]["allowed"]);
		return self::$def[$attribute]["allowed"];
	}

	public static function initial($attribute)
	{
		return isset(self::$def[$attribute]) ? self::$def[$attribute]["initial"] : null;
	}

	public static function isInherited($attribute)
	{
		return isset(self::$def[$attribute]) && isset(self::$def[$attribute]["inherited"]) ? self::$def[$attribute]["inherited"] : false;
	}

	public static function isKeyword($value)
	{
		return in_array($value, self::$keywords);
	}

	public static function isShorthand($attribute)
	{
		return isset(self::$def[$attribute]) && isset(self::$def[$attribute]["shorthand"]) ? self::$def[$attribute]["shorthand"] : false;
	}

	public static function isPrivate($attribute)
	{
		foreach(self::$PrivPrefixes as $prefix)
			if (strpos($attribute, $prefix) === 0)
				return true;
		return false;
	}

}

?>
