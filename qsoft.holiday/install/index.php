<?php
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Config\Option;

Loc::loadMessages(__FILE__);

class qsoft_holiday extends CModule
{

    public function __construct(){

        if(file_exists(__DIR__."/version.php")){

            $arModuleVersion = array();

            include_once(__DIR__."/version.php");

            $this->MODULE_ID 		   = str_replace("_", ".", get_class($this));
            $this->MODULE_VERSION 	   = $arModuleVersion["VERSION"];
            $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
            $this->MODULE_NAME 		   = Loc::getMessage("HOLIDAY_NAME");
            $this->MODULE_DESCRIPTION  = Loc::getMessage("HOLIDAY_DESCRIPTION");
            $this->PARTNER_NAME 	   = Loc::getMessage("HOLIDAY_PARTNER_NAME");
            $this->PARTNER_URI  	   = Loc::getMessage("HOLIDAY_PARTNER_URI");
        }

        return false;
    }

    public function DoInstall()
    {
        global $APPLICATION;

        if(CheckVersion(ModuleManager::getVersion("main"), "14.00.00"))
        {
            CopyDirFiles(__DIR__ . "/admin", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin");
            ModuleManager::registerModule($this->MODULE_ID);
            $this->createUserField();
            $this->createSpriteBlock();
        }
        else
        {
            $APPLICATION->ThrowException(Loc::getMessage("HOLIDAY_INSTALL_VERSION_ERROR"));
        }

        $APPLICATION->IncludeAdminFile(Loc::getMessage("HOLIDAY_INSTALL_TITLE")." \"".Loc::getMessage("HOLIDAY_NAME")
            ."\"", __DIR__."/step.php");

        return false;
    }

    public function DoUninstall()
    {
        global $APPLICATION;
        global $step;

        $step = intval($step);

        if ($step < 2)
        {
            $APPLICATION->IncludeAdminFile(Loc::getMessage("HOLIDAY_UNINSTALL_TITLE")." \"".Loc::getMessage("HOLIDAY_NAME")."\"", __DIR__."/unstep1.php");
        }
        elseif ($step == 2)
        {
            DeleteDirFiles(__DIR__ . "/admin", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin");

            if ($_REQUEST["savedata"] == "Y")
            {
                $this->deleteUserField();
                $this->deleteSpriteBlock();
            }

            ModuleManager::unRegisterModule($this->MODULE_ID);

            $APPLICATION->IncludeAdminFile(Loc::getMessage("HOLIDAY_UNINSTALL_TITLE")." \"".Loc::getMessage("HOLIDAY_NAME")."\"", __DIR__."/unstep2.php");
        }
    }

    private function createUserField()
    {
        $fieldName = "UF_ICON";
        $rsData = CUserTypeEntity::GetList( array("ENTITY_ID"=>"USER"), array("FIELD_NAME" => $fieldName))->Fetch();
        if (!$rsData)
        {
            $oUserTypeEntity    = new CUserTypeEntity();
            $aUserFields    = array(
                'ENTITY_ID'         => 'USER',
                'FIELD_NAME'        => $fieldName,
                'USER_TYPE_ID'      => 'string',
                'XML_ID'            => 'XML_'.$fieldName,
                'SORT'              => 500,
                'MULTIPLE'          => 'N',
                'MANDATORY'         => 'N',
                'SHOW_FILTER'       => 'N',
                'SHOW_IN_LIST'      => '',
                'EDIT_IN_LIST'      => '',
                'IS_SEARCHABLE'     => 'N',
                'SETTINGS'          => array(
                    'DEFAULT_VALUE' => '',
                    'SIZE'          => '20',
                    'ROWS'          => '1',
                    'MIN_LENGTH'    => '0',
                    'MAX_LENGTH'    => '0',
                    'REGEXP'        => '',
                ),
                'EDIT_FORM_LABEL'   => array(
                    'ru'    => 'Спрайт-блок праздничного стиля',
                    'en'    => 'Sprite module qsoft.holiday',
                ),
                'LIST_COLUMN_LABEL' => array(
                    'ru'    => 'Спрайт-блок праздничного стиля',
                    'en'    => 'Sprite module qsoft.holiday',
                ),
                'LIST_FILTER_LABEL' => array(
                    'ru'    => 'Спрайт-блок праздничного стиля',
                    'en'    => 'Sprite module qsoft.holiday',
                ),
                'ERROR_MESSAGE'     => array(
                    'ru'    => 'Ошибка при заполнении Спрайт-блок праздничного стиля',
                    'en'    => 'An error in completing the user field',
                ),
                'HELP_MESSAGE'      => array(
                    'ru'    => '',
                    'en'    => '',
                ),
            );
            $resId = $oUserTypeEntity->Add($aUserFields);
            Option::set($this->MODULE_ID, "USER_FIELD_ID", $resId);
        }
    }

    private function deleteUserField()
    {
        $oUserTypeEntity = new CUserTypeEntity();
        $fieldId = Option::get($this->MODULE_ID, "USER_FIELD_ID");
        $oUserTypeEntity->Delete($fieldId);
    }

    private function createSpriteBlock()
    {
        // Тип и название инфоблока для спрайтов
        $iblockName = "PARTY_ICONS";
        $iblockType = "HOLIDAY_ICONS";

        $iBlockId = null;

        // Найдем их в системе, если они есть
        $resIblockName = CIBlock::GetList(["SORT" => "ASC"], ["CODE" => $iblockName])->Fetch();
        $resIblockType = CIBlockType::GetByID($iblockType)->Fetch();

        // Если нету такого типа, то добавим его
        if (!$resIblockType)
        {
            $arFields = array(
                "ID" => $iblockType,
                "SECTIONS" => "Y",
                "IN_RSS" => "N",
                "SORT" => 100,
                "LANG" => array(
                    "ru" => array(
                        "NAME" => "Праздничные иконки",
                        "SECTION_NAME" => "Праздничные иконки",
                        "ELEMENT_NAME" => "Праздничные иконки",
                    ),
                ),
            );
            $obIblockType = new CIBlockType();
            $obIblockType->Add($arFields);
        }

        // И далее добавим инфоблок, если его нет
        if (!$resIblockName)
        {
            $ib = new CIBlock();
            $arFields = array(
                "ACTIVE" => "Y",
                "NAME" => "Пользовательские иконки",
                "CODE" => $iblockName,
                "IBLOCK_TYPE_ID" => $iblockType,
                "SITE_ID" => array(SITE_ID),
                "GROUP_ID" => array( "1" => "X", "2" => "R"),
                "VERSION" => 1,
                "SORT" => 500,
            );
            $res = $ib->Add($arFields);
            if ($res)
            {
                $iBlockId = Option::set($this->MODULE_ID, "SPRITE_BLOCK_ID", $res);
            }
        }

        // Добавим два свойства в инфоблок
        $ibp = new CIBlockProperty;
        // Свойство "Где выводить"
        $arWhereShow = array(
            "NAME" => "Где выводить",
            "ACTIVE" => "Y",
            "SORT" => 100,
            "CODE" => "WHERE_SHOW",
            "PROPERTY_TYPE" => "L",
            "IBLOCK_ID" => $iBlockId,
        );
        $arWhereShow["VALUES"][0] = array(
            "VALUE" => "n",
            "DEF" => "N",
            "SORT" => "100",
            "XML_ID" => "n",
        );
        $arWhereShow["VALUES"][1] = array(
            "VALUE" => "popup",
            "DEF" => "N",
            "SORT" => "200",
            "XML_ID" => "popup",
        );
        $arWhereShow["VALUES"][2] = array(
            "VALUE" => "gratitude",
            "DEF" => "N",
            "SORT" => "300",
            "XML_ID" => "gratitude",
        );

        // Свойство "Изображение"
        $arPicture = array(
            "NAME" => "Картинка",
            "ACTIVE" => "Y",
            "SORT" => "100",
            "CODE" => "IMAGE",
            "PROPERTY_TYPE" => "F",
            "FILE_TYPE" => "jpg, gif, bmp, png, jpeg, svg",
            "IBLOCK_ID" => $iBlockId,
        );

        $ibp->Add($arWhereShow);
        $ibp->Add($arPicture);
    }

    private function deleteSpriteBlock()
    {
        $iBlockId = Option::get($this->MODULE_ID, "SPRITE_BLOCK_ID");
        $iBlocktype = "HOLIDAY_ICONS";

        CIBlock::Delete($iBlockId);
        CIBlockType::Delete($iBlocktype);
    }

}
