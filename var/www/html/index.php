<html>
<style>

    html{
        font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;

        color: gray;
    }
    span{
        font-weight: bold;
        color: darkgoldenrod;
    }

</style>
</html>

<?php
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

$separatorSize = 120;
echo '<pre>';
echo "\n";
echo str_repeat('-', $separatorSize);
echo "<br>\n";
echo '<span>Deploy with ansible : ' . $_SERVER['HTTP_HOST'] . '</span>';
echo "<br>\n";
echo str_repeat('-', $separatorSize);
echo "<br>\n";
foreach($_SERVER as $k => $v)
{
    if (strstr($k, 'APP_')){
        echo "$k : $v";
        echo "<br>\n";
    }
}
echo str_repeat('-', $separatorSize);
echo "<br>\n";
echo "Config";
echo "<br>\n";
echo str_repeat('-', $separatorSize);
echo "<br>\n";
var_dump(json_decode(file_get_contents($pathData)));
echo "<br>\n";
echo str_repeat('-', $separatorSize);
echo "<br>\n";
echo "Global Variables";
echo "<br>\n";
echo str_repeat('-', $separatorSize);
echo "<br>\n";
var_dump($_SERVER);
var_dump($_ENV);