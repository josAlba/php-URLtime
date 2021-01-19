<?php

namespace jos\database;

class influxStatic{

    private static function ConfirmarConexion($url,$time=2){

        try{

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch,CURLOPT_TIMEOUT,$time);
            $BUFFERO = curl_exec($ch);

            $curl_errno = curl_errno($ch);
            $curl_error = curl_error($ch);

            curl_close($ch);

            if($curl_errno > 0){

                return false;

            }else{

                return true;
            }

        }catch(\Exception $e){

            
        }

        return false;

    }

    public static function send($valor,$measurement,$tags=[]){

        $host = "127.0.0.1";
        $port = "8086";

        //Comprobar conexion con influxdb.
        if(! static::ConfirmarConexion($host.':'.$port) ){
            
            return;
        }

        try{

            $client = new \InfluxDB\Client($host, $port);

            $database = $client->selectDB('b2b');

            $points[] = new \InfluxDB\Point(
                $measurement,           // name of the measurement
                $valor,                 // the measurement value
                [],                     // tags
                $tags,                  // extra
                time()                  // Time precision has to be set to seconds!
            );
            $database->writePoints($points,\InfluxDB\Database::PRECISION_SECONDS);

        }catch(\Exception $e){

            return;

        }
        
    }

}