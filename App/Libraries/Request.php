<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11/17/2018
 * Time: 12:55 PM
 */

namespace App\Libraries;


class Request
{
    private $requests = [];

    public function __construct()
    {
        $this->requests = $this->all();
    }

    public function all(): array
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == 'GET' || $method == 'HEAD') {
            array_push($this->requests, $_GET);
            return $_GET;
        } elseif ($method == 'POST') {
            array_push($this->requests, $_POST);
            return $_POST;
        } elseif ($method == 'PUT' || $method == 'PATCH' || $method == 'DELETE') {
            $post_vars = [];
            parse_str(file_get_contents("php://input"), $post_vars);
            array_push($this->requests, $post_vars);
            return $post_vars;
        } else {
            return [];
        }
    }

    public function only(array $names): array
    {
        $requests = $this->requests;
        foreach ($requests as $request => $val) {
            if (!in_array($request, $names))
                unset($requests[$request]);
        }
        return $requests;
    }

    public function exept(string $name)
    {
        $request = $this->requests;
        if (isset($request[$name]))
            unset($request[$name]);
        return $request;
    }

    public function exepts(array $names)
    {
        $request = $this->requests;
        foreach ($names as $name) {
            if (isset($request[$name]))
                unset($request[$name]);
        }
        return $request;
    }

    public function __isset($name)
    {
        return isset($this->requests[$name]);
    }

    public function __get($name)
    {
        if (isset($this->requests[$name]))
            return $this->requests[$name];
        return null;
    }
}