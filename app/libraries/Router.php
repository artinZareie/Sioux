<?php


namespace App\Libraries;


class Router
{
    public static $routes = [];
    public static $attr_groupes = [];
    private $options = [];
    public static $attr_subjects = ['prefix', 'name_prefix', 'middlewares', 'name'];

    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    public static function __callStatic($method, $params)
    {
        $method .= '_Method';

        $instance = new static();

        if (method_exists($instance, $method)) {
            return $instance->{$method}(...$params);
        }

        return make_error("Method Router::${method} doesn't exists");
    }

    public function __call($method, $params)
    {
        $method .= '_Method';
        if (method_exists($this, $method)) {
            return $this->{$method}(...$params);
        }

        return make_error("Method Router->${method} doesn't exists");
    }

    private function set_route(string $url, $response, array $methods, array $options = [])
    {
        $final_options = array_merge($options, $this->options);
        if (is_callable($response)) {
            array_push(self::$routes,
                ["callable" => $response, "uri" => $url, "methods" => $methods, "type" => "callable", 'attr' => $final_options]
            );
        } elseif (is_string($response)) {
            if (preg_match("/[a-zA-Z0-9_]+\@[a-zA-Z0-9_]+/", $response)) {
                $exploded = explode('@', rtrim($response, '/'));
                if (!isset($exploded[0]) && !isset($exploded[1])) {
                    make_error("Route <code>" . $url . "</code> controller method has an error", "the controller method must matches with /[.]+\@[.]+/");
                } else {
                    $controller = $exploded[0];
                    $method = $exploded[1];
                    array_push(self::$routes,
                        ["controller" => $controller, "method" => $method, "uri" => $url, "methods" => $methods, "type" => "controller", 'attr' => $final_options]
                    );
                }
            } else {
                make_error("Route <code>" . $url . "</code> controller method has an error", "the controller method must matches with /[.]+\@[.]+/");
            }
        } else {
            make_error("Route is not matches with Callable or Controller Method", "It should be a Clouser or A controller method like Controller@method");
        }
    }

    public function get_Method(string $url, $response, array $settings = [])
    {
        $this->set_route($url, $response, ["GET", "HEAD"], array_merge($this->options, $settings));
    }

    public function post_Method(string $url, $response, array $settings = [])
    {
        $this->set_route($url, $response, ["POST"], array_merge($this->options, $settings));
    }

    public function put_Method(string $url, $response, array $settings = [])
    {
        $this->set_route($url, $response, ["PUT", "PATCH"], array_merge($this->options, $settings));
    }

    public function patch_Method(string $url, $response, array $settings = [])
    {

        $this->set_route($url, $response, ["PATCH"], array_merge($this->options, $settings));
    }

    public function options_Method(string $url, $response, array $settings = [])
    {
        $this->set_route($url, $response, ["PATH"], array_merge($this->options, array_merge($this->options, $settings)));
    }

    public function delete_Method(string $url, $response, array $settings = [])
    {
        $this->set_route($url, $response, ["DELETE"], $settings);
    }

    public function all_Method(string $url, $response, array $settings = [])
    {
        $this->set_route($url, $response, ["GET", "POST", "PUT", "PATCH", "DELETE"], array_merge($this->options, $settings));
    }

    public function matches_Method(string $url, $response, array $methods, array $settings = [])
    {
        $this->set_route($url, $response, $methods, array_merge($this->options, $settings));
    }

    public function crud_Method(string $uri, string $controllerName, string $id_type = '\d?')
    {
        if (method_exists('App\\HTTP\\Controllers\\' . $controllerName, 'index')) {
            Router::get('/', $controllerName . '@index');
            Router::post('/', $controllerName . '@store');
            Router::delete('/(' . $id_type . ')', $controllerName . '@destroy');
            Router::put('/(' . $id_type . ')', $controllerName . '@edit');
        }
    }

    public function group_Method(array $options, \Closure $callable)
    {
        $correct_subjects = [];
        foreach ($options as $key => $val) {
            if ($key == 'name') {
                make_error('Can\'t set a unique name for all routes of a attr group', "You put a name for route group");
            } elseif (in_array($key, self::$attr_subjects)) {
                $correct_subjects[$key] = $val;
            } else {
                make_error("in route group , ${key} is not a attr subject");
            }
        }
        $me = new static(array_merge($options, $this->options));
        $callable($me);
    }

    public function redirect_Method(string $from, string $to, int $status = Response::HTTP_MOVED_PERMANENTLY, array $methods = ["GET", "POST", "PUT", "PATCH", "DELETE"])
    {
        $GLOBALS['____REDIRECTOR_STATUS____'] = $status;
        $GLOBALS['____REDIRECTOR_TO____'] = $to;
        Router::matches($from, function () {
            Response::status_code_message($GLOBALS['____REDIRECTOR_STATUS____']);
            redirect(url($GLOBALS['____REDIRECTOR_TO____']));
        }, $methods);
    }
}