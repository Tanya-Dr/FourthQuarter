<?php
// спагетти-код
class M_Catalog{
    public $countGoods;

    public function __construct(){
        $sql = "SELECT COUNT(*) AS count FROM goods";
        $res = M_DB::getInstance() -> Select($sql); 
        $this->countGoods = $res[0]['count'];
    }

    function getGoods($lastId = 0,$lastCountView){ 
        if($lastId == 0){
            $sql = "SELECT * FROM goods ORDER BY countView DESC, id ASC LIMIT 10";
        }else{
            $sql = "SELECT * FROM goods WHERE (countView = $lastCountView AND id > $lastId) OR (countView < $lastCountView) ORDER BY countView DESC, id ASC LIMIT 10";
        }           
        $res = M_DB::getInstance() -> Select($sql); 
        foreach($res as $row){
            $items[] = $row;
        }   
        return $items;
    }

    function getGoodsToEdit($lastId = 0){
        if($lastId == 0){                                                              //
            $sql = "SELECT * FROM goods ORDER BY id ASC LIMIT 5";                      // лишнее ветвление
        }else{                                                                         // 
            $sql = "SELECT * FROM goods WHERE id > $lastId ORDER BY id ASC LIMIT 5";   // 
        }           
        $res = M_DB::getInstance() -> Select($sql); 
        foreach($res as $row){
            $items[] = $row;
        }   
        return $items;
    }
}