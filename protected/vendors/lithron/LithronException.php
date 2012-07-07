<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LithronException
 *
 * @author tobias
 */
class LithronException extends Exception {

    public function __construct($errorMessage)
	{
		$this->_errorCode=$errorMessage;
		#$errorMessage=$this->translateErrorMessage($errorMessage);
		$args=func_get_args();
		array_shift($args);
		$n=count($args);
		$tokens=array();
		for($i=0;$i<$n;++$i)
			$tokens['{'.$i.'}']=$args[$i];
		parent::__construct(strtr($errorMessage,$tokens));
	}

}
?>
