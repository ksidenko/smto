<?php

require_once(dirname(__FILE__)."/WorkerBlock.php");

class WorkerPage extends WorkerBlock
{
	public function work($rop)
	{
		$cw = $this->getWidth();
		$ch = $this->getHeight($cw);

		$rop->setDimensions($cw, $ch);
		
		$rop->PRE_begin_page_ext($cw, $ch, "");
		$rop->PRE_translate(0, $ch);
		$rop->PRE_save();
		parent::work($rop, $cw, $ch);
		$rop->POST_restore();
		$rop->POSTLI_positioned();

		$rop->POST_end_page_ext("");
	}
}


?>