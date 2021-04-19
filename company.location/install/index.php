<?php

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Config\Option;
use \Bitrix\Main\Application;

Loc::loadMessages(__FILE__);

/**
 * Class company_location
 */
Class company_location extends CModule
{
    var $MODULE_ID = "company.location";

    function __construct()
    {
        $arModuleVersion = array();
        include(__DIR__ . "/version.php");

        $this->MODULE_ID = 'company.location';
        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        $this->MODULE_NAME = Loc::getMessage("COMPANY_LOCATION_MODULE_NAME");
        $this->MODULE_DESCRIPTION = Loc::getMessage("COMPANY_LOCATION_MODULE_DESC");

        $this->PARTNER_NAME = Loc::getMessage("COMPANY_LOCATION_PARTNER_NAME");
        $this->PARTNER_URI = Loc::getMessage("COMPANY_LOCATION_PARTNER_URI");

        $this->MODULE_SORT = 1;
    }

    public function isVersionD7()
    {
        return CheckVersion(\Bitrix\Main\ModuleManager::getVersion('main'), '14.00.00');
    }

    public function GetPath($notDocumentRoot = false)
    {
        if ($notDocumentRoot)
            return str_ireplace(Application::getDocumentRoot(), '', dirname(__DIR__));
        else
            return dirname(__DIR__);
    }

    function InstallFiles($arParams = array())
    {
        $path = $this->GetPath() . "/install/components";

        if (\Bitrix\Main\IO\Directory::isDirectoryExists($path)) {
            if (\Bitrix\Main\IO\Directory::isDirectoryExists($_SERVER["DOCUMENT_ROOT"] . '/bitrix/components/company')) {
                CopyDirFiles($path . '/company', $_SERVER["DOCUMENT_ROOT"] . "/bitrix/components/company", true, true);
            } else {
                CopyDirFiles($path, $_SERVER["DOCUMENT_ROOT"] . "/bitrix/components", true, true);
            }
        } else {
            throw new \Bitrix\Main\IO\InvalidPathException($path);
        }

        return true;
    }

    function UnInstallFiles()
    {
        \Bitrix\Main\IO\Directory::deleteDirectory($_SERVER["DOCUMENT_ROOT"] . '/bitrix/components/company/location.search');

        return true;
    }

    function InstallDB()
    {
        //Установочнве данные я бы вынесла в csv файл
        $dataCompany = array(
            array('UF_CITY_NAME' => 'Москва'),
            array('UF_CITY_NAME' => 'Санкт-Петербург'),
            array('UF_CITY_NAME' => 'Брянск'),
        );

        $dataContacts = array(
            array('UF_CONTACTS_CITY_ID' => 1, 'UF_CONTACTS_ADDRESS' => 'Адрес 1', 'UF_CONTACTS_PHONE' => 'Телефон 1'),
            array('UF_CONTACTS_CITY_ID' => 2, 'UF_CONTACTS_ADDRESS' => 'Адрес 2', 'UF_CONTACTS_PHONE' => 'Телефон 2'),
            array('UF_CONTACTS_CITY_ID' => 3, 'UF_CONTACTS_ADDRESS' => 'Адрес 3', 'UF_CONTACTS_PHONE' => 'Телефон 3'),
        );

        Loader::includeModule($this->MODULE_ID);

        $resultCompany = \Company\Location\DataHighloadblock::getInstance()->createTable('CompanyCity', 'companycity');

        if (intval($resultCompany)) {
            $this->createFileldText('HLBLOCK_' . $resultCompany, 'CITY_NAME', string);

            foreach ($dataCompany as $company) {
                \Company\Location\DataHighloadblock::getInstance()->addField($resultCompany, $company);
            }
        }

        $resultContacts = \Company\Location\DataHighloadblock::getInstance()->createTable('CompanyContacts', 'companycontacts');

        if (intval($resultContacts)) {

            //Здесь я бы лучше сделала поле привязки
            $this->createFileldText('HLBLOCK_' . $resultContacts, 'CONTACTS_CITY_ID', integer);

            $this->createFileldText('HLBLOCK_' . $resultContacts, 'CONTACTS_ADDRESS', string);
            $this->createFileldText('HLBLOCK_' . $resultContacts, 'CONTACTS_PHONE', string);

            foreach ($dataContacts as $contacts) {
                \Company\Location\DataHighloadblock::getInstance()->addField($resultContacts, $contacts);
            }
        }

    }

    function UnInstallDB()
    {
        Loader::includeModule($this->MODULE_ID);

        $tables = \Company\Location\DataHighloadblock::getInstance()->getTables();
        if (!empty($tables)) {
            foreach ($tables as $key => $table) {
                switch (strtolower($table)) {
                    case 'companycity':
                    case 'companycontacts':
                        \Company\Location\DataHighloadblock::getInstance()->removeTable($key);
                        breack;
                }
            }
        }

    }


    function DoInstall()
    {
        global $APPLICATION;

        if ($this->isVersionD7()) {

            \Bitrix\Main\ModuleManager::registerModule($this->MODULE_ID);

            $this->InstallDB();
            $this->InstallFiles(array(
                "public_dir" => $_REQUEST["public_dir"],
                "public_rewrite" => $_REQUEST["public_rewrite"],
            ));


        } else {
            $APPLICATION->ThrowException(Loc::getMessage("COMPANY_LOCATION_INSTALL_ERROR_VERSION"));
        }
        $APPLICATION->IncludeAdminFile(Loc::getMessage("COMPANY_LOCATION_INSTALL_TITLE"), $this->GetPath() . "/install/step.php");
    }

    function DoUninstall()
    {

        global $APPLICATION;

        $this->UnInstallDB();
        $this->UnInstallFiles();

        \Bitrix\Main\ModuleManager::unRegisterModule($this->MODULE_ID);

        $APPLICATION->IncludeAdminFile(Loc::getMessage("COMPANY_LOCATION_UNINSTALL_TITLE"), $this->GetPath() . "/install/unstep.php");

    }

    function createFileldText($entity, $field, $type)
    {
        $oUserTypeEntity = new \CUserTypeEntity();
        $aUserFields = array(
            'ENTITY_ID' => $entity,
            'FIELD_NAME' => 'UF_' . $field,
            'USER_TYPE_ID' => $type,
            'XML_ID' => 'XML_ID_' . $field,
            'SORT' => 500,
            'MULTIPLE' => 'N',
            'MANDATORY' => 'N',
            'SHOW_FILTER' => 'N',
            'SHOW_IN_LIST' => '',
            'EDIT_IN_LIST' => 'Y',
            'IS_SEARCHABLE' => 'N',
            'SETTINGS' => array(
                'DEFAULT_VALUE' => '',
                'SIZE' => '20',
                'ROWS' => '1',
                'MIN_LENGTH' => '0',
                'MAX_LENGTH' => '0',
                'REGEXP' => '',
            ),
            'EDIT_FORM_LABEL' => array(
                'ru' => $field,
                'en' => '$field',
            ),
            'LIST_COLUMN_LABEL' => array(
                'ru' => $field,
                'en' => $field,
            ),
            'LIST_FILTER_LABEL' => array(
                'ru' => $field,
                'en' => $field,
            ),
            'ERROR_MESSAGE' => array(
                'ru' => $field,
                'en' => $field,
            ),
            'HELP_MESSAGE' => array(
                'ru' => '',
                'en' => '',
            ),
        );

        $oUserTypeEntity->Add($aUserFields);
    }

}
