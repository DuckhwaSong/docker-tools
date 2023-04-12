<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @author lgs
 * @version 1.0.0
 * @license copyright by GABIA_lgs
 * @since 12. 2. 1 10:10 ~
 */

/**
 * config_search
 * 설정 코드를 뒷쪽 와일드카드 검색합니다.
 *
 * @param string $groupcd 일치 검색되는 그룹 코드입니다.
 * @param string $code_q 뒷쪽 와일드카드 검색되는 코드입니다.
 * @return array|null 반환되는 설정의 stdClass 배열입니다. 일치하는 값이 없으면 null이 반환됩니다.
 */
function config_search(string $groupcd, string $code_q) {
	$CI = get_instance();

	if(empty($groupcd) || empty($code_q)) throw new BadFunctionCallException;

	if(
		[
			'step' => true,
			'payment' => true,
			'export_status' => true,
			'coupon_export_status' => true,
		][$groupcd]
		&& !preg_match('/^admin\//', uri_string())
		)switch($CI->config_system['language']) {
		case "US": $groupcd .= '_en'; break;
		case "CN": $groupcd .= '_cn'; break;
		case "JP": $groupcd .= '_jp'; break;
	}

	return (clone $CI->db)->reset_query()
		->select('codecd, value, regist_date')
		->from('fm_config')
		->where('groupcd', $groupcd)
		->like('codecd', $code_q, 'after')
		->get()
		->result();
}

// 설정로드
function config_load($groupcd, $codecd='', $getDate = false) {
	$CI =& get_instance();

	if(empty($groupcd) && empty($codecd)) {
		return false;
	}

	$returnArr = false;
	$cache_item_id = sprintf('config_%s%s', $groupcd, $codecd ? '_' . $codecd : '');
	if ($CI->config_system['file_cache'] == 'Y') {
		$returnArr = $CI->cache->get($cache_item_id);
	}
	if ($returnArr === false) {
		$returnArr = array();

		if (($groupcd == 'step' || $groupcd == 'payment' || $groupcd == 'export_status' || $groupcd == 'coupon_export_status') && !preg_match('/^admin\//',uri_string())) {
			switch($CI->config_system['language']){
				case "US";$groupcd = $groupcd.'_en';break;
				case "CN";$groupcd = $groupcd.'_cn';break;
				case "JP";$groupcd = $groupcd.'_jp';
			}
		}

		if ($groupcd) {
			$aWhere['groupcd'] = $groupcd;
		}
		if ($codecd) {
			$aWhere['codecd'] = $codecd;
		}

		// 쿼리 초기화
		$CI->db->reset_query();

		$query = $CI->db->select('codecd, value, regist_date')
		->from('fm_config')
		->where($aWhere)
		->order_by('regist_date ASC, codecd ASC')
		->get();
		foreach ($query->result_array() as $row){
			if ($row['value'] || $row['value'] =='0') {
				if (preg_match('/a:/',$row['value'])) $row['value'] = unserialize(strip_slashes($row['value']));
				$returnArr[$row['codecd']] = $row['value'];
				// 설정일
				if ($getDate) {
					$returnArr[$row['codecd'] . '_date'] = $row['regist_date'];
				}
			}
		}

		if ($groupcd == 'system' && !isset($returnArr['cutting_sale_use']))	$returnArr['cutting_sale_use'] = null;
		if ($groupcd == 'system' && !isset($returnArr['cutting_sale_price'])) $returnArr['cutting_sale_price'] = null;
		if ($groupcd == 'system' && !isset($returnArr['cutting_sale_action'])) $returnArr['cutting_sale_action'] = null;
		if ($groupcd == 'system' && !isset($returnArr['cutting_price'])) $returnArr['cutting_price'] = null;

		// 절사기본값 필터링
		if( $groupcd == 'system' && $codecd=='' ){
			if(!$returnArr['cutting_sale_price'] && !$returnArr['cutting_sale_use']){
				if( $returnArr['cutting_price'] == 'none' || !$returnArr['cutting_price'] ){
					$returnArr['cutting_sale_use'] = 'none';
				}else if( $returnArr['cutting_price'] ){
					if(!$returnArr['cutting_sale_price'])$returnArr['cutting_sale_price'] = $returnArr['cutting_price'];
				}
				if(!$returnArr['cutting_sale_action']) $returnArr['cutting_sale_action'] = 'dscending';
			}else{
				if($returnArr['cutting_sale_use'] == 'none'){
					$returnArr['cutting_price'] = 'none';
				}else{
					$returnArr['cutting_price'] = $returnArr['cutting_sale_price'];
				}
			}
		}

		if( $groupcd == 'system'){
			// SSL 설정이 변경되어 기존에 저장되어 있던 환경변수가 아닌 각 도메인에 맞는 환경 변수로 변경 #24252 by hed
			$CI->load->library('ssllib');
			$CI->ssllib->init_ssl_config($returnArr);
		}
		if (! is_cli() && $CI->config_system['file_cache'] == 'Y') {
			$CI->cache->save($cache_item_id, $returnArr, $CI->config->item('cache_ttl'));
		}
	}

	return $returnArr;
}

// 설정저장
function config_save($type,$ar_data){
	$CI =& get_instance();
	$tmpTime = time();
	foreach($ar_data as $key=>$value){
		if($value || $value =='0'){
			if( is_array($value) ) $value = serialize($value);
			$date = date('Y-m-d H:i:s',$tmpTime);
			$query = "REPLACE INTO fm_config (`groupcd`,`codecd`,`value`,`regist_date`) VALUES(?,?,?,?)";
			$CI->db->query($query,array($type,$key,$value,$date));
			$tmpTime++;
		}else{
			$query = "DELETE FROM `fm_config` WHERE `groupcd`=? and `codecd`=?";
			$query = $CI->db->query($query,array($type,$key));
		}
	}
	// cache clear
	cache_clean('config');
}

function config_save_array($type,$ar_data){
	$CI =& get_instance();
	$tmpTime = time();
	foreach($ar_data as $key=>$value){
		if($value || $value =='0'){
			if( is_array($value) ) $value = serialize($value);
			$date = date('Y-m-d H:i:s',$tmpTime);
			$replacequery .= "('".$type."','".$key."','".$value."','".$date."'),";
			$tmpTime++;
		}else{
			$query = "DELETE FROM `fm_config` WHERE `groupcd`=? and `codecd`=?";
			$query = $CI->db->query($query,array($type,$key));
		}
	}
	if($replacequery) {
		$replacequeryNew = substr($replacequery,0,strlen($replacequery)-1);
		//debug_var("REPLACE INTO fm_config (`groupcd`,`codecd`,`value`,`regist_date`) VALUES ".$replacequeryNew);
		$CI->db->query("REPLACE INTO fm_config (`groupcd`,`codecd`,`value`,`regist_date`) VALUES ".$replacequeryNew);
	}
	// cache clear
	cache_clean('config');
}

// 설정초기화
function config_delete($type){
	$CI =& get_instance();
	$query = "DELETE FROM `fm_config` WHERE `groupcd`=?";
	$query = $CI->db->query($query,array($type));
	// cache clear
	cache_clean('config');
}

// 코드로드
function code_load($groupcd, $codecd='', $tail='') {
	$CI =& get_instance();
	if( $tail )	$groupcd .= $tail;
	$returnArr = false;
	$cache_item_id = sprintf('code_%s%s', $groupcd, $codecd ? '_' . $codecd : '');
	if ($CI->config_system['file_cache'] == 'Y') {
		$returnArr = $CI->cache->get($cache_item_id);
	}
	if ($returnArr === false) {
		$returnArr = array();
		$aWhere['groupcd'] = $groupcd;
		if ($codecd) {
			$aWhere['codecd'] = $codecd;
		}
		$query = $CI->db->select('codecd, value')
		->from('fm_code')
		->where($aWhere)
		->order_by('regist_date ASC, codecd ASC')
		->get();
		foreach ($query->result_array() as $row) {
			if (preg_match('/a:/',$row['value'])) {
				if ($groupcd!='currency_symbol') {
					$row['value'] = strip_slashes($row['value']);
				}
				$row['value'] = unserialize($row['value']);
			}
			$returnArr[] = $row;
		}
		if (! is_cli() && $CI->config_system['file_cache'] == 'Y') {
			$CI->cache->save($cache_item_id, $returnArr, $CI->config->item('cache_ttl'));
		}
	}

	return $returnArr;
}

// 코드목록에서 찾기
function code_search($codes=array(),$codecd){
	foreach($codes as $value){
		if($value['codecd']==$codecd) return $value['value'];
	}
	return null;
}

// 코드저장
function code_save($type,$ar_data){
	$CI =& get_instance();
	foreach($ar_data as $key=>$value) {
		if($value){
			if( is_array($value) ) $value = serialize($value);
			$value = addslashes($value);
			$query = "REPLACE INTO fm_code (`groupcd`,`codecd`,`value`,`regist_date`) VALUES(?,?,?,now())";
			$CI->db->query($query,array($type,$key,$value));
		}else{
			$query = "DELETE FROM `fm_code` WHERE `groupcd`=? and `codecd`=?";
			$query = $CI->db->query($query,array($type,$key));
		}
	}
	// cache clear
	cache_clean('code');
}

// 코드초기화
function code_delete($type, $code=''){
	$CI =& get_instance();
	$CI->db->where('groupcd', $type);
	if($code != '') $CI->db->where('codecd', $code);
	$CI->db->delete('fm_code');
	// cache clear
	cache_clean('code');
}


// 불법접근을 막도록 토큰을 생성하면서 토큰값을 리턴
function get_token() {
	$CI =& get_instance();

	$token = md5(uniqid(rand(), TRUE));
	$CI->session->set_userdata('ss_token', $token);

	return $token;
}

// POST로 넘어온 토큰과 세션에 저장된 토큰 비교
function check_token($url=FALSE) {
	$CI =& get_instance();
	// 세션에 저장된 토큰과 폼값으로 넘어온 토큰을 비교하여 틀리면 에러
	if ($CI->input->post('token') && $CI->session->userdata('ss_token') == $CI->input->post('token')) {
		// 맞으면 세션을 지운다. 세션을 지우는 이유는 새로운 폼을 통해 다시 들어오도록 하기 위함
		$CI->session->unset_userdata('ss_token');
	}
	else
		alert('Access Error',($url) ? $url : $CI->input->server('HTTP_REFERER'));

	// 잦은 토큰 에러로 인하여 토큰을 사용하지 않도록 수정
	// $CI->session->unset_userdata('ss_token');
	// return TRUE;
}

function check_wrkey() {
	$CI =& get_instance();
	$key = $CI->session->userdata('captcha_keystring');
	if (!($key && $key == $CI->input->post('wr_key'))) {
		$CI->session->unset_userdata('captcha_keystring');
	    alert('정상적인 접근이 아닙니다.', '/');
	}
}

/**
* AES-KEY VALUES FOR USE
* @Disable exception handling is required shopSno
*/
function get_shop_key(){
	$CI =& get_instance();
	$system = ($CI->config_system)?$CI->config_system:config_load('system');
	$key = base64_encode($system['shopSno']);
	return $key;
}

function get_encrypt_qry($field_nm){
	$key = get_shop_key();
	$qry = " {$field_nm} = HEX(AES_ENCRYPT({$field_nm}, '{$key}')) ";
	return $qry;
}

function get_encrypt_where($field_nm, $data){
	$key = get_shop_key();
	$qry = " {$field_nm} = HEX(AES_ENCRYPT('{$data}', '{$key}')) ";
	return $qry;
}

function get_rows($table_nm, $where=array()){
	$CI =& get_instance();
	if($where) $CI->db->where($where);
	$query = $CI->db->get($table_nm);
	$count = $query->num_rows();
	return $count;
}

function get_data($table_nm, $where_arr=array()){
	$CI =& get_instance();
	$data = "";
	if($where_arr) $CI->db->where($where_arr);
	$query = $CI->db->get($table_nm);
	foreach ($query->result_array() as $row){
		$data[] = $row;
	}
	return $data;
}

/* 디렉토리 용량 체크 */
function Yget_dir_size($dir, $debug=false){
	if (!is_dir($dir)) return false;
	if (!preg_match("`/$`", $dir)) $dir .= '/';
	$get_size = 0;
	$d = dir($dir);
	while (false !== ($entry = $d->read())) {
		if (substr($entry, 0, 1) == '.') continue;

		if (is_file($dir . $entry)) {
			$get_size += filesize($dir . $entry);
			if ($debug == true) echo $dir . $entry . ' ' . filesize($dir . $entry) . "<br>\n";
		}
		else if (is_dir($dir . $entry)){
			$get_size += Yget_dir_size($dir . $entry, $debug);
		}
		else{
			continue;
		}
 	}
	$d->close();
	return $get_size;
}

// 종류에 따른 시작일과 종료일 계산
function getRangeDate($kind, $type = ''){
	switch($kind){
		case 'today':
			$return['s'] = $return['e'] = date('Y-m-d');
		break;
		case 'yesterday':
			$return['s'] = $return['e'] = date('Y-m-d', strtotime('-1 day'));
		break;
		case 'calendar_thisweek':	// 달력기준 ( 일 ~ 토 )
			$return['s']	= date('Y-m-d', strtotime('-' . (date('w') - 1) . ' day'));
			$return['e']	= date('Y-m-d', strtotime('+' . (6 - date('w')) . ' day'));
		break;
		case 'calendar_lastweek':	// 달력기준 ( 일 ~ 토 )
			$return['s']	= date('Y-m-d', strtotime('-' . (7 + date('w')) . ' day'));
			$return['e']	= date('Y-m-d', strtotime('-' . (date('w') + 1) . ' day'));
		break;
		case 'work_thisweek':		// 회계기준 ( 월 ~ 일 )
			$return['s']	= date('Y-m-d', strtotime('-' . (date('N') - 1) . ' day'));
			$return['e']	= date('Y-m-d', strtotime('+' . (7 - date('N')) . ' day'));
		break;
		case 'work_lastweek':		// 회계기준 ( 월 ~ 일 )
			$return['s']	= date('Y-m-d', strtotime('-' . (6 + date('N')) . ' day'));
			$return['e']	= date('Y-m-d', strtotime('-' . date('N') . ' day'));
		break;
		case 'thismonth':
			$return['s']	= date('Y-m-01');
			$return['e']	= date('Y-m-') . date('t');
		break;
		case 'lastmonth':
			$return['s']	= date('Y-m-01', strtotime('-1 month'));
			$return['e']	= date('Y-m-', strtotime('-1 month')) . date('t', strtotime('-1 month'));
		break;
	}

	if	($type)	return $return[$type];
	else		return $return;
}

/**
 * @author lwh
 * param : code
 * 서비스별 code에 따른 return 값을 지정하여준다. -> echo가 기본이나 특정코드는 return으로 지정
 * library 에 해당 code값이 지정되어있는지 필히 확인
 * 타입A는 true 및 값이 있으면 제한 / false 면 통과
 */
function serviceLimit($code, $type='echo')
{
	$CI =& get_instance();
	$CI->load->library('servicecheck');
	$CI->load->helper('javascript');

	if(!$code) {
		echo 'not find code';
		return false;
	};
	$result = $CI->servicecheck->service_limit($code);

	// 특정 코드 출력 타입 고정
	$LIMIT_TYPE = substr($code,0,1);
	if(($LIMIT_TYPE == 'H' || $LIMIT_TYPE == 'S') && $type == 'echo'){
		$type = 'return';
	}

	if			($type == 'return'){
		return $result;
	}else if	($type == 'process'){
		if($result){
			pageRedirect('../', '잘못된 접근입니다.', 'top'); exit;
		}else if($result){
			echo '[' . $code . '] 코드오류'; exit;
		}
	}else if	($type == 'echo'){
		echo $result;
	}
}

// _GET PARAMETER에 대한 보안 강화
function chk_parameter_xss_clean($val, $type = 'string'){
	$targets	= array('<', '>', '(', ')', '{', '}', "'", '"', ';');
	$codes		= array('&lt;', '&gt;', '&#40;', '&#41;', '&#123;', '&#125;', '&#39;', '&#34;', '&#59;');
	if	($type == 'string'){
		$val		= str_replace($targets, $codes, $val);
	}else{
		if	($val) foreach($val as $k => $v){
			$val[$k]	= str_replace($targets, $codes, $v);
		}
	}

	return $val;
}

// 혹시 모를 서비스 체크 함수 오류로 인해 임시 :: 2016-12-08 lwh
function solutionServiceCheck($chkAllowCode = NULL, $chkExceptCode = NULL, $service_code = SERVICE_CODE){
	$CI =& get_instance();
	//$CI->load->library('service');
	$CI->load->library('servicecheck');

	if		($chkAllowCode)		$code = 'S'.$chkAllowCode;
	else if ($chkExceptCode)	$code = 'SN'.$chkExceptCode;
	return $CI->servicecheck->service_limit($code);
}

/**
* @2015-10 라이선스보안작업
**/

/* 가비아 통신처리시에는 건너뜀 */
$nofilear	= array('_batch', '_firstmallplus', '_gabia', 'dev', 'cron', '_batch.php', '_firstmallplus.php', '_gabia.php', 'dev.php', 'cron.php');
$argvstring	= $_SERVER['argv'];
$uristring	= explode('/',$_SERVER['REQUEST_URI']);

if( ($argvstring && !in_array($argvstring[1], $nofilear) && !in_array($argvstring[2],$nofilear)) || (!$argvstring && $uristring && !in_array($uristring[1],$nofilear))) {
	$CI =& get_instance();
	if( empty($CI->environment) ) $CI->load->helper('environment');
	if( empty($CI->dbenvironment) ) $CI->load->library('dbenvironment');
	if( !$errorcode && !function_exists('checkEnvironmentValidation') )					$errorcode = "1003";
	if( !$errorcode && !class_exists('dbenvironment') )									$errorcode = "1103";
	if( !$errorcode && !method_exists("dbenvironment", "checkDBEnvironmentValidation"))	$errorcode = "1103";
	if( !$errorcode ) checkEnvironmentValidation();
	if( !$errorcode && !($CI->firstmallplusenv === true) )								$errorcode = "1003";
	if( !$errorcode && !($CI->firstmallplusdbenv === true) )							$errorcode = "1103";
	if( !$errorcode ) $CI->dbenvironment->checkDBEnvironmentValidation();
	if( !$errorcode && !($CI->firstmallplusdbenvvalidation === true) )					$errorcode = "1103";
	if( $errorcode ) shopErrorScreen($errorcode);
}
/**
* @2015-10 라이선스보안작업
**/

// 자사사이트에서 사용하는 샵번호 인코딩 신규 추가 :: 2019-08-26 pjw
// 샵번호 인코딩 용도로 사용
function optimusEncode($shopsno){
	// 기본 변수 세팅
	$MAX_INT	= 2147483647;
	$prime		= 402764083;
	$inverse	= 1071755771;
	$xor		= 1013035884;
	$mode		= PHP_INT_SIZE === 4 ? 'gmp' : 'native';

	// 번호로 넘어오지 않는 경우 블락
	if (! is_numeric($shopsno))	return false;

	// 암호화 후 리턴
	switch ($mode) {
		case 'gmp':
			return (gmp_intval(gmp_mul($shopsno, $prime)) & $MAX_INT) ^ $xor;
		default:
			return (((int) $shopsno * $prime) & $MAX_INT) ^ $xor;
	}
}

// JWT 키
function get_jwt_key() {
	$JWT_SECRET = 'f!ir@3s4tbm@a5l$#l*';
	return md5($JWT_SECRET. get_shop_key());
}

/**
* secretKey 검증
*/
function isSecretCorrect($secret,$timestamp) {
	$CI =& get_instance();
	$system = ($CI->config_system)?$CI->config_system:config_load('system');
	$addService = config_load('additionService');

	$api_key = $addService['api_key'];
	$storekey = 'f'.$system['shopSno'];

	if($secret == hash('sha256', $api_key.$storekey.$timestamp)) return true;
	else return false;
}

/*
	interface sample file link
*/
function get_interface_sample_path($filename, $dir = 'excel_sample')
{
	return 'https://interface.firstmall.kr/' . $dir . '/' . $filename;
}

/**
 * 사용가능한 PG
 * nation ('' : 국내PG,'all' : + 해외PG)
 */
function available_pg($arr=array())
{
	// 관리자에 노출된 순서
	$pgModule = ['kicc', 'inicis', 'lg', 'allat', 'kcp', 'kspay'];

	if ($arr['nation'] == 'all') {
		$pgForeign = ['paypal', 'eximbay'];
		$pgModule = array_merge($pgModule, $pgForeign);
	}

	return $pgModule;
}

/*
* type
	null : web or db checking
	pg : pg setting checking
* licenseType
	small type : one domain license free
	full type : one domain license free and remove the pg prefix
* freeDomains
	free license domain list
*/
function checkFreeLicense($type = '')
{
	// setting
	// small(domain), full(domain + pg)
	$licenseType = "";
	// domain setting
	// 정식도메인 ex) abc.co.kr
	// 임시도메인 ex) abc.firstmall.kr
	$freeDomains = [
	];

	if(!$licenseType){
		return false;
	}

	if($type == 'pg' && $licenseType == 'small')
	{
		return false;
	}


	return _checkFreeLicense($freeDomains);
}

/*
* free license check
*/
function _checkFreeLicense($domainList = array())
{
	$pattern_arr = array();
	foreach($domainList as $_domain)
	{
		$_domain 		= str_replace('.', '\.', str_replace('-', '\-', $_domain));
		$pattern_arr[] 	= '^'.$_domain;
		$pattern_arr[] 	= '[a-zA-Z-]*\.*[a-zA-Z-]*\.'.$_domain;
	}
	$pattern = implode("|", $pattern_arr);
	$subject = $_SERVER['HTTP_HOST'];
	$nResult = preg_match( '/'.$pattern.'/' , $subject, $matches);

	if($nResult!=(int)0)
	{
		$allowDomain = array();
		foreach ($matches as $key => $val)
		{
			$allowDomain = $val;
		}

		if ($subject==$allowDomain)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	else
	{
		return false;
	}
}

/*
Develop Server IPs
*/
function getDevServer($type='web')
{
	if ($type === 'web') {
		$return = [
			'121.78.197.230',
			'121.78.197.232',
			'139.150.80.140',
			'139.150.79.64',
			'10.9.68.42',
			'10.9.68.22'
		];
	} elseif($type === "db") {
		$return = [
			'121.78.114.51',
			'121.78.114.25'
		];
	}
	return $return;
}

/**
 * 그룹별 캐시 삭제
 * @param string $prefix
 */
function cache_clean($prefix = '')
{
	$CI =& get_instance();
	if (! $prefix) {
		return;
	}
	$caches = $CI->cache->cache_info();
	if (! $caches) {
		return;
	}

	if ( ! is_array($prefix)) {
		$aPreFix = array($prefix);
	} else {
		$aPreFix = $prefix;
	}

	foreach ($aPreFix as $sPreFix) {
		foreach ($caches as $id => $v) {
			if (strrpos($id, $sPreFix) === 0) {
				$CI->cache->delete($id);
			}
		}
	}
}

/**
 * 파일 캐시 조회
 * @param string $cache_item_id
 */
function cache_load($cache_item_id)
{
	$CI =& get_instance();
	if ($CI->config_system['file_cache'] == 'Y') {
		return $CI->cache->get($cache_item_id);
	} else {
		return false;
	}
}

/**
 * 파일 캐시 저장
 * @param string $cache_item_id
 */
function cache_save($cache_item_id, $data)
{
	$CI =& get_instance();
	if ( ! $cache_item_id || ! $data) {
		return false;
	}
	if ($CI->config_system['file_cache'] == 'Y') {
		$CI->cache->save($cache_item_id, $data, $CI->config->item('cache_ttl'));
	}
	return true;
}

// END
/* End of file basic_helper.php */
/* Location: ./app/helpers/basic_helper.php */