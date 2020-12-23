<?php

namespace app\Helpers;

class Json
{
    static function deletar($path)
    {
        if(file_exists($path))
            unlink($path);
    }

    static function ler($path)
    {
        if(!file_exists($path))
            throw new \Exception("{$path} not exists");

        return json_decode(file_get_contents($path), true);
    }

    static function escrever($path, $params)
    {
        file_put_contents($path, json_encode($params));
        return self::ler($path);
    }
}
