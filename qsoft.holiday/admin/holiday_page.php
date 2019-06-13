<?php

use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

$module_id = "qsoft.holiday";
$POST_RIGHT = $APPLICATION->GetGroupRight($module_id);
if($POST_RIGHT=="D")
    $APPLICATION->AuthForm(Loc::getMessage("HOLIDAY_SETTINGS_ERR_APP"));

$moduleStyle = $_SERVER["DOCUMENT_ROOT"]."/local/modules/".$module_id."/include/body.css";

$aTabs = [
    [
        "DIV" => "edit1",
        "TAB" => Loc::getMessage("HOLIDAY_SETTINGS_TAB"),
        "ICON" => "ib_settings",
        "TITLE" => Loc::getMessage("HOLIDAY_SETTINGS_TITLE"),
    ]
];

$tabControl = new CAdminTabControl("tabControl", $aTabs);
if($REQUEST_METHOD == "POST" && ($save!="" || $apply!="") && $POST_RIGHT>="W" && check_bitrix_sessid())
{
    if ($_FILES["BACKGROUND_TILE"]["size"] != 0)
    {
        $bgTile = CFile::SaveFile($_FILES["BACKGROUND_TILE"], $module_id);
        $BACKGROUND_TILE = Option::set($module_id,"BACKGROUND_TILE",$bgTile);
    }
    if ($_FILES["PARTY_IMAGE"]["size"] != 0)
    {
        $pImage = CFile::SaveFile($_FILES["PARTY_IMAGE"], $module_id);
        $PARTY_IMAGE = Option::set($module_id,"PARTY_IMAGE",$pImage);
    }
    $SWITCH_ON = Option::set($module_id,"SWITCH_ON",($SWITCH_ON <> "Y"? "N":"Y"));
    $TUMBLER_ON = Option::set($module_id,"TUMBLER_ON",$TUMBLER_ON);
    $TUMBLER_OFF = Option::set($module_id,"TUMBLER_OFF",$TUMBLER_OFF);
    $MODAL_NAME = Option::set($module_id,"MODAL_NAME",$MODAL_NAME);
    $MODAL_BUTTON = Option::set($module_id,"MODAL_BUTTON",$MODAL_BUTTON);
    $SPRITE_BLOCK_ID = Option::set($module_id,"SPRITE_BLOCK_ID",$SPRITE_BLOCK_ID);
    $URL_NAME = Option::set($module_id,"URL_NAME", $URL_NAME);
    $URL_HREF = Option::set($module_id,"URL_HREF", $URL_HREF);

    file_put_contents($moduleStyle, $_POST["BODY_STYLE"]);
}

$SWITCH_ON = Option::get($module_id,"SWITCH_ON", "N");
$TUMBLER_ON = Option::get($module_id,"TUMBLER_ON", Loc::getMessage("HOLIDAY_SETTINGS_TUMBLER_ON_DEF_VAL"));
$TUMBLER_OFF = Option::get($module_id,"TUMBLER_OFF", Loc::getMessage("HOLIDAY_SETTINGS_TUMBLER_OFF_DEF_VAL"));
$MODAL_NAME = Option::get($module_id,"MODAL_NAME",Loc::getMessage("HOLIDAY_SETTINGS_MODAL_NAME_DEF_VAL"));
$MODAL_BUTTON = Option::get($module_id,"MODAL_BUTTON",Loc::getMessage("HOLIDAY_SETTINGS_MODAL_BUTTON_DEF_VAL"));
$SPRITE_BLOCK_ID = Option::get($module_id,"SPRITE_BLOCK_ID",Loc::getMessage("HOLIDAY_SETTINGS_MODAL_BUTTON_DEF_VAL"));
$URL_NAME = Option::get($module_id,"URL_NAME", Loc::getMessage("HOLIDAY_SETTINGS_URL_NAME_DEF_VAL"));
$URL_HREF = Option::get($module_id,"URL_HREF", "/");
$BACKGROUND_TILE = CFile::GetPath(Option::get($module_id,"BACKGROUND_TILE"));
$PARTY_IMAGE = CFile::GetPath(Option::get($module_id,"PARTY_IMAGE"));

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

if(file_exists($moduleStyle)){
    $moduleStyle = file_get_contents($moduleStyle);
}else{
    $moduleStyle = "";
}

$arIBlock=array();
$rsIBlock = CIBlock::GetList(Array("ID" => "ASC"), Array("ACTIVE"=>"Y"));
while($arr=$rsIBlock->Fetch())
{
    $arIBlock[] = ["ID" => $arr["ID"], "NAME" => $arr["NAME"]];
}

$arOptions = [
    [
        "TUMBLER_ON",
        Loc::getMessage("HOLIDAY_SETTINGS_TUMBLER_ON_DESC"),
        $TUMBLER_ON,
        array("text", 50),
    ],
    [
        "TUMBLER_OFF",
        Loc::getMessage("HOLIDAY_SETTINGS_TUMBLER_OFF_DESC"),
        $TUMBLER_OFF,
        array("text", 50),
    ],
    [
        "MODAL_NAME",
        Loc::getMessage("HOLIDAY_SETTINGS_MODAL_NAME_DESC"),
        $MODAL_NAME,
        array("text", 50),
    ],
    [
        "MODAL_BUTTON",
        Loc::getMessage("HOLIDAY_SETTINGS_MODAL_BUTTON_DESC"),
        $MODAL_BUTTON,
        array("text", 50),
    ],
    [
        "URL_NAME",
        Loc::getMessage("HOLIDAY_SETTINGS_URL_NAME"),
        $URL_NAME,
        array("text", 50),
    ],
    [
        "URL_HREF",
        Loc::getMessage("HOLIDAY_SETTINGS_URL_HREF"),
        $URL_HREF,
        array("text", 50),
    ],
];
$tabControl->Begin();
?>
    <form method="POST" action="<?echo $APPLICATION->GetCurPage()?>"  enctype="multipart/form-data" name="holiform">
        <?$tabControl->BeginNextTab();?>
        <tr class="heading">
            <td align="center" valign="top" colspan="3"><?= Loc::getMessage("HOLIDAY_SETTINGS_FORM_INDEX_DESC") ?></td>
        </tr>
        <tr>
            <td width="40%"><?= Loc::getMessage("HOLIDAY_SETTINGS_SWITCH_ON_DESC") ?></td>
            <td><input type="checkbox" name="SWITCH_ON" id="SWITCH_ON" value="Y" <?if($SWITCH_ON == "Y") echo " checked"?>></td>
        </tr>
        <tr class="heading">
            <td align="center" valign="top" colspan="3"><?= Loc::getMessage("HOLIDAY_SETTINGS_FORM_DESC") ?></td>
        </tr>
        <tr>
            <td width="40%"><?= Loc::getMessage("HOLIDAY_SETTINGS_BACKGROUND_TILE") ?></td>
            <td><input name="BACKGROUND_TILE" size="50" type="file"><br>Загружен файл: <?= $BACKGROUND_TILE ?></td>
        </tr>
        <tr>
            <td width="40%"><?= Loc::getMessage("HOLIDAY_SETTINGS_PARTY_IMAGE") ?></td>
            <td><input name="PARTY_IMAGE" size="50" type="file"><br>Загружен файл: <?= $PARTY_IMAGE ?></td>
        </tr>
        <tr>
            <td width="40%"><?= Loc::getMessage("HOLIDAY_SETTINGS_SPRITE_BLOCK_ID") ?></td>
            <td><select name="SPRITE_BLOCK_ID">
                    <? foreach ($arIBlock as $item): ?>
                        <option value="<?=$item["ID"]?>"<?=($item["ID"] == $SPRITE_BLOCK_ID) ? " selected" : ""?>>[<?=$item["ID"]?>] <?=$item["NAME"]?></option>
                    <? endforeach; ?>
                </select></td>
        </tr>

        <? foreach($arOptions as $input){?>
            <tr>
                <td width="40%"><?=$input[1];?></td>
                <td><input type="<?=$input[3][0];?>" name="<?=$input[0];?>" id="<?=$input[0];?>" value="<?=$input[2];?>" size="<?=$input[3][1];?>" maxlength="255"></td>
            </tr>
        <?}?>
        <tr>
            <td><?= Loc::getMessage("HOLIDAY_SETTINGS_FORM_STYLES_DESC") ?></td>
            <td><textarea name="BODY_STYLE" id="" rows=10 cols=45><?=$moduleStyle;?></textarea></td>
        </tr>

        <?
        $tabControl->Buttons(
            array(
                "disabled"=>($POST_RIGHT<"W"),
                "back_url"=>"subscr_admin.php?lang=".LANG,

            )
        );

        ?>
        <?=bitrix_sessid_post();?>
    </form>
<?$tabControl->End();



require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");