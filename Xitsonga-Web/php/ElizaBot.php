<?php

require "Util.php";
require "ElizaConfigs.php";

class ElizaBot
{
	public $_dataParsed = false;

	protected $noRandom;
	protected $capitalizeFirstLetter = true;
	protected $debug = false;
	protected $memSize = 20;
	protected $version = "1.1 (original)";
	protected $quit;
	protected $mem = [];
	protected $lastChoice = [];
	protected $pres = [];
	protected $posts = [];
	protected $preExp;
	protected $sentence;

	function ElizaBot($noRandomFlag=false) {
		Util::echoln("construct ElizaBot");

		$this->noRandom = ($noRandomFlag) ? true : false;
		$this->capitalizeFirstLetter = true;
		$this->debug = false;
		$this->memSize = 20;
		if(!$this->_dataParsed)
			$this->_init();
		$this->reset();
	}

	function __destruct() {
		Util::echoln("destruct ElizaBot");
	}

	function reset() {
		Util::echoln("called reset()");

		global $elizaKeywords;

		$this->quit = false;
		$this->mem = [];
		$this->lastChoice = [];
		for($k=0; $k<count($elizaKeywords); $k++)
		{
			$this->lastChoice[$k] = [];
			$rules = $elizaKeywords[$k][2];
			for($i=0; $i<count($rules); $i++)
				$this->lastChoice[$k][$i] = -1;
		}
	}

	function _init() {
		Util::echoln("called _init()");

		global $elizaSynons;
		global $elizaKeywords;
		global $elizaPres;
		global $elizaPosts;
		global $elizaQuits;

		// parse data and convert it from canonical form to internal use
		// prodoce synonym list
		$synPatterns = [];
		if( $elizaSynons && is_array($elizaSynons) ) {
			foreach($elizaSynons as $key => $arrayValues)
				$synPatterns[$key] = '('.$key.'|'.join('|', $arrayValues).')';
		}

		// check for keywords or install empty structure to prevent any errors
		if(!$elizaKeywords) {
			$elizaKeywords = [['###',0,[['###',[]]]]];
		}
		// 1st convert rules to regexps
		// expand synonyms and insert asterisk expressions for backtracking
		$sre='/@(\S+)/';
		$are='/(\S)\s*\*\s*(\S)/';
		$are1='/^\s*\*\s*(\S)/';
		$are2='/(\S)\s*\*\s*$/';
		$are3='/^\s*\*\s*$/';
		$wsre='/\s+/';

		for($k=0; $k<count($elizaKeywords); $k++)
		{
			$rules = $elizaKeywords[$k][2];
			$elizaKeywords[$k][3] = $k;	// save original index for sorting
			for($i=0; $i<count($rules); $i++)
			{
				$r = $rules[$i];
				// check mem flag and store it as decomp's elements 2
				if($r[0][0] == '$')
				{
					$ofs = 1;
					while($r[0][$ofs] == ' ')
						$ofs++;
					$r[0] = substr($r[0], $ofs);
					$r[2] = true;
				}
				else
				{
					$r[2] = false;
				}

				// expand synonyms (v.1.1: work around lambda function)
				preg_match($sre, $r[0], $m, PREG_OFFSET_CAPTURE);
				while($m)
				{
					// consult https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/RegExp/exec for documentation on this section.
					$sp = $synPatterns[$m[1][0]] ? $synPatterns[$m[1][0]] : $m[1][0];
					$r[0] = substr($r[0], 0, $m[0][1]-1).$sp.substr($r[0], $m[0][1] + strlen($m[0][0]));
					preg_match($sre, $r[0], $m, PREG_OFFSET_CAPTURE);
				}
				// expand asterisk expressions (v.1.1: work around lambda function)
				if(preg_match($are3, $r[0]))
				{
					$r[0] = '\\s*(.*)\s*';
				}
				else
				{
					preg_match($are, $r[0], $m, PREG_OFFSET_CAPTURE);
					if($m)
					{
						$lp = '';
						$rp = $r[0];
						while($m)
						{
							$lp .= substr($rp, 0, $m[0][1]);
							if ($m[1][0] != ')')
								$lp .= '\\b';
							$lp .= '\\s*(.*)\\s*';
							if (($m[2][0] != '(') && ($m[2][0] != '\\'))
								$lp .= '\\b';
							$lp .= $m[2][0];
							$rp = substr($rp, $m[0][1] + strlen($m[0][0]));
							preg_match($are, $rp, $m, PREG_OFFSET_CAPTURE);
						}
						$r[0] = $lp.$rp;
					}
					preg_match($are1, $r[0], $m, PREG_OFFSET_CAPTURE);
					if($m)
					{
						$lp = '\\s*(.*)\\s*';
						if (($m[1][0] != ')') && ($m[1][0] != '\\'))
							$lp .= '\\b';
						$r[0] = $lp.substr($r[0], $m[0][1]-1+strlen($m[0][0]));
					}
					preg_match($are2, $r[0], $m, PREG_OFFSET_CAPTURE);
					if(m)
					{
						$lp = substr($r[0], 0, $m[0][1]);
						if ($m[1][0] != '(')
							$lp .= '\\b';
						$r[0] = $lp.'\\s*(.*)\\s*';
					}
				}
				// expand white space
				$r[0] = preg_replace($wsre, '\\s+', $r[0]);
			}
		}
		// now sort keywords by rank (highest first)
		sort($elizaKeywords, "self::_sortKeywords");
		// and compose regexps and refs for pres and posts
		if($elizaPres && count($elizaPres))
		{
			$a = [];
			for($i = 0; $i < count($elizaPres); $i+=2)
			{
				$a[] = $elizaPres[i];
				$this->pres[$elizaPres[$i]] = $elizaPres[$i+1];
			}
			$this->preExp = '\\b('.join('|', $a).')\\b';
		}
		else
		{
			// default (should not match)
			$this->preExp = '/####/';
			$this->pres['####'] = '####';
		}
		if($elizaPosts && count($elizaPosts))
		{
			$a = [];
			for($i=0; $i<count($elizaPosts); $i+=2)
			{
				$a[] = $elizaPosts[i];
				$this->posts[$elizaPosts[i]] = $elizaPosts[i+1];
			}
			$this->postExp = '\\b('.join('|', $a).')\\b';
		}
		else
		{
			// default (should not match)
			$this->postExp = '/####/';
			$this->posts['####'] = '####';
		}
		// check for elizaQuits and install default if missing
		if (!isset($elizaQuits))
		{
			$elizaQuits = [];
		}
		// done
		$this->_dataParsed = true;
	}

	function _sortKeywords($a, $b) {
		// sort by rank
		if($a[1] > $b[1])
			return -1;
		else if($a[1] < $b[1])
			return 1;
		// or original index
		else if($a[3] > $b[3])
			return 1;
		else if($a[3] < $b[3])
			return -1;
		else
			return 0;
	}

	function transform($text) 
	{
		global $elizaQuits;
		global $elizaKeywords;

		$rpl = '';
		$this->quit = false;
		// unify text string
		$text = strtolower($text);
		$text = preg_replace('/@#\$%\^&\*\(\)_\+=~`\{\[\}\]\|:;<>\/\\\t/', ' ', $text);
		$text = preg_replace('/\s+-+\s+/', '.', $text);
		$text = preg_replace('/\s*[,\.\?!;]+\s*/', '.', $text);
		$text = preg_replace('/\s*\bbut\b\s*/', '.', $text);
		$text = preg_replace('/\s{2,}/', ' ', $text);
		
		// split text in part sentences and loop through them
		$parts = explode('.', $text);
		for($i=0; $i<count($parts); $i++)
		{
			$part = $parts[$i];
			if($part != '')
			{
				// check for quit expression
				for ($q=0; $q<count($elizaQuits); $q++)
				{
					if($elizaQuits[$q] == $part)
					{
						$this->quit = true;
						return $this->getFinal();
					}
				}

				// preprocess (v.1.1: work around lambda function)
				preg_match($this->preExp, $part, $m, PREG_OFFSET_CAPTURE);
				if($m)
				{
					$lp ='';
					$rp = $part;
					while($m)
					{
						$lp .= substr($rp, 0, $m[0][1]-1).$this->pres[$m[1]];
						$rp = substr($rp, $m[0][1]+count($m[0]));
						preg_match($this->preExp, $rp, $m, PREG_OFFSET_CAPTURE);
					}
					$part = $lp.$rp;
				}
				$this->sentence = $part;

				// loop through keywords
				for($k = 0; $k<count($elizaKeywords); $k++)
				{
					if(preg_match('/\\b'.$elizaKeywords[$k][0].'\\b/i', $part))
					{
						$rpl = $this->_execRule($k);
					}
					if($rpl != '')
						return $rpl;
				}
			}
		}

		// nothing matched try mem
		$rpl = $this->_memGet();
                
		// if nothing in mem, so try xnone
		if($rpl == '')
		{         
			$this->sentence = ' ';
			$k = $this->_getRuleIndexByKey('xnone');
			if($k >= 0)
				$rpl = $this->_execRule($k);
		}
		// return reply or default string
		return ($rpl != '') ? $rpl : 'I am at ta loss for words.';
	}

	function _execRule($k)
	{
		global $elizaKeywords;

		$rule = $elizaKeywords[$k];
		$decomps = $rule[2];
		$paramre = '/\(([0-9]+)\)/';
		for($i=0; $i<count($decomps); $i++)
		{
			preg_match_all('/'.$decomps[$i][0].'/', $this->sentence, $m);
			if ($m) {
				$reasmbs = $decomps[$i][1];
				$memflag = $decomps[$i][2];
				$ri = $this->noRandom ? 0 : floor(Util::randomFloat() * count($reasmbs));
				if( ($this->noRandom && $this->lastChoice[$k][$i] > $ri) || $this->lastChoice[$k][$i] == $ri )
				{
					$ri = ++$this->lastChoice[$k][$i];
					if($ri >= count($reasmbs))
					{
						$ri = 0;
						$this->lastChoice[$k][$i] = -1;
					}
				}
				else
				{
					$this->lastChoice[$k][$i] = $ri;
				}

				$rpl = $reasmbs[$ri];
				if($this->debug)
					Util::echoln('match:\nkey: '.$elizaKeywords[$k][0].
								 '\nrank: '.$elizaKeywords[$k][1].
								 '\ndecomp: '.$decomps[$i][0].
								 '\nreasmb: '.$rpl.
								 '\nmemflag: '.$memflag);
				if(preg_match('/^goto/i', $rpl))
				{
					$ki = $this->_getRuleIndexByKey(substr($rpl, 5));
					if($ki>=0)
						return $this->_execRule($ki);
				}

				// substitute positional params (v.1.1: work around lambda function)
				preg_match($paramre, $rpl, $m1, PREG_OFFSET_CAPTURE);
				if($m1)
				{
					$lp = '';
					$rp = $rpl;
					while($m1)
					{
						$param = $m[0][intval($m1[1])];
						// postprocess param
						preg_match($this->postExp, $param, $m2, PREG_OFFSET_CAPTURE);
						if($m2)
						{
							$lp2 = '';
							$rp2 = $param;
							while($m2)
							{
								$lp2 .= substr($rp2, 0, $m2[0][1]-1).$this->posts[$m2[1][1]];
								$rp2 = substr($rp2, $m2[0][1] + strlen($m2[0][0]));
								preg_match($this->postExp, $rp2, $m2, PREG_OFFSET_CAPTURE);
							}
							$param = $lp2.$rp2;
						}
						$lp .= substr($rp, 0, $m1[0][1]-1).$param;
						$rp = substr($rp, $m1[0][1] + strlen($m1[0][0]));
						preg_match($paramre, $rp, $m1, PREG_OFFSET_CAPTURE);
					}
					$rpl = $lp.$rp;
				}
				$rpl = $this->_postTransform($rpl);
				if($memflag)
					$this->_memSave($rpl);
				else
					return $rpl;
			}
		}

		return '';
	}

	function _postTransform($s)
	{
		global $elizaPostTransforms;

		// final cleanings
		$s = preg_replace('/\s{2,}/', ' ', $s);
		$s = preg_replace('/\s+\./', '.', $s);
		if( $elizaPostTransforms && count($elizaPostTransforms) )
		{
			for($i=0; $i<count($elizaPostTransforms); $i+=2)
			{
				$s = preg_replace($elizaPostTransforms[i], $elizaPostTransforms[$i+1], $s);
			}
		}
		// capitalize first char (v.1.1: work around lambda function)
		if($this->capitalizeFirstLetter)
		{
			$re = '/^([a-z])/';
			if(preg_match($re, $s, $m))
				$s = strtoupper($m[0]).substr($s, 1);
		}
		return $s;
	}

	function _getRuleIndexByKey($key)
	{
		global $elizaKeywords;

		for($k=0; $k < count($elizaKeywords); $k++)
		{
			if($elizaKeywords[$k][0] == $key)
				return $k;
		}

		return -1;
	}

	function _memSave($t)
	{
		$this->mem[] = $t;
		if(count($this->mem) > $this->memSize)
			array_shift($this->mem);
	}

	function _memGet()
	{
		if(count($this->mem))
		{
			if($this->noRandom)
				return array_shift($this->mem);
			else
			{
				$n = floor(Util::randomFloat() * count($this->mem));
				$rpl = $this->mem[$n];
				for($i=$n+1; $i<count($this->mem); $i++)
					$this->mem[$i-1] = $this->mem[$i];
				array_pop($this->mem);
				return $rpl;
			}
		}
		else
			return '';
	}

	function getFinal()
	{
		global $elizaFinals;

		return $elizaFinals[floor(Util::randomFloat() * count($elizaFinals))];
	}

	function getInitial() 
	{
		global $elizaInitials;

		return $elizaInitials[floor(Util::randomFloat() * count($elizaInitials))];
	}
}
