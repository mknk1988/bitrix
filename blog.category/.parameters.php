<?php
use Bitrix\Main\Localization\Loc;

$arComponentParameters = Array(
    "PARAMETERS" => Array(
        "CACHE_TIME"  =>  array("DEFAULT"=>36000000),
        "USER_URL" => array(
            "NAME" => Loc::getMessage("PROFILE_URL"),
            "TYPE" => "STRING",
            "DEFAULT" => "",
        ),
        "ELEMENTS_COUNT" => array(
            "NAME" => Loc::getMessage("PARAMS_ELEMENTS_COUNT"),
            "TYPE" => "NUMBER",
            "DEFAULT" => "10"
        ),
    )
);
