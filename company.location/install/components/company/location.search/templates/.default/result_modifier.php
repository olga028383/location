<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$arResult['COUNT_ITEMS'] = count($arResult['ITEMS']);
$this->__component->SetResultCacheKeys(array("COUNT_ITEMS"));