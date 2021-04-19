<?php

namespace Company\Location;

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Application;
use \Bitrix\Main\Config\Option;

Loc::loadMessages(__FILE__);

/**
 * Class Location
 * @package Company\Location
 */
class Location

{
    /**
     *
     */
    const moduleID = "company.location";

    /**
     * @var array
     */
    private static $instances = array();

    /**
     * @var string
     */
    private $city = 'city';

    /**
     * Location constructor.
     */
    protected function __construct()
    {
    }

    /**
     * @return mixed
     */
    public static function getInstance()
    {

        $cls = static::class;

        if (!isset(static::$instances[$cls])) {
            static::$instances[$cls] = new static;
        }

        return static::$instances[$cls];
    }

    /**
     * @param $city
     */
    public function setCity($city)
    {
        $session = Application::getInstance()->getSession();
        $session->set($this->city, $city);
    }

    /**
     * @return mixed
     */
    public function getCurrentCity()
    {
        $session = Application::getInstance()->getSession();
        return $session->get($this->city);
    }
}