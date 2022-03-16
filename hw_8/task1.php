<?php
function fileExplorer($path, $tab = null){
    $dir = new DirectoryIterator($path);
    foreach ($dir as $item){        
        if($item->isDot()) continue;
        echo $tab . $item . PHP_EOL;
        if($item->isDir()){
            $newPath = $path . "/" . $item;
            fileExplorer($newPath, "->".$tab);
        }
    }
}

$path = "./";
fileExplorer($path);