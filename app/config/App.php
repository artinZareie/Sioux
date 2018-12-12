<?php

namespace App\Config;

// Directories
define("SLASH", DIRECTORY_SEPARATOR);
define("BASE_URL", "http://localhost/Sioux/");
define("MAIN_DIR", __DIR__ . SLASH . ".." . SLASH . ".." . SLASH);
define("APP_DIR", __DIR__ . SLASH . ".." . SLASH);
define("PUBLIC_DIR", __DIR__ . SLASH . ".." . SLASH . ".." . SLASH . "public" . SLASH);
define("CSS_DIR", PUBLIC_DIR . "css" . SLASH);
define("JS_DIR", PUBLIC_DIR . "js" . SLASH);
define("IMAGE_DIR", PUBLIC_DIR . "image" . SLASH);



class App
{
    /**
     * @param string $dir
     * @return string
     */
    public static function make_url(string $dir = ""): string
    {
        return BASE_URL . $dir;
    }

    public static function make_asset(string $asset = "", string $asset_type = "asset"): string
    {
        switch ($asset_type) {
            case "asset" :
                if (file_exists($asset))
                    return PUBLIC_DIR . SLASH . $asset;
                else
                    make_error("Asset not founded : ", "Asset not founded at " . PUBLIC_DIR . $asset);
                break;
            case "css" :
                if (file_exists(CSS_DIR . SLASH . $asset))
                    return CSS_DIR . SLASH . $asset;
                else
                    make_error("Asset not founded : ", "Asset not founded at " . CSS_DIR . SLASH . $asset);
                break;
            case "js" :
                if (file_exists(JS_DIR . SLASH . $asset))
                    return JS_DIR . SLASH . $asset;
                else
                    make_error("Asset not founded : ", "Asset not founded at " . JS_DIR . SLASH . $asset);
                break;
            case "image" :
                if (file_exists(IMAGE_DIR . SLASH . $asset))
                    return IMAGE_DIR . SLASH . $asset;
                else
                    make_error("Asset not founded : ", "Asset not founded at " . IMAGE_DIR . SLASH . $asset);
                break;
            default:
                make_error("Asset not founded : ", "Asset not founded at " . PUBLIC_DIR . SLASH . $asset);
                break;
        }
        return "";
    }
}