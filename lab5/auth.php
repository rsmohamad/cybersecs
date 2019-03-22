<?php
    function _getBase32LookupTable() {
        return array(
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', //  7
            'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', // 15
            'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', // 23
            'Y', 'Z', '2', '3', '4', '5', '6', '7', // 31
            '=',  // padding char
        );
    }

    function _base32Decode($secret) {
        if (empty($secret)) {
            return '';
        }

        $base32chars = _getBase32LookupTable();
        $base32charsFlipped = array_flip($base32chars);

        $paddingCharCount = substr_count($secret, $base32chars[32]);
        $allowedValues = array(6, 4, 3, 1, 0);
        if (!in_array($paddingCharCount, $allowedValues)) {
            return false;
        }
        for ($i = 0; $i < 4; ++$i) {
            if ($paddingCharCount == $allowedValues[$i] &&
                substr($secret, -($allowedValues[$i])) != str_repeat($base32chars[32], $allowedValues[$i])) {
                return false;
            }
        }
        $secret = str_replace('=', '', $secret);
        $secret = str_split($secret);
        $binaryString = '';
        for ($i = 0; $i < count($secret); $i = $i + 8) {
            $x = '';
            if (!in_array($secret[$i], $base32chars)) {
                return false;
            }
            for ($j = 0; $j < 8; ++$j) {
                $x .= str_pad(base_convert(@$base32charsFlipped[@$secret[$i + $j]], 10, 2), 5, '0', STR_PAD_LEFT);
            }
            $eightBits = str_split($x, 8);
            for ($z = 0; $z < count($eightBits); ++$z) {
                $binaryString .= (($y = chr(base_convert($eightBits[$z], 2, 10))) || ord($y) == 48) ? $y : '';
            }
        }

        return $binaryString;
    }

    function _getCode($secret) {
        $timeSlice = floor(time()/30);
        $secretkey = _base32Decode($secret);

        // Pack time into binary string
        $time = chr(0).chr(0).chr(0).chr(0).pack('N*', $timeSlice);
        // Hash it with users secret key
        $hm = hash_hmac('SHA1', $time, $secretkey, true);
        // Use last nipple of result as index/offset
        $offset = ord(substr($hm, -1))&0x0F;
        // grab 4 bytes of the result
        $hashpart = substr($hm, $offset, 4);
        // Unpack binary value
        $value = unpack('N', $hashpart);
        // Only 32 bits
        $value = $value[1] & 0x7FFFFFFF;
        // modulo of divided by 1000000
        $modulo = $value%pow(10, 6);
        // Add padding
        $code = str_pad($modulo, 6, '0', STR_PAD_LEFT);

        return $code;
    }

    function verify($secret, $token) {
        $result = 0;
        $code = _getCode($secret);

        if (strlen($code)==strlen($token)) {
            for ($i=0; $i<strlen($token); $i++) {
                $result |= (ord($code[$i])^ord($token[$i]));
            }
        }

        return ($result===0);  // they are only identical strings if $result is exactly 0...
    }
?>