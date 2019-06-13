<?php require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');
use Bitrix\Main\Config\Option;
$bgImg = CFile::GetPath(Option::get("qsoft.holiday", "BACKGROUND_TILE"));
header("Content-type: text/css");
?>
html body {
    background: url("<?=$bgImg?>");
}