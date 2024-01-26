<?php
$data = [
    'requert header'=>getallheaders(),
    'requert body'=>file_get_contents("php://input"),
    #'server'=>$_SERVER,
];
$print = print_r($data,1);
exit("<xmp>{$print}</xmp>");
?>