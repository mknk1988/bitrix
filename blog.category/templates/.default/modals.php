<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use Bitrix\Main\Localization\Loc;
?>
<div class="modal fade" id="avatar-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered raif-popup-container" role="document">
        <div class="modal-content raif-popup raif-popup-rules">
            <div class="raif-popup__inner">
                <header class="raif-popup-rules__header">
                    <?= Loc::getMessage("EDIT"); ?>
                </header>
                <div class="raif-popup-rules__content">
                    <input type="hidden" name="edit-id" id="edit-id">
                    <input type="text" name="edit-new-name" id="edit-new-name" placeholder="Введите новое имя">
                    <div class="mt-30px d-flex justify-content-center">
                        <input type="submit" class="raif-btn raif-btn--w-220 link link--no-hover" id="cat-rename"
                               data-dismiss="modal" aria-label="Close" value="Сохранить" onclick="Rename()">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
