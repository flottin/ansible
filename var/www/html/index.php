<html>

<style>

    html{
        font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
        background: black;
        color: darkgoldenrod;
    }
    span{
        font-weight: bold;
        color: darkgoldenrod;
    }

</style>

</html>


<?php

$separatorSize = 120;

echo "<br>\n";
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

echo '<pre>';
var_dump($_SERVER);
var_dump($_ENV);