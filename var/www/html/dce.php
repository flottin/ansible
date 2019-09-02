<?php

//$p = 'archivename.zip';
$p = 'DCE TEST FF.zip';
//$p = 'test.zip';

$result = dce::getDceListing($p);
$pathTmp = '';
echo "<pre>";
$sep = "\n";

foreach ($result as $res){
    $path =  implode("/", $res['path']) ;
    $deep = count($res['path']);
    if ($pathTmp !== $path){
        echo str_repeat('   ', $deep - 1);
        if (count($res['path']) > 0) {
            $exclude = [];
            $count = 0;
            foreach ($res['path'] as $segment) {
                array_pop($excludePathTmp);
                if (!in_array($excludePathTmp, $exclude)){
                    echo $sep;
                    echo str_repeat('   ', $count);
                    echo '/';
                    echo $segment;
                }
                $count++;
            }
            $exclude [] = $excludePath;
            echo $sep;
        }
    }
    $pathTmp =  $path;
    if (count($res['path']) > 0){
        if(!empty($res['name']) ) {
            echo str_repeat('   ', $deep);
        }
    } else {
        echo $sep;
        echo '';
    }
    if(!empty($res['name']) ){
        echo '<input type="checkbox"> /';
        echo $res['name'] . ' - size : ' . $res['size'] . $sep;

    }
}

class dce
{
    public static function getDceListing($p){
        $zip = new ZipArchive;
        $res = $zip->open($p);
        $result = [];
        if ($res) {
            $i=0;
            while(!empty($zip->statIndex($i)['name']))
            {
                $name = $zip->statIndex($i)['name'];
                $size = $zip->statIndex($i)['size'];
                $pathExploded = explode('/', $name);
                $fileName = array_pop($pathExploded);
                if($zip->statIndex($i)['comp_size']>0) {
                    $result [] = [
                        'name' => $fileName,
                        'size' => $size,
                        'path' => $pathExploded,
                        'comp_method' => $zip->statIndex($i)['comp_method']
                    ];
                }
                $i++;
            }
        }
        return $result;
    }


    public static function getOrderedFilesForZip($dce_tmp_file, $deleteOriginalZip = false)
    {

        $zip = zip_open($dce_tmp_file);


        $dce_items = array();
        $dce_items_size = array();


        if (is_resource($zip)) {
            $index = 0;
            $i = 0;
            while ($zip_entry = zip_read($zip)) {
                $index++;
                $dce_items[$index] = Atexo_Config::toPfEncoding(self::formaterZipEntryName(zip_entry_name($zip_entry)));
                $dce_items_size[$index] = zip_entry_compressedsize($zip_entry);
                $i++;
            }
            zip_close($zip);
            if ($deleteOriginalZip) {
                Atexo_Files::delete_file($dce_tmp_file);
            }
        }
        $ordered_res = array();
        $root = '';
        asort($dce_items, SORT_NATURAL);
        foreach ($dce_items as $index_file => $one_file) {
            $tab = explode("/", $one_file);
            $profondeur = count($tab) - 1;
            $base_path = substr($one_file, 0, strrpos($one_file, '/'));
            // Hack très laid (LCH) pour le "bug" de l'affichage en arborescence
            if ($profondeur == 2 && $index_file == 1) {
                $ordered_res['__R__'][] = array("index" => 0,
                    "profondeur" => 0,
                    "base" => substr($base_path, 0, strpos($base_path, '/')),
                    "name" => substr($base_path, 0, strpos($base_path, '/')),
                    "type" => "folder");
            }
            if ($profondeur == 1 && $index_file == 1) {
                $ordered_res['__R__'][0] = array("index" => 0,
                    "profondeur" => 0,
                    "base" => $base_path,
                    "name" => $base_path,
                    "type" => "folder");
                $root = "$base_path/";
            }
            if ($root == $one_file && $root != '') {
                $ordered_res['__R__'][0]['index'] = $index_file;
                continue;
            }
            // remplissage du tableau ordered_res qui contient tous les fichiers et dossiers + des infos
            // d'affichage (profondeur etc.). La clé du tableau est le chemin du fichier ou dossier
            if (!$ordered_res[$base_path]) {
                $ordered_res[$base_path] = array();
            }
            if (substr($one_file, strlen($one_file) - 1, 1) == "/") {// terminé par un "/" => c'est un répertoire
                if (strrpos($base_path, '/')) {// si contient un "/" => sous-répertoire, on extrait la dernière partie après les /
                    $name = substr($base_path, strrpos($base_path, '/') + 1);
                } else {
                    $name = $base_path; // pas de "/", c'est le dossier racine
                }
                // on ajoute l'élément à la fin du tableau ordered_res
                $ordered_res[$base_path][] = array("index" => $index_file,
                    "profondeur" => $profondeur - 1,
                    "base" => substr($one_file, 0, strrpos($one_file, '/')),
                    "name" => $name,
                    "type" => "folder");
            } else {// non terminé par un "/" => c'est un fichier
                $taille = $dce_items_size[$index_file];
                $tailleOctet = $taille;
                $taille = ceil($taille / 1000);
                if (strrpos($one_file, '/')) {// fichier dans un ré&pertoire
                    $name = substr($one_file, strrpos($one_file, '/') + 1, strlen($one_file));
                    $base = substr($one_file, 0, strrpos($one_file, '/') + 1);
                    if ($ordered_res['__R__'][0]['name'] != $base_path && empty($ordered_res[$base_path]) && $index_file > 1) {
                        $ordered_res[$base_path][] = array("index" => "x_$index_file", "base" => $base_path, "profondeur" => ($profondeur - 1), 'name' => "$base_path", "type" => "folder");
                    }
                } else {// fichier à la racine
                    $name = $one_file;
                    $base = "__R__";
                }
                // on ajoute l'élément à la fin du tableau ordered_res
                $ordered_res[$base_path][] = array("index" => $index_file,
                    "profondeur" => $profondeur,
                    "base" => $base,
                    "name" => $name,
                    "taille" => Atexo_Util::arrondirSizeFile($taille),
                    "tailleOctet" => $tailleOctet,
                    "type" => "file");
            }
        }
        return $ordered_res;
    }
}
