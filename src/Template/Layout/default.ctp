<?php
$currentController = $this->request->getParam('controller');
$currentAction = $this->request->getParam('action');
$class = 'bg-white';
if (($currentController == 'Combos' && $currentAction == 'view') || ($currentController == 'Hotels' && $currentAction == 'view')
    || ($currentController == 'LandTours' && $currentAction == 'view') || ($currentController == 'Vouchers' && $currentAction == 'view')) {
    $class = 'bg-white';
}
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=0">
    <meta name="format-detection" content="telephone=no">
    <title>
        <?= $title ?>
    </title>
    <!-- Meta for Facebook -->
    <meta content="Mustgo" property="og:site_name">
    <meta property="og:url" content="<?= (isset($fb_meta_url)) ? $fb_meta_url : $this->Url->build('/', true) ?>"/>
    <meta property="og:type" content="article"/>
    <meta property="og:title" content='<?= (isset($fb_meta_title)) ? $fb_meta_title : 'Mustgo' ?>'/>
    <meta property="og:description" content='<?= (isset($fb_meta_description)) ? $fb_meta_description : 'Mustgo' ?>'/>
    <meta property="og:image" content="<?= (isset($fb_meta_image) && $fb_meta_image) ? $this->Url->build($fb_meta_image, true) : $this->Url->build('/frontend/img/must-go-share.png', true) ?>"/>
    <!-- End Meta for  Facebook -->
    <?= $this->Html->meta('icon') ?>
    <?php echo $this->element('Front/css_header'); ?>
    <?php echo $this->Html->css('/backend/vendors/select2/dist/css/select2.min'); ?>

    <?= $this->fetch('css') ?>
    <?= $this->fetch('cssHeader') ?>

    <script src="https://kit.fontawesome.com/79215f78f6.js" crossorigin="anonymous"></script>
    <script src="https://www.gstatic.com/firebasejs/5.7.0/firebase-app.js"></script>
    <!-- Add additional services that you want to use -->
    <script src="https://www.gstatic.com/firebasejs/5.7.0/firebase-auth.js"></script>
    <script src="https://www.gstatic.com/firebasejs/5.7.0/firebase-firestore.js"></script>
</head>
<body class="<?= $class ?>">
<?php if (!isset($isSaleVinBooking) || !$isSaleVinBooking): ?>
    <?php echo $this->element('Front/header'); ?>
<?php endif; ?>
<?= $this->fetch('content') ?>
<?php if (!isset($isSaleVinBooking) || !$isSaleVinBooking): ?>
    <?php echo $this->element('Front/footer') ?>
    <?php echo $this->element('Front/message') ?>
<?php endif; ?>
<?php echo $this->element('Front/js_footer'); ?>
<?php echo $this->Html->script('/backend/vendors/select2/dist/js/select2.full.min'); ?>
<?= $this->fetch('script') ?>
</body>
</html>
