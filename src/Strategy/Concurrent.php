<?php
namespace App\Strategy;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Yaml\Yaml;


class Concurrent {

    public function getArrayConcurrent(?string $name) : ?array
    {
        return $this->getArrayConcurrentByYaml($name);
    }

    private function getArrayConcurrentByApi($name) {
        $client = HttpClient::create();
        $response = $client->request('GET', 'url marketplace');
        $array = $response->getContent();

        return (isset($array[$name])) ? $array[$name] : null;
    }

    private function getArrayConcurrentByYaml($name) {
        $array = Yaml::parseFile(dirname(__FILE__).'/../../config/payload.yaml');

        return (isset($array[$name])) ? $array[$name] : null;
    }
}