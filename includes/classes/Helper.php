<?php

/**
 *  a class that have many helpful methods for general purposes
 *  Parviz Elite Helper
 *
 * @category   Helper
 * @version    1.0.0
 * @since      1.0.0
 */

namespace DCore;

use DateTime;
use Exception;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * Class Helper
 *
 * @package RTL\General
 */
class Helper {
	private const USER_AGENT = 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:54.0) Gecko/20100101 Firefox/75.0';

	/**
	 * search and replace first case
	 *
	 * @param string $search
	 * @param string $replace
	 * @param string $string
	 *
	 * @return string|null
	 */
	public static function replaceOnce (string $search, string $replace, string $string) : string {
		$search = '/' . preg_quote($search, '/') . '/';

		return preg_replace($search, $replace, $string, 1);
	}

	/**
	 * generate secure token
	 *
	 * @param string $string
	 * @param bool   $randomize
	 *
	 * @return string
	 */
	public static function generateToken (string $string, bool $randomize = false) : string {
		$token = $string;
		$token = md5(sha1(base64_encode($token)));

		if ( $randomize ) {
			$token .= time();
		}

		$token = crc32($token);
		$token = str_replace(0, 'x', $token);

		return $token;
	}

	/**
	 * @param string $string
	 * @param int    $min
	 * @param int    $max
	 *
	 * @return bool
	 */
	public static function checkLen (string $string, int $min, int $max) : bool {
		$len = strlen($string);

		return !($len < $min or $len > $max);
	}

	/**
	 * format iran mobile number
	 *
	 * @param string $number
	 *
	 * @return string
	 */
	public static function fixMobileNumber (string $number) : string {
		$number = trim($number);
		$ret    = $number;

		if ( empty($ret) ) {
			return false;
		}

		if ( strncmp($number, '%2B', 3) === 0 ) {
			$ret    = (string) substr($number, 3);
			$number = $ret;
		}
		if ( strncmp($number, '%2b', 3) === 0 ) {
			$ret    = (string) substr($number, 3);
			$number = $ret;
		}
		if ( strncmp($number, '0098', 4) === 0 ) {
			$ret    = (string) substr($number, 4);
			$number = $ret;
		}
		if ( strncmp($number, '098', 3) === 0 ) {
			$ret    = (string) substr($number, 3);
			$number = $ret;
		}
		if ( strncmp($number, '+98', 3) === 0 ) {
			$ret    = (string) substr($number, 3);
			$number = $ret;
		}
		if ( strncmp($number, '98', 2) === 0 ) {
			$ret    = (string) substr($number, 2);
			$number = $ret;
		}
		if ( strncmp($number, '0', 1) === 0 ) {
			$ret = (string) substr($number, 1);
		}

		if ( $ret[0] !== '9' ) {
			return false;
		}

		return '+98' . $ret;
	}

	/**
	 * generate easy to remember random numbers
	 *
	 * @param int $minNumCount
	 * @param int $maxNumCount
	 * @param int $minLen
	 * @param int $maxLen
	 *
	 * @return string
	 */
	public static function randNumber (int $minNumCount = 2, int $maxNumCount = 4, int $minLen = 5, int $maxLen = 8) : string {
		$nums = [];
		$out  = '';

		try {
			$len    = random_int($minLen, $maxLen);
			$ncount = random_int($minNumCount, $maxNumCount);
		} catch ( Exception $e ) {
			return time();
		}

		for ( $i = 0; $i < $ncount; $i++ ) {
			try {
				$rnd_num = random_int(1, 9);
			} catch ( Exception $e ) {
				return time();
			}

			if ( in_array($rnd_num, $nums, true) ) {
				$i--;
			} else {
				$nums[] = $rnd_num;
			}
		}

		for ( $j = 0; $j < $len; $j++ ) {
			$rand_arr = array_rand($nums);
			$out      .= $nums[$rand_arr];
		}

		return $out;
	}

	/**
	 * generate random string
	 *
	 * @param int $minLen
	 * @param int $maxLen
	 *
	 * @return int|string
	 */
	public static function randStr (int $minLen = 10, int $maxLen = 15) : string {
		$rnd_str    = '';
		$rnd_status = 1;
		$temp_int   = $rnd_status;

		try {
			$len = random_int($minLen, $maxLen);
		} catch ( Exception $e ) {
			$len = 10;
		}

		try {
			for ( $i = 1; $i <= $len; $i++ ) {
				switch ( $rnd_status ) {
					case 1:
						$temp_int = random_int(97, 122);
						break;
					case 2:
						$temp_int = random_int(65, 90);
						break;
					case 3:
						$temp_int = random_int(48, 57);
						break;
				}
				$rnd_str    .= chr($temp_int);
				$rnd_status = random_int(1, 3);
			}
		} catch ( Exception $e ) {
			return time();
		}

		return $rnd_str;
	}

	/**
	 * include php files
	 *
	 * @param string $file
	 * @param string $ext
	 * @param bool   $show_message
	 *
	 * @return string
	 */
	public static function loadFile (string $file, string $ext = '.php', bool $show_message = true) : string {
		$file .= $ext;

		if ( file_exists($file) ) {
			require_once($file);

			return true;
		}

		if ( $show_message ) {
			echo '<h4><span style="color: #FF0000;">Missing File : </span><strong>' . $file . '</strong></h4>';
		}

		return false;
	}

	/**
	 * @param string $url
	 *
	 * @return string
	 */
	public static function redirect (string $url) : string {
		if ( !headers_sent() ) {
			header('Location: ' . $url, true, 302);
			die();
		}

		echo '<script type="text/javascript">';
		echo 'window.location.href="' . $url . '";';
		echo '</script>';
		echo '<noscript>';
		echo '<meta http-equiv="refresh" content="0;url=' . $url . '" />';
		echo '</noscript>';
		die();
	}

	/**
	 * Redirect with Post params
	 *
	 * @param string $url
	 * @param array  $data
	 * @param string $note
	 */
	public static function postRedirect (string $url, array $data, string $note = '') : void {
		?>
        <!DOCTYPE html>
        <html xmlns="http://www.w3.org/1999/xhtml" dir="rtl" lang="fa">
        <head>
            <meta charset="utf-8">
            <meta name="robots" content="noindex,nofollow"/>
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
            <title>در حال انتقال ...</title>
            <script type="text/javascript">function Redirect() {
                    document.forms["PostRedirect"].submit();
                }</script>
            <style>
                #PostRedirect {
                    display: none;
                }

                .Note {
                    -webkit-border-radius: 5px;
                    -moz-border-radius: 5px;
                    border-radius: 5px;
                    background-color: #0099CC;
                    color: #FFFFFF;
                    font-size: 1.4rem; /*text-align: center;*/
                    margin: 25px;
                    padding: 15px;
                    font-family: 'Vazir', 'IRANYekanWeb', 'IRANSansWeb', 'B Yekan', 'BYekan', 'Yekan', 'Tahoma', Arial, sans-serif;
                <?php if ( empty($note) ) { echo 'display: none;'; } ?>
                }
            </style>
        </head>
        <body onload="Redirect();">
        <div class="Note"><?php echo $note; ?></div>

        <form name="PostRedirect" id="PostRedirect" method="post" action="<?php echo $url; ?>">
			<?php
			if ( $data !== null ) {
				foreach ( $data as $K => $V ) {
					echo '<input type="hidden" name="' . $K . '" value="' . $V . '" />' . PHP_EOL;
				}
			}
			?>
        </form>
        </body>
        </html>
		<?php
		die();
	}

	/**
	 * Convert Persian numbers chars to English numbers chars
	 *
	 * @param $number
	 *
	 * @return string
	 */
	public static function englisToPersianNum ($number) : string {
		$farsi_array   = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹', '٫'];
		$english_array = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '.'];

		return str_replace($english_array, $farsi_array, $number);
	}

	/**
	 * Convert English numbers chars to Persian numbers chars
	 *
	 * @param $number
	 *
	 * @return string
	 */
	public static function persianToEnglishNum ($number) : string {
		$farsi_array   = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹', '٫'];
		$english_array = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '.'];

		return str_replace($farsi_array, $english_array, $number);
	}

	/**
	 * Check if a given string is number or not
	 *
	 * @param $inputString
	 *
	 * @return bool
	 */
	public static function isNumber ($inputString) : bool {
		if ( !preg_match('/\D/', $inputString) ) {
			return true;
		}

		return false;
	}

	/**
	 * Check if PHP Session is started
	 *
	 * @return bool
	 */
	public static function isSessionStarted () : bool {
		if ( PHP_SAPI !== 'cli' ) {
			if ( PHP_VERSION_ID >= 50400 ) {
				return session_status() === PHP_SESSION_ACTIVE;
			}

			return session_id() !== '';
		}

		return false;
	}

	/**
	 * Start the PHP Session
	 */
	public static function startSession () : void {
		if ( !self::isSessionStarted() ) {
			@session_start();
		}
	}

	/**
	 * Remove all special chars
	 *
	 * @param string $text
	 * @param string $execludes
	 *
	 * @return string
	 */
	public static function removeAllSpecialChar (string $text, $execludes = '') : string {
		if ( self::textHasString($text, '-') ) {
			$text = str_replace('-', '', $text) . '-';
		}

		return preg_replace('/[^a-z0-9' . $execludes . ']+/i', '', $text);
	}

	/**
	 * Get remote url content without any trouble
	 *
	 * @param string $url
	 * @param string $using
	 * @param bool   $urlDecode
	 *
	 * @return string
	 * @noinspection CurlSslServerSpoofingInspection
	 */
	public static function getRemoteContent (string $url, string $using = 'CURL', bool $urlDecode = true) : string {
		// $Using : FGC , CURL

		if ( $urlDecode ) {
			$url = urldecode($url);
		}

		$content = '';

		if ( $using === 'FGC' ) {
			$contextOptions = [
				'ssl' => [
					'verify_peer'      => false,
					'verify_peer_name' => false
				]
			];

			$content = @file_get_contents($url, false, stream_context_create($contextOptions));

		} elseif ( $using === 'CURL' ) {
			$curlOptions = [
				CURLOPT_CUSTOMREQUEST  => 'GET',
				CURLOPT_POST           => false,
				CURLOPT_USERAGENT      => self::USER_AGENT,
				CURLOPT_COOKIEFILE     => 'Cookie.txt',
				CURLOPT_COOKIEJAR      => 'Cookie.txt',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_HEADER         => false,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_ENCODING       => '',
				CURLOPT_AUTOREFERER    => true,
				CURLOPT_CONNECTTIMEOUT => 120,
				CURLOPT_TIMEOUT        => 1200,
				CURLOPT_MAXREDIRS      => 10,
				CURLOPT_SSL_VERIFYPEER => false
			];

			$curl = curl_init($url);
			curl_setopt_array($curl, $curlOptions);

			$content = curl_exec($curl);
			curl_close($curl);

		}

		if ( !empty($content) ) {
			return (string) $content;
		}

		return '';
	}

	/**
	 * Send post request
	 *
	 * @param string $url
	 * @param        $data
	 * @param bool   $isJson
	 * @param array  $extraHttpHeaders
	 *
	 * @return bool|mixed|string
	 * @noinspection CurlSslServerSpoofingInspection
	 */
	public static function postRequest (string $url, $data, bool $isJson = false, array $extraHttpHeaders = []) {
		if ( $isJson ) {
			$postVars = json_encode($data);
		} elseif ( is_array($data) ) {
			$postVars = http_build_query($data);
		} else {
			$postVars = $data;
		}

		$httpHeaders[] = 'Content-Length: ' . mb_strlen($postVars);

		if ( $isJson ) {
			$httpHeaders[] = 'Content-Type: application/json';
		} else {
			$httpHeaders[] = 'Content-Type: application/x-www-form-urlencoded';
		}

		if ( !empty($extraHttpHeaders) ) {
			$httpHeaders[] = $extraHttpHeaders;
		}

		$curlOptions = [
			CURLOPT_POST           => true,
			CURLOPT_POSTFIELDS     => $postVars,
			CURLOPT_USERAGENT      => self::USER_AGENT,
			CURLOPT_COOKIEFILE     => 'Cookie.txt',
			CURLOPT_COOKIEJAR      => 'Cookie.txt',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HEADER         => false,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_ENCODING       => '',
			CURLOPT_AUTOREFERER    => true,
			CURLOPT_CONNECTTIMEOUT => 120,
			CURLOPT_TIMEOUT        => 1200,
			CURLOPT_MAXREDIRS      => 10,
			CURLOPT_HTTPHEADER     => $httpHeaders,
			CURLOPT_SSL_VERIFYPEER => false
		];

		$curl = curl_init($url);
		curl_setopt_array($curl, $curlOptions);

		$content = curl_exec($curl);
		curl_close($curl);

		if ( !empty($content) ) {
			if ( self::isJson($content) ) {
				$content = json_decode($content, false);
			}

			return $content;
		}

		return false;
	}

	/**
	 * Check if a given string is JSON or not
	 *
	 * @param $value
	 *
	 * @return bool
	 */
	public static function isJson ($value) : bool {
		if ( empty($value) || !is_string($value) ) {
			return false;
		}

		json_decode($value, true);

		return (json_last_error() === JSON_ERROR_NONE);
	}

	/**
	 * Check if a given string is Base64 Encoded or Not
	 *
	 * @param $value
	 *
	 * @return bool
	 */
	public static function isBase64 ($value) : bool {
		if ( empty($value) || !is_string($value) ) {
			return false;
		}

		$result = base64_decode($value, true);

		return $result !== false;
	}

	/**
	 * Check if a text contain string
	 *
	 * @param $text
	 * @param $string
	 *
	 * @return bool
	 */
	public static function textHasString (string $text, string $string) : bool {
		return strpos($text, $string) !== false;
	}

	/**
	 * Generate text excerpt
	 *
	 * @param      $str
	 * @param int  $startPos
	 * @param int  $maxLength
	 * @param bool $withEtc
	 *
	 * @return string
	 */
	public static function getExcerpt (string $str, int $startPos = 0, int $maxLength = 100, bool $withEtc = true) : string {
		if ( mb_strlen($str) > $maxLength ) {
			if ( $withEtc ) {
				$maxLength -= 3;
			}
			$excerpt   = mb_substr($str, $startPos, $maxLength);
			$lastSpace = mb_strrpos($excerpt, ' ');
			$excerpt   = mb_substr($excerpt, 0, $lastSpace);
			if ( $withEtc ) {
				$excerpt .= ' ...';
			}
		} else {
			$excerpt = $str;
		}

		return $excerpt;
	}

	/**
	 * Encode & Compress text
	 *
	 * @param $string
	 *
	 * @return string
	 */
	public static function encode (string $string) : string {
		return rtrim(strtr(base64_encode(gzdeflate($string, 9)), '+/', '-_'), '=');
	}

	/**
	 * Decode text that Encoded with above method
	 *
	 * @param $string
	 *
	 * @return string
	 */
	public static function decode (string $string) : string {
		try {
			return gzinflate(base64_decode(strtr($string, '-_', '+/')));
		} catch ( Exception $e ) {
			return '';
		}
	}

	/**
	 * Easy way to set a cookie
	 *
	 * @param        $cookieName
	 * @param        $cookieValue
	 * @param int    $cookieExpire
	 * @param string $expireUnit
	 *
	 * @return bool
	 */
	public static function setCookie (string $cookieName, string $cookieValue, int $cookieExpire = 30, string $expireUnit = 'D') : bool {
		if ( $expireUnit === 'D' ) {
			$cookieExpire *= 60 * 60 * 24;
		}

		if ( $expireUnit === 'H' ) {
			$cookieExpire *= 60 * 60;
		}

		if ( $expireUnit === 'M' ) {
			$cookieExpire *= 60;
		}

		$expire = time() + $cookieExpire;

		$result = setcookie($cookieName, $cookieValue, $expire, '/');

		if ( $result ) {
			$_COOKIE[$cookieName] = $cookieValue;
		}

		return $cookieValue;
	}

	/**
	 * Delete cookie
	 *
	 * @param $cookieName
	 *
	 * @return bool
	 */
	public static function deleteCookie (string $cookieName) : bool {
		return @setcookie($cookieName, null, strtotime('-1 day'), '/');
	}

	/**
	 * Get File Name from URL
	 *
	 * @param $url
	 *
	 * @return string
	 */
	public static function getUrlFileName (string $url) : string {
		$fileName = parse_url($url, PHP_URL_PATH);

		return basename($fileName);
	}

	/**
	 * Download remote file
	 *
	 * @param $remoteFile
	 * @param $localPath
	 *
	 * @return bool
	 */
	public static function downloadRemoteFile (string $remoteFile, string $localPath) : bool {
		set_time_limit(1200);
		ini_set('max_execution_time', '1200');

		if ( strlen($remoteFile) !== mb_strlen($remoteFile, 'utf-8') ) {
			$remoteFile = rawurlencode($remoteFile);
			$remoteFile = (string) str_replace(['%2F', '%3A'], ['/', ':'], $remoteFile);
		}

		$FP = fopen($localPath, 'wb+');
		$CH = curl_init($remoteFile);
		curl_setopt($CH, CURLOPT_FILE, $FP);
		curl_setopt($CH, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($CH, CURLOPT_USERAGENT, self::USER_AGENT);
		curl_setopt($CH, CURLOPT_CONNECTTIMEOUT, 120);
		curl_setopt($CH, CURLOPT_TIMEOUT, 1200);

		curl_exec($CH);
		if ( curl_errno($CH) ) {
			$result = false;
		} else {
			$resultStatus = curl_getinfo($CH, CURLINFO_HTTP_CODE);
			if ( ($resultStatus === 200) && file_exists($localPath) ) {
				$result = true;
			} else {
				$result = false;
			}
		}

		curl_close($CH);
		fclose($FP);

		return $result;
	}

	/**
	 * Convert Gregorian Date To Shamsi
	 *
	 * @param DateTime $date
	 * @param string   $format
	 *
	 * @return array|string
	 */
	public static function gregorianToJalali (DateTime $date, string $format = 'Y-m-d H:i:s') : ?string {
		if ( !($date instanceof DateTime) ) {
			return null;
		}

		$year  = (int) $date->format('Y');
		$month = (int) $date->format('m');
		$day   = (int) $date->format('d');

		$g_d_m = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];
		$jy    = ($year <= 1600) ? 0 : 979;
		$year  -= ($year <= 1600) ? 621 : 1600;
		$gy2   = ($month > 2) ? ($year + 1) : $year;
		$days  = (365 * $year) + ((int) (($gy2 + 3) / 4)) - ((int) (($gy2 + 99) / 100)) + ((int) (($gy2 + 399) / 400)) - 80 + $day + $g_d_m[$month - 1];
		$jy    += 33 * ((int) ($days / 12053));
		$days  %= 12053;
		$jy    += 4 * ((int) ($days / 1461));
		$days  %= 1461;
		$jy    += (int) (($days - 1) / 365);

		if ( $days > 365 ) {
			$days = ($days - 1) % 365;
		}

		$jm = ($days < 186) ? 1 + (int) ($days / 31) : 7 + (int) (($days - 186) / 30);
		$jd = 1 + (($days < 186) ? ($days % 31) : (($days - 186) % 30));

		$result = implode('-', [$jy, $jm, $jd]) . $date->format(' H:i:s');
		$result = DateTime::createFromFormat('Y-m-d H:i:s', $result);

		return $result->format($format);
	}

	/**
	 * Convert Shamsi date to gregorian
	 *
	 * @param DateTime $date
	 * @param string   $format
	 *
	 * @return array|string
	 */
	public static function jalaliToGregorian (DateTime $date, string $format = 'Y-m-d H:i:s') : ?string {
		if ( !($date instanceof DateTime) ) {
			return null;
		}

		$year  = (int) $date->format('Y');
		$month = (int) $date->format('m');
		$day   = (int) $date->format('d');

		if ( $year > 979 ) {
			$gy   = 1600;
			$year -= 979;
		} else {
			$gy = 621;
		}

		$days = (365 * $year) + (((int) ($year / 33)) * 8) + ((int) ((($year % 33) + 3) / 4)) + 78 + $day + (($month < 7) ? ($month - 1) * 31 : (($month - 7) * 30) + 186);
		$gy   += 400 * ((int) ($days / 146097));
		$days %= 146097;

		if ( $days > 36524 ) {
			$gy   += 100 * ((int) (--$days / 36524));
			$days %= 36524;
			if ( $days >= 365 ) {
				$days++;
			}
		}

		$gy   += 4 * ((int) (($days) / 1461));
		$days %= 1461;
		$gy   += (int) (($days - 1) / 365);

		if ( $days > 365 ) {
			$days = ($days - 1) % 365;
		}

		$gd = $days + 1;

		$arr = [
			0,
			31,
			((($gy % 4 === 0) and ($gy % 100 !== 0)) or ($gy % 400 === 0)) ? 29 : 28,
			31,
			30,
			31,
			30,
			31,
			31,
			30,
			31,
			30,
			31
		];
		foreach ( $arr as $gm => $v ) {
			if ( $gd <= $v ) {
				break;
			}
			$gd -= $v;
		}

		$result = implode('-', [$gy, $gm, $gd]) . $date->format(' H:i:s');
		$result = DateTime::createFromFormat('Y-m-d H:i:s', $result);

		return $result->format($format);
	}

	/**
	 * Get Current Jalali Date
	 *
	 * @param string $format
	 *
	 * @return string
	 */
	public static function getJalaliDate (string $format = 'Y-m-d H:i:s') : ?string {
		$dateTime = DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));

		return self::gregorianToJalali($dateTime, $format);
	}

	/**
	 * Add image watermark to another image
	 *
	 * @param string $imagePath
	 * @param string $watermarkPath
	 * @param string $newImagePath
	 * @param int    $mRight
	 * @param int    $mBottom
	 *
	 * @return bool
	 */
	public static function addImageWaterMark (string $imagePath, string $watermarkPath, string $newImagePath = '', int $mRight = 0, int $mBottom = 10) : bool {
		$Image = @imagecreatefromjpeg($imagePath);

		if ( $Image === false ) {
			$Image = @imagecreatefrompng($imagePath);
		}

		if ( $Image === false ) {
			return false;
		}

		$wmark = @imagecreatefrompng($watermarkPath);

		if ( $wmark === false ) {
			$wmark = @imagecreatefromjpeg($imagePath);
		}

		if ( $wmark === false ) {
			return false;
		}

		imagesx($wmark);
		imagesy($wmark);

		imagecopy($Image, $wmark, //ImageSX($Image) - $sx - $Marge_Right,
			//ImageSY($Image) - $sy - $Marge_Bottom,
			$mRight, $mBottom, 0, 0, imagesx($wmark), imagesy($wmark));

		if ( !empty($newImagePath) ) {
			imagejpeg($Image, $newImagePath);
		} else {
			imagejpeg($Image, $imagePath);
		}

		imagedestroy($Image);

		return true;
	}

	/**
	 * Add text watermark to image
	 *
	 * @param string $imgPath
	 * @param string $wm_text
	 *
	 * @return bool
	 */
	public static function addTextWatermark (string $imgPath, string $wm_text) : bool {
		$image = $imgPath;

		$newImg = @imagecreatefromjpeg($image);
		if ( $image === false ) {
			$newImg = @imagecreatefrompng($image);
		}
		if ( $image === false ) {
			return false;
		}

		$fontSize = 5;

		$xPosition = 10;
		$yPosition = 10;

		$fontColor = imagecolorallocate($newImg, 255, 255, 255);

		imagestring($newImg, $fontSize, $xPosition, $yPosition, $wm_text, $fontColor);

		imagejpeg($newImg, $imgPath);

		imagedestroy($newImg);

		return true;
	}

	/**
	 * Convert array to object
	 *
	 * @param mixed $array
	 *
	 * @return object
	 */
	public static function arrayToObject ($array) : object {
		return (object) json_decode(json_encode($array), false);
	}

	/**
	 * Convert object to array
	 *
	 * @param mixed $Object
	 *
	 * @return array
	 */
	public static function objectToArray ($Object) : array {
		return (array) json_decode(json_encode($Object), true);
	}

	/**
	 * Alternative for php var_dump
	 *
	 * @param mixed  $var
	 * @param string $fun
	 * @param bool   $die
	 *
	 * @noinspection ForgottenDebugOutputInspection
	 */
	public static function dump ($var, $fun = 'VD', $die = true) : void {
		echo PHP_EOL;

		switch ( $fun ) {
			case 'VD':
				var_dump($var);
				break;

			case 'PR':
				print_r($var);
				break;

			case 'DZ':
				debug_zval_dump($var);
		}

		echo PHP_EOL;

		if ( $die ) {
			die();
		}
	}

	/**
	 * Change array item index
	 *
	 * @param array $array
	 * @param int   $index
	 * @param int   $newIndex
	 */
	public static function moveArrayItem (array &$array, int $index, int $newIndex) : void {
		$result = array_splice($array, $index, 1);

		array_splice($array, $newIndex, 0, $result);
	}

	/**
	 * Fix directory separators in path
	 *
	 * @param string $path
	 * @param string $ds
	 *
	 * @return string
	 */
	public static function dsFix (string $path, string $ds = DIRECTORY_SEPARATOR) : string {
		$path = str_replace(['/', chr(92)], $ds, $path);

		return $path;
	}

	/**
	 * Get client real ip
	 *
	 * @return string
	 */
	public static function getIP () : string {
		if ( isset($_SERVER['HTTP_CF_CONNECTING_IP']) ) {
			$_SERVER['REMOTE_ADDR']    = $_SERVER['HTTP_CF_CONNECTING_IP'];
			$_SERVER['HTTP_CLIENT_IP'] = $_SERVER['HTTP_CF_CONNECTING_IP'];
		}

		$client  = $_SERVER['HTTP_CLIENT_IP'] ?? null;
		$forward = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? null;
		$remote  = $_SERVER['REMOTE_ADDR'];

		if ( filter_var($client, FILTER_VALIDATE_IP) ) {
			return $client;
		}

		if ( filter_var($forward, FILTER_VALIDATE_IP) ) {
			return $forward;
		}

		return $remote;
	}

	/**
	 * Get current url
	 *
	 * @param bool $removeQueryString
	 *
	 * @return string
	 */
	public static function currentURL (bool $removeQueryString = false) : string {
		$result = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

		if ( $removeQueryString ) {
			$result = strtok($result, '?');
		}

		return $result;
	}

	/**
	 * Check if array item is set and not empty and equal to specified value
	 *
	 * @param array|object $array
	 * @param string       $key
	 * @param string|null  $value
	 *
	 * @return bool
	 */
	public static function isSet ($array = [], string $key = '', $value = null) : bool {
		if ( empty($array) ) {
			return false;
		}

		if ( is_object($array) ) {
			$array = self::objectToArray($array);
		}

		if ( empty($array) || empty($key) || !is_array($array) ) {
			return false;
		}

		if ( isset($array[$key]) && !empty($array[$key]) ) {
			return !($value !== null && $array[$key] !== $value);
		}

		return false;
	}

	/**
	 * Remove protocol from url
	 *
	 * @param string $url
	 *
	 * @return string
	 */
	public static function removeUrlProtocol (string $url) : string {
		if ( strncmp($url, '//', 2) === 0 ) {
			$url = (string) self::replaceOnce('//', '', $url);
		}
		if ( strncmp($url, 'http://', 7) === 0 ) {
			$url = (string) self::replaceOnce('http://', '', $url);
		}
		if ( strncmp($url, 'https://', 8) === 0 ) {
			$url = (string) self::replaceOnce('https://', '', $url);
		}

		return $url;
	}

	/**
	 * Set protocol to url
	 *
	 * @param string $url
	 * @param string $protocol
	 *
	 * @return string
	 */
	public static function setUrlProtocol (string $url, string $protocol) : string {
		$url = self::removeUrlProtocol($url);

		return $protocol . $url;
	}

	/**
	 * Convert file size in bytes to human readable format
	 *
	 * @param float $bytes
	 *
	 * @return float|int|string
	 */
	public static function bytesToReadable (float $bytes) {
		$result  = 'نا مشخص';
		$arBytes = [
			0 => [
				'UNIT'  => 'ترابایت',
				'VALUE' => 1024 ** 4
			],
			1 => [
				'UNIT'  => 'گیگابایت',
				'VALUE' => 1024 ** 3
			],
			2 => [
				'UNIT'  => 'مگابایت',
				'VALUE' => 1024 ** 2
			],
			3 => [
				'UNIT'  => 'کیلوبایت',
				'VALUE' => 1024
			],
			4 => [
				'UNIT'  => 'بایت',
				'VALUE' => 1
			]
		];

		foreach ( $arBytes as $arItem ) {
			if ( $bytes >= $arItem['VALUE'] ) {
				$result = $bytes / $arItem['VALUE'];
				$result = str_replace('.', ',', (string) round($result, 2)) . ' ' . $arItem['UNIT'];
				break;
			}
		}

		return $result;
	}

	/**
	 * Get called class if exists
	 *
	 * @param string $output > backtrace index
	 *
	 * @return mixed|string
	 */
	public static function getCalled (string $output = 'class') {
		$trace = debug_backtrace();

		$outIndex = $trace[1][$output];

		for ( $i = 1, $iMax = count($trace); $i < $iMax; $i++ ) {
			if ( isset($trace[$i][$output]) && $outIndex !== $trace[$i][$output] ) {
				return $trace[$i][$output];
			}
		}

		return '';
	}

	/**
	 * Get Elapsed Time from Specified Date
	 *
	 * @param        $dateTime
	 * @param bool   $full
	 * @param string $suffix
	 *
	 * @return bool|string
	 */
	public static function getElapsedTime ($dateTime, $full = false, $suffix = 'قبل') {
		$now = new DateTime();

		if ( self::isNumber($dateTime) ) {
			$dateTime = date('Y-m-d H:i:s', $dateTime);
		}

		try {
			$ago = new DateTime($dateTime);
		} catch ( Exception $E ) {
			return false;
		}

		$diff = $now->diff($ago);

		$diff->w = floor($diff->d / 7);
		$diff->d -= $diff->w * 7;

		$string = [
			'y' => 'سال',
			'm' => 'ماه',
			'w' => 'هفته',
			'd' => 'روز',
			'h' => 'ساعت',
			'i' => 'دقیقه',
			's' => 'ثانیه'
		];

		foreach ( $string as $key => &$value ) {
			if ( $diff->{$key} ) {
				$value = $diff->{$key} . ' ' . $value;
			} else {
				unset($string[$key]);
			}
		}

		unset($value);

		if ( !$full ) {
			$string = array_slice($string, 0, 1);
		}

		return $string ? implode(', ', $string) . ' ' . $suffix : 'همین الان';
	}

	/**
	 * Search Array For Pairs
	 *
	 * @param array  $array    : Target Array For Search
	 * @param array  $pairs    : Search For? ex: ['id' => 5]
	 * @param bool   $single   : Return First Founded Item
	 * @param string $compare  : Can Be 'like' OR '='
	 * @param string $operator : Can Be 'or' OR 'and'
	 *
	 * @return array
	 */
	public static function arraySearch (array $array, array $pairs, bool $single = true, string $compare = '=', string $operator = 'and') : array {
		$found        = [];
		$coincidences = 0;

		foreach ( $array as $akey => $aval ) {
			if ( is_array($aval) ) {
				$mFound = self::arraySearch($aval, $pairs, $single, $compare, $operator);

				if ( !empty($mFound) ) {
					if ( $single ) {
						return $mFound;
					}

					$aval = $mFound;
					$coincidences++;
				}
			}

			foreach ( $pairs as $pkey => $pval ) {
				if ( !is_array($aval) ) {
					continue;
				}

				$are = array_key_exists($pkey, $aval);

				if ( $compare === 'like' ) {
					if ( $are && strpos($aval[$pkey], $pval) !== false ) {
						if ( $operator === 'or' ) {
							$found[$akey] = $aval;

							if ( $single ) {
								return $found;
							}
						} else {
							$coincidences++;
						}
					}
				} elseif ( $are && $aval[$pkey] === $pval ) {
					if ( $operator === 'or' ) {
						$found[$akey] = $aval;

						if ( $single ) {
							return $found;
						}
					} else {
						$coincidences++;
					}
				}
			}

			if ( ($operator === 'and') && $coincidences === count($pairs) ) {
				$found[$akey] = $aval;

				if ( $single ) {
					return $found;
				}
			}
		}

		return $found;
	}

	/**
	 * Convert camelCase to snake_case
	 *
	 * @param string $camelCase
	 *
	 * @return string
	 */
	public static function camelToSnake (string $camelCase) : string {
		if ( empty($camelCase) ) {
			return '';
		}

		preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $camelCase, $matches);

		$ret = $matches[0];
		foreach ( $ret as &$match ) {
			$match = $match === strtoupper($match) ? strtolower($match) : lcfirst($match);
		}

		return implode('_', $ret);
	}

	/**
	 * Convert snake_case to camelCase
	 *
	 * @param string $snakeCase
	 *
	 * @return string
	 */
	public static function snakeToCamel (string $snakeCase) : string {
		$result = ucwords(str_replace(['-', '_'], ' ', $snakeCase));
		$result = str_replace(' ', '', $result);

		return lcfirst($result);
	}

	/**
	 * Get Value from a Number by Percent
	 *
	 * @param     $number
	 * @param     $percent
	 *
	 * @return float
	 */
	public static function getNumberByPercent (float $number, float $percent) : float {
		return ($percent / 100) * $number;
	}

	/**
	 * Get Percent of a Value from a Number
	 *
	 * @param     $number
	 * @param     $percentOf
	 *
	 * @return float
	 */
	public static function getPercentByNumber (float $number, float $percentOf) : float {
		return (100 * $number) / $percentOf;
	}

	/**
	 * Remove Extra Decimal Points without Rounding
	 *
	 * @param float $number
	 * @param int   $decimalPoints
	 *
	 * @return float
	 */
	public static function removeExtraDecimalPoints (float $number, int $decimalPoints = 2) : float {
		return bcdiv($number, 1, $decimalPoints);
	}

	/**
	 * Round Number By Thousand
	 *
	 * Example : 15,326 > 16,000
	 * Example : 258    > 1,000
	 *
	 * @param $number
	 *
	 * @return float|int
	 */
	public static function roundByThousand (float $number) : float {
		return ceil($number / 1000) * 1000;
	}

	/**
	 * Remove Item from Array & Return if item Removed or Not
	 *
	 * @param array $array
	 * @param       $value
	 * @param bool  $checkType
	 *
	 * @return bool
	 */
	public static function removeArrayItem (array &$array, $value, bool $checkType = false) : bool {
		if ( !in_array($value, $array, $checkType) ) {
			return false;
		}

		$key = array_search($value, $array, $checkType);

		if ( $key !== false ) {
			unset($array[$key]);

			return true;
		}

		return false;
	}

	/**
	 * @param int  $length
	 * @param bool $base64Encode
	 *
	 * @return string
	 */
	public static function getRandomSalt ($length = 32, $base64Encode = true) : string {
		try {
			$randomBytes = random_bytes($length);
		} catch ( Exception $e ) {
			$randomBytes = microtime();
		}

		$saltBytes = gzdeflate($randomBytes, 9);

		return $base64Encode ? base64_encode($saltBytes) : $saltBytes;
	}

	/**
	 * Get Microtime in Number Format
	 *
	 * @return string
	 */
	public static function getMicroTime () : string {
		$microTime = microtime(true);

		return self::onlyNumbers($microTime);
	}

	/**
	 * Extract Numbers From String
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	public static function onlyNumbers (string $string) : string {
		return preg_replace('/\D/', '', $string);
	}

	/**
	 * Check if Specified Date is in Specified Format
	 *
	 * @param string $date
	 * @param string $format
	 *
	 * @return bool
	 */
	public static function checkDateFormat (string $date, string $format) : bool {
		$dateTime = DateTime::createFromFormat($format, $date);

		return $dateTime && $dateTime->format($format) === $date;
	}

	/**
	 * Get all Functions Names inside of a File
	 * Note: This method read the Entire File Line By Line
	 *
	 * @param string $filePath
	 * @param bool   $sort
	 *
	 * @return array
	 */
	public static function getFileFunctions (string $filePath, bool $sort = false) : array {
		$file      = file($filePath);
		$functions = [];

		foreach ( $file as $line ) {
			$line = trim($line);

			if ( stripos($line, 'function ') !== false ) {
				$function_name = trim(str_ireplace([
					'public',
					'private',
					'protected',
					'static'
				], '', $line));

				$function_name = trim(substr($function_name, 9, strpos($function_name, '(') - 9));

				if ( !in_array($function_name, [
					'__construct',
					'__destruct',
					'__get',
					'__set',
					'__isset',
					'__unset'
				]) ) {
					$functions[] = $function_name;
				}
			}
		}

		if ( $sort ) {
			asort($functions);
			$functions = array_values($functions);
		}

		return $functions;
	}

	/**
	 * Get all Functions Names inside of a DIR (Recursive)
	 * Note: This method read the Entire Folder File's Line By Line
	 *
	 * @param string $dirPath
	 * @param bool   $sort
	 *
	 * @return array
	 */
	public static function getDirFunctions (string $dirPath, bool $sort = false) : array {
		$RII       = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dirPath));
		$functions = [];

		foreach ( $RII as $file ) {
			if ( $file->isDir() ) {
				continue;
			}

			$filePath  = self::dsFix($file->getPathname());
			$functions = array_merge(self::getFileFunctions($filePath), $functions);
		}

		return $functions;
	}

	/**
	 * Get Object Class Name
	 *
	 * @param      $object
	 * @param bool $withNamespace
	 *
	 * @return string
	 */
	public static function getObjectClassName (object $object, bool $withNamespace = false) : string {
		$objectNamespace = get_class($object);

		if ( $withNamespace ) {
			return $objectNamespace;
		}

		$objectClass = explode('\\', $objectNamespace);

		return end($objectClass);
	}

	/**
	 * Get Previous Date
	 *
	 * @param int    $daysToMinus
	 * @param string $format
	 *
	 * @return string
	 */
	public static function getPrevDate (int $daysToMinus, string $format = 'Y-m-d H:i:s') : string {
		return date($format, strtotime('-' . $daysToMinus . ' days'));
	}

	/**
	 * Get Next Date
	 *
	 * @param int    $daysToAdd
	 * @param string $format
	 *
	 * @return string
	 */
	public static function getNextDate (int $daysToAdd, string $format = 'Y-m-d H:i:s') : string {
		return date($format, strtotime($daysToAdd . ' days'));
	}

	/**
	 * Get Directory Files Array
	 *
	 * @param string $dirPath
	 *
	 * @return array
	 */
	public static function getDirFiles (string $dirPath) : array {
		if ( !file_exists($dirPath) ) {
			return [];
		}

		return array_diff(scandir($dirPath), ['.', '..']);
	}

	/**
	 * Check if string is Date
	 *
	 * @param string $date
	 * @param string $format
	 *
	 * @return bool
	 */
	public static function isDate (string $date, string $format = '') : bool {
		if ( empty($date) ) {
			return false;
		}

		try {
			if ( !empty($format) ) {
				$dateTime = DateTime::createFromFormat($format, $date);

				if ( $dateTime === false || !($dateTime instanceof DateTime) ) {
					return false;
				}
			} else {
				new DateTime($date);
			}
		} catch ( Exception $e ) {
			return false;
		}

		return true;
	}

	/**
	 * Check if a file is image or not
	 *
	 * @param string $filePath
	 *
	 * @return bool
	 */
	public static function isImage (string $filePath) : bool {
		if ( !file_exists($filePath) ) {
			return false;
		}

		try {
			$info = getimagesize($filePath);
		} catch ( Exception $e ) {
			return false;
		}

		return is_array($info);
	}

	/**
	 * Check if specified array is Associative or Not
	 *
	 * @param array $array
	 *
	 * @return bool
	 */
	public static function isAssocArray (array $array) : bool {
		if ( [] === $array ) {
			return false;
		}

		return array_keys($array) !== range(0, count($array) - 1);
	}

	/**
	 * Number Short Formatter
	 *
	 * @param        $n
	 * @param bool   $detailed
	 * @param string $lang
	 * @param int    $precision
	 *
	 * @return string
	 */
	public static function numberShortFormat ($n, $detailed = true, $lang = 'fa', $precision = 1) : string { // TODO
		$faPreffix = '';
		if ( $detailed ) {
			if ( $n < 900 ) {
				// 0 - 900
				$n_format = number_format($n, $precision);
				$suffix   = '';
				$faSuffix = '';
			} else if ( $n < 900000 ) {
				// 0.9k-850k
				$n_format = number_format($n / 1000, $precision);
				$suffix   = 'K';
				$faSuffix = ' هزار';
			} else if ( $n < 900000000 ) {
				// 0.9m-850m
				$n_format = number_format($n / 1000000, $precision);
				$suffix   = 'M';
				$faSuffix = ' میلیون';
			} else if ( $n < 900000000000 ) {
				// 0.9b-850b
				$n_format = number_format($n / 1000000000, $precision);
				$suffix   = 'B';
				$faSuffix = ' میلیارد';
			} else {
				// 0.9t+
				$n_format = number_format($n / 1000000000000, $precision);
				$suffix   = 'T';
				$faSuffix = ' بیلیون';
			}
		} else {
			if ( $n >= 0 && $n < 1000 ) {
				// 1 - 999
				$n_format = floor($n);
				$suffix   = '';
				$faSuffix = '';
			} else if ( $n >= 1000 && $n < 1000000 ) {
				// 1k-999k
				$n_format  = floor($n / 1000);
				$faPreffix = '+';
				$suffix    = 'K+';
				$faSuffix  = ' هزار';
			} else if ( $n >= 1000000 && $n < 1000000000 ) {
				// 1m-999m
				$n_format  = floor($n / 1000000);
				$faPreffix = '+';
				$suffix    = 'M+';
				$faSuffix  = ' میلیون';
			} else if ( $n >= 1000000000 && $n < 1000000000000 ) {
				// 1b-999b
				$n_format  = floor($n / 1000000000);
				$faPreffix = '+';
				$suffix    = 'B+';
				$faSuffix  = ' میلیارد';
			} else {
				// 1t+
				$n_format  = floor($n / 1000000000000);
				$faPreffix = '+';
				$suffix    = 'T+';
				$faSuffix  = ' بیلیون';
			}
		}

		// Remove unnecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
		// Intentionally does not affect partials, eg "1.50" -> "1.50"
		if ( $precision > 0 ) {
			$dotzero  = '.' . str_repeat('0', $precision);
			$n_format = str_replace($dotzero, '', $n_format);
		}

		if ( $lang === 'fa' ) {
			return $faPreffix . $n_format . $faSuffix;
		}

		return $n_format . $suffix;
	}

	/**
	 * Check if array is Multidimensional
	 *
	 * @param array $array
	 *
	 * @return bool
	 */
	public static function isMultidimensionalArray (array $array) : bool {
		$rv = array_filter($array, 'is_array');

		return count($rv) > 0;
	}

	/**
	 * Check if given string is mobile number (IRAN) or not
	 *
	 * @param string $mobile
	 *
	 * @return bool
	 */
	public static function isMobile (string $mobile) : bool {
		$preg = preg_match('/^0(9)\d{9}$/', $mobile);

		return $preg === 1;
	}

	/**
	 * Check if given string contain Persian Chars or not
	 *
	 * @param string $string
	 *
	 * @return bool
	 */
	public static function isPersian (string $string) : bool {
		$preg = preg_match('/^[^\x{600}-\x{6FF}]+$/u', str_replace("\\\\", '', $string));

		return $preg === 0;
	}

	/**
	 * convert hex colors to rgba
	 *
	 * @param               $hex
	 * @param numeric       $alpha
	 * @param bool          $echo
	 *
	 * @return array|string
	 */
	public static function hexToRgba ($hex, $alpha = 100, bool $echo = true) {
		if ( strpos($hex, 'rgb') !== false && strpos($hex, 'rgba') === false ) {
			return $hex;
		}
		$rgb = [];
		if ( strpos($hex, 'rgba') !== false ) {
			$hex = explode(',',str_replace(['rgba(',')'],'',$hex));
			$rgb['r'] = $hex[0] ?? 255;
			$rgb['g'] = $hex[1] ?? 255;
			$rgb['b'] = $hex[2] ?? 255;
			$rgb['ao'] = $hex[3] ?? 1;

		}else {
			$hex       = str_replace('#', '', $hex);
			$length    = strlen($hex);
			$rgb['r']  = hexdec($length >= 6 ? substr($hex, 0, 2) : ($length == 3 ? str_repeat(substr($hex, 0, 1), 2) : 0));
			$rgb['g']  = hexdec($length >= 6 ? substr($hex, 2, 2) : ($length == 3 ? str_repeat(substr($hex, 1, 1), 2) : 0));
			$rgb['b']  = hexdec($length >= 6 ? substr($hex, 4, 2) : ($length == 3 ? str_repeat(substr($hex, 2, 1), 2) : 0));
			$rgb['ao'] = hexdec($length >= 8 ? substr($hex, 6, 2) : 'FF') / 255;
		}

		$rgb['a'] = ($alpha / 100) * ((int) $rgb['ao']);

		if ( $echo ) {
			return 'rgba(' . $rgb['r'] . ',' . $rgb['g'] . ',' . $rgb['b'] . ',' . $rgb['a'] . ')';
		}

		return $rgb;
	}
}
