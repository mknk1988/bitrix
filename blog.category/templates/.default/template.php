<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
Extension::load("ui.buttons.icons");
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\UI\Extension; ?>
<div class="raif-container">
    <div class="content">
        <div class="raif-container__inner">
            <div class="row">

                <div class="col-12 d-flex justify-content-between mb-20px">
                    <div class="d-flex">
                        <div class="raif-input mr-15px"><input class="raif-input__input h-100" type="text" name="cat-name" id="cat-new-name" required
                                                          placeholder="<?= Loc::getMessage("CATEGORY_NAME") ?>"></div>
                        <div class="cat_form-cell"><input class="raif-btn" type="button" id="cat-create-button" value="<?=
                            Loc::getMessage("CATEGORY_CREATE") ?>" onclick="Add()"></div>
                    </div>
                    <form method="post">
                        <input type="hidden" name="action" value="export">
                        <button class="ui-btn ui-btn-sm
                    ui-btn-icon-download raif-btn raif-btn--bx-icon raif-btn--dark h-100 text-transform-none" type="submit"><?= Loc::getMessage("DOWNLOAD_EXCEL"); ?></button>
                    </form>
                </div>
                <div class="col-12">
                    <? $APPLICATION->IncludeComponent('bitrix:main.ui.grid', '', [
                        'GRID_ID' => $arResult["LIST_ID"],
                        'COLUMNS' => array(
                            array("id"=>"ID", "name"=> Loc::getMessage("COL_ID"), "sort" => "ID", "default"=>true),
                            array("id"=>"NAME", "name"=> Loc::getMessage("COL_NAME"), "sort" => "NAME",
                            "default"=>true),
                        ),
                        'ROWS' => $arResult["CATEGORY"],
                        'SHOW_ROW_CHECKBOXES' => false,
                        'NAV_OBJECT' => $arResult["NAV"],
                        'AJAX_MODE' => 'Y',
                        'AJAX_ID' => CAjax::getComponentID('bitrix:main.ui.grid', '.default', ''),
                        'AJAX_OPTION_JUMP'          => 'N',
                        'SHOW_CHECK_ALL_CHECKBOXES' => true,
                        'SHOW_ROW_ACTIONS_MENU'     => true,
                        'SHOW_GRID_SETTINGS_MENU'   => true,
                        'SHOW_NAVIGATION_PANEL'     => true,
                        'SHOW_PAGINATION'           => true,
                        'SHOW_SELECTED_COUNTER'     => false,
                        'SHOW_TOTAL_COUNTER'        => false,
                        'SHOW_PAGESIZE'             => true,
                        'SHOW_ACTION_PANEL'         => false,
                        'ALLOW_COLUMNS_SORT'        => true,
                        'ALLOW_COLUMNS_RESIZE'      => true,
                        'ALLOW_HORIZONTAL_SCROLL'   => true,
                        'ALLOW_SORT'                => true,
                        'ALLOW_PIN_HEADER'          => true,
                        'AJAX_OPTION_HISTORY'       => 'N'
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<? include_once($_SERVER["DOCUMENT_ROOT"].$templateFolder."/modals.php"); ?>
