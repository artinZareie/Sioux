<?php

namespace App\Libraries;

class Boof_lexer
{
    private $compress = false;
    private $startCode = '{{';
    private $endCode = '}}';
    private $index = 0;
    public $item = null;
    private $source = '';

    public function __construct($source, $startCode = '<%', $endCode = '%>')
    {
        $this->source = $source;
        $this->startCode = $startCode;
        $this->endCode = $endCode;

    }

    public function next()
    {
        if ($this->index == strlen($this->source)) {
            $this->item = null;
        } else {
            if (substr($this->source, $this->index, strlen($this->startCode)) == $this->startCode) {
                $data = $this->getLexerCode();
                if (count($data) == 0) {
                    $this->next();
                } else {
                    if ($data[0] == 'compress') {
                        $this->compress = true;
                        $this->next();
                    } elseif ($data[0] == 'decompress') {
                        $this->compress = false;
                        $this->next();
                    } else {
                        $this->item = [
                            'type' => 'code',
                            'value' => $data
                        ];
                    }
                }
            } else {
                $data = $this->getLexerText();
                if ($data == '') {
                    $this->next();
                } else {
                    $this->item = [
                        'type' => 'text',
                        'value' => $data
                    ];
                }
            }
        }
    }

    private function getLexerCode()
    {
        $out = [];
        $part = "";
        $isString = false;
        $isOperator = false;
        $operator = ['~', '!', '@', '#', '$', '%', '^', '&', '*', '=', '<', '>', '?', '\\', '/', '`', '+', '-'];
        $stringStarter = "";

        for ($i = $this->index + strlen($this->startCode); $i < strlen($this->source); $i++) {
            if (substr($this->source, $i, strlen($this->endCode)) == $this->endCode) {
                $this->index = $i + strlen($this->endCode);
                if ($part != '') {
                    if ($isString) {
                        $out[] = $part . $stringStarter;
                    } else {
                        $out[] = $part;
                    }
                }
                return $out;
            }

            $char = substr($this->source, $i, 1);
            if ($isString) {
                if ($char == $stringStarter) {
                    $out[] = $part . '\'';
                    $part = '';
                    $isString = false;
                } else {
                    $part = $part . $char;
                }
            } else {
                if ($char == ' ' || $char == "\n" || $char == "\r") {
                    if ($part != '') {
                        $out[] = $part;
                        $part = '';
                        $isOperator = false;
                    }
                } elseif ($char == "'" || $char == '"') {
                    $isString = true;
                    if ($part != '') {
                        $out[] = $part;
                        $part = '';
                        $isOperator = false;
                    }
                    $part = $part . '\'';
                    $stringStarter = $char;
                } else {
                    if ($isOperator) {
                        if (!in_array($char, $operator)) {
                            if ($part != '') {
                                $out[] = $part;
                                $part = '';
                            }
                            $isOperator = false;
                        }
                    } else {
                        if (in_array($char, $operator)) {
                            if ($part != '') {
                                $out[] = $part;
                                $part = '';
                            }
                            $isOperator = true;
                        }
                    }
                    $part = $part . $char;
                }
            }
        }
        if ($part != '') {
            if ($isString) {
                $out[] = $part . $stringStarter;
            } else {
                $out[] = $part;
            }
        }
        $this->index = strlen($this->source);
        return $out;
    }

    private function getLexerText()
    {
        $out = '';
        $start = strpos($this->source, $this->startCode, $this->index);
        if ($start === false) {
            $substr = substr($this->source, $this->index);
            if ($this->compress) {
                $substr = str_replace(["\r", "\n"], ' ', $substr);
                $substr = preg_replace('/\s+/', ' ', $substr);
            }
            $out = $substr;
            $this->index = strlen($this->source);
        } else {
            $substr = substr($this->source, $this->index, $start - $this->index);
            if ($this->compress) {
                $substr = str_replace(["\r", "\n"], ' ', $substr);
                $substr = preg_replace('/\s+/', ' ', $substr);
            }
            $out = $substr;
            $this->index = $start;
        }
        return $out;
    }

}

class Boof_parser
{
    private $startCode = '<%';
    private $endCode = '%>';
    private $lexer = null;

    public function __construct($startCode = '<%', $endCode = '%>')
    {
        $this->startCode = $startCode;
        $this->endCode = $endCode;
    }

    public function parse($source)
    {
        $this->lexer = new Boof_lexer($source, $this->startCode, $this->endCode);
        $this->lexer->next();
        $code = $this->parseBlock();
        $this->lexer = null;
        return $code;
    }

    private function parseBlock()
    {
        $out = [];
        while (!is_null($this->lexer->item)) {
            if ($this->lexer->item['type'] == 'text') {
                $out[] = $this->lexer->item['value'];
            } else {
                $code = $this->lexer->item['value'][0];
                switch ($code) {
                    case 'if':
                        $out[] = $this->parseIfFor();
                        break;
                    case 'for':
                        $out[] = $this->parseIfFor();
                        break;
                    case 'function':
                        $out[] = $this->parseFunction();
                        break;
                    case 'else':
                        return $out;
                        break;
                    case 'end':
                        return $out;
                        break;
                    default:
                        $out[] = [
                            'code' => $this->lexer->item['value']
                        ];
                        break;
                }
            }
            $this->lexer->next();
        }
        return $out;
    }

    private function parseIfFor()
    {
        $out = [
            'code' => $this->lexer->item['value'],
            'block' => [],
            'else' => []
        ];
        $this->lexer->next();
        $out['block'] = $this->parseBlock();
        if (!is_null($this->lexer->item)) {
            if ($this->lexer->item['value'][0] == 'else') {
                $this->lexer->next();
                $out['else'] = $this->parseBlock();
            }
        }
        return $out;
    }

    private function parseFunction()
    {
        if (count($this->lexer->item['value']) < 2) {
            return '';
        }
        $out = [
            'code' => $this->lexer->item['value'],
            'block' => []
        ];
        $this->lexer->next();
        $out['block'] = $this->parseBlock();
        return $out;
    }


}


class Boof_compiler
{

    private $startCode = '<%';
    private $endCode = '%>';
    private $path = '';
    private $cache = null;

    public function __construct($path, $cache = null, $startCode = '<%', $endCode = '%>')
    {

        $this->startCode = $startCode;
        $this->endCode = $endCode;
        $this->path = $path;
        $this->cache = $cache;
    }

    private $parser = null;

    public function compileSource($source)
    {
        if (is_null($this->parser))
            $this->parser = new Boof_parser($this->startCode, $this->endCode);
        return $this->parser->parse($source);
    }

    public function compileFile($name)
    {
        $viewfile = $this->path . DIRECTORY_SEPARATOR . str_replace('.', DIRECTORY_SEPARATOR, $name) . '.tpl';
        $cachefile = '';
        if (!is_null($this->cache)) {
            $cachefile = $this->cache . DIRECTORY_SEPARATOR . $name . '.json';
            if (file_exists($cachefile)) {
                $data = file_get_contents($cachefile);
                if ($data !== false) {
                    $code = @json_decode($data, true);
                    if ($code !== false) {
                        if (filemtime($cachefile) >= filemtime($viewfile)) {
                            return $code;
                        }
                    }
                }
            }
        }
        if (file_exists($viewfile)) {
            $data = file_get_contents($viewfile);
            if ($data !== false) {
                $code = $this->compileSource($data);
                if ($cachefile != '' and count($code) != 0) {
                    @file_put_contents($cachefile, json_encode($code));
                }
                return $code;
            }
        }
        return [];
    }
}

class Boof_VM
{
    private $bultins = [];
    private $templates = []; //iner function
    private $parent;
    private $layout = '';

    public function __construct($parent)
    {
        $this->parent = $parent;

        $this->bultins['='] = [$this, 'fun_equal'];

        $this->bultins['!'] = [$this, 'fun_html'];
        $this->bultins['?'] = [$this, 'fun_smallif'];
        $this->bultins['//'] = [$this, 'fun_comment'];
        $this->bultins['layout'] = [$this, 'fun_layout'];
        $this->bultins['include'] = [$this, 'fun_include'];
        $this->bultins['value'] = [$this, 'fun_value'];
        $this->bultins['set'] = [$this, 'fun_set'];
    }

    public function view($name, $env = [], $layoutEnv = [])
    {
        $code = $this->parent->compiler->compileFile($name);

        $this->layout = '';
        $content = $this->runBlock($code, $env);
        if ($this->layout != '') {
            $code = $this->parent->compiler->compileFile($this->layout);
            if (count($code) != 0) {
                $layoutEnv['content'] = $content;
                $content = $this->runBlock($code, $layoutEnv);
            }
        }
        return $content;
    }

    public function render($source, $env = [])
    {
        $code = $this->parent->compiler->compileSource($source);
        $content = $this->runBlock($code, $env);
        return $content;
    }

    private function runBlock(&$code, &$env = [])
    {
        $out = '';
        for ($i = 0; $i < count($code); $i++) {
            if (is_array($code[$i])) {
                $fun = $code[$i]['code'][0];
                if ($fun == 'for') {
                    $out .= $this->runFor($code[$i], $env);
                } elseif ($fun == 'function') {
                    $this->addTemplateFunction($code[$i], $env);
                } elseif ($fun == 'if') {
                    $out .= $this->runIf($code[$i], $env);
                } else {
                    $out .= $this->runItem($code[$i], $env);
                }
            } elseif (is_string($code[$i])) {
                $out .= $code[$i];
            }
        }
        return $out;
    }

    private function addTemplateFunction($item, &$vm)
    {
        if (count($item['code']) >= 2) {
            $this->templates[$item['code'][1]] = $item;
        }
    }

    private function isAssociative($arr)
    {
        if ([] === $arr)
            return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    private function runFor(&$item, &$env)
    {
        if (count($item['code']) == 4) {
            $var_name = $item['code'][1];
            $arr_name = $item['code'][3];
        } else {
            return '';
        }
        $arr = $this->getValue($arr_name, $env);
        if (is_array($arr)) {
            if (count($arr) == 0) {
                return $this->runBlock($item['else'], $env);
            } else {
                $out = '';
                $id = 0;
                $asso = $this->isAssociative($arr);
                $oldForIndex = null;
                if (isset($env['for_index']))
                    $oldForIndex = $env['for_index'];
                foreach ($arr as $ai => $av) {
                    if ($asso)
                        $env['for_index'] = $ai;
                    else
                        $env['for_index'] = $id;
                    $env[$var_name] = $av;
                    $out .= $this->runBlock($item['block'], $env);
                    $id++;
                }
                unset($env[$var_name]);
                if (!is_null($oldForIndex))
                    $env['for_index'] = $oldForIndex;
                else
                    unset($env['for_index']);
                return $out;
            }
        }
        return $this->runBlock($item['else'], $env);
    }

    private function runIf(&$item, &$env)
    {
        if (count($item['code']) == 2) {
            $var = $this->getValue($item['code'][1], $env);
            if ($var) {
                return $this->runBlock($item['block'], $env);
            } else {
                return $this->runBlock($item['else'], $env);
            }
        } elseif (count($item['code']) == 4) {
            $var1 = $this->getValue($item['code'][1], $env);
            $oper = $item['code'][2];
            $var2 = $this->getValue($item['code'][3], $env);
            $con = false;
            switch ($oper) {
                case '>':
                    $con = ($var1 > $var2);
                    break;
                case '<':
                    $con = ($var1 < $var2);
                    break;
                case '==':
                    $con = ($var1 == $var2);
                    break;
                case '!=':
                    $con = ($var1 != $var2);
                    break;
                case '>=':
                    $con = ($var1 >= $var2);
                    break;
                case '<=':
                    $con = ($var1 <= $var2);
                    break;
                case 'in':
                    $con = in_array($var1, $var2);
                    break;
            }
            if ($con) {
                return $this->runBlock($item['block'], $env);
            } else {
                return $this->runBlock($item['else'], $env);
            }
        } else {
            return '';
        }
    }

    private function runItem(&$item, &$env)
    {
        $fun = $item['code'][0];
        if (isset($this->parent->functions[$fun])) {
            return $this->runFunction($item, $env);
        } elseif (isset($this->bultins[$fun])) {
            return $this->runBultin($item, $env);
        } elseif (isset($this->templates[$fun])) {
            return $this->runTemplate($item, $env);
        } elseif ($fun == 'layout') {
            return $this->setlayout($item, $env);
        } else {
            $out = [];
            for ($i = 0; $i < count($item['code']); $i++) {
                $a = $this->getValue($item['code'][$i], $env);
                if (!is_array($a))
                    $out[] = $a;
            }
            return implode(' ', $out);
        }
    }

    private function runBultin(&$item, &$env)
    {
        $par = [];
        $par[] =& $env;
        for ($i = 1; $i < count($item['code']); $i++) {
            $par[] = $this->getValue($item['code'][$i], $env);
        }
        if ($item['code'][0] == 'set') {
            $par[1] = $item['code'][1];
        }
        $fun = $this->bultins[$item['code'][0]];
        if (is_callable($fun)) {
            return call_user_func_array($fun, $par);
        }
        return '';
    }

    private function runFunction(&$item, &$env)
    {
        $par = [];
        for ($i = 1; $i < count($item['code']); $i++) {
            $par[] = $this->getValue($item['code'][$i], $env);
        }
        $fun = $this->parent->functions[$item['code'][0]];
        if (is_callable($fun))
            return call_user_func_array($fun, $par);
        return '';
    }

    private function runTemplate(&$item, &$env)
    {
        $func = $this->templates[$item['code'][0]];
        if ((count($func['code']) - 1) < count($item['code'])) {
            return '';
        }
        $par = [];
        for ($i = 1; $i < count($item['code']); $i++) {
            $par[$func['code'][$i + 1]] = $this->getValue($item['code'][$i], $env);
        }
        return $this->runBlock($func['block'], $par);
    }

    private function getValue($name, $envs)
    {
        $first = substr($name, 0, 1);
        if ($first == '\'') {
            return substr($name, 1, -1);
        } elseif (is_numeric($name)) {
            return $name + 0;
        }
        if ($name == 'true') {
            return true;
        }
        if ($name == 'false') {
            return false;
        }

        if (!preg_match('/[a-zA-Z][a-zA-Z0-9\\.]*/', $name)) {
            return $name;//for operator
        }
        $parts = explode('.', $name);
        $parent = $envs;
        for ($i = 0; $i < count($parts); $i++) {
            if (isset($parent[$parts[$i]])) {
                $parent = $parent[$parts[$i]];
            } else {
                return '';
            }
        }
        return $parent;
    }

    public function fun_comment($env = [])
    {
        return '';
    }

    public function fun_equal($env, $p1 = 0, $p2 = '', $p3 = 0)
    {
        switch ($p2) {
            case '+':
                return $p1 + $p3;
                break;
            case '-':
                return $p1 - $p3;
                break;
            case '*':
                return $p1 * $p3;
                break;
            case '/':
                return $p1 / $p3;
                break;
            case '.':
                return $p1 . $p3;
                break;
            default:
                return $p1;
                break;
        }
    }

    public function fun_set($env, $p1 = '', $p2 = '=', $p3 = '', $p4 = '', $p5 = '')
    {
        $a = '';
        switch ($p4) {
            case '+':
                $a = $p3 + $p5;
                break;
            case '-':
                $a = $p3 - $p5;
                break;
            case '*':
                $a = $p3 * $p5;
                break;
            case '/':
                $a = $p3 / $p5;
                break;
            case '.':
                $a = $p3 . $p5;
                break;
            default:
                $a = $p3;
                break;
        }
        $env[$p1] = $a;

        return '';
    }

    public function fun_html($env, $a = '')
    {
        return htmlentities($a, ENT_QUOTES, "UTF-8");
    }

    public function fun_smallif($env, $con = false, $is = '', $els = '')
    {
        if ($con) {
            return $is;
        } else {
            return $els;
        }
    }

    public function fun_layout($env, $layoutname = '')
    {
        $this->layout = $layoutname;
    }

    public function fun_value($env, $obj, $value = '', $def = '')
    {
        if (isset($obj[$value]))
            return $obj[$value];
        return $def;
    }

    public function fun_include($env = [], $obj)
    {
        $code = $this->parent->compiler->compileFile($obj);
        return $this->runBlock($code, $env);
    }
}

class Boof
{
    public $functions = [];
    public $compiler;

    public function __construct($path = '', $cache = null, $startCode = '<%', $endCode = '%>')
    {
        $this->compiler = new Boof_Compiler($path, $cache, $startCode, $endCode);
    }

    public function view($name, $env = [], $layoutEnv = [])
    {
        $vm = new Boof_VM($this);
        return $vm->view($name, $env, $layoutEnv);
    }

    public function reander($source, $env)
    {
        $vm = new Boof_VM($this);
        return $vm->render($source, $env);
    }

    public function addFunction($name, $func)
    {
        $this->functions[$name] = $func;
    }
}

?>
