<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arComponentDescription = array(
    "NAME" => GetMessage('COMPANY_LOCATION_SEARCH_NAME'),
    "DESCRIPTION" => GetMessage('COMPANY_LOCATION_SEARCH_DESCRIPTION'),
    "ICON" => "/images/icon.gif",
    "SORT" => 150,
    "CACHE_PATH" => "Y",
    "PATH" => array(
        "ID" => "company",
        "NAME" => GetMessage('COMPANY_NAME'),
    ),
    "COMPLEX" => "N",
);
