<?php


namespace App\Route;


use App\Authorization\JWTAuthorization;
use App\Db\DBConnection;
use App\Exceptions\WrongParamException;

/**
 * Class Route
 * @package App\Route
 */
class Route
{

    /**
     * @var array
     */
    private array $maps = [];

    /**
     * @param $httpMethod
     * @param $route
     * @param $callback
     * @param bool $jwtRequired
     */
    public function map($httpMethod, $route, $callback, $jwtRequired = false): void
    {
        $this->maps[] = [
            'method' => $httpMethod,
            'route' => $route,
            'callback' => $callback,
            'jwtRequired' => $jwtRequired
        ];
    }

    /**
     * @return mixed
     * @throws WrongParamException
     * @throws \ReflectionException
     */
    public function match(): mixed
    {
        $url = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];
        foreach ($this->maps as $map) {
            $pattern = "@^" . preg_replace('/\\\:[a-zA-Z0-9]+/', '([a-zA-Z0-9]+)', preg_quote($map['route'])) . "$@";
            $matches = [];
            if ($method == $map['method'] && preg_match($pattern, $url, $matches)) {

                JWTAuthorization::authorizeCheck($map['jwtRequired']);

                array_shift($matches);
                if (is_string($map['callback'])) {
                    $pieces = explode("#", $map['callback']);
                    $class = "\App\\Controllers\\$pieces[0]Controller";
                    $method = $pieces[1];

                    $reflection = new \ReflectionClass($class);
                    $constructorReflection = [];
                    foreach ($reflection->getConstructor()->getParameters() as $parameter) {
                        $constructorReflection[] = new ($parameter->getType()->getName());
                    }

                    foreach ($reflection->getMethod($method)->getParameters() as $key => $parameter) {
                        if (array_key_exists($key, $matches)) {
                            if (is_null($parameter->getType()) && !ctype_digit($matches[$key])) {
                                throw new WrongParamException(WrongParamException::CODE_5000);
                            } else if (!is_null($parameter->getType())) {

                                $db = DBConnection::getInstance();
                                $table = $parameter->getName();
                                $stm = $db->prepare("SELECT * FROM $table WHERE id = ?");
                                $stm->execute([$matches[$key]]);
                                $data = $stm->fetch(\PDO::FETCH_ASSOC);
                                if (is_bool($data)) {
                                    throw new WrongParamException(WrongParamException::CODE_5000);
                                }
                                $obj = new ($parameter->getType()->getName())($data);
                                $matches[$key] = $obj;
                            }
                        } else {
                            $obj = new ($parameter->getType()->getName());
                            $matches[] = $obj;
                        }
                    }
                    echo (new $class(...$constructorReflection))->$method(...$matches);
                    die;
                } else {
                    echo empty(!$matches) ? call_user_func($map['callback'], ...$matches) : call_user_func($map['callback']);
                    die;
                }
            }
        }

        throw new WrongParamException(WrongParamException::CODE_5000);
    }
}