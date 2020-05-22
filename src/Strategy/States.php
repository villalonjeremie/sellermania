<?php
namespace App\Strategy;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\HttpKernel\KernelInterface;

class States {

    protected $projectDir;

    public function getStates() {
        $array = Yaml::parseFile(dirname(__FILE__).'/../../config/statepriorities.yaml');

        return $array;
    }
}