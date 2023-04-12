<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//db라이선스
class dbenvironment
{
	function __construct() {
		$CI =& get_instance(); 
		$CI->firstmallplusdbenv = true;
	}

	/* DB라이센스 생성 */
	function getDBLicenseSerial(){
		$CI =& get_instance();
		if(!$CI->config_system) $CI->config_system = config_load('system');
		return md5($_SERVER['DOCUMENT_ROOT']."||".$_SERVER['SERVER_ADDR']."||".$CI->db->username."||".$CI->db->database."||".$CI->config_system['shopSno']);
	}

	/* DB라이센스 유효여부 */
	function isValidDBLicense(){
		$CI =& get_instance();
		if(!$CI->config_system) $CI->config_system = config_load('system');
		$arrSerial = explode("||",$CI->config_system['dbserial']);
		$currentServerSerial = $this->getDBLicenseSerial(); 
		if(empty($CI->config_system['dbserial'])) {return '1101';} // 라이선스 존재하지 않을때

		$flag = '1102'; // 유효하지 않을때
		foreach($arrSerial as $dbserial){
			if($dbserial == $currentServerSerial) $flag = '0000'; // 유효할때
		}

		return $flag;
	}

	/* DB라이센스 유효성 체크 */
	function checkDBEnvironmentValidation(){ 
		
		/* 가비아 통신처리시에는 건너뜀 */
		$uristring = explode('/',$_SERVER['REQUEST_URI']);
		if($uristring[1]=='_gabia') return;
		if($_SERVER['SHELL']) return;
		if(php_sapi_name() == 'cli' ) return;
		
		$CI =& get_instance();
		$CI->firstmallplusdbenvvalidation = true;
		$uri_str = uri_string();

		$devserver = array('121.78.114.51','121.78.114.25','121.78.197.236');//개발/QA 사이트 라이선스체크제외
		if( in_array($_SERVER['SERVER_ADDR'],$devserver) || in_array($CI->db->hostname,$devserver) || $_SERVER['SERVER_ADDR']=='10.13.24.13' || strpos($uri_str, "dev") !== false) return;

return;
// 임시 라이센스 체크 해제 :: gcs ysm  @2020-12-29
$permit_url = array('newtreemall.co.kr','www.newtreemall.co.kr' ,'www.m.newtreemall.co.kr' ,'m.newtreemall.co.kr');
//array_push($permit_url, 'newtreemall.firstmall.kr','www.newtreemall.firstmall.kr' ,'www.m.newtreemall.firstmall.kr' ,'m.newtreemall.firstmall.kr');
array_push($permit_url, 'test.newtreemall.co.kr','www.test.newtreemall.co.kr' ,'www.m.test.newtreemall.co.kr' ,'m.test.newtreemall.co.kr');
array_push($permit_url, 'devnewtree.firstmall.kr','www.devnewtree.firstmall.kr' ,'www.m.devnewtree.firstmall.kr' ,'m.devnewtree.firstmall.kr');
array_push($permit_url, 'd9eks8ewcrt25.cloudfront.net');
array_push($permit_url, 'newtree-dev-alb-965648967.ap-northeast-2.elb.amazonaws.com','www.newtree-dev-alb-965648967.ap-northeast-2.elb.amazonaws.com' ,'www.m.newtree-dev-alb-965648967.ap-northeast-2.elb.amazonaws.com' ,'m.newtree-dev-alb-965648967.ap-northeast-2.elb.amazonaws.com');
		
		foreach($permit_url as $key => $val){ 
	       if($_SERVER['HTTP_HOST'] == $val ){
	       	//echo '성공'. $val."<br>";return;
			return true;
	      }
	    } 
		
		$code = $this->isValidDBLicense();
		if($code!=='0000'){
			shopErrorScreen($code);
		}else{
			$this->chkServiceSerial();
		}
	}
	
	//front_base
	function checkDBEnvironment($shop_sno){
		$CI =& get_instance();  
		$sysdbenvironmentsar = config_load('dbenvironments');
		unset($system_dbenvironments);
		foreach ($sysdbenvironmentsar as $sysdbenvironments){
			$system_dbenvironments[] = $_SERVER['DOCUMENT_ROOT']."|".$_SERVER['SERVER_ADDR']."|".$sysdbenvironments."|".$shop_sno;
		}
		$str_environment = $_SERVER['DOCUMENT_ROOT']."|".$_SERVER['SERVER_ADDR']."|".$CI->db->username."|".$CI->db->database."|".$shop_sno; 
		if( $system_dbenvironments ) {
			if( in_array( $str_environment,$system_dbenvironments ) ){
				return array(1,$system_dbenvironments);
			}
		}

		return array(0,$system_dbenvironments);
	}


	function checkFirstmallPluslicenseError($code, $contents){ 
		$CI =& get_instance();
		$CI->load->helper("readurl");
	if(!$CI->config_system) $CI->config_system = config_load('system');
		if(in_array($code,array('1001','1002','1003','1101','1102','1103','1301','1302','1303')) && !in_array($_SERVER['SERVER_ADDR'],array('121.78.114.165'))){
			if(in_array($code,array('1101','1102','1103'))){
				$data['u'] = base64_encode($CI->db->username);
				$data['d'] = base64_encode($CI->db->database);
				$type = "dbserver";
			}elseif(in_array($code,array('1301','1302','1303'))){
				$data['s'] = base64_encode($CI->config_system['service']['code']);
				$type = "servicer";
			}else{
				$data['h'] = base64_encode($_SERVER['HTTP_HOST']);
				$data['a'] = base64_encode($_SERVER['SERVER_ADDR']);
				$data['r'] = base64_encode($_SERVER['DOCUMENT_ROOT']); 
				$type = "webserver";
			}
			 
			$data['detail_log']	= base64_encode(mb_convert_encoding($contents, "EUC-KR", "UTF-8")); 
			$requestUrl_dbenv = "https://gapi.firstmall.kr/license/errorLogs?destination=".$CI->config_system['shopSno']."&error_type=".$type."&error_code=".$code; 
			$out = readurl($requestUrl_dbenv,$data,'',1);
			return $out;
		}
	}

	// Hash Serial 생성함수
	public function getHashSerial(){
		$CI		=& get_instance();
		if	(!$CI->config_system)	$CI->config_system	= config_load('system');

		$keyString	= $_SERVER['DOCUMENT_ROOT'].$_SERVER['SERVER_ADDR'].$CI->db->username.$CI->db->database.$CI->config_system['shopSno'].$CI->config_system['service']['code'];

		$result	= hash('sha256', md5($keyString));

		return $result;
	}

	// Service Serial 생성함수
	public function getServiceSerial(){
		$hashVal	= $this->getHashSerial();
		$dateVal	= date('ymd');
		$randVal	= rand(1,9);
		$dateLen	= strlen($dateVal);
		$tmpHashVal	= $hashVal;
		for ($d = 0; $d < $dateLen; $d++){
			unset($inStr, $str1, $str2);
			$inStr		= substr($dateVal, $d, 1);
			$str1		= substr($tmpHashVal, 0, $randVal);
			$str2		= substr($tmpHashVal, $randVal);
			$result		.= $str1 . $inStr;
			$tmpHashVal	= $str2;
		}
		$result	= $randVal . $result . $str2;

		return $result;
	}

	// Service Serial 비교함수
	public function chkServiceSerial(){
		$CI			=& get_instance();
		if	(!$CI->config_system)	$CI->config_system	= config_load('system');

		// Service Serial 비교
		$serviceSerial	= $CI->config_system['serviceserial'];
		if	($serviceSerial){
			$hashVal1			= $this->getHashSerial();
			$serviceSerialArr	= explode('||', $serviceSerial);
			if	(count($serviceSerialArr) > 0){
				foreach($serviceSerialArr as $k => $sSerial){
					$hashVal2	= $this->getServiceOrigialSerial($sSerial);
					if	($hashVal2){
						if	($hashVal1 == $hashVal2){
							$resultCode	= '0000';	// 정상일 경우
							break;
						}else{
							$resultCode	= '1302';	// 불일치
						}
					}else{
						$resultCode	= '1303';		// hash값이 이상하여 original hash를 구할 수 없는 경우
					}
				}
			}else{
				$resultCode	= '1301';			// hash값이 없을 경우
			}
		}else{
			$resultCode	= '1301';			// hash값이 없을 경우
		}

		if	($resultCode !== '0000'){
			shopErrorScreen($resultCode);
		}
	}

	// Service Serial 가공제거 함수
	public function getServiceOrigialSerial($serviceSerial){
		if	($serviceSerial){
			$randVal	= substr($serviceSerial, 0, 1);
			$tmpHashVal	= substr($serviceSerial, 1);
			$dateVal	= date('ymd');
			$dateLen	= strlen($dateVal);
			$result		= '';
			$dateVal	= '';
			for ($d = 0; $d < $dateLen; $d++){
				unset($inStr, $str1, $str2);
				$str1		= substr($tmpHashVal, 0, $randVal);
				$str2		= substr($tmpHashVal, $randVal);
				$inStr		= substr($str2, 0, 1);
				$str2		= substr($str2, 1);
				$tmpHashVal	= $str2;

				$result		.= $str1;
				$dateVal	.= $inStr;
			}

			$result			= $result . $str2;
			if	(preg_match('/[^0-9]/', $dateVal)){
				$result		= '';
			}
		}

		return $result;
	}
}
?>