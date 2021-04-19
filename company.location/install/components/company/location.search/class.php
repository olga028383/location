<?php

use \Bitrix\Main;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use \Company\Location\DataHighloadblock;
use \Company\Location\Location;

/**
 * Class LocationSearchClass
 */
class LocationSearchClass extends CBitrixComponent
{
    /**
     * Метод проверяет установлен ли модуль
     */
    private function checkModules()
    {
        if (!Loader::includeModule('company.location')) {
            ShowError(Loc::getMessage('COMPANY_LOCATION_MODULE_NOT_INSTALLED'));
        }
    }

    /**
     * @return bool
     */
    private function isAjax()
    {
        return isset($_REQUEST['AJAX']) && $_REQUEST['AJAX'] === 'Y';
    }

    /**
     * @param $params
     * @return mixed
     */
    public function onPrepareComponentParams($params)
    {
        $params = parent::onPrepareComponentParams($params);
        //Здесь еще можно было бы сделать проверку на соотвествие hlb,
        // а хранить его напрмер в настройках модуля

        $params["BLOCKS"] = intval(trim($params["BLOCKS"]));
        return $params;
    }

    /**
     *
     */
    private function getAction()
    {
        $request = \Bitrix\Main\HttpApplication::getInstance()->getContext()->getRequest();
        $action = $request->getPost('action');
        switch ($action) {
            case 'search':
                return $this->getCity();
                break;
            case 'set':
                return $this->setCity();
                break;
        }
    }

    /**
     * @return mixed
     */
    private function getCity()
    {
        $request = \Bitrix\Main\HttpApplication::getInstance()->getContext()->getRequest();
        $phrases = $request->getPost('city');

        //Поиск по названию
        $this->arResult['ITEMS'] = DataHighloadblock::getInstance()->GetFields($this->arParams["BLOCKS"], array('%=UF_CITY_NAME' => "{$phrases}%"));

        return array('success' => 'ok', 'items' => $this->arResult['ITEMS']);
    }

    /**
     * @param $city
     */
    private function setCity()
    {
        $request = \Bitrix\Main\HttpApplication::getInstance()->getContext()->getRequest();
        $city = $request->getPost('city');
        Location::getInstance()->setCity($city);
        return array('success' => 'ok', 'city' => $city);
    }

    /**
     * @return mixed
     */
    public function executeComponent()
    {
        global $APPLICATION;

        try {
            $this->checkModules();

            if ($_REQUEST['AJAX']) {
                $APPLICATION->RestartBuffer();
                header('Content-Type: application/json');
                echo Main\Web\Json::encode($this->getAction());
                Main\Application::getInstance()->end();
                die();
            } else {
                if ($this->startResultCache()) {
                    $this->isAjax();
                    $this->arResult["CURRENT_CITY"] = Location::getInstance()->getCurrentCity();
                    $this->includeComponentTemplate();
                }

                return $this->arResult["CURRENT_CITY"];
            }

        } catch (Main\SystemException $e) {
            ShowError($e->getMessage());
        }

    }

}
