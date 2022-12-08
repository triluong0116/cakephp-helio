<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="images/favicon.ico" type="image/ico"/>

    <title>Trippal - Quản trị</title>

    <?php
    echo $this->Html->css('/backend/vendors/bootstrap/dist/css/bootstrap.min');
    echo $this->Html->css('/backend/vendors/font-awesome/css/font-awesome.min');
    echo $this->Html->css('/backend/vendors/select2/dist/css/select2.min');
    echo $this->Html->css('/backend/vendors/iCheck/skins/flat/green');
    echo $this->Html->css('/backend/vendors/google-code-prettify/bin/prettify.min');
    echo $this->Html->css('/backend/vendors/starrr/dist/starrr');
    echo $this->Html->css('/backend/vendors/bootstrap-daterangepicker/daterangepicker');
    echo $this->Html->css('/backend/vendors/dropzone/dist/min/dropzone.min');
    echo $this->Html->css('/backend/vendors/pnotify/dist/pnotify');
    echo $this->Html->css('/backend/vendors/pnotify/dist/pnotify.buttons');
    echo $this->Html->css('/backend/vendors/pnotify/dist/pnotify.nonblock');
    echo $this->Html->css('/frontend/libs/lightGallery/dist/css/lightgallery.min');
    echo $this->Html->css('/backend/build/css/custom.min');
    echo $this->Html->css('/backend/css/style');
    echo $this->fetch('meta');
    echo $this->fetch('css');
    ?>
</head>
<body class="nav-md">
<div class="container body">
    <div class="main_container">
        <?php echo $this->element('Backend/sidebar'); ?>
        <?php echo $this->element('Backend/top_bar'); ?>
        <?php echo $this->element('Backend/content'); ?>
        <?php echo $this->element('Backend/footer'); ?>

    </div>
</div>
<?php echo $this->element('Backend/modal'); ?>
<script src="https://www.gstatic.com/firebasejs/5.7.0/firebase-app.js"></script>
<!-- Add additional services that you want to use -->
<script src="https://www.gstatic.com/firebasejs/5.7.0/firebase-auth.js"></script>
<script src="https://www.gstatic.com/firebasejs/5.7.0/firebase-firestore.js"></script>
<script type="text/javascript">
    var baseUrl = "<?php echo $this->Url->build('/', true); ?>";
    var csrfToken = '<?php echo $this->request->getParam('_csrfToken') ?>';
    var surcharges = {
        SUR_WEEKEND: <?= SUR_WEEKEND ?>,
        SUR_HOLIDAY: <?= SUR_HOLIDAY ?>,
        SUR_ADULT: <?= SUR_ADULT ?>,
        SUR_CHILDREN: <?= SUR_CHILDREN ?>,
        SUR_BONUS_BED: <?= SUR_BONUS_BED ?>,
        SUR_BREAKFAST: <?= SUR_BREAKFAST ?>,
        SUR_CHECKIN_SOON: <?= SUR_CHECKIN_SOON ?>,
        SUR_CHECKOUT_LATE: <?= SUR_CHECKOUT_LATE ?>,
        SUR_OTHER: <?= SUR_OTHER ?>,
    };
    var paginateTotal = <?= (isset($amountItems) && !empty($amountItems)) ? $amountItems : 0 ?>;
    <?php
    if (!isset($listChatRoomId)){
        $listChatRoomId = "";
    }
    if (!isset($user_id)){
        $user_id = "";
    }
    ?>
    var room = "";
    room = '<?php echo json_encode($listChatRoomId) ?>';
    var user = [];
    user = "<?php echo $user_id ?>";
    var chat_room_id = [];
    let roomCheck = JSON.parse(room);
    if (roomCheck) {
        var config = {
            apiKey: "AIzaSyCq63i6LAwC6IMmikwWdTW4KP4OnSlsSu4",
            authDomain: "mustgoproj.firebaseapp.com",
            projectId: "mustgoproj",
            storageBucket: "mustgoproj.appspot.com",
            messagingSenderId: "800412534543",
            appId: "1:800412534543:web:31b037c7928dc208dce07a"
        };
        firebase.initializeApp(config);
        // Initialize Cloud Firestore through Firebase
        var db = firebase.firestore();
        // Disable deprecated features
        db.settings({
            timestampsInSnapshots: true
        });
        chat_room_id = roomCheck;
    }
    var current_u_id = [];
    if (user != null){
        current_u_id = "<?= $user_id ?>";
    }
</script>
<?php
echo $this->Html->script('/backend/vendors/jquery/dist/jquery.min');
echo $this->Html->script('/backend/vendors/bootstrap/dist/js/bootstrap.min');
echo $this->Html->script('/backend/vendors/fastclick/lib/fastclick');
echo $this->Html->script('/backend/vendors/iCheck/icheck.min');
echo $this->Html->script('/backend/vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min');
echo $this->Html->script('/backend/vendors/jquery.hotkeys/jquery.hotkeys');
echo $this->Html->script('/backend/vendors/google-code-prettify/src/prettify');

echo $this->Html->script('/backend/vendors/moment/min/moment.min');
echo $this->Html->script('/backend/vendors/bootstrap-daterangepicker/daterangepicker');

echo $this->Html->script('/backend/vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min');
echo $this->Html->script('/backend/vendors/select2/dist/js/select2.full.min');
echo $this->Html->script('/backend/vendors/parsleyjs/dist/parsley.min');
echo $this->Html->script('/backend/vendors/autosize/dist/autosize.min');
echo $this->Html->script('/backend/vendors/starrr/dist/starrr');
echo $this->Html->script('/backend/vendors/devbridge-autocomplete/dist/jquery.autocomplete.min');
echo $this->Html->script('/backend/vendors/validator/validator');
echo $this->Html->script('/backend/vendors/dropzone/dist/min/dropzone.min');
echo $this->Html->script('/backend/libs/tinymce/tinymce.min');
echo $this->Html->script('/backend/vendors/pnotify/dist/pnotify');
echo $this->Html->script('/backend/vendors/pnotify/dist/pnotify.buttons');
echo $this->Html->script('/backend/vendors/pnotify/dist/pnotify.nonblock');
echo $this->Html->script('/frontend/libs/lightGallery/dist/js/lightgallery-all.min');
echo $this->Html->script('/backend/build/js/custom');
echo $this->Html->script('/backend/js/backend');
echo $this->Html->script('/backend/js/common');
echo $this->fetch('script');
?>
<?= $this->fetch('scriptBottom') ?>
</body>
</html>
