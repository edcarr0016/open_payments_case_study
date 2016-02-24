<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class OpenPayments extends Model
{
    //
    private static $endpoint = 'https://openpaymentsdata.cms.gov/resource/v3nw-usd7';
    private static $token = "pCmmvkrz2usJcMmrA9oaw0aIa";
    private static $user = "edc598@gmail.com";
    private static $pass = "C@s3studyp@ss";

    public static function getData($limit = null, $offset = null){
        $params = '';

        if(!is_null($limit) && !is_null($offset)){
            $params = '?$limit=' . $limit . '&$offset=' . $offset;
        }

        $request = curl_init();
        $headers = [
            'Accept: application/json',
            'X-App-Token: ' . self::$token
        ];

        curl_setopt($request, CURLOPT_URL, self::$endpoint . $params);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($request, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($request, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($request, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($request, CURLOPT_USERPWD, self::$user. ":" . self::$pass);

        $response = curl_exec($request);
        curl_close($request);

        return new Collection(json_decode($response, true));
    }
}
