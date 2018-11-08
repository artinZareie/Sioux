<?php

namespace Config;


define("BASE_URL", "http://localhost/");
define("APP_DIR", __DIR__ . "/../");
define("PUBLIC_DIR", __DIR__ . "../../public/");
define("CSS_DIR", __DIR__ . "../../public/css/");
define("JS_DIR", __DIR__ . "../../public/js/");
define("IMAGE_DIR", __DIR__ . "../../public/image/");

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