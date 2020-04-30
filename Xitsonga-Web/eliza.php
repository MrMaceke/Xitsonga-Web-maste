<?
#####################################################
# eliza.php- Final Project: Eliza program written in PHP
#
# The purpose of this program is to mimick the 
# infamous Eliza program that was created some
# time ago.
#
# Note: I am not especially good with regular
# expressions at the moment, thus much of this code
# could most likely have been slimmed down.  This is
# also the reason I don't have parts of strings the
# user types stored in a variable and print them out
# when responding to the user (for ex: User: "I have
# a cat".... Eliza: "Oh you have a cat? That's nice.")
# I will be getting a regular expression book for
# X-Mas, however =)
#
# �2001 Wayne Eggert
#-----------------------------------------------------
# DISCLAIMER: THIS CODE MAY NOT BE MODIFIED IN ANY MANNER
# WITHOUT MY CONSENT.  IT SHOULD BE USED FOR EDUCATIONAL
# PURPOSES ONLY.  LEARN, BUT DO NOT STEAL -- MEANING
# DO NOT COPY CODE VERBATIM JUST TO PASS A CLASS IF YOU
# EVER HAVE A SIMILAR PROJECT, ETC.  IT WILL BE WORTH
# YOUR WHILE TO PUT SOME THOUGHT INTO THE TASK AT HAND
# AND COME UP WITH YOUR OWN SOLUTION.  THAT'S ALL I'M
# GOING TO SAY -- OTHERWISE GOD HELP YOU WHEN YOU ENTER
# INTO THE REAL WORLD WITH THOSE HABITS.
######################################################
?>

<HTML>
<HEAD><TITLE>PHPLiza</TITLE></HEAD>
<BODY>
<?php
// setup initial variables and values
$kwarray = array();
$vararray = array();
$resparray = array();
$priarray = array();
$wordarray = array();
$kwcount=0; $varcount=0; $respcount=0; $syncount=0;
mt_srand ((double) microtime() * 1000000);

// load knowledge file
$lines_array = file("knowledge.txt");

$count = count($lines_array);

// This for loop goes through the entire knowledge file and places
// the elements into arrays.  This later allows us to pull the information
// (ie. key words, variances on the keywords, and responses) out of the
// arrays.
for ($x=0;$x<$count;$x++){
	$lines_array[$x] = trim($lines_array[$x]);
	$lines_array[$x] = ereg_replace("[\]","",$lines_array[$x]);
    if (strstr($lines_array[$x],"key:")){
	eregi("key: (.*)",$lines_array[$x],$kw);
	$kwarray[$kwcount] = strtoupper($kw[1]);
	$currentkw = $kwcount;
	$kwcount++;
	$varcount=0; // reset varcount to null
	$respcount=0; // reset respcount to null
	$pricount=0; // reset pricount to null
    }else if (strstr($lines_array[$x],"var:")){
       	eregi("var: (.*)",$lines_array[$x],$variance);
	$vararray[$currentkw][$varcount] = strtoupper($variance[1]);
	$varcurrent=$varcount;
	$varcount++;
	$respcount=0;
    }else if (strstr($lines_array[$x],"pri:")){
	eregi("pri: (.*)",$lines_array[$x],$priority);
	$priarray[$currentkw] = $priority[1];
    }else if (strstr($lines_array[$x],"resp:")){
        eregi("resp: (.*)",$lines_array[$x],$response);
	$resparray[$currentkw][$varcurrent][$respcount] = $response[1];
	$respcount++;
    }else if (strstr($lines_array[$x],"syn:")){
	eregi("syn: (.*)",$lines_array[$x],$synonym);
	$synonymarray[$syncount] = strtoupper($synonym[1]);
	$syncount++;
    }else if (strstr($lines_array[$x],"goto:")){
	eregi("goto: (.*)",$lines_array[$x],$goto);
	$goto = strtoupper($goto[1]);
	// find the keyword
	for ($zcount=0;$zcount<count($kwarray);$zcount++){
	   // if the keyword already exists
	   if (eregi($goto,$kwarray[$zcount])){
		// then we assign properties of the keyword
		$vararray[$currentkw][0] = $kwarray[$currentkw];
		$resparray[$currentkw] = $resparray[$zcount];
	   }
	}
   }
}

$y=0;
$z=0;
$v=0;
$bestpriority=-2;
$originalstring = $string;
if (!$string){
    $string = "hello";
}
$string = strtoupper($string);

// Figures out what word in the string has the most priority.
// It can then check words to the left/right of this word depending
// upon settings in the knowledge.txt file.
while ($y < count($kwarray)){
	// remove beginning and trailing white space, breaks, etc
	$string = trim($string);
	// remove puncuation from string
	$string = ereg_replace('[!?,.]','',$string);
    // split the string up into seperate words   
    $wordarray = explode(" ",$string);
    while ($v < count($wordarray)){
        if(eregi($wordarray[$v]."$",$kwarray[$y])){
	    // find which word holds the most weight in the sentance
	    if($bestpriority==-2){
	       $bestpriority=$y;
            }else if ($priarray[$bestpriority] < $priarray[$y]){
		$bestpriority = $y;
    	    }
	}
    $v++;
    }
    $v = 0;
    $y++;
}

// find the variance with the most matching words
$vcount=0;
while ($vcount < count($vararray[$bestpriority])){
	if (strstr($vararray[$bestpriority][$vcount],"@")){
		eregi("@(.*)",$vararray[$bestpriority][$vcount],$syn); // fix this
		$syn = $syn[1];
		for($x=0;$x<count($synonymarray);$x++){
			if (eregi($syn,strtoupper($synonymarray[$x]))){
				$sarray = explode(" ",$synonymarray[$x]);
				for($f=0;$f<count($sarray);$f++){
					$newstring = ereg_replace("@(.*)$",$sarray[$f],$vararray[$bestpriority][$vcount]);
					// works to this point
					if(eregi($newstring."$",$string)){
					   $varray = explode(" ",$vararray[$bestpriority][$vcount]);
					   if(count($varray) > $pvarray){
						  $bestvariance = $vcount;
						  $pvarray = count($varray);
					   }
					}
				 }
			}
		}
	}else{
	if(ereg($vararray[$bestpriority][$vcount],$string)){
	   $varray = explode(" ",$vararray[$bestpriority][$vcount]);
	   if(count($varray) > $pvarray){
		  $bestvariance = $vcount;
		  $pvarray = count($varray);
	   }
	}
	}
	$vcount++;
}

// Using the bestpriority (aka the keyword (key:) with the most weight in the sentence)
// and the bestvariance (aka, the variance (var:) phrase that most fits the context of
// the original sentence, we form a response.
if (count($resparray[$bestpriority][$bestvariance]) > 1){
	$random = mt_rand(0,count($resparray[$bestpriority][$bestvariance])-1);
}else{
	$random = 0;
}
$response = $resparray[$bestpriority][$bestvariance][$random];
if ($response==""){
	$response = "Sorry, I don't understand what you're trying to say.";
}

$originalstring = ereg_replace("[\]","",$originalstring);
echo "<h3>Chat with PHPliza!</h3>";
echo "<b>You: </b>".$originalstring."<br>";
echo "<b>PHPliza: </b>".$response;

?>
<FORM ACTION="eliza.php">
   <INPUT TYPE="text" size="20" name="string"><INPUT TYPE="SUBMIT" value="Submit">
</FORM>	
</BODY>
</HTML>
