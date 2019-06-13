<?php

namespace QSOFT\Holiday;

use CUser;
use CFile;
use CIBlockElement;
use Bitrix\Main\Page\Asset;
use Bitrix\Main\Config\Option;

class Holiday
{

    const MODULE_ID = "qsoft.holiday";

    public static function getBackground()
    {
        $moduleCheck = Option::get(self::MODULE_ID, "SWITCH_ON");
        if ($moduleCheck == "Y")
        {
            $backgroundCss = "/local/modules/".self::MODULE_ID."/include/background.php";
            $bodyCss = "/local/modules/".self::MODULE_ID."/include/body.css";
            if(file_exists($_SERVER["DOCUMENT_ROOT"].$backgroundCss)){
                Asset::getInstance()->addCss($backgroundCss);
            }
            if(file_exists($_SERVER["DOCUMENT_ROOT"].$bodyCss)){
                Asset::getInstance()->addCss($bodyCss);
            }
            return true;
        }
        return false;
    }

    public static function getLogo()
    {
        global $APPLICATION;
        $moduleCheck = Option::get(self::MODULE_ID, "SWITCH_ON");
        if ($moduleCheck == "Y")
        {
            $file = "/local/modules/".self::MODULE_ID."/include/logo.php";
            if(file_exists($_SERVER["DOCUMENT_ROOT"].$file)) {
                $APPLICATION->IncludeFile($file);
            }
        }
        return false;
    }

    public static function spritesForMyTeam()
    {
        $moduleCheck = Option::get(self::MODULE_ID, "SWITCH_ON");
        if ($moduleCheck == "Y")
        {
            $arMask = self::getMask();
            $mask = $arMask["POPUP"][rand(0, count($arMask["POPUP"]) - 1)]["SRC"];
            echo "<img class=\"img-space\" src=\"$mask\">";
        }
        return false;
    }

    public static function getUserMask()
    {
        $moduleCheck = Option::get(self::MODULE_ID, "SWITCH_ON");
        if ($moduleCheck == "Y")
        {
            global $USER;
            $userData = CUser::GetByID($USER->GetID())->Fetch();
            if ($userData["UF_ICON"])
            {
                $res = CIBlockElement::GetByID($userData["UF_ICON"]);
                $arItem = $res->GetNextElement()->GetProperties();
                return CFile::GetPath($arItem["IMAGE"]["VALUE"]);

            }
        }
        return false;
    }

    public static function getTitle($var = null){
        if(isset($var)){
            $title = Option::get(self::MODULE_ID,$var);
            if($title)
                return $title;
        }
        return false;
    }

    public static function getModal()
    {
        $moduleCheck = Option::get(self::MODULE_ID, "SWITCH_ON");
        if ($moduleCheck == "Y")
        {
            $arMask = self::getMask("POPUP");

            if(count($arMask) == 1){
                $fileJs = "/local/modules/".self::MODULE_ID."/js/single.js";
                Asset::getInstance()->addJs($fileJs);
                $arImg = array_shift($arMask);

                $res = "<script>
                    let idEl = ".$arImg["ID"].";
                    let idImage = ".$arImg["PROPERTY_IMAGE_VALUE"].";
                    </script>";
            }else{
                $fileJs = "/local/modules/".self::MODULE_ID."/js/script.js";
                Asset::getInstance()->addJs($fileJs);
                $modalName = Option::get(self::MODULE_ID, "MODAL_NAME");
                $modalButton = Option::get(self::MODULE_ID, "MODAL_BUTTON");


                $res = "<div class=\"modal modal--bravo fade modal-smoke\" id=\"space-modal\">
                        <div class=\"modal-dialog modal-dialog-centered\">
                        <div class=\"modal-content bravo__modal\">
                        <header class=\"bravo__modal-header bravo__modal-header--starred  mb-30\">$modalName</header>
                        <div class=\"row mb-30\">";


                $resMask = "";
                foreach ($arMask as $item)
                {
                    $resMask .= "<div class=\"col-4\">
                             <div class=\"space-checkbox\">
                                <input type=\"radio\" id=\"".$item["ID"]."\" name=\"space-ava\">
                                <label for=\"".$item["ID"]."\">
                                    <img class=\"img-space\" id=\"".$item["ID"]."\" name=\"".$item["PROPERTY_IMAGE_VALUE"]."\" src=\"".$item["SRC"]."\">
                                    <div class=\"space-checkbox__bg\"></div>
                                    <i class=\"fas fa-check\"></i>
                                </label>
                            </div>
                        </div>";
                }
                $res .= $resMask;

                $res .= "</div>

                    <div class=\"text-center\">
                    <button type=\"submit\" class=\"bravo__modal-btn-submit\" data-dismiss=\"modal\">
                        $modalButton
                    </button>
                    </div>

                    <a href=\"javascript:\" class=\"bravo__modal-close\" data-dismiss=\"modal\" id=\"bravo__modal-close\">
                    <i class=\"fas fa-times\"></i>
                    </a>
                    </div>
                    </div>
                    </div>";
            }
            return $res;
        }
        return false;
    }

    private static function getMask($name = null)
    {
        $iblockId = Option::get(self::MODULE_ID, "SPRITE_BLOCK_ID");
        $arFilter = Array("IBLOCK_ID" => $iblockId, "ACTIVE_DATE" => "Y", "ACTIVE" => "Y");
        $arSelect = Array("ID", "PROPERTY_IMAGE", "PROPERTY_WHERE_SHOW");
        $res = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
        $masks = [];

        while ($obj = $res->GetNextElement())
        {
            $img = $obj->GetFields();
            $img["SRC"] = CFile::GetPath($img["PROPERTY_IMAGE_VALUE"]);
            switch ($img["PROPERTY_WHERE_SHOW_VALUE"]){
                case "popup":
                    $masks["POPUP"][] = $img;
                    break;
                case "gratitude":
                    $masks["GRAD"][] = $img;
                    break;
                default:
                    $masks["OTHER"][] = $img;
            }
        }
        if(is_array($masks)){
            if(isset($name))
                return $masks[$name];
            return $masks;
        }
        return false;
    }

}
