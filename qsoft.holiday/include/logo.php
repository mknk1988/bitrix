<?php
use Bitrix\Main\Config\Option;
?>
<div class="col-12">
	<div class="aside-left__main-space">
        <img src="<?=CFile::GetPath(Option::get("qsoft.holiday", "PARTY_IMAGE"))?>" class="aside-left__main-space-img">
        <div>
            <a href="<?= Option::get("qsoft.holiday", "URL_HREF") ?>"><span style="color: #ffffff;"><?= Option::get("qsoft.holiday", "URL_NAME") ?></span></a>
        </div>
	</div>
</div>
 <br>