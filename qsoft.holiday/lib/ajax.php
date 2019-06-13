<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

if ($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST["type"]))
{
    global $USER;
    $type = $_POST["type"];
    switch ($type)
    {
        case "space_day":
            $idEl = ["UF_ICON" => intval($_POST["id"])];
            $fileId = intval($_POST["fileId"]);
            $userId = $USER->GetID();
            $result = null;

            //Обновим пользовательское поле UF_ICON у пользователя.
            if ($res = $USER->Update($userId, $idEl))
            {
                //Передадим путь к файлу спрайта для обновления на странице
                $filePath = \CFile::GetPath($fileId);
                $result = ["type" => "Поле обновлено.", "url" => $filePath];
            }
            else
            {
                $result = ["type" => $res->LAST_ERROR];
            }

            echo json_encode($result);
            break;
        case "delete":
            $userId = $USER->GetID();
            $idEl = ["UF_ICON" => ""];
            $USER->Update($userId, $idEl);
            echo json_encode(["type" => "delete"]);
    }

}
