<?php

    $path = 'manga';
    $type = [1 => 'jpg', 2 => 'jpeg', 3 => 'png', 4 => 'gif'];
    $tab = [];

    $j = import_directory($path, $tab, $type);

    echo '<pre>';
    print_r($j);
    $fp = fopen('index.json', 'w');
    fwrite($fp, json_encode($j));
    fclose($fp);

    function import_directory($directory, $j, $type)
    {
        $files = scandir($directory);
        foreach ($files as $file){
            if ($file == '.' || $file == '..') continue;
            if (is_dir($directory.'/'.$file)){
                $t = import_directory($directory.'/'.$file, $j, $type);
                foreach($t as $c){
                    $ch = true;
                    foreach($j as $t){
                        if($t['plik'] == $c['plik']){
                            $ch = false;
                        }
                    }
                    if($ch){
                        if(!substr_count(basename($c['plik']), '_min')){
                            $ext = explode(".",basename($c['plik']));
                            if(isset($ext[1])) $ext = $ext[1];
                            if(in_array($ext,$type)){
                                $id = explode('/', $c['plik']);
                                $id = $id[(count($id)-2)];
                                array_push($j, ['id' => $id, 'plik' => $c['plik']]);
                            }
                        }
                    }
                }
            }else{
                $s = $directory . '/' . $file;
                $ch = true;
                foreach($j as $t){
                    if($t['plik'] == $s){
                        $ch = false;
                    }
                }
                if($ch) {
                    if(!substr_count(basename($s), '_min')) {
                        $ext = explode(".", basename($s))[1];
                        if (in_array($ext, $type)) {
                            $id = explode('/', $s);
                            $id = $id[(count($id) - 2)];
                            array_push($j, ['id' => $id, 'plik' => $s]);
                        }
                    }
                }
            }
        }
        return $j;
    }


?>

