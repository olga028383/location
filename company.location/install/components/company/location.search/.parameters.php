<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use \Company\Location\DataHighloadblock;

if(!CModule::IncludeModule("company.location"))
    return;

$arBlocks = DataHighloadblock::getInstance()->getTables();

$arComponentParameters = array(
	"GROUPS" => array(
	),
    "PARAMETERS" => array(
        "BLOCKS"  =>  Array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("BLOCKS"),
            "TYPE" => "LIST",
            "VALUES" => $arBlocks,
            "DEFAULT" => '',
        ),
        "CACHE_TIME"  =>  Array("DEFAULT"=>36000000),
        "CACHE_GROUPS" => array(
            "PARENT" => "CACHE_SETTINGS",
            "NAME" => GetMessage("CP_BCM_CACHE_GROUPS"),
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "Y",
        ),
    ),
);
