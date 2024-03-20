<?php
#$result=parse_ini_file('myapi.ini',1);
#echo("<xmp>".print_r( $result,1)."</xmp>");

# yaml 파일로 저장
#yaml_emit_file('myapi.yaml',$result, YAML_UTF8_ENCODING);
//$result2=yaml_parse_file('docker-compose.yml');
//echo("<xmp>".print_r( $result2,1)."</xmp>");

# yaml 파일로 호출

$result2=yaml_parse_file('myapi.yaml');
echo("<xmp>".print_r( $result2,1)."</xmp>");
exit;
?>