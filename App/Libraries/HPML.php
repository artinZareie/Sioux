<?php

namespace App\Libraries;


class HPML
{
    const TAG_FORMS = [
        'liner' => '<HPML_TAG_NAME HPML_TAG_OPTIONS />',
        'contentable' => '<HPML_TAG_NAME HPML_TAG_OPTIONS>HPML_TAG_CONTENT</HPML_TAG_NAME>'
    ];

    private static $registred_styles = [];
    private static $defult_registred_styles = [];
    private static $head_tag = '';

    public static function __callStatic($name, $arguments)
    {
        ini_set('xdebug.max_nesting_level', 300);
        if (substr($name, 0, 6) != 'front_')
            return self::front_tag($name, ...$arguments);
        else
            return self::{$name}(...$arguments);
    }

    public static function front_register_style(string $name, string $style)
    {
        self::$registred_styles[$name] = $style;
    }

    public static function front_register_style_by_file(string $file_directory)
    {
        if (file_exists(APP_DIR . 'HTTP' . SLASH . 'Views' . SLASH . 'registred_styles' . SLASH . $file_directory . '.css'))
            self::$registred_styles[$file_directory] = file_get_contents(APP_DIR . 'HTTP' . SLASH . 'Views' . SLASH . 'registred_styles' . SLASH . $file_directory . '.css');
        else
            make_error("File $file_directory.css in " . APP_DIR . 'HTTP' . SLASH . 'Views' . SLASH . 'registred_styles' . SLASH . " does not exists.");
    }

    public static function front_set_registred_style_as_defult($style_name)
    {
        if (isset(self::$registred_styles[$style_name]))
            array_push(self::$defult_registred_styles, self::$registred_styles[$style_name]);
        else
            make_error("This style is not registed yet!!!", "Fatal: The $style_name style is not registred!!!");
    }

    public static function front_get_registred_style(string $style_name): string
    {
        if (isset(self::$registred_styles[$style_name]))
            return '<style>' . self::$registred_styles[$style_name] . '</style>';
        else
            return make_error("This style is not registed yet!!!", "Fatal: The $style_name style is not registred!!!");
    }

    public static function front_get_all_registred_styles()
    {
        return self::$registred_styles;
    }

    public static function front_get_all_defult_registred_styles()
    {
        return self::$defult_registred_styles;
    }

    public static function front_starter_template(\Closure $content, array $html_tag_attr = []): string
    {
        $attr = "";
        foreach ($html_tag_attr as $key => $value) {
            if ($value instanceof \Closure) {
                $value = $value();
            }
            $value = str_replace('\'', '"', $value);
            $attr .= $key . "='" . $value . "'" . " ";
        }
        $tmp = '<!doctype html><html ' . $attr . ' >';
        $tmp .= self::$head_tag;
        $tmp .= $content();
        $tmp .= '</html>';
        return $tmp;
    }

    public static function front_tag(string $tag_name, array $attributes = [], \Closure $content): string
    {
        $attr_parser = '';
        $as_liner = false;
        foreach ($attributes as $key => $attribute) {
            if ($attribute instanceof \Closure) {
                $attribute = $attribute();
            }
            if ($key != 'HPML_LINER') {
                $attribute = str_replace('\'', '"', $attribute);
                $attr_parser .= $key . "='" . $attribute . "'" . " ";
            } elseif ($key == "HPML_LINER")
                $as_liner = $attribute == true;
        }
        if ($as_liner == false) {
            $tag_replaced_form = self::TAG_FORMS['contentable'];
            $tag_replaced_form = str_replace('HPML_TAG_NAME', $tag_name, $tag_replaced_form);
            $tag_replaced_form = str_replace('HPML_TAG_OPTIONS', $attr_parser, $tag_replaced_form);
            $tag_replaced_form = str_replace('HPML_TAG_CONTENT', $content(), $tag_replaced_form);
            return $tag_replaced_form;
        } elseif ($as_liner) {
            $tag_replaced_form = self::TAG_FORMS['liner'];
            $tag_replaced_form = str_replace('HPML_TAG_NAME', $tag_name, $tag_replaced_form);
            $tag_replaced_form = str_replace('HPML_TAG_OPTIONS', $attr_parser, $tag_replaced_form);
            return $tag_replaced_form;
        }
        return '';
    }

    public static function front_make_head_tag(\Closure $content)
    {
        $tmp = '';
        $tmp .= '<head>';
        foreach (self::$defult_registred_styles as $style) {
            $tmp .= "<style>$style</style>";
        }
        $tmp .= $content();
        $tmp .= '</head>';
        self::$head_tag = $tmp;
    }
}