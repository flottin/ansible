<?php
require __DIR__ . '/vendor/autoload.php';

use Codedungeon\PHPCliColors\Color;

class Probe{
    protected $name;
    protected $status;
    protected $path = ' - ';
    protected $error = ' - ';

    /**
     * Probe constructor.
     * @param $name
     */
    public function __construct($name = null)
    {
        $this->name = $name;
        return $this;
    }

    public function run(){

        $status = false;
        if (function_exists('curl_version')){
            $status = $this->request($this->getPath());
        }
        $this->setStatus($status);
        return $this;
    }

    public function request($url){

        $ch = curl_init();

        // Configuration de l'URL et d'autres options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Récupération de l'URL et affichage sur le navigateur
        $return = curl_exec($ch);

        // Fermeture de la session cURL
        curl_close($ch);
        return (bool)$return;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param $path
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param string $error
     */
    public function setError($error)
    {
        $this->error = $error;
    }
}

class ProbeSpace extends Probe{
    public function isSpaceEnough(){
        return parent::run();
    }

    public function isMount(){
        return parent::run();
    }
}

class ProbeMysql extends Probe{
    public function run(){
        parent::setName(__CLASS__);
        // bdd
        $dsn = 'mysql:host=localhost';
        $user = 'root';
        $password = 'bb';
        $status = false;
        try {
            $dbh = new PDO($dsn, $user, $password);
            $status = true;
        } catch (PDOException $e) {
            parent::setError('Connexion échouée : ' . $e->getMessage());
        }
        return parent::setStatus($status);
    }
}

class ProbeData extends Probe{
    public function run(){

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
        $res = 'File not exists !';
        $status = false;
        if (file_exists($pathData)){
            $res = realpath($pathData);
            $status = true;
        }
        parent::setPath($res);
        parent::setName(__CLASS__);
        return parent::setStatus($status);

    }
}

class ProbePdo extends Probe{
    public function run(){
        parent::setName(__CLASS__);
        return parent::run();
    }
}

class ProbeZip extends Probe{
    public function run(){
        parent::setName(__CLASS__);
        return parent::run();
    }
}

class ProbeCurl extends Probe{
    public function run(){
        $status = function_exists('curl_version');
        parent::setStatus($status);
        parent::setName(__CLASS__);
        return $this;
    }
}


$res = [];
$res [] = (new ProbeMysql())->run();
$res [] = (new ProbeData())->run();
$res [] = (new ProbePdo())->run();
$res [] = (new ProbeZip())->run();
$res [] = (new ProbeCurl())->run();
$res [] = (new Probe('Fake'))->setPath('http://www.google.frFAKE')->run();
$res [] = (new Probe('Google'))->setPath('http://www.google.fr')->run();
$res [] = (new Probe('Dume'))->setPath('http://dume')->run();
$res [] = (new Probe('ApiEntreprise'))->setPath('http://api')->run();
$res [] = (new Probe('Tus'))->setPath('http://tus')->run();
$res [] = (new Probe('Arcade'))->setPath('http://arcade')->run();
$res [] = (new Probe('Crypto'))->run();
$res [] = (new Probe('Signature'))->run();
$res [] = (new Probe('Chorus'))->run();
$res [] = (new Probe('Atlas'))->run();
$res [] = (new ProbeSpace('Tmp'))->isSpaceEnough();



// display
$bul =  "\u{02055} ";
$redBullet =  "<span class=red>" . $bul . "</span>";
$greenBullet =  "<span class=green>" . $bul . "</span>";

$style = '<style>html{color: #2e303e; background-color: #EAEAEA} .green{color: green} .red{color: red}</style>';
$pre = '<pre>';
$sep = '<br>';
if (!isset($_SERVER['HTTP_USER_AGENT']) || strstr($_SERVER['HTTP_USER_AGENT'], 'curl')){
    $pre = '';
    $sep = "\n";
    $style = '';
    $redBullet =  Color::RED . $bul . Color::RESET;
    $greenBullet =  Color::GREEN . $bul . Color::RESET;
}

$out = $pre;


$out .= head("Probes", $sep);

foreach ($res as $probe){
    $bullet = $redBullet;
    if ($probe->getStatus() === true){
        $bullet = $greenBullet;
    }
    $out .= ' ' ;
    $out .= $bullet . ' | ' . str_pad($probe->getName(), 30, ' ') . ' | ' . $probe->getPath();
    $out .= $sep;

}


function head($str, $sep){
    $out =  $sep;
    $out .= str_repeat('-', 80);
    $out .=  $sep;
    $out .=  "     $str";

    $out .=  $sep;
    $out .=  str_repeat('-', 80);
    $out .=  $sep;
    return $out;
}

//$out .= head("Server Variables", $sep);
//foreach ($_SERVER as $key => $val){
//    $out .=  '   | ';
//    $out .=  str_pad($key, 30);
//    $out .=  ' | ';
//    $out .=  trim(str_replace("<address>", "", $val));
//    $out .= $sep;
//
//
//}
$out .= $style;

$out .= $sep;
echo $out;
