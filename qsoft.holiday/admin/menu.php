<?php

use Bitrix\Main\Loader;

global $APPLICATION;

if ($APPLICATION->GetGroupRight('qsoft.holiday') == 'D') {
    return false;
}

if (!Loader::includeModule('qsoft.holiday')) {
    return false;
}

$aMenu = array(
    'parent_menu' => 'global_menu_services',
    'sort' => 50,
    'text' => "Праздничный стиль",
    'icon' => 'fav_menu_icon_yellow',
    'page_icon' => 'fav_page_icon',
    'url' => "holiday_page.php?lang=".LANGUAGE_ID,
);

return $aMenu;