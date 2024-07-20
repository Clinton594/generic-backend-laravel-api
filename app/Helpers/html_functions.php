<?php

use App\Helpers\Encoding;

function cleanUpTags($a)
{
	$a = str_replace('lang=EN-US', '', $a);
	$a = str_replace('class=MsoNormal', '', $a);
	$a = strip_tags($a, '<p><a><span><li><ul><tr><td><table><u><img><br>');
	$a = stripslashes($a);

	foreach (['p', 'span', 'li', 'ul', 'tr', 'td', 'table'] as $k => $v) {
		$a = clean_tag($a, $v);
	}
	return $a;
}

function clean_tag($a, $tag)
{
	$a = str_replace("`", "#%*)", $a);
	$a = str_replace("~", "(*%#", $a);
	$a = preg_replace("/<{$tag}[^>]*>/", "`", $a);
	$a = preg_replace("/<\/{$tag}>/", "~", $a);
	$a = str_replace("`", "<{$tag}>", $a);
	$a = str_replace("~", "</" . "{$tag}>", $a);
	$a = str_replace("#%*)", "`", $a);
	$a = str_replace("(*%#", "~", $a);
	return $a;
}

function remove_tag($a, $tag)
{
	$a = str_replace("`", "#%*)", $a);
	$a = str_replace("~", "(*%#", $a);
	$a = preg_replace("/<{$tag}[^>]*>/", "`", $a);
	$a = preg_replace("/<\/{$tag}>/", "~", $a);
	$a = preg_replace("/`[^~]*~/", "", $a);
	$a = str_replace("#%*)", ".", $a);
	$a = str_replace("(*%#", "~", $a);
	return $a;
}

function remove_itag($text, $tag)
{
	$a = preg_replace("/<{$tag}[^>]*>/", "", $text);
	$a = preg_replace("/<\/{$tag}>/", "", $a);
	return $a;
}


function strip_tag($text, $tag)
{
	return preg_replace("/(<\/?)($tag)([^>]*>)/e", "", $text);
}

function get_tag($a, $tag)
{
	$a = str_replace("`", "#%*)", $a);
	$a = str_replace("~", "(*%#", $a);
	$a = preg_replace("/<{$tag}[^>]*>/", "`", $a);
	$a = preg_replace("/<\/{$tag}>/", "~", $a);
	preg_match_all("/`([^~]*)~/", $a, $out, PREG_PATTERN_ORDER);
	foreach ($out[1] as $key => $value) {
		$out[1][$key] = str_replace("`", "#%*)", $value);
		$out[1][$key] = str_replace("~", "(*%#", $value);
	}
	return $out[1];
}

function cleanupHTMLAttributes($string = '')
{
	//Ignore XML errors and manage error handling or xml error
	libxml_use_internal_errors(true);
	$dom = new DOMDocument;
	if (is_html($string)) {
		$dom->loadHTML($string);
		$xpath = new DOMXPath($dom);
		$nodes = $xpath->query('//@*');
		//Clear all attributes except these;
		$ignorlist = ["src", "href", "width", "height"];
		foreach ($nodes as $node) {
			if (!in_array($node->nodeName, $ignorlist)) {
				$node->parentNode->removeAttribute($node->nodeName);
			}
		}
		$string = $dom->saveHTML();
		$string = strip_tags($string, '<p><a><span><li><ul><tr><td><table><u><img><br>');
	}
	libxml_clear_errors();
	return $string;
}


function first_sentence($text, $strip_tags = false)
{
	if ($strip_tags) $text = strip_tags($text);
	$pos = strpos($text, '.');
	if ($pos === false) {
		$sentence = substr($text, 0, 130);
	} else {
		$sentence = substr($text, 0, $pos);
	}
	return "{$sentence}.";
}

function cleanupHTML($string = '')
{
	if (is_string($string)) {
		$string = Encoding::fixUTF8($string);
		$string = Encoding::toUTF8($string);
		$string = str_replace("?s", "`s", $string);
		foreach (["??", "ã", "©", '\t\t', '\n\n'] as $key => $stubborn_special_characters) {
			$string = str_replace($stubborn_special_characters, "", $string);
		}
		$string = cleanupHTMLAttributes($string); // Does neat cleanup of attributes with some ignore list
		// $string = cleanUpTags($string); // This one cleans up all attributes with brute force
		$string = str_replace("<span></span>", "", $string);
	} elseif (is_array($string)) {
		foreach ($string as $key => $value) {
			$string[$key] = cleanupHTML($value);
		}
	} elseif (is_object($string)) {
		foreach ($string as $key => $value) {
			$string->{$key} = cleanupHTML($value);
		}
	}
	return $string;
}


function insert($string = '', $wordCount = 1000, $insert_string = null)
{
	$words = array();
	$html = "";
	if (!empty($string)) $string = cleanUpTags($string);
	$string = "<div>{$string}</div>";
	$openTag = '<';
	$tagOpenPostions = array();
	$lastPos = 0;
	while (($lastPos = strpos($string, $openTag, $lastPos)) !== false) {
		$tagOpenPostions[] = $lastPos;
		$lastPos = $lastPos + strlen($openTag);
	}

	//Getting positions of all closing tags in the html document
	$closeTag = '>';
	$tagClosePostions = array();
	$lastPos = 0;
	while (($lastPos = strpos($string, $closeTag, $lastPos)) !== false) {
		$tagClosePostions[] = $lastPos;
		$lastPos = $lastPos + strlen($closeTag);
	}

	$domTextStart = 0;
	//foreach html opening tag
	foreach ($tagOpenPostions as $i => $openTagPos) {
		$currentDomText = substr($string, $domTextStart, ($openTagPos - $domTextStart));
		$domWords = explode(" ", $currentDomText);
		foreach ($domWords as $j => $thisword) {
			$thisword = trim($thisword);
			if (!empty($thisword)) $words[] = $thisword;
		}
		//add the closing tag
		array_push($words, substr($string, $openTagPos, $tagClosePostions[$i] - $openTagPos) . $closeTag);
		$domTextStart = $tagClosePostions[$i] + 1;
	}
	if ($insert_string === null) {
		$html = array();
		$curr = 0;
		$html[$curr] = "";
		foreach ($words as $key => $element) {
			if (($key) % $wordCount == 0 && $key > 0) {
				$curr++;
			}
			$html[$curr] .= $element . " ";
		}
	} else {
		foreach ($words as $key => $element) {
			if (($key) % $wordCount == 0 && $key > 0) {
				$html .=  $insert_string;
			}
			$html .= $element . " ";
		}
	}
	return ([$html, count($words)]);
}

function is_html($string)
{
	// Check if string contains any html tags.
	return preg_match('/<\s?[^\>]*\/?\s?>/i', $string);
}
