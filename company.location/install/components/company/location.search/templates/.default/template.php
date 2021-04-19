<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

?>

<div>
    <a href="" class="js-city">Найти город</a>
    <div>Ваш город: <?php echo $arResult['CURRENT_CITY'] ?></div>
    <div class="popup popup--hidden">
        <button type="button" class="popup__button">x</button>
        <div class="popup__body">
            <form method="post" class="popup__form">
                <input type="hidden" value="set" name="action">
                <input type="hidden" value="Y" name="AJAX">
                <input type="text" value="" name="city" autocomplete="off">
            </form>
            <div class="popup__results">

            </div>
        </div>
    </div>
</div>

