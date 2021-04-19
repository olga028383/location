<?php

namespace Company\Location;

use Bitrix\Main\Loader;
use Bitrix\Highloadblock\HighloadBlockTable as HLBT;

/**
 * Class DataHighloadblock
 * @package Company\DataHighloadblock
 */
class DataHighloadblock
{

    /**
     * @var array
     */
    private $errors = array(
        'notInstallModule' => 'Модуль не установлен',
        'notId' => 'Не передан идентификатор',
        'notData' => 'Данные для записи не переданы'
    );

    /**
     * @var array
     */
    private static $instances = array();


    /**
     * @param $highloadBlockId
     * @return mixed
     * @throws Exception
     */
    private function GetEntityDataClass($highloadBlockId)
    {
        if (empty($highloadBlockId)) {
            throw new \Exception($this->errors->notId);
        }

        Loader::includeModule('highloadblock');

        $highloadBlock = HLBT::getById($highloadBlockId)->fetch();
        $entity = HLBT::compileEntity($highloadBlock);
        $entityDataClass = $entity->getDataClass();

        return $entityDataClass;
    }

    /**
     * DataHighloadblock constructor.
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
     * @param $name
     * @param $tableName
     * @return bool
     * @throws Exception
     */
    public function createTable($name, $tableName)
    {

        Loader::includeModule('highloadblock');

        //Здесь должна быть проверка входных данных

        $result = HLBT::add(array(
            'NAME' => $name,
            'TABLE_NAME' => $tableName,
        ));

        return ($result->isSuccess()) ? $result->getId() : $result->getErrorMessages();
    }

    /**
     * @return array
     */
    public function getTables()
    {

        Loader::includeModule('highloadblock');
        $result = array();
        $rsData = HLBT::getList();
        while ($arData = $rsData->Fetch()) {
            $result[$arData["ID"]] = $arData["NAME"];
        }

        return $result;
    }

    /**
     * @param $name
     * @param $tableName
     * @return bool
     * @throws Exception
     */
    public function removeTable($name)
    {

        Loader::includeModule('highloadblock');

        //Здесь должна быть проверка входных данных  и подключение модуля

        return HLBT::delete($name);

    }

    /**
     * @param $id
     * @param array $arFilter
     * @param array $arFields
     * @param int $limit
     * @return array
     * @throws Exception
     */
    public function GetFields($highloadBlockId, array $arFilter = array(), array $arFields = array(), $limit = 0)
    {

        if (!$highloadBlockId) {
            throw new \Exception($this->errors->notId);
        }

        $dataLimit = array();

        if ($limit) {
            $dataLimit = array('limit' => $limit);
        }

        $arResult = array();
        $entityDataClass = $this->GetEntityDataClass($highloadBlockId);

        $rsData = $entityDataClass::getList(
            array_merge(array(
                'select' => (empty($arFields)) ? array('*') : $arFields,
                'filter' => (empty($arFilter)) ? array('*') : $arFilter,
            ), $dataLimit));

        while ($el = $rsData->fetch()) {
            $arResult[] = $el;
        }

        return $arResult;
    }

    /**
     * @param array $fields
     * @return mixed
     * @throws Exception
     */
    public function addField($highloadBlockId, $fields = array())
    {

        if (!$highloadBlockId) {
            throw new \Exception($this->errors->notId);
        }

        if (empty($highloadBlockId)) {
            throw new \Exception($this->errors->notData);
        };

        $entityDataClass = $this->GetEntityDataClass($highloadBlockId);

        $result = $entityDataClass::add($fields);

        return $result;
    }

}
