<?php
/**
 * @title       Mr.Clean PHP class
 * @author		Andrew Phillips <andrew@andrewphillips.online>
 * @copyright	Copyright (C), 2017-2025 Andrew PHillips
 *
 * @link		http://andrewphillips.online
 *
 * @descript    MrClean class is designed to make PHP life just more simple.
 *				Methods are made to give functionality that is normally needed
 *				for every day scripts to run. Need to clean up strings? Debug
 *				a form and make sure the required fields are set and not just blank.
 *				Or even just make something SEO styled for a url.
 *				This class is your singular need to include with the classes you build.
 *
 * @git	        https://github.com/APonline/MrClean-class
 */

class MrClean
{
	public $profanity;
	public $profanityFix;

	public function __construct(){
		$this->profanity = array("fuck","fuckr","fucker","fucking","motherfuckers","shit","bullshit","ass","asshole","faggot","bitch","bitchass","bitches","skank","cunt","pussy","nigger","nigga","niggas","cock");
		$this->profanityFix = "*";
	}


	/**
  	 * @name       badLanguageFix
  	 * @descript   Allows the user to change the censor symbol used.
  	 * @params     $char = A signular symbol declared.
  	 * @returns
  	 */
	public function badLanguageFix($char){
		$this->profanityFix = (string)$char;
		return;
	}

	/**
  	 * @name       badLanguage
  	 * @descript   Checks content for specified bad language to censor or to send back for correction.
  	 * @params     $content = A string or large text.
  	 * @params     $cta = determines if censor or return strictly not good text to be fixed.
  	 * @returns    Array, if $cta is set then an extra param 'judgement' = *pass* OR *fail* will be included.
  	 */
	public function badLanguage($content, $cta = null){

		$contentSet = array(
			'fixedCount'=>0,
			'wordsFound'=>'',
			'original'=>$content,
			'edited'=>''
		);

		$fix = $this->profanityFix;
		$profanityList = $this->profanity;

		$contentExamine = preg_split('/[\s]+/', $content);
		$editedContent="";
		$wordCount=0;
		$wordsFound=array();

		foreach($contentExamine as $word){
			if(in_array(preg_replace("/[^a-z0-9_\s-]/", "", strtolower($word)), $profanityList)){
				$len = strlen($word);

				if(preg_match('/^\PL+|\PL\z/u', $word)&&preg_match("/[\n\r]/", $word)){
					$edit = substr($word, 0, 2).str_repeat($fix, $len - 3).substr($word, $len - 1, 1);
				}elseif(preg_match('/[\p{P}\p{N}]$/u', $word)&&preg_match("/[\n\r]/", $word)){
					$edit = substr($word, 0, 1).str_repeat($fix, $len - 2).substr($word, $len - 1, 1);
				}else{
					$edit = substr($word, 0, 1).str_repeat($fix, $len - 1);
				}
				$editedContent .= $edit." ";
				$wordCount++;

				$w = preg_replace("/[^a-zA-Z0-9_\s-]/", "", $word);
				$wordsFound[] = $w;
			}else{
				$editedContent .= $word." ";
			}
		}

		$badWordCaught = array();
		$badwords = array_count_values($wordsFound);
		foreach($badwords as $key => $value){
			$badWordCaught[] = array('word'=>$key,'count'=>$value);
		}

		$contentSet['edited'] = $editedContent;
		$contentSet['fixedCount'] = $wordCount;
		$contentSet['wordsFound'] = $badWordCaught;

		if(isset($cta)&&$cta=='judge'){
			if($wordCount==0){
				$contentSet['judgement'] = "Pass";
			}else{
				$contentSet['judgement'] = "Fail";
			}
		}

		return $contentSet;
	}


	/**
  	 * @name       isRequired
  	 * @descript   Checks if required fields are:
  	 *             1: Actually set as a field.
  	 *             2: Value isn't just blank spaces.
  	 *             3: Value isn't just blank.
  	 * @params     $args = Array of data submitted by the form.
  	 * @params     $req  = Array of required fields.
  	 * @params     $filler  = String to replace empty fields if $req is null.
  	 * @returns    Boolean
  	 */
	public function isRequired($args, $req = null, $filler = null){

		$reqSet=1;

		//sets req if it is null
	 	if($req == null){
	 		if($filler==null)
	 			$filler='N/A';
	 		$reqSet=0;
	 		$req = array();
	 		foreach($args as $key=>$arg){
	 			$req[] = $key;
	 		}
	 	}

	 	//Checks for foul lanuage
		foreach($args as $key=>$arg){
			$badies = $this->badLanguage($arg, 'censor');
			$args[$key] = $badies['edited'];
		}

		//Unset non required fields
		foreach($args as $key=>$arg){
			if(!in_array($key, $req))
				unset($args[$key]);
		}

		//Make sure Arg isset
		foreach($args as $arg){
			if(!isset($arg))
				return false;
		}

		//Check Arg isn't just spaces
		foreach($args as $key=>$arg){
			if(ctype_space($arg)&&$reqSet==1){
				return false;
			}elseif(ctype_space($arg)&&$reqSet==0){
				$args[$key] = $filler;
			}
		}

		//Make sure Arg isn't just blank
		foreach($args as $key=>$arg){
			if(strlen($arg)==0&&$reqSet==1){
				return false;
			}elseif(strlen($arg)==0&&$reqSet==0){
				$args[$key] = $filler;
			}
		}

		$data = array(
			'success'=>true,
			'data'=>$args
		);

		return $data;
	}


	/**
  	 * @name       makeSEOUrl
  	 * @descript   Turns string into seo friendly slug.
  	 * @params     $string = String to be altered.
  	 * @returns    String
  	 */
	public function makeSEOUrl($string){
		$string = strtolower($string);
		$string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
		$string = preg_replace("/[\s-]+/", " ", $string);
		$string = preg_replace("/[\s_]/", "-", $string);

		return $string;
	}


	/**
  	 * @name       undoSEOURL
  	 * @descript   Turns string into display text from a friendly slug.
  	 * @params     $string = String to be altered.
  	 * @returns    String
  	 */
	public function undoSEOURL($string){
		$string = str_replace('-', ' ', $string);
		$string = preg_replace('/(?<!\s)-(?!\s)/', ' ', $string);
		$string = ucwords($string);

		return $string;
	}
}
