<?php if($oFlashMessage->checkFlashMessage('success')): ?>
<div class="alert-box m-b-10"><?= $oFlashMessage->getFlashMessage('success') ?></div>
<?php endif; ?>
<?php if($oFlashMessage->checkFlashMessage('warning')): ?>
<div class="error-box m-b-10"><?= $oFlashMessage->getFlashMessage('warning') ?></div>
<?php endif; ?>
<?php if($oFlashMessage->checkFlashMessage('error')): ?>
<div class="error-box m-b-10"><?= $oFlashMessage->getFlashMessage('error') ?></div>
<?php endif; ?>
