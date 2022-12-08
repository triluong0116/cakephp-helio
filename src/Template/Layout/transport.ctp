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
<html lang="en" id="airport_dichung" class="seo ">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <title>
        <?= $title ?>
    </title>
    <!-- End Meta for  Facebook -->
    <?= $this->Html->meta('icon') ?>
    <?php echo $this->element('Front/css_header'); ?>

    <?= $this->fetch('css') ?>

</head>
<body class="<?= $class ?>">
<?php echo $this->element('Front/transport_header') ?>
<?= $this->fetch('content') ?>
<?php echo $this->element('Front/footer') ?>
<?php //echo $this->element('Front/js_footer'); ?>
<?= $this->fetch('script') ?>
</body>
</html>
