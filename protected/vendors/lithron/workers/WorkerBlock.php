<?php

class WorkerBlock extends WorkerPlugin
{
	const BWR_OK = 0;
	const BWR_FULL = 1;
	const BWR_COLUMNBREAK = 2;
	
	public function getPrefWidth()
	{
		$akku = array();
		$lb = null;
		$sub = $this->firstChildWorker;
		while($sub)
		{
			if ($sub->isInline())
			{
				Lithron::trace("<span style=\"color:blue;\">".$this->DOMNode->nodeName.".getPrefWidth() - processing inline child ".$sub->DOMNode->nodeName."</span>", "Layout", $this->Level+1);
				$saveid = $sub->save();
				do
				{
					if ($lb === null) $lb = new LineBox(Lithron::INFINITY);
					$ret = $sub->flowInto($lb);	
					if ($ret == LineBox::FLOW_NEWLINE || $ret == LineBox::FLOW_COLUMNBREAK || $ret == LineBox::FLOW_FULL)
					{
						$akku[] = $lb->getWidth();
						$lb = null;						
					}
				}
				while ($ret != LineBox::FLOW_DONE);
				$sub->restore($saveid);
			}
			elseif (!$sub->isPositioned() && !$sub->isMarker())
			{
				Lithron::trace("<span style=\"color:blue;\">".$this->DOMNode->nodeName.".getPrefWidth() - processing static child ".$sub->DOMNode->nodeName."</span>", "Layout", $this->Level+1);
				if ($lb !== null)
				{
					$akku[] = $lb->getWidth();
					$lb = null;						
				}

				if ($sub->typeOfWidth("<percentage>"))
					$cw = Lithron::INFINITY;
				else 
					$cw = $sub->propWidth();
				if ($cw == "auto") $cw = $sub->getPrefWidth();
				$mw = $sub->propMaxWidth();
				if ($mw == "none") $mw = Lithron::INFINITY;
				$cw = max($sub->propMinWidth(), min($cw, $mw));	
				$akku[] = $sub->calcMBPHoriz() + $cw;			
			}
			$sub = $sub->nextSiblingWorker;
		}
		if ($lb !== null)
		{
			$akku[] = $lb->getWidth();
			$lb = null;						
		}
		$res = count($akku) == 0 ? 0 : max($akku);
		Lithron::trace("<span style=\"color:blue;\">".$this->DOMNode->nodeName.".getPrefWidth() = $res</span>", "Layout", $this->Level);
		return $res;
	}
	
	public function getWidth($containerwidth = null)
	{
		$cw = $this->propWidth();
		if ($cw == "auto") $cw = $this->getPrefWidth(); 
		elseif ($this->typeOfWidth("<percentage>"))
		{
			if ($containerwidth !== null)
			{
				$match = $this->matchOfWidth("<percentage>");
				$cw = 0.01 * $match[0] * $containerwidth; 
			}
			else
				$cw = $this->getPrefWidth();
		}
		$mw = $this->propMaxWidth(); 
		if ($mw == "none") $mw = Lithron::INFINITY;
		$cw = max($this->propMinWidth(), min($cw, $mw));
		Lithron::trace("<span style=\"color:blue;\">".$this->DOMNode->nodeName.".getWidth($containerwidth) = $cw</span>", "Layout", $this->Level);
		return $cw;				
	}

	public function getPrefHeight($width)
	{
		$akku = 0;
		$lb = null;
		$sub = $this->firstChildWorker;
		while($sub)
		{
			if ($sub->isInline())
			{
				Lithron::trace("<span style=\"color:blue;\">".$this->DOMNode->nodeName.".getPrefHeight() - processing inline child ".$sub->DOMNode->nodeName."</span>", "Layout", $this->Level+1);
				$saveid = $sub->save();
				do
				{
					if ($lb === null) $lb = new LineBox($width);
					$ret = $sub->flowInto($lb);	
					if ($ret == LineBox::FLOW_NEWLINE || $ret == LineBox::FLOW_FULL)
					{
						$akku += $lb->getHeight();
						$lb = null;						
					}
				}
				while ($ret != LineBox::FLOW_DONE);
				$sub->restore($saveid);
			}
			elseif (!$sub->isPositioned() && !$sub->isMarker())
			{
				Lithron::trace("<span style=\"color:blue;\">".$this->DOMNode->nodeName.".getPrefHeight() - processing static child ".$sub->DOMNode->nodeName."</span>", "Layout", $this->Level+1);
				if ($lb !== null)
				{
					$akku += $lb->getHeight();
					$lb = null;						
				}
				$ch = $sub->propHeight(); 
				if ($ch == "auto") $ch = $sub->getPrefHeight($width - $sub->calcMBPHoriz()); 
				$mh = $sub->propMaxHeight();
				if ($mh == "none") $mh = Lithron::INFINITY;
				$ch = max($sub->propMinHeight(), min($ch, $mh));	
				$akku += $sub->calcMBPVert() + $ch;
			}			
			$sub = $sub->nextSiblingWorker;
		}
		if ($lb !== null)
		{
			$akku += $lb->getHeight();
			$lb = null;						
		}
		Lithron::trace("<span style=\"color:blue;\">".$this->DOMNode->nodeName.".getPrefHeight($width) = $akku</span>", "Layout", $this->Level);
		return $akku;
	}

	public function getHeight($width)
	{
		$ch = $this->propHeight();
		if ($ch == "auto") $ch = $this->getPrefHeight($width); 
		$mh = $this->propMaxHeight(); 
		if ($mh == "none") $mh = Lithron::INFINITY;
		$ch = max($this->propMinHeight(), min($ch, $mh));
		Lithron::trace("<span style=\"color:blue;\">".$this->DOMNode->nodeName.".getHeight($width) = $ch</span>", "Layout", $this->Level);
		return $ch;				
	}
	
	
	protected function getFirstWorker()
	{
		return $this->firstChildWorker;
	}

	protected function getNextWorker($cur_worker)
	{
		return $cur_worker->nextSiblingWorker;
	}


	protected function workSetup(&$trace, &$wellpath, &$sub, &$tracemode)
	{
		if (count($trace))
		{
			$sub = array_shift($trace);
			$tracemode = true;
		}
		else
		{
			$sub = $this->getFirstWorker();
			$tracemode = false;
		}
		
		if (is_array($wellpath))
			$wellpath[] = $this;
	}


	private function drawBackground($rop, $width, $height)
	{
		$bgcol = $this->propBackgroundColor();
		if ($bgcol != "transparent")
		{
			$rop->PRELI_setfillcolor($bgcol);
			$pl = $this->propPaddingLeft();
			$pt = $this->propPaddingTop();
			$rop->PRE_translate(-$pl, $pt);
			$rop->PRELI_rect($width + $this->calcPHoriz(), $height + $this->calcPVert());
			$rop->PRE_translate($pl, -$pt);
		}
	}

	private function drawBorders($rop, $width, $height)
	{
		// TODO
		$rop->PRE_save();

		$pl = $this->calcPLeft() + $this->calcBLeft()/2;
		$pt = $this->calcPTop() + $this->calcBTop()/2;
		$rop->PRE_translate(-$pl, $pt);

        if ($this->propBorderStrokeMode() == "single")
        {
            $map = array(
                "Top" => array(0,0),
                "Right" => array($width + $this->calcPHoriz()+$this->calcBHoriz()/2, 0),
                "Bottom" => array($width + $this->calcPHoriz()+$this->calcBHoriz()/2, -$height - $this->calcPVert() - $this->calcBVert()/2),
                "Left" => array(0, -$height - $this->calcPVert() - $this->calcBVert()/2)
            );
            foreach($map AS $side => $pos)
            {
                $fcolor = "propBorderStrokeColor";
                $color = $this->$fcolor();
                $fwidth = "propBorderStrokeWidth";
                $width = $this->$fwidth();

                if ($side == "Top") {
                    if ($color != "colorprop") $rop->PRELI_setstrokecolor($color);
                    $rop->PRE_setlinewidth($width);
                    if ($this->propBorderStrokePattern() !== "none") $rop->PRE_setdashpattern("dasharray={".$this->propBorderStrokePattern()."}");
                    $rop->PRE_setlinejoin($this->propBorderStrokeJoin());
                    $rop->PRE_setlinecap($this->propBorderStrokeCap());
                    $rop->PRE_moveto(0,0);
                }
                $rop->PRE_lineto($pos[0], $pos[1]);
                if ($side == "Left") $rop->PRE_closepath_stroke();
            }
        }
        else
        {
            // TODO
            Lithron::log("Can not draw borders", LOG_DEBUG, "WorkerBlock");
        }

        $rop->PRE_translate($pl, -$pt);

        $rop->PRE_restore();
	}

	private function workSubWorker($sub, $subrop, $child_w, $child_h, &$remain, $trace = array(), $wellpath = null)
	{
		$subrop->setDimensions($child_w, $child_h);
		$mx = $sub->calcMBPLeft();
		$my = $sub->calcMBPTop();
		$subrop->PRE_save();
		#echo "<br>";
        $complete_h = $child_h + $sub->calcMBPVert();
		if ($rot = $sub->propRotation())
		{
			$subrop->PRE_translate(0, -$complete_h);
			$subrop->PRE_rotate($rot);
			$rot_trans = 2*M_PI*$rot/360.0;
			$subrop->PRE_translate(sin($rot_trans), cos($rot_trans));
		}
		$subrop->PRE_translate($mx, -$my);
		$ret = $sub->work($subrop, $child_w, $child_h, $trace, $wellpath);
		$subrop->POST_restore();
		$subrop->POSTLI_positioned();
		$subrop->POST_translate(0, -$complete_h);
		$remain -= $child_h + $sub->calcMBPVert();
		return $ret;
	}
	
	private function workLineBox($rop, &$lb, &$remain)
	{
		if ($lb !== null)
		{
			$lbh = $lb->getHeight();
			if ($lbh == 0)
			{
				$lb = null;
				return self::BWR_OK;
			}
			if ($remain + Lithron::EPSILON < $lbh) 
			{
				$lb->revert();
				$lb = null;
				return self::BWR_FULL;
			}
			$subrop = new RenderOperation();
			$rop->addChild($subrop);
			$subrop->PRE_save();
			$lb->render($subrop);
			$subrop->POST_restore();
			$subrop->POST_translate(0, -$lb->getHeight());
			$remain -= $lbh;
			Lithron::trace("<span style=\"color:brown;\">".$this->DOMNode->nodeName.".work() - added linebox with height $lbh</span><br/>".$lb->dump(), "Layout", $this->Level+1);
			$lb = null;
		}
		return self::BWR_OK;
	}
	
	private function leaveTrace($wellpath, $sub)
	{
		if ($wellpath !== null)
		{
			$wp = $wellpath;
			$wp[] = $sub;
			$mywell = array_shift($wp);
			array_shift($wp);
			$mywell->setTracePath($wp);
			Lithron::trace("<span style=\"font-weight:bold;\">TRACEPATH TO ".$sub->DOMNode->nodeName." SET</span>", "Layout");
		}
	}
	

	public function work($rop, $width, $height, $trace = array(), $wellpath = null)
	{		
		Lithron::trace("<span style=\"color:brown;\">".$this->DOMNode->nodeName.".work() - Start</span>", "Layout", $this->Level);

		$this->workSetup($trace, $wellpath, $sub, $tracemode);
		$this->drawBackground($rop, $width, $height);
		$this->drawBorders($rop, $width, $height);
		
		$lb = null;
		$flow_remaining_h = $height;
		$res_work = self::BWR_OK;
		while ($sub)
		{
			if ($sub->isPositioned())
			{
				// absolute and fixed
				$subrop = new RenderOperation($sub);
				$subrop->setPosition($sub->propTop(), $sub->propRight(), $sub->propBottom(), $sub->propLeft());
				$this->findContainingRop()->addPositioned($subrop);
				$child_w = $sub->getWidth();
				$child_h = $sub->getHeight($child_w);
				Lithron::trace("<span style=\"color:brown;\">".$this->DOMNode->nodeName.".work() - processing positioned child ".$sub->DOMNode->nodeName." with size ($child_w/$child_h)</span>", "Layout", $this->Level+1);
				$this->workSubWorker($sub, $subrop, $child_w, $child_h, $dummy);
			}
			elseif ($sub->isInline())
			{

				Lithron::trace("<span style=\"color:brown;\">".$this->DOMNode->nodeName.".work() - processing inline child ".$sub->DOMNode->nodeName."</span>", "Layout", $this->Level+1);

				$this->leaveTrace($wellpath, $sub);

				if ($sub->isContainedInFixed())
					$saveid = $sub->save();
				do
				{
					if ($lb === null) $lb = new LineBox($width);
					$res_flow = $sub->flowInto($lb);	
					if ($res_flow == LineBox::FLOW_NEWLINE || $res_flow == LineBox::FLOW_FULL)
						$res_work = $this->workLineBox($rop, $lb, $flow_remaining_h);
				}
				while ($res_flow != LineBox::FLOW_DONE && $res_flow != LineBox::FLOW_COLUMNBREAK && $res_work == self::BWR_OK);
				if ($sub->isContainedInFixed())
					$sub->restore($saveid);

				if ($res_flow == LineBox::FLOW_COLUMNBREAK)
					$res_work = self::BWR_COLUMNBREAK;
			}
			else
			{
				$res_work = $this->workLineBox($rop, $lb, $flow_remaining_h);
				if ($res_work == self::BWR_OK)
				{
					if ($sub->isMarker())
					{
						// marker
						$subrop = new RenderOperation($sub);
						$rop->addPositioned($subrop);
						$child_w = $sub->getWidth();
						$pref_h = $sub->getHeight($child_w);
						$max_h = $flow_remaining_h - $sub->calcMBPVert();
						$child_h = min($max_h, $pref_h);
						Lithron::trace("<span style=\"color:brown;\">".$this->DOMNode->nodeName.".work() - processing marker child ".$sub->DOMNode->nodeName." with size ($child_w/$child_h)</span>", "Layout", $this->Level+1);
						$res_work = $this->workSubWorker($sub, $subrop, $child_w, $child_h, $dummy);
					}
					else
					{
						// static and relative
						$this->leaveTrace($wellpath, $sub);

						$child_w = min($sub->getWidth($width - $sub->calcMBPHoriz()), $width - $sub->calcMBPHoriz());
						$pref_h = $sub->getHeight($child_w);
						$max_h = $flow_remaining_h - $sub->calcMBPVert();
						if (($pref_h > $max_h) && $sub->propBreakable() == "no")
						{
							$res_work = self::BWR_FULL;							
						}
						else
						{
							$subrop = new RenderOperation($sub);
							$rop->addChild($subrop);
							$child_h = min($max_h, $pref_h);
							Lithron::trace("<span style=\"color:brown;\">".$this->DOMNode->nodeName.".work() - processing static child ".$sub->DOMNode->nodeName." with size ($child_w/$child_h) - ".($flow_remaining_h)." left</span>", "Layout", $this->Level+1);
							$res_work = $this->workSubWorker($sub, $subrop, $child_w, $child_h, $flow_remaining_h, $trace, $wellpath);
						}
					}					
				}
			}				
			
			$trace = array();
			$break_now = $res_work != self::BWR_OK; 
			$col = $break_now ? "red" : "green";
			Lithron::trace("<span style=\"color:$col;\">".$this->DOMNode->nodeName.".work() - finished processing child ".$sub->DOMNode->nodeName." - (".($height-$flow_remaining_h)."/$height) - ".($break_now?"CHILD WANTS BREAK":"CONTINUE")."</span>", "Layout", $this->Level+1);
			$sub = $break_now ? null : $this->getNextWorker($sub);
		}
		
		if ($res_work == self::BWR_OK)
		{
			$res_work = $this->workLineBox($rop, $lb, $flow_remaining_h);
		}
		elseif ($res_work == self::BWR_COLUMNBREAK)
		{
			$this->workLineBox($rop, $lb, $flow_remaining_h);
		}
		elseif ($lb !== null) 
		{
			$lb->revert();
			$res_work = self::BWR_FULL;
		}

		$break_now = $res_work != self::BWR_OK; 
		$col = $break_now ? "red" : "green";
		Lithron::trace("<span style=\"color:$col;\">".$this->DOMNode->nodeName.".work() - End</span>", "Layout", $this->Level);
		return $res_work;
	}
	


					/*
					if ($break_now && $wellpath !== null)
					{
						$wp = $wellpath;
						$wp[] = $sub;
						$mywell = array_shift($wp);
						array_shift($wp);
						$mywell->setTracePath($wp);
						Lithron::trace("<b style=\"color:red;\">TRACEPATH TO ".$sub->DOMNode->nodeName." SET</b>", "Layout", $this->Level);
					}
					if (!$break_now || $wellpath === null || $sub->propBreakable() == "yes")
					{
						$subrop = new RenderOperation($sub);
						$rop->addChild($subrop);
						$child_h = $break_now ? $max_h : $pref_h;
	
						$this->workSubWorker($sub, $subrop, $child_w, $child_h, $flow_remaining_h);
					}
					*/

					




	
}


?>