<?php

/* 퍼스트몰 서비스정보 저장 호출 */
function callSetEnvironment(){
	$CI =& get_instance();
	if(!$CI->config_system) $CI->config_system = config_load('system');
	$CI->load->helper("readurl");

	$url = get_connet_protocol()."firstmall.kr/ec_hosting/_shopConn/firstmall_plus_environment.php?destination=".$CI->config_system['shopSno'];
	$res = readurl($url);

	if($res=='success'){
		echo "OK";
	}else{
		echo $res;
	}
}

/* 퍼스트몰 서비스정보 저장 */
function setEnvironment(){
	$CI =& get_instance();
	if(isset($_GET['code'])){
		$data = unserialize(base64_decode($_GET['code']));

		if(is_array($data)){
			foreach($data as $codecd=>$value){
				config_save('system',array($codecd=>$value));
			}

			$params = array();
			$CI->load->model('multishopmodel');
			$CI->load->model('adminenvmodel');
			$CI->load->model('currencymodel');

			if( $_GET['mode'] ){
				$CI->adminenvmodel->truncate();
				$CI->currencymodel->truncate();
			}

			$shopList	= $CI->multishopmodel->getAdminEnv($params);
			if	($shopList) foreach($shopList as $k => $env_data){
				$data_shop_list[$env_data['shopSno']] = $env_data;
			}
			$cfg_system = config_load('system');
			if($cfg_system['dbs']){
				foreach($cfg_system['dbs'] as $shopSno=>$info){
					$query = "select aes_decrypt(unhex(?), 'firstmall') as info";
					$row = $CI->db->query($query,$info)->row_array();
					$db_data[$shopSno] = unserialize($row['info']);
					if( !$data_shop_list[$shopSno]) {
						$insert_param = $CI->adminenvmodel->get_default_params($shopSno, $db_data[$shopSno]['domain']);
						$CI->adminenvmodel->insert($insert_param);
						$admin_env_seq	= $CI->db->insert_id();
						$bind_array	= $CI->currencymodel->get_default_params($admin_env_seq);
						foreach($bind_array as $insert_param){
							$CI->currencymodel->insert($insert_param);
						}
					}
				}
			}else{
				$errMsg = 'dbs err';
			}

			// 환경 저장 시 SSL 설정 정보를 확인하여 해지됬을 경우 리다이렉션 설정 해지
			$CI->load->library("ssllib");
			$CI->ssllib->setSslEnvironment();

			if($errMsg)		echo $errMsg;
			else			echo "success";
		}
	}
}

/* 퍼스트몰 라이센스 저장 호출 */
function callSetLicense(){
/*
	$CI =& get_instance();
	if(!$CI->config_system) $CI->config_system = config_load('system');
	$CI->load->helper("readurl");

	$url = "http://firstmall.kr/ec_hosting/_shopConn/firstmall_plus_license.php?destination=".$CI->config_system['shopSno'];
	$res = readurl($url);

	if($res=='success'){
		echo "OK";
	}else{
		echo $res;
	}
	return $res;
*/
	echo 'OK';
	return 'success';
}

/* 라이센스 세팅 */
function setLicense(){
	$CI =& get_instance();
	if(!$CI->config_system) $CI->config_system = config_load('system');
	$CI->load->helper("readurl");

	$destination	= $_GET['destination'];
	$proctoken		= $_GET['proctoken'];

	if($destination!=$CI->config_system['shopSno']){
		echo 'error::shopSno is not matched.';
		exit;
	}

	$data['h'] =  base64_encode($_SERVER['HTTP_HOST']);
	$data['a'] =  base64_encode($_SERVER['SERVER_ADDR']);
	$data['r'] =  base64_encode($_SERVER['DOCUMENT_ROOT']);
	$data['u'] =  base64_encode($CI->db->username);
	$data['d'] =  base64_encode($CI->db->database);
	$data['s'] =  base64_encode($CI->config_system['service']['code']);
	$data['t']	= base64_encode($proctoken);

	$url = "https://gapi.firstmall.kr/license/sets?destination=".$CI->config_system['shopSno']."&serial=".urlencode(getLicenseSerial())."&dbserial=".urlencode(getDBLicenseSerial())."&serviceserial=".urlencode(getServiceSerial());
	$res = readurl($url, $data);
	$res = unserialize(base64_decode($res));

	if($res['code'] == 'success' && $res['serial']){
		$serials['serial']											= $res['serial'];
		if	( $res['dbserial'] )		$serials['dbserial']		= $res['dbserial'];
		if	( $res['serviceserial'] )	$serials['serviceserial']	= $res['serviceserial'];

		config_save('system', $serials);

		echo 'success';
		exit;
	}else{
		echo $res['code'].'::'.$res['msg'];
		exit;
	}

}

/* 라이센스 생성 */
function getLicenseSerial(){
	$CI =& get_instance();
	if(!$CI->config_system) $CI->config_system = config_load('system');
	return md5($_SERVER['DOCUMENT_ROOT']."||".$_SERVER['SERVER_ADDR']."||".$CI->config_system['shopSno']);
}

/* 라이센스 생성 */
function getDBLicenseSerial(){
	$CI =& get_instance();
	if(!$CI->config_system) $CI->config_system = config_load('system');
	if( empty($CI->dbenvironment)) $CI->load->library('dbenvironment');
	$licenseserial = $CI->dbenvironment->getDBLicenseSerial();
	return $licenseserial;
}

/* 라이센스 생성 */
function getServiceSerial(){
	$CI =& get_instance();
	if(!$CI->config_system) $CI->config_system = config_load('system');
	if( empty($CI->dbenvironment)) $CI->load->library('dbenvironment');

	return $CI->dbenvironment->getServiceSerial();
}

/* 라이센스 유효여부 */
function isValidLicense(){
	$CI =& get_instance();
	if(!$CI->config_system) $CI->config_system = config_load('system');

	$arrSerial = explode("||",$CI->config_system['serial']);
	$currentServerSerial = getLicenseSerial();

	if(empty($CI->config_system['service']['code']) || empty($CI->config_system['serial'])) {return '1001';} // 라이선스 존재하지 않을때

	$flag = '1002'; // 유효하지 않을때

	foreach($arrSerial as $serial){
		if($serial == $currentServerSerial) $flag = '0000'; // 유효할때
	}
	return $flag;
}

/* 구버젼 라이선스체크함수 변경 #3491 2012-11-18 **/
function checkLicenseValidation(){
	checkEnvironmentValidation();
}

/* 라이센스 유효성 체크 */
function checkEnvironmentValidation(){

	/* 가비아 통신처리시에는 건너뜀 */
	$uristring = explode('/',$_SERVER['REQUEST_URI']);
	if($uristring[1]=='_gabia') return;
	if($_SERVER['SHELL']) return;
	if(php_sapi_name() == 'cli' ) return;

	$CI =& get_instance();

	if ($_SERVER['SERVER_ADDR']=='127.0.0.1') {
		 $aAddr = gethostbynamel(php_uname('n'));
		 $sIP = $aAddr[0];
	 }else{
		 $sIP = $_SERVER['SERVER_ADDR'];
	}

	if(!$CI->config_system) $CI->config_system = config_load('system');
	$CI->firstmallplusenv = true;
	$uri_str = uri_string();

	$devserver = array(
		'121.78.197.230',
		'121.78.197.232',
		'139.150.80.140',
		'139.150.79.64',
		'10.9.68.9',
		'10.9.68.22', 
		'121.78.197.236'
	);

	if( in_array($_SERVER['SERVER_ADDR'],$devserver) || in_array($CI->db->hostname,$devserver) || substr($sIP,0,6)=='172.16' || strpos($uri_str, "dev") !== false) return;
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

	$code = isValidLicense();
	if($code!=='0000'){
		shopErrorScreen($code);
	}
}

/* 쇼핑몰 접속에러화면 출력 */
function shopErrorScreen($code = '')
{
	$CI = &get_instance();
	$CI->load->helper('javascript');
	$aServer = $CI->input->server();

	$aErrorCode['ip_dir'] = array(
		'1001',
		'1002',
		'1003',
		'1101',
		'1102',
		'1103',
		'1301',
		'1302',
		'1303'
	);
	$aErrorCode['db'] = array(
		'1101',
		'1102',
		'1103'
	);
	$aErrorCode['service'] = array(
		'1301',
		'1302',
		'1303'
	);
	$aErrorCode['license'] = array(
		'1001',
		'1002',
		'1003',
		'1101',
		'1102',
		'1103',
		'1301',
		'1302',
		'1303'
	);
	$aExceptIp = array(
		'121.78.114.165',
		'139.150.76.19',
		'10.9.68.25'
	);
	$devserver = array(
		'121.78.197.230',
		'121.78.197.232',
		'139.150.80.140',
		'139.150.79.64',
		'10.9.68.9',
		'10.9.68.22'
	);
	$aErrorMail = array(
		'fm_li_err@gabiacns.com'
	);
	if ($aServer['SERVER_ADDR'] == '127.0.0.1') {
		$aAddr = gethostbynamel(php_uname('n'));
		$sIP = $aAddr[0];
	} else {
		$sIP = $aServer['SERVER_ADDR'];
	}
	$uri_str = uri_string();
	$uristring = explode('/', $aServer['REQUEST_URI']);

	// 라이선스 체크
	if ( ! $CI->config_system) {
		$CI->config_system = config_load('system');
	}
	if ($uristring[1] == '_gabia' || $aServer['SHELL'] || php_sapi_name() == 'cli') {
		return;
	}
	if (in_array($aServer['SERVER_ADDR'], $devserver) || in_array($CI->db->hostname, $devserver) || substr($sIP, 0, 6) == '172.16' || strpos($uri_str, "dev") !== false) {
		return;
	}
	if (in_array($code, $aErrorCode['ip_dir']) && ! in_array($aServer['SERVER_ADDR'], $aExceptIp)) {
		if (in_array($code, $aErrorCode['db'])) {
			$title = "[WARNING] 퍼스트몰  플러스 DB라이센스 불일치 알림 - " . $aServer['SERVER_ADDR'] . " / " . $aServer['HTTP_HOST']; // DBlicense
		} elseif (in_array($code, $aErrorCode['service'])) {
			$title = "[WARNING] 퍼스트몰  플러스 서비스라이센스 불일치 알림 - " . $aServer['SERVER_ADDR'] . " / " . $aServer['HTTP_HOST']; // Serviselicense
		} else {
			$title = "[WARNING] 퍼스트몰  플러스 라이센스 불일치 알림 - " . $aServer['SERVER_ADDR'] . " / " . $aServer['HTTP_HOST'];
		}
		ob_start();
		echo "<pre>";
		print_r($aServer);
		print_r($CI->config_system);
		echo "</pre>";
		$contents = ob_get_contents();
		ob_clean();
		if (empty($CI->dbenvironment))
			$CI->load->library('dbenvironment');
		$returnok = $CI->dbenvironment->checkFirstmallPluslicenseError($code, $contents);
		if (! $returnok['code']) {
			sendDirectMail($aErrorMail, $aErrorMail[0], $title, $contents);
		}
	}

	// 라이선스 안내 출력
	if (in_array($code, $aErrorCode['license'])) {
		$file_path = $CI->config_system['adminSkin'] . "/common/error_license.html";
	} else {
		$file_path = $CI->config_system['adminSkin'] . "/common/error.html";
	}
	$CI->template->template_dir = BASEPATH . "../admin/skin";
	$CI->template->compile_dir = BASEPATH . "../_compile/admin";
	if (! is_dir($CI->template->compile_dir)) {
		@mkdir($CI->template->compile_dir);
		@chmod($CI->template->compile_dir, 0777);
	}
	$CI->template->assign('code', $code);
	$CI->template->define(array(
		'tpl' => $file_path
	));
	$CI->template->print_("tpl");
	exit();
}

/* (사용자화면) 만료일 체크
 * 무료형 : 관리자접속일 기준
 * 유료형 : 만료일 기준
 *  */
function checkExpireDate(){
	$CI =& get_instance();
	$CI->load->model('usedmodel');
	$data = $CI->usedmodel->get_period_status();
	$code	= $data['code'];
	$intval	= $data['intval'];

	// 무료형 : 관리자 미접속 31일째부터
	// 유료형 : 만기도래 31일째부터

	if(substr($code,-1)>=3){
		shopErrorScreen($code);
	}
}

/* (관리자화면) 만료일 체크
 * 무료형 : 관리자접속일 기준
 * 유료형 : 만료일 기준
 *  */
function warningExpireDate(){

	$CI =& get_instance();
	$CI->load->model('usedmodel');

	$data = $CI->usedmodel->get_period_status();

	/* 무료형 로그인시 마지막접속일 체크해서 쿠키로 구워놓았다가, 그 값으로 체크함*/
	if($CI->session->userdata('showFreeMallExpireIntval')){
		$data['code'] = $CI->session->userdata('showFreeMallExpireCode');
		$data['intval'] = $CI->session->userdata('showFreeMallExpireIntval');
		$unsetuserdata = array('showFreeMallExpireCode' => '', 'showFreeMallExpireIntval' => '');
		$CI->session->unset_userdata($unsetuserdata);
	}

	$code	= $data['code'];
	$intval	= $data['intval'];

	if($code=='0000') return;

	$warningFlag = false;

	/* 무료형 */
	if($CI->config_system['service']['code']=='P_FREE'){
		if(substr($code,-1)>=3){
			$warningFlag = true;
			if($code=='2003'){
				if(uri_string()=='admin/main/index'){
					$warningFlag = true;
				}else{
					$warningFlag = false;
				}
			}
		}
	}
	/* 유료형 */
	else{
		if(substr($code,-1)!='0'){
			$warningFlag = true;
		}
	}


	if($warningFlag){
		$CI->template->assign($data);
		$file_path = $CI->config_system['adminSkin']."/common/warning.html";
		$CI->template->define(array('warningScript'=>$file_path));
	}
}

if( !function_exists('makeEncriptParam') ) {
	/* 인코딩 파라미터 생성 */
	function makeEncriptParam($str){
		$param = null;
		$param = Str_Cript($str);
		return $param;
	}
}

if( !function_exists('makeDecriptParam') ) {
	/* 인코딩 파라미터 파싱 */
	function makeDecriptParam($str){
		$param = null;
		$param = Str_Dcript($str);
		parse_str($param, $output);
		if(count($output) > 0)
			return $output;
		else
			return false;
	}
}

if( !function_exists('Str_Cript') ) {
	/* 문자열 인코딩 */
	function Str_Cript($str){
		$delim = md5(rand(0, 10));
		if (strlen($delim) > 10) {
			$delim = substr($delim, 0, 3);
		}

		$CRIP_STR = "";
		for($i=0; $i<strlen($str); $i++) {
			$CRIP_STR = $CRIP_STR . ord(substr($str, $i, 1)) . $delim;
		}
		return $CRIP_STR . "-" . $delim;
	}
}

if( !function_exists('Str_Dcript') ) {
	/* 문자열 디코딩 */
	function Str_Dcript($str){
		$DCRIP_STR = "";
		$tmp = explode("-", $str);
		$str = $tmp[0];
		$div = $tmp[1];

		if (!empty($div)) $str_arr = explode($div, $str);
		for ($i=0; $i<sizeof($str_arr)-1; $i++) {
			$DCRIP_STR = $DCRIP_STR . chr($str_arr[$i]);
		}
		return $DCRIP_STR;
	}
}

/* 인터페이스서버용 토큰 반환 */
function getInterfaceToken(){
	$CI =& get_instance();
	$CI->load->helper("readurl");

	$url = get_connet_protocol()."interface.firstmall.kr/firstmall_plus/request.php?cmd=getToken&shopSno=".$CI->config_system['shopSno'];
	$res = readurl($url);
	return $res;
}

function checkEnvironment($shop_sno){
	$system_environments = config_load('environments');
	$str_environment = $_SERVER['HTTP_HOST']."|".$_SERVER['SERVER_ADDR']."|".$_SERVER['DOCUMENT_ROOT']."|".$_SERVER['DOCUMENT_ROOT']."|".$shop_sno;

	if( in_array( $str_environment,$system_environments ) ){
		return array(1,$system_environments);
	}

	return array(0,$system_environments);
}

function checkPgEnvironment($pgCompany){
	$pg = config_load($pgCompany);
	if(!$pg['mallId']) $pg['mallId'] = $pg['mallCode'];
	$pg_environments = config_load('pg_environments','setting');
	$str_environment = $pg['mallId']."|".$pgCompany;
	if( $str_environment != $pg_environments && $pg['mallId'] && $pgCompany)
	{
		return array(false,$pg,$pg_environments);
	}
	return array(true,$pg,$pg_environments);
}

//관리자 본인인증 과정
function checksmskey($realnametype )
{
	if(!$realnametype) return;

	if( $realnametype == 'phone' ) {//본인인증 : 휴대폰
		$sSite['Code'] 			= "G3517";			// 본인인증 사이트 코드
		$sSite['Password']		= "NFTNGQXLLC2L";			// 본인인증 사이트 패스워드
	}
	elseif( $realnametype == 'ipin' ) {//아이핀체크
		$sSite['Code']			= "G042";
		$sSite['Password']		= "69726190";
	}
	return $sSite;
}

function getAdminAccessToken(){
	$CI				=& get_instance();
	$query			= $CI->db->query('select shopSno from fm_admin_env order by admin_env_seq asc limit 1');
	$query			= $query->row_array();
	$master			= $query['shopSno'];

	$tailEnc		= md5($master);
	$bodyEnc		= hash('sha256',date('Ymd'));

	for($i=0; $i < 4; $i++){
		$encArr[]	= substr($tailEnc,$i*8,8);
		$encArr[]	= substr($bodyEnc,$i*16,16);
	}

	return implode('',$encArr);
}

function decAdminAccessToken($token){
	if	(!$token) return;

	for($i=0; $i < 4; $i++){
		$rstEnc[]	= substr($token,$i*24,8);
	}

	return implode('',$rstEnc);
}
?>