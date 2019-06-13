<?php

if (is_file($_SERVER["DOCUMENT_ROOT"] . "/local/modules/qsoft.holiday/admin/holiday_page.php")) {
    require($_SERVER["DOCUMENT_ROOT"] . "/local/modules/qsoft.holiday/admin/holiday_page.php");
} else {
    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/qsoft.holiday/admin/holiday_page.php");
}
