<?php
// спагетти-код
class M_Product{
    public function showProduct($id,$countView=false){
        $sql = "SELECT countView FROM goods WHERE id=$id";
        $resCount = M_DB::getInstance() -> Select($sql);
        if($countView){
            $object = ['countView' => $resCount[0]['countView'] + 1];
            $where = "id=$id";
            $changedRow = M_DB::getInstance() -> Update('goods', $object, $where);
        }        
        $sql = "SELECT * FROM goods WHERE id=$id";
        $res = M_DB::getInstance() -> Select($sql);         
        return $res[0];
    }

    // используются разные стили написания переменных (camelCase и snake_case)
    public function resizePhoto($file, $pathSmallPhoto){ 
        $info = getimagesize($file);
        
        $max_width_size = 200;
        $max_height_size = 233;
        
        if ($info['mime'] == 'image/jpeg')
            $source = imagecreatefromjpeg($file);
        elseif ($info['mime'] == 'image/png')
            $source = imagecreatefrompng($file);
        elseif ($info['mime'] == 'image/gif')
            $source = imagecreatefromgif($file);
        else
            return false;
    
        
        $w_src = imagesx($source); 
        $h_src = imagesy($source);
    
        
        $w = $max_width_size;   // плохое название переменной
    
        // в обоих случаях вовращается pathSmallPhoto (излишне прописывать в каждом)
        if ($w_src > $w)
        {
            $ratio = $w_src/$w;
            $w_dest = @round($w_src/$ratio);    
            $h_dest = min(@round($h_src/$ratio),$max_height_size);
    
            $dest = @imagecreatetruecolor($w_dest, $h_dest);
    
            @imagecopyresampled($dest, $source, 0, 0, 0, 0, $w_dest, $h_dest, $w_src, $h_src);
    
            @imagejpeg($dest, $pathSmallPhoto, 90);
    
            @imagedestroy($dest);
            @imagedestroy($source);
    
            return $pathSmallPhoto;
    
        } else {
            
            @imagejpeg($source, $pathSmallPhoto, 90);
            @imagedestroy($source);
    
            return $pathSmallPhoto;
        }
    }

    // возвращаются разные типы данных
    public function editProduct($id,$info,$size,$photoTmpName){
        if($size == 0){
            unset($info['img']);
        }
        $where = "id=$id";
        $resUpdate = M_DB::getInstance() -> Update('goods', $info, $where);

        // запутанное ветвление
        if($resUpdate && $size != 0){
            $pathSmallPhoto = __DIR__ . '/../' .PATH_TO_SMALL_PHOTO."/".$info['img'];
            $pathBigPhoto = __DIR__ . '/../' .PATH_TO_BIG_PHOTO."/".$info['img'];
            if($this->resizePhoto($photoTmpName,$pathSmallPhoto) && move_uploaded_file($photoTmpName,$pathBigPhoto)){
                return $id;                             // тут возвращается число
            }
            return "error with file";                   // тут возвращается строка
        }elseif($resUpdate){
            return "Goods updated successfully";        // тут возвращается строка
        }

        return "error with editing product";            // тут возвращается строка
    }

    // возвращаются разные типы данных
    public function addProduct($info,$size,$photoTmpName){
        $resInsert = M_DB::getInstance() -> Insert('goods', $info);

        if($resInsert && $size != 0){
            $pathSmallPhoto = __DIR__ . '/../' .PATH_TO_SMALL_PHOTO."/".$info['img'];
            $pathBigPhoto = __DIR__ . '/../' .PATH_TO_BIG_PHOTO."/".$info['img'];
            if($this->resizePhoto($photoTmpName,$pathSmallPhoto) && move_uploaded_file($photoTmpName,$pathBigPhoto)){
                return $resInsert;          // тут возвращается число
            }
            return "error with file";       // тут возвращается строка
        }elseif($resInsert){
            return $resInsert;              // тут возвращается число
        }

        return "error with adding product"; // тут возвращается строка
    }

    public function deleteProduct($id){
        $sql = "SELECT * FROM goods WHERE id=$id";
        $res = M_DB::getInstance() -> Select($sql);
        $img = $res[0]['img'];

        $pathSmallPhoto = __DIR__ . '/../' .PATH_TO_SMALL_PHOTO."/".$img;
        $pathBigPhoto = __DIR__ . '/../' .PATH_TO_BIG_PHOTO."/".$img;

        $where = "id=$id";
        $res = M_DB::getInstance() -> Delete('goods', $where);

        if($res){
            if(unlink($pathSmallPhoto) && unlink($pathBigPhoto)){
                return "Goods deleted successfully";
            }
        }
        return "error";
    }
}