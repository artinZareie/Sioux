<?php


namespace App\Libraries;


class Response
{
    public static function json(array $data)
    {
        header('Content-Type:application/json');
        return json_encode($data);
    }

    public function xml(array $data, string $main_tag = 'root')
    {
        header('Content-Type:text/xml');
        $xml = new \SimpleXMLElement("<$main_tag/>");
        array_walk_recursive($data, array ($xml, 'addChild'));
        return $xml->asXML();
    }
}