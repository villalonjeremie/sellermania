<?php
namespace App\Strategy;

use App\Strategy\Concurrent;
use App\Strategy\States;

class ProcessPrice {
    const DELTA_PRICE_ETAT_EQUAL = 0.01;
    const DELTA_PRICE_ETAT_NOT_EQUAL = 1;

    protected $concurrent;
    protected $states;

    public function __construct(Concurrent $concurrent, States $states)
    {
        $this->concurrent = $concurrent;
        $this->states = $states;
    }

    public function calculPrice(array $vars) : ?float
    {
        $this->formatFormVars( $vars);
        $concurrents = $this->concurrent->getArrayConcurrent($vars['name']);
        $states = $this->states->getStates();

        if (isset($concurrents)) {
            $this->formatFormVars($concurrents);
        } else {
            return (empty($vars['price']) || ($vars['limitprice'] > $vars['price'])) ? floatval($vars['limitprice']) : floatval($vars['price']);
        }

        $priceMin = $this->getMinPrice($concurrents, $vars['state']);

        if (isset($priceMin)) {
            return  $this->checkLimitPrice(floatval($vars['limitprice']), floatval($priceMin) - self::DELTA_PRICE_ETAT_EQUAL);
        }

        $nextIndexState = array_search($vars['state'], $states);

        do {
            $priceMin = $this->getMinPrice($concurrents, $states[$nextIndexState]);

            if (isset($priceMin)) {
                return $this->checkLimitPrice(floatval($vars['limitprice']),(floatval($priceMin) - self::DELTA_PRICE_ETAT_NOT_EQUAL));
            }
            $nextIndexState++;

        } while (isset($states[$nextIndexState]));

        return (empty($vars['price']) || ($vars['limitprice'] > $vars['price'])) ? floatval($vars['limitprice']) : floatval($vars['price']);
    }

    private function formatFormVars (&$vars) {
        return array_walk_recursive($vars, function (&$item) {
            $item = strtolower($item);
        });
    }

    private function checkLimitPrice($limitPrice, $price) {
        if ($price >= $limitPrice) {
            return $price;
        } else {
            return $limitPrice;
        }
    }

    private function getMinPrice($concurrents, $state) {
        $keys = array_keys(array_column($concurrents, 'state'), $state);
        $arrayR = [];

        foreach ($keys as $key) {
            $arrayR[$key] = $concurrents[$key]['price'];
        }

        return !empty($arrayR) ? min($arrayR) : null;
    }

}