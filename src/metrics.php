<?php

namespace jos\metrics;

class memoria{

    public static function getMemoria($memo_ini,$memo_fin){

        //echo "Usados un máximo de: ".round(($memo_fin - $memo_ini)/(1024*1024),2). "Mb";
        return round(($memo_fin - $memo_ini)/(1024*1024),2);
    }

    public static function memoria(){

        return memory_get_peak_usage();

    }

}

namespace jos\metrics;

class tiempo{

    /**
     * Microsegundos.
     */
    public static function getSeconds(){

        list($useg, $seg) = explode(" ", microtime());
        return ((float)$useg + (float)$seg);

    }
    /**
     * Calcular tiempo.
     */
    public static function getTiempo($t1,$t2){
        return $t2-$t1;
    }

}