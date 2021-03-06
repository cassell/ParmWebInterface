<?php

namespace ParmWebInterface\Page;

abstract class HtmlPage
{
	var $htmlTitle;
	var $scripts = array();
	var $styleSheets = array();
	
	const FAVICON_PNG = '/img/favicon.png';

	// prototypes
	abstract function open();
	abstract function close();
	
	function insertScript($scriptName)
	{
		$this->scripts[] =  '<script src="' . $scriptName . '"></script>';
	}
	
	function insertStyleSheet($styleSheet)
	{
		$this->styleSheets[] = '<link rel="stylesheet" href="' . $styleSheet . '" type="text/css" />';
	}
	
	function insertPrintMediaStyleSheet($styleSheet)
	{
		$this->styleSheets[] = '<link rel="stylesheet" href="' . $styleSheet . '" type="text/css" media="print" />';
	}
	
	function insertJavaScriptBlock($block)
	{
		// async means I promise not to document.write
		$this->scripts[] = '<script>' . $block . '</script>';
	}
	
	function insertJavascriptData($array,$variableName = 'data')
	{
		if($array != null)
		{
			$this->insertJavaScriptBlock('var ' . $variableName . ' = ' . self::toJSONString($array) . ';');
		}
		else
		{
			$this->insertJavaScriptBlock('var ' . $variableName . ' = [];');
		}
	}
	
	function insertStyleBlock($block)
	{
		$this->styleSheets[] = '<style type="text/css">' . $block . '</style>';
	}
	
	function getScripts()
	{
		return $this->scripts;
	}
	
	function getStyleSheets()
	{
		return $this->styleSheets;
	}
	
	function setHtmlTitle($val) { $this->htmlTitle = $val; }
	function getHtmlTitle() { return $this->htmlTitle; }
	
	function printHtmlHeader()
	{
		echo '<!DOCTYPE html>',
			 '<html>',
		 	 '<head>',
		  	 '<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />',
		     '<meta http-equiv="Pragma" content="no-cache" />',
			 '<meta http-equiv="cache-control" content="no-cache" /> ',
			 '<title>' , $this->getHtmlTitle()  , '</title>';
		
		echo @implode("",$this->getStyleSheets());
		echo @implode("",$this->getScripts());
		
		echo '</head>';
		
		echo '<body onunload="">';  // onunload forces the browser to reload the page when the back button is pressed (Safari back button issue)
									// See:
									// http://stackoverflow.com/questions/5297122/preventing-cache-on-back-button-in-safari-5
									// http://stackoverflow.com/questions/24046/the-safari-back-button-problem
	}
	
	function printHtmlFooter() 
	{
		echo '</body>';
		echo '</html>';
	}
	
	/**
	 * Convert to a JSON string
     * @return string The row formatted in JSON
     */
	static function toJSONString(Array $array)
	{
		return json_encode(self::utf8EncodeArray($array));
	}
	
	static protected function utf8EncodeArray(Array $array)
	{
		foreach ($array as $key => $value)
		{
			if (is_array($value))
			{
				$array[$key] = self::utf8EncodeArray($value);
			}
			else
			{
				$array[$key] = utf8_encode($value);
			}
		}

		return $array;
	}
	
}
