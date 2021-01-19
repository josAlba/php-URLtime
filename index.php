<?php

    require_once __DIR__ . '/vendor/autoload.php';

    include(__DIR__.'/src/db.php');
    include(__DIR__.'/src/metrics.php');

    $d = array(
        'memoria'   =>0,
        'tiempo'    =>0,
        'cadena'    =>0
    );

    $ROUTE = $argv[1];
    $ROUTE = str_replace('https://','',$ROUTE);
    $ROUTE = str_replace('http://','',$ROUTE);
    $ROUTE = str_replace('/','--',$ROUTE);
    $ROUTE = str_replace('.','-',$ROUTE);

    $m1 = \jos\metrics\memoria::memoria();
    $t1 = \jos\metrics\tiempo::getSeconds();
    $OK = false;

    try{

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $argv[1]);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $BUFFERO = curl_exec($ch);

        $curl_errno = curl_errno($ch);
        $curl_error = curl_error($ch);

        curl_close($ch);

        if($BUFFERO!=''){

           $OK = true;

        }

    }catch(Exception $e){

        echo "\n".$e;
        exit();
        
    }

    if($OK==false){
        echo "\n";
        exit();
    }

    $d['memoria'] = \jos\metrics\memoria::getMemoria($m1,\jos\metrics\memoria::memoria());

    $d['tiempo'] = \jos\metrics\tiempo::getTiempo($t1,\jos\metrics\tiempo::getSeconds());    

    $d['cadena'] = strlen($BUFFERO);

    \jos\database\influxStatic::send($d['tiempo'],'url-'.$ROUTE,array(
        'memoria'   =>$d['memoria'],
        'cadena'    =>$d['cadena']
    ));

    echo "\n";
        print_r($d);
    echo "\n";