<?php
function hex2rgb($hex){

	$hex = prepareHex($hex);
	$rgb = array(hexdec(substr($hex,0,2)), hexdec(substr($hex,2,2)), hexdec(substr($hex,4,2)));
	return $rgb;
}

function hex2rgbstr($hex){
	$rgb = hex2rgb($hex);
	return implode(",", $rgb);
}
function rgb2hex($rgb){
	$hex = '';
	for($i=0; $i < 3; $i++) {
		// Convert the decimal digit to hex
		$hexDigit = dechex($rgb[$i]);
		// Add a leading zero if necessary
		if(strlen($hexDigit) == 1) {
		$hexDigit = "0" . $hexDigit;
		}
		// Append to the hex string
		$hex .= $hexDigit;
	}
	return '#'.$hex;
}
function prepareHex($hex){
	// Remove hash if given
	$hex = str_replace('#','',$hex);
	// Check if shorthand hex value given (eg. #FFF instead of #FFFFFF)
	if(strlen($hex) == 3) {
	$hex = str_repeat(substr($hex,0,1), 2) . str_repeat(substr($hex,1,1), 2) . str_repeat(substr($hex,2,1), 2);
	}
	return $hex;
}
function colourBrightness($hex, $percent) {
	$hex = prepareHex($hex);
	/// HEX TO RGB
	//$rgb = array(hexdec(substr($hex,0,2)), hexdec(substr($hex,2,2)), hexdec(substr($hex,4,2)));
	$rgb = hex2rgb($hex);
	//// CALCULATE
	
	for ($i=0; $i<3; $i++) {
		$range = $percent > 0 ? 255 - $rgb[$i] : $rgb[$i] ;
		$rgb[$i] = $rgb[$i] + round($range * $percent);
		// In over 255 or under 0 (shouldn't happen but check anyway)
		if ($rgb[$i] > 255) {
			$rgb[$i] = 255;
		}
		if ($rgb[$i] < 0) {
			$rgb[$i] = 0;
		}
	}
	$hex = rgb2hex($rgb);
	// hash added above
	return $hex;
}
function colourBlend($col1,$col2,$factor){
	$col1RGB = hex2rgb( prepareHex($col1) );
	$col2RGB = hex2rgb( prepareHex($col2) );
	
	$blend = array();
	//echo ('/*');
	for ($i=0; $i<3; $i++) {
		$min = min($col1RGB[$i],$col2RGB[$i]);
		$max = max($col1RGB[$i],$col2RGB[$i]);
		$blend[$i] = round( $min + ( ($max - $min) * $factor) ) ;
		
		//echo ($i .': min '. $min . ', max '. $max .' = '.  $blend[$i].'\n\r' );
		
	}
	//echo ('*/');
	return rgb2hex($blend);
}

?>
