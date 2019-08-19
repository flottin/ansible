<?php

// bdd
$dsn = 'mysql:host=172.17.0.1';
$user = 'root';
$password = 'bb';

try {
    $dbh = new PDO($dsn, $user, $password);
    $_SERVER['MYSQL_PDO'] = 'OK';
} catch (PDOException $e) {
    $_SERVER['MYSQL_PDO'] = 'ERROR : Connexion échouée : ' . $e->getMessage();
}

// datas
$path = "../data";
$pathData = $path . '/data-json.txt';
if (!file_exists($path)){
    mkdir($path);
}


$config = [
    'module1' => [
        'param1' => 'val 1',
        'param2' => 'val 2',
        'param3' => 'val 3',
        'param4' => 'val 4',
    ],

    'module2' => [
        'param1' => 'val 1',
        'param2' => 'val 2',
        'param3' => 'val 3',
        'param4' => 'val 4',
    ]
];
file_put_contents($pathData, json_encode($config) );
$_SERVER['PATH_DATA'] = 'ERROR';
if (file_exists($pathData)){
    $_SERVER['PATH_DATA'] = realpath($pathData);
}

// display
$style = '<style>html{color: #2e303e; background-color: #EAEAEA}</style>';
$pre = '<pre>';
$sep = '<br>';
if (strstr($_SERVER['HTTP_USER_AGENT'], 'curl')){
    $pre = '';
    $sep = "\n";
    $style = '';
}

$out = $pre;
foreach ($_SERVER as $key => $val){
    $out .=  '   ';
    $out .=  str_pad($key, 40);
    $out .=  ' | ';
    $out .=  trim(str_replace("<address>", "", $val));
    $out .= $sep;


}
echo $out;
echo $style;
