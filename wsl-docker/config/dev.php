<?php
/*
	[개발용]
	- 설치본 만들때는 제외(삭제)됩니다.
*/
set_time_limit(0);
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH ."controllers/base/common_base".EXT);

class dev extends common_base {
	public function __construct() {

		parent::__construct();
		$this->load->library('dbenvironment');
	}

	public function index(){
		echo "<!DOCTYPE html>\n";
		echo "<html>\n";
		echo "<head>\n";
		echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />\n";
		echo "<title>Firstmall Dev</title>\n";
		echo "<script type='text/javascript' src='/app/javascript/jquery/jquery.min.js'></script>\n";
		echo "<script type='text/javascript' src='/app/javascript/jquery/jquery-ui.min.js'></script>\n";
		echo "</head>\n";
		echo "<body>\n\n";
		?>

			<fieldset style="margin-bottom:5px">
				서비스코드변경
				<select name="changeType" onchange="$.get('/dev/setServiceCode',{'type':$('select[name=changeType]').val()},function(res){$('.changeTypeResult').html(res);});">
					<option value="P_FREE" <?if($this->config_system['service']['code']=='P_FREE'){?>selected<?}?>>P_FREE</option>
					<option value="P_PREM" <?if($this->config_system['service']['code']=='P_PREM'){?>selected<?}?>>P_PREM</option>
					<option value="P_PRSC" <?if($this->config_system['service']['code']=='P_PRSC'){?>selected<?}?>>P_PRSC</option>
					<option value="P_STOR" <?if($this->config_system['service']['code']=='P_STOR'){?>selected<?}?>>P_STOR</option>
					<option value="P_STSC" <?if($this->config_system['service']['code']=='P_STSC'){?>selected<?}?>>P_STSC</option>
					<option value="P_EXPA" <?if($this->config_system['service']['code']=='P_EXPA'){?>selected<?}?>>P_EXPA</option>
					<option value="P_EXSC" <?if($this->config_system['service']['code']=='P_EXSC'){?>selected<?}?>>P_EXSC</option>
					<option value="P_ADVA" <?if($this->config_system['service']['code']=='P_ADVA'){?>selected<?}?>>P_ADVA</option>
					<option value="P_ADSC" <?if($this->config_system['service']['code']=='P_ADSC'){?>selected<?}?>>P_ADSC</option>
					<option value="P_AREN" <?if($this->config_system['service']['code']=='P_AREN'){?>selected<?}?>>P_AREN</option>
					<option value="P_ARSC" <?if($this->config_system['service']['code']=='P_ARSC'){?>selected<?}?>>P_ARSC</option>
				</select>
				<span class="changeTypeResult"></span>
			</fieldset>

			<form action="/dev/sample_insert" onsubmit="return confirm('카테고리,브랜드,상품 데이터가 초기화됩니다.');">
			<fieldset style="margin-bottom:5px">
				<button type="submit">샘플 데이터 INSERT (카테고리,브랜드,상품)</button>
			</fieldset>
			</form>

		<form>
			<fieldset>
				Service Key변경 &nbsp;&nbsp;&nbsp;&nbsp;
				<input type="text" name="shopSno" value="<?echo $this->config_system['shopSno'];?>"  size="40">
				<input type="button" name="" value="Service Key변경" onclick="$.get('/dev/setshopsno',{'shopSno':$('input[name=shopSno]').val()},function(res){$('span.changeTypeResult2').html(res)});" />
				<br/><span class="changeTypeResult2"></span>
			</fieldset>
			<fieldset>
				계정라이선스변경
				<input type="text" name="serial" value="<?echo $this->config_system['serial'];?>" size="40">
				<input type="button" name="" value="계정라이선스변경" onclick="$.get('/dev/setserial',{'serial':$('input[name=serial]').val()},function(res){$('span.changeTypeResult3').html(res)});" />
				(<?php echo md5($_SERVER['DOCUMENT_ROOT']."||".$_SERVER['SERVER_ADDR']."||".$this->config_system['shopSno']);?>)
				<br/><span class="changeTypeResult3"></span>
			</fieldset>
			<fieldset>
				DB라이선스변경 &nbsp;&nbsp;
				<input type="text" name="dbserial" value="<?echo $this->config_system['dbserial'];?>"  size="40">
				<input type="button" name="" value="DB라이선스변경" onclick="$.get('/dev/setdbserial',{'dbserial':$('input[name=dbserial]').val()},function(res){$('span.changeTypeResult4').html(res)});" />
(<?php echo md5($_SERVER['DOCUMENT_ROOT']."||".$_SERVER['SERVER_ADDR']."||".$this->db->username."||".$this->db->database."||".$this->config_system['shopSno']);?>)
				<br/><span class="changeTypeResult4"></span>
			</fieldset>
			<fieldset>
				서비스라이선스변경
				<br/><input type="text" name="serviceserial" value="<?echo $this->config_system['serviceserial'];?>" size="82">
				<input type="button" name="" value="서비스라이선스변경" onclick="$.get('/dev/setserviceserial',{'serviceserial':$('input[name=serviceserial]').val()},function(res){$('span.changeTypeResult5').html(res)});" />
				(NEW:<span id="NewServiceSerial"><?php echo $this->dbenvironment->getServiceSerial();?></span>)
				<br/><span class="changeTypeResult5"></span>
			</fieldset>

			<fieldset>
				[회원 개발 툴]
				<br/><input type="text" name="memberInsert" value="10">명
				<input type="button" name="" value="임의 회원 생성" onclick="$.get('/dev/memberInsert',{'memberInsert':$('input[name=memberInsert]').val()},function(res){$('span.changeTypeResult6').html(res)});" />
				<br/>
				<input type="button" name="" value="회원 초기화(모두 삭제)" onclick="if(confirm('취소 불가합니다. 처리하시겠습니까?')){$.get('/dev/memberReset',{},function(res){$('span.changeTypeResult6').html(res)});}" /><br/>
				
				<br/>
				member_seq : <input type="text" name="memberDormancy" size="1">
				<input type="button" value="강제 휴면 처리" onclick="$.get('/dev/memberDormancy',{'mode':'dormancy','memberSeq':$('input[name=memberDormancy]').val()},function(res){$('span.changeTypeResult6').html(res)});">
				<input type="button" value="해제처리"  onclick="$.get('/dev/memberDormancy',{'mode':'undormancy','memberSeq':$('input[name=memberDormancy]').val()},function(res){$('span.changeTypeResult6').html(res)});">

				<br/><span class="changeTypeResult6"></span>
			</fieldset>	

			<fieldset>
				[보안키+sms_id 입력] <a onclick="$('#systemServiceInfo').show();">▼</a>
				<br/>
				<div id="systemServiceInfo" style="display:none;"><xmp><?=print_r($this->config_system['service'],1);?></xmp></div>
				<input type="text" name="secuKey" value="<?=end(config_load('master','sms_auth'))?>" placeholder="보안키" size="30">
				<input type="text" name="sms_id"  value="<?=$this->config_system['service']['sms_id']?>" placeholder="sms_id">
				<input type="button" name="" value="입력" onclick="$.get('/dev/secuKeyUpdate',{'secuKey':$('input[name=secuKey]').val(),'sms_id':$('input[name=sms_id]').val()},function(res){$('span.changeTypeResult7').html(res)});" />
				<br/><span class="changeTypeResult7"></span>
			</fieldset>	
			<fieldset>
				[모든 데이터 검색]
				<br/>
				<input type="text" name="allDataSearch" placeholder="검색어입력 - 검색시간은 다소 오래 걸릴수도..." size="50">
				<input type="button" name="" value="검색(시간오래 걸림)" onclick="$.get('/dev/allDataSearch',{'str':$('input[name=allDataSearch]').val()},function(res){$('span.changeTypeResult8').html(res)});" />
				<br/><span class="changeTypeResult8"></span>
			</fieldset>	
		</form>
		<?
		echo "\n</body>\n";
		echo "</html>\n";
	}

	public function setshopsno(){ //라이선스보안점검
			config_save('system',array('shopSno'=>$_GET['shopSno']));
			$this->db->update('fm_manager_auth',['shopSno'=>$_GET['shopSno']]);
			$this->db->update('fm_admin_env',['shopSno'=>$_GET['shopSno']]);			
			echo "Service Key ".$this->config_system['shopSno']."=>".$_GET['shopSno'].'로 변경완료';
	}

	public function setserial(){
			config_save('system',array('serial'=>$_GET['serial']));
			echo "계정라이선스 ".$this->config_system['serial']."=>".$_GET['serial'].'로 변경완료';
	}

	public function setdbserial(){
			config_save('system',array('dbserial'=>$_GET['dbserial']));
			echo "DB라이선스 ".$this->config_system['dbserial']."=>".$_GET['dbserial'].'로 변경완료';
	}

	public function setserviceserial(){
			config_save('system',array('serviceserial'=>$_GET['serviceserial']));
			echo "서비스라이선스 ".$this->config_system['serviceserial']."=>".$_GET['serviceserial'].'로 변경완료';
	}

	public function setautoserial(){	
		config_save('system',array('serial'=>md5($_SERVER['DOCUMENT_ROOT']."||".$_SERVER['SERVER_ADDR']."||".$this->config_system['shopSno'])));
		config_save('system',array('dbserial'=>md5($_SERVER['DOCUMENT_ROOT']."||".$_SERVER['SERVER_ADDR']."||".$this->db->username."||".$this->db->database."||".$this->config_system['shopSno'])));
		config_save('system',array('serviceserial'=>$this->dbenvironment->getServiceSerial()));
		echo "* 개발용 라이선스 갱신 완료!";
	}

	public function setServiceCode(){
		config_save('system',array('service'=>array(
			'code'=>$_GET['type'],
			'name'=>$_GET['type'].' 개발용',
			'hosting_name'=>'슈퍼비지니스',
			'setting_date'=>date('Y-m-d'),
			'expire_date'=>date('Y-m-d',strtotime('+100 year')),
			'disk_space'=>1024000,
			'traffic'=>'무제한',
			'sms_id'=>$this->config_system['service']['sms_id'],
			'cid'=>'gsunsoft',
			'max_board_cnt'=>'5',
			'max_manager_cnt'=>'1',
		)));

		$this->load->helper('environment_helper');
		config_save('system',array('serial'=>getLicenseSerial()));
		$this->config_system	= config_load('system');
		config_save('system',array('serviceserial'=>$this->dbenvironment->getServiceSerial()));
		if	(preg_match('/SC$/', $_GET['type'])){
			config_save('scm', array('use' => 'Y'));
		}else{
			config_save('scm', array('use' => 'N'));
		}

		echo $_GET['type'].'로 변경완료';
	}

	// 테스트 데이터 삭제
	public function del_order()
	{
		$arr_order_seq[] = '2015080419060917530';
		$arr_order_seq[] = '2015080311281617530';
		$arr_order_seq[] = '2015080311303617531';
		$arr_order_seq[] = '2015081014445717531';
		$arr_order_seq[] = '2015081014435617530';


		$today = date("Y-m-d H:i:s");

		$tmp = array_unique($arr_order_seq);
		$wherein = "'".implode("','",$tmp)."'";

		$query = "delete from fm_goods_export_item where export_code in (select export_code from fm_goods_export where order_seq in (".$wherein."))";
		$this->db->query($query);
		$query = "delete from fm_goods_export where order_seq in (".$wherein.")";
		$this->db->query($query);
		$query = "update fm_order_item_option set step=15,step35=0,step45=0,step55=0,step65=0,step75=0 where order_seq in (".$wherein.")";
		$this->db->query($query);
		$query = "update fm_order_item_suboption set step=15,step35=0,step45=0,step55=0,step65=0,step75=0 where order_seq in (".$wherein.")";
		$this->db->query($query);
		$query = "update fm_order set step=15,deposit_date='',regist_date='".$today."' where order_seq in (".$wherein.")";
		$this->db->query($query);

		debug_var('테스트 주문상태 상품준비로 변경완료!');

	}

	// 마이그레이션 추가옵션 정산금액
	public function goods_suboption_commission_zero(){
		set_time_limit(0);

		$query = "select * from fm_goods_suboption where commission_rate = 0";
		$res = mysql_query($query);
		while($data_suboption = mysql_fetch_array($res)){
			$query = "update fm_order_item_suboption set commission_price=price where commission_price=0 and title='".$data_suboption['suboption_title']."' and suboption='".$data_suboption['suboption']."' and item_seq in (select item_seq from fm_order_item where goods_seq='".$data_suboption['goods_seq']."')";
			echo($query."<br/>");
			mysql_query($query);
		}

		echo "OK!";

	}

	public function set_license()
	{
		$out = getLicenseSerial();
		debug($out);
	}

	// 주문상태 마이그레이션
	public function migration_step()
	{
		$this->load->model('ordermodel');

		$query = "select item_option_seq from fm_order_item_option where step in ('55','65','75') limit 10";
		$res = mysql_query($query);
		while($data = mysql_fetch_array($res)){
			$this->ordermodel->set_option_step($data['item_option_seq'],'option');
		}

		$query = "select item_suboption_seq from fm_order_item_suboption where step in ('55','65','75')";
		$res = mysql_query($query);
		while($data = mysql_fetch_array($res)){
			$this->ordermodel->set_option_step($data['item_suboption_seq'],'suboption');
		}

		$query = "select order_seq from fm_order where step in ('55','65','75')";
		$res = mysql_query($query);
		while($data = mysql_fetch_array($res)){
			$this->ordermodel->set_order_step($data['order_seq'],'db');
		}

		echo "OK!";

	}

	// 마이그레이션 사은품의 배송일련번호
	public function migration_gift_order_shipping_seq()
	{
		set_time_limit(0);
		$query = "select o.item_option_seq,o.order_seq,i.provider_seq from fm_order_item_option o,fm_order_item i where o.item_seq=i.item_seq and (o.shipping_seq is null or o.shipping_seq='') and i.goods_type='gift'";
		$res = mysql_query($query);
		while($data = mysql_fetch_array($res)){
			$query = "select o.shipping_seq from fm_order_item_option o,fm_order_item i where o.item_seq=i.item_seq and o.shipping_seq and i.goods_type!='gift' and i.provider_seq=? and o.order_seq=? limit 1";
			$query = $this->db->query($query,array($data['provider_seq'],$data['order_seq']));
			list($row) = $query->result_array();
			$query = "update fm_order_item_option set shipping_seq=? where item_option_seq=?";
			$this->db->query($query,array($row['shipping_seq'],$data['item_option_seq']));
		}
		echo "OK!!";
	}

	// 마이그레이션 주문다운로드
	public function migration_exceldownload()
	{
		set_time_limit(0);

		$query = "update fm_exceldownload set update_date=regdate where update_date is null";
		mysql_query($query);

		$query = "select * from fm_exceldownload";
		$res = mysql_query($query);
		while($data = mysql_fetch_array($res)){
			$item = str_replace('delivery_company_code','delivery_company',$data['item']);
			if(preg_match('/recipient_info/',$item)) $item .= "recipient_info|".$item;
			if(preg_match('/export_item_seq/',$item)) $item .= "export_item_seq|".$item;
			$query = "update set `item`='".$item."' where seq='".$data['seq']."'";
			mysql_query($query);
		}
		echo "OK!!";
	}

	// 샘플 데이터 삽입
	public function sample_insert(){
		set_time_limit(0);

		//기존 데이터 삭제

		$this->db->query("truncate table fm_goods;");
		$this->db->query("truncate table fm_goods_addition;");
		$this->db->query("truncate table fm_goods_image;");
		$this->db->query("truncate table fm_goods_info;");
		$this->db->query("truncate table fm_goods_option;");
		$this->db->query("truncate table fm_goods_suboption;");
		$this->db->query("truncate table fm_goods_supply;");
		$this->db->query("truncate table fm_brand_link;");
		$this->db->query("truncate table fm_category_link;");

		$now_datetime = date('Y-m-d H:i:s');
		$arr_price = array(500,1000,3000,5000,10000,30000,50000,70000,100000);

		$arr_category = array();
		$sql = "select category_code from fm_category where level=2 order by category_code";
		$query = $this->db->query($sql);
		$result = $query->result_array();
		foreach($result as $row) $arr_category[] = $row['category_code'];

		$arr_brand = array();
		$sql = "select category_code from fm_brand where level=2 order by category_code";
		$query = $this->db->query($sql);
		$result = $query->result_array();
		foreach($result as $row) $arr_brand[] = $row['category_code'];

		$arr_category = array('0006');

		for($i=1;$i<400;$i++){

			unset($this->db->queries);
			unset($this->db->query_times);

			$price = $arr_price[rand(0,count($arr_price)-1)];
			$consumer_price = $price*1.5;
			$supply_price = $price*0.5;

			$goods_seq = $i+1;

			$data = array(
				'goods_seq'			=>	$goods_seq,
				'provider_seq'		=>	1,
				'provider_status'	=>	'1',
				'goods_name'		=>	"샘플 상품 ".($goods_seq),
				'goods_status'		=>	"normal",
				'goods_view'		=>	"look",
				'contents'			=>	"샘플 상품 ".($goods_seq),
				'option_use'		=>	"1",
				'option_view_type'	=>	"divide",
				'regist_date'		=>	$now_datetime,
				'default_consumer_price'	=> $consumer_price,
				'default_price'				=> $price,
			);

			$ar_keys=array();
			$ar_vals=array();
			foreach($data as $key=>$value) {
				$ar_keys[]="`$key` = ?";
				$ar_vals[]=$value?$value:'';
			}
			$sql = "insert into fm_goods set ".implode(" , ",$ar_keys);
			$this->db->query($sql,$ar_vals);

			$sql = "insert into fm_goods_option set
			goods_seq = '{$goods_seq}',
			default_option = 'y',
			consumer_price = '".$consumer_price."',
			price = '".$price."'
			";
			$this->db->query($sql);
			$option_seq = $this->db->insert_id();

			$sql = "insert into fm_goods_supply set
			goods_seq = '{$goods_seq}',
			option_seq = '{$option_seq}',
			supply_price = '".$supply_price."',
			stock = '100'
			";
			$this->db->query($sql);

			$category_code = $arr_category[rand(0,count($arr_category)-1)];
			$sql="insert into fm_category_link  set goods_seq='".$goods_seq."', category_code='".$category_code."', sort='".$goods_seq."', link=1;";
			$this->db->query($sql);

			$brand_code = $arr_brand[rand(0,count($arr_brand)-1)];
			$sql="insert into fm_brand_link  set goods_seq='".$goods_seq."', category_code='".$brand_code."', sort='".$goods_seq."', link=1;";
			$this->db->query($sql);

		}

		echo "OK!!";
	}

	public function view_log(){
		$order_seq = $_GET['no'];
		//2016090509093517530
		$this->load->model('paymentlog');
		debug($this->paymentlog->get_log($order_seq));
	}

	public function issue_test(){
		$this->load->model('scmbasicmodel');
		$cfg_priod['warehousing'] = "6개월";
		 $start_date = '2010-01-01';
		$startDateTime = $start_date.' 00:00:00';
		$sc['regist_date>='] = $startDateTime;
		$query = $this->scmbasicmodel->get_sorder_count($sc);
		echo end($this->db->queries);
		$query = $this->scmbasicmodel->get_warehousing_count($sc);
		echo end($this->db->queries);
	}

	public function getMakeStringRand($len = 5, $type = 'Aa0')	// 랜덤 문자 생성 함수
	{
		$lowercase = 'abcdefghijklmnopqrstuvwxyz';				# 소문자
		$uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';				# 대문자
		$numeric = '0123456789';													# 숫자
		$special = '`~!@#$%^&*()-_=+\\|[{]};:\'",<.>/?';	# 특수문자
		if (strpos($type,'0') > -1 || strpos($type,'1') > -1 ) $key .= $numeric;
		if (strpos($type,'a') > -1) $key .= $lowercase;
		if (strpos($type,'A') > -1) $key .= $uppercase;
		if (strpos($type,'$') > -1 || strpos($type,'@') > -1 ) $key .= $special;
		for ($i = 0; $i < $len; $i++) $token .= $key[mt_rand(0, strlen($key) - 1)];
		return $token;
	}
	public function randArray($array){
		return $array[mt_rand(0, count($array) - 1)];
	}

	public function randomID($prefix="",$subfix=""){
		return $prefix.hash("crc32b",time()).$subfix;
	}
	public $randomPersonSolt=0;
	public function randomPersonName($sex=0){
		
		// 성씨 배열
		$var['surnamesMajor'] = ["김","이","박"];
		$var['surnamesNormal'] = ["최", "정", "강", "조", "윤", "장", "임","한", "오", "서", "신", "권", "황", "안", "송", "류", "홍", "전", "고", "문", "양", "손", "배", "조", "백", "허", "남"];
		$var['surnamesMinor'] = ["제갈","제","갈", "황보", "심", "유", "노", "하", "전", "곽", "성", "차", "주","우", "구", "신", "임", "나", "민", "진", "지", "엄", "원", "채", "천", "마", "길"
			, "탁", "예", "서", "강", "피", "도", "소", "설", "기", "여", "추", "건", "위", "편", "변", "염", "계", "예", "동", "봉", "석", "맹", "변", "표", "명", "기", "반", "선", "후", "수"
			, "화", "호", "서", "시", "선", "양", "장", "모", "무", "견", "영", "금"];

		// 주요이름-남녀구분
		$var['nameMan'] = ["지훈","동현","현우","성민","정훈","영수","영호","영식","영길","정웅","영철","성수","성호","정호","정훈","성훈","성진","상훈","준호","동현","민수","민준","민재","서준","예준","주원","도윤"];	
		$var['nameWomen'] = ["영숙","정숙","정희","순자","영자","춘자","정순","정숙","영희","미숙","미경","경숙","은주","은정","은영","미영","지혜","지영","헤진","은정","수진","유진","민지","지은","지현","서연","수빈","지원","서윤","서현","지우","민서","하윤","지유","수연"];
		
		$var['middleNames'] = array(	// 중간돌림 배열
			"도", "영", "재", "준", "예", "지", "현", "승", "수", "우", "찬", "민", "태", "기", "시", "건", "진", "하", "희", "윤",	"원", "호", "대", "인", "훈", "유", "상", "철", "성", "동",
			"선", "석", "광", "창", "복", "길", "남", "종", "효", "일",	"미", "정", "규", "린", "빈", "소", "아", "라", "나", "림",	"헌", "온", "후", "애", "솔", "령", "람", "안", "무",
			"덕", "은", "언", "능", "곤", "룡", "명", "담", "옥", "학",	"수", "황", "연", "림", "봉", "혁", "묵", "별", "향", "사",	"완", "매", "관", "강", "역", "마", "용", "송", "평",
			"백", "반", "방", "배", "백", "범", "병", "보", "부", "비"
		);
		
		$var['lastNames'] = array(		// 마지막 배열
			"원", "희", "민", "수", "율", "지", "현", "승", "수", "우",	"찬", "민", "태", "기", "시", "건", "진", "하", "희", "윤",	"원", "호", "대", "인", "훈", "유", "상", "철", "성", "동",
			"선", "석", "광", "창", "복", "길", "남", "종", "효", "일",	"미", "정", "규", "린", "빈", "소", "아", "라", "나", "림",	"헌", "온", "후", "애", "솔", "령", "람", "안", "무",
			"덕", "은", "언", "능", "곤", "룡", "명", "담", "옥", "학",	"수", "황", "연", "림", "봉", "혁", "묵", "별", "향", "사",	"완", "매", "관", "강", "역", "마", "용", "송", "평",
			"백", "반", "방", "배", "백", "범", "병", "보", "부", "비"
		);

		$surnames = array_merge($var['surnamesMajor'],$var['surnamesNormal'],$var['surnamesMinor']);
		$surnames = array_merge($surnames,$var['surnamesMajor'],$var['surnamesMajor'],$var['surnamesMajor'],$var['surnamesMajor'],$var['surnamesMajor']);	// 가중치
		$surnames = array_merge($surnames,$var['surnamesMajor'],$var['surnamesMajor'],$var['surnamesMajor'],$var['surnamesMajor'],$var['surnamesMajor']);	// 가중치		
		$surnames = array_merge($surnames,$var['surnamesNormal'],$var['surnamesNormal'],$var['surnamesNormal'],$var['surnamesNormal'],$var['surnamesNormal']);	// 가중치
		$surnames = array_merge($surnames,$var['surnamesMinor']);	// 가중치

		$surname = $this->randArray($surnames);

		// 성별 처리
		if($sex==0) $sex=$this->randomPersonSolt%2;
		if($sex==1) $nameKey = 'nameMan';
		else $nameKey = 'nameWomen';
		if($this->randomPersonSolt % 9 == 0) $nameKey = '';
		
		if($nameKey == '') $name = $this->randArray($var['middleNames']).$this->randArray($var['lastNames']);	// 무작위로 성씨, 중간돌림, 마지막 이름을 선택합니다.
		else $name = $this->randArray($var[$nameKey]);

		$this->randomPersonSolt++;

		// TODO : 금지단어 제외 처리
		return $surname . $name;
	}

	public function memberInsert(){
		$param = $this->input->get();

		$this->load->library('memberlibrary');
		for($i=1;$i<=$param['memberInsert'];$i++){
			$userid = $this->getMakeStringRand(5,'a').str_pad($i,strlen($param['memberInsert']),'0',STR_PAD_LEFT);
			$user_name = $this->randomPersonName();
			$params[$i-1] = [
				"mtype" => "member"
				,"userid" => $userid
				,"password" => "1qaz2wsx!@"
				,"re_password" => "1qaz2wsx!@"
				,"user_name" => $user_name
				,"email" => "{$userid}@{$userid}.com"
				,"cellphone" => "010-0000-0000"
			];
			//$memberJoinMsg = $this->memberlibrary->join_member($params);
		}
		echo("<xmp>" . print_r($params, 1) . "</xmp>\n");
		foreach ($params as $memData) echo("<xmp>" . print_r($this->memberlibrary->join_member($memData), 1) . "</xmp>\n");
	}

	public function memberReset(){

		$this->db->truncate('fm_member_withdrawal');
		$this->db->truncate('fm_member_o2o');
		$this->db->truncate('fm_member_o2o_dr');
		$this->db->truncate('fm_member_group');
		$this->db->truncate('fm_member_business');
		$this->db->truncate('fm_membersns');
		$this->db->truncate('fm_delivery_address');
		$this->db->truncate('fm_member_dr');

		$this->db->truncate('fm_member_business_dr');
		$this->db->truncate('fm_membersns_dr');
		$this->db->truncate('fm_delivery_address_dr');
		$this->db->truncate('fm_member');
		$this->db->truncate('fm_member_dr');
	
		// 개인정보 보호 주문 분리
		/*$this->load->library('personalinfolibrary');
		if($this->personalinfolibrary->isPersonalInfoUse()) {
			$mbdata = $this->get_member_seq_only($params['member_seq']);
			$this->personalinfolibrary->separatePersonalInfo([$mbdata]);
		}*/
	}

	# 강제 휴면/해제
	public function memberDormancy(){
		$params = $this->input->get();
		switch($params['mode']){
			case 'dormancy':
				if(!empty($params['memberSeq'])){
					$this->db->query("UPDATE fm_member SET regist_date=DATE_ADD(NOW(), INTERVAL -1 YEAR),lastlogin_date=DATE_ADD(NOW(), INTERVAL -1 YEAR)
					WHERE member_seq={$params['memberSeq']}");
					//require_once(APPPATH ."controllers/batch/batchUser".EXT);
					//$this->dailyMember->dormancy_request();	
					echo "새창에서 휴면처리 됩니다.<br>memberSeq:{$params['memberSeq']} 회원 휴면 처리 완료!";
					exit("
					<script>
					alert('새창에서 휴면처리 됩니다. 단, 휴면 회원 사용 여부 설정이 [사용함]인 경우만 동작합니다.');					
					window.open('/batch/dailyMember/dormancy_request');
					//location.href='/batch/dailyMember/dormancy_request';
					</script>
					");
				}
				break;
			case 'undormancy':
				$this->load->model("membermodel");
				$result = $this->membermodel->dormancy_off($params['memberSeq']);		// 휴면회원 해제
				if($result !== false) echo "memberSeq:{$params['memberSeq']} 회원 휴면 해제 완료!";
				break;
		}
		
	}

	# 보안키+sms_id 입력
	public function secuKeyUpdate(){
		$params = $this->input->get();
		if(!empty($params['secuKey'])){
			config_save('master',array('sms_auth'=>$params['secuKey']));
			echo "<li>보안키 : {$params['secuKey']}</li>";
		}
		if(!empty($params['sms_id'])){
			$serviceConfig = $this->config_system['service'];
			$serviceConfig['sms_id'] = $params['sms_id'];
			config_save('system',array('service'=>$serviceConfig));	
			echo "<li>sms_id : {$this->config_system['service']['sms_id']} => {$params['sms_id']}</li>";
		}
		echo "설정이 저장되었습니다.";
		
	}

	# 모든 데이터 조회
	public function allDataSearch(){
		$str = $this->input->get('str');
		$sqry = " like '%{$str}%'"; //찾을 조건
		$table_schema = $this->db->database;
		$query = "select * from information_schema.COLUMNS where TABLE_SCHEMA = '".$table_schema."' ";		
		$result= $this->db->query( $query )->result_array();
		foreach($result as $d){
			$tables[$d['TABLE_NAME']] []= $d['COLUMN_NAME'];
		}

		ob_start();
		ob_implicit_flush(true);
		$r = str_repeat("\r", 4096 ); //화면 표시를 위한 버버채우기용 문자
		

		foreach( $tables as $tkey1=>$tval1 )
		{
		  foreach( $tval1 as $tkey2=>$tval2 )		
		  {
			// https://sdh.devbox.firstmall.kr/dev/allDataSearch
			$query = "select count(*) from ".$table_schema.".".$tkey1." where `".$tval2."`".$sqry." limit 1";
			$find = end(end($this->db->query( $query )->result_array()));
			
			if( $find > 0 )
			{
				$query = str_replace("count(*)","*",$query);
				$result = $this->db->query( $query )->result_array();
				echo "Table : <b>".$tkey1."</b> Column : <b>".$tval2."</b><br><br>";
				echo "{$query}<br>";debug($result);
				//echo("{$query}<br><xmp>".print_r($result,true)."</xmp>");
				echo $r ;		
				ob_flush();		
				usleep(100000);		
			}
		  }		
		}
		ob_end_flush();
	}
}
?>