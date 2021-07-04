<?php
//using the class
use MCServerStatus\MCPing;

function MCPing($server)
{
	require_once __DIR__ . '/MCPing.php';
	require_once  __DIR__ . '/Exceptions/MCPingException.php';
	require_once  __DIR__ . '/Responses/MCBaseResponse.php';
	require_once  __DIR__ . '/Responses/MCPingResponse.php';

	$response = MCPing::check($server);
	

	//get informations from object
	//var_dump($response);

	//or from array
	//var_dump($response->toArray());

	$res = $response->toArray();
	$res['favicon'] = str_replace("\n", '', $res['favicon']);
	$res['motd_html'] = $response->getMotdToHtml();
	$res['motd_text'] = $response->getMotdToText();

	return $res;
}

function motd2html($motd) {
	preg_match_all("/[^§&]*[^§&]|[§&][0-9a-z][^§&]*/", $motd, $brokenupstrings);
	$returnstring = "";
	foreach($brokenupstrings as $results) {
		$ending = '';
		foreach($results as $individual) {
			$code = preg_split("/[&§][0-9a-z]/", $individual);
			preg_match("/[&§][0-9a-z]/", $individual, $prefix);
			if(isset($prefix[0])) {
				$actualcode = substr($prefix[0], 1);
				switch($actualcode) {
					case "1":
						$returnstring = $returnstring . '<span style="color:#0000aa">';
						$ending = $ending . "</span>";
						break;
					case "2":
						$returnstring = $returnstring . '<span style="color:#00aa00">';
						$ending = $ending . "</span>";
						break;
					case "3":
						$returnstring = $returnstring . '<span style="color:#00aaaa">';
						$ending = $ending . "</span>";
						break;
					case "4":
						$returnstring = $returnstring . '<span style="color:#aa0000">';
						$ending = $ending . "</span>";
						break;
					case "5":
						$returnstring = $returnstring . '<span style="color:#aa00aa">';
						$ending = $ending . "</span>";
						break;
					case "6":
						$returnstring = $returnstring . '<span style="color:#ffaa00">';
						$ending = $ending . "</span>";
						break;
					case "7":
						$returnstring = $returnstring . '<span style="color:#aaaaaa">';
						$ending = $ending . "</span>";
						break;
					case "8":
						$returnstring = $returnstring . '<span style="color:#555555">';
						$ending = $ending . "</span>";
						break;
					case "9":
						$returnstring = $returnstring . '<span style="color:#5555ff">';
						$ending = $ending . "</span>";
						break;
					case "a":
						$returnstring = $returnstring . '<span style="color:#55ff55">';
						$ending = $ending . "</span>";
						break;
					case "b":
						$returnstring = $returnstring . '<span style="color:#55ffff">';
						$ending = $ending . "</span>";
						break;
					case "c":
						$returnstring = $returnstring . '<span style="color:#ff5555">';
						$ending = $ending . "</span>";
						break;
					case "d":
						$returnstring = $returnstring . '<span style="color:#ff55ff">';
						$ending = $ending . "</span>";
						break;
					case "e":
						$returnstring = $returnstring . '<span style="color:rgb(221, 195, 0)">';
						$ending = $ending . "</span>";
						break;
					case "f":
						$returnstring = $returnstring . '<span style="color:#ffffff">';
						$ending = $ending . "</span>";
						break;
					case "l":
						if(strlen($individual) > 2) {
							$returnstring = $returnstring . '<span style="font-weight:bold;">';
							$ending = "</span>" . $ending;
							break;
						}
					case "m":
						if(strlen($individual) > 2) {
							$returnstring = $returnstring . '<del>';
							$ending = "</del>" . $ending;
							break;
						}
					case "n":
						if(strlen($individual) > 2) {
							$returnstring = $returnstring . '<span style="text-decoration: underline;">';
							$ending = "</span>" . $ending;
							break;
						}
					case "o":
						if(strlen($individual) > 2) {
							$returnstring = $returnstring . '<i>';
							$ending = "</i>" . $ending;
							break;
						}
					case "r":
						$returnstring = $returnstring . $ending;
						$ending = '';
						break;
				}
				if(isset($code[1])) {
					$returnstring = $returnstring . $code[1];
					if(isset($ending) && strlen($individual) > 2) {
						$returnstring = $returnstring . $ending;
						$ending = '';
					}
				}
			}
			else {
				$returnstring = $returnstring . $individual;
			}
		}
	}
	return $returnstring;
}