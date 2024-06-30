<?php
/**
* Generate a unique key.
* Optional Suffix can be an integer or valid IPv4, either of which is converted to Base36 equivalent
* If Suffix is neither Numeric or IPv4, the string itself is appended
*
* @param   string  $suffix Append this to generated Key.
* @return  string
*/
function generateKey($suffix = null) {
    // Default tokens contain no "ambiguous" characters: 1,i,0,o
    if(isset($suffix)){
        // Fewer segments if appending suffix
        $num_segments = 3;
        $segment_chars = 6;
    }else{
        $num_segments = 4;
        $segment_chars = 5;
    }
    $tokens = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    $keyString = '';
    // Build Default Key String
    for ($i = 0; $i < $num_segments; $i++) {
        $segment = '';
        for ($j = 0; $j < $segment_chars; $j++) {
            $segment .= $tokens[rand(0, strlen($tokens)-1)];
        }
        $keyString .= $segment;
        if ($i < ($num_segments - 1)) {
            $keyString .= '-';
        }
    }
    // If provided, convert Suffix
    if(isset($suffix)){
        if(is_numeric($suffix)) {   // Userid provided
            $keyString .= '-'.strtoupper(base_convert($suffix,10,36));
        }else{
            $long = sprintf("%u\n", ip2long($suffix),true);
            if($suffix === long2ip($long) ) {
                $keyString .= '-'.strtoupper(base_convert($long,10,36));
            }else{
                $keyString .= '-'.strtoupper(str_ireplace(' ','-',$suffix));
            }
        }
    }
    return $keyString;
}