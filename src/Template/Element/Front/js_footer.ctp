<?php echo $this->Html->script('/frontend/libs/clientjs/client.min'); ?>
<script type="text/javascript">
    var baseUrl = "<?php echo $this->Url->build('/', true); ?>";
    var csrfToken = '<?php echo $this->request->getParam('_csrfToken') ?>';
    var currentUserId = <?= ($this->request->getSession()->read('Auth.User')) ? $this->request->getSession()->read('Auth.User.id') : 0 ?>;
    var referenceCode = '<?= $this->request->getSession()->read('refAgencyCode') ?>';
    var COMBO_TYPE = <?= COMBO ?>;
    var VOUCHER_TYPE = <?= VOUCHER ?>;
    var HOTEL_TYPE = <?= HOTEL ?>;
    var LANTOUR_TYPE = <?= LANDTOUR ?>;
    var paginateTotal = <?= (isset($amountItems) && !empty($amountItems)) ? $amountItems : 0 ?>;
    var clientJS = new ClientJS();
// Get the client's fingerprint id
    var fingerPrint = clientJS.getFingerprint() + '';

    let room = '<?= $chatRoomId ?>';
    // console.log(room);
    // let roomCheck = JSON.parse(room);
    if (room){
        // Initialize Firebase
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
        var chat_room_id = room;
    }
    else {
        var chat_room_id = [];
    }
    let user = "<?= $userId ?>";
    if (user != null){
        var current_u_id = "<?= $userId ?>";
    } else {
        var current_u_id = "";
    }
    var current_s_id = "<?= $saleAdmin ? $saleAdmin['id'] : '' ?>";
    //if (!sale){
    //    var current_s_id = "<?//= $saleAdmin['id'] ?>//";
    //} else {
    //    var current_s_id = "";
    //}
</script>
<script>
    window.fbAsyncInit = function () {
        FB.init({
            appId: '328968574492385',
            cookie: true,
            xfbml: true,
            version: 'v3.0'
        });

        FB.AppEvents.logPageView();

    };

    (function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {
            return;
        }
        js = d.createElement(s);
        js.id = id;
        js.src = "https://connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>
<script src="https://sp.zalo.me/plugins/sdk.js"></script>
<script src="https://zjs.zdn.vn/zalo/sdk.js"></script>
<script>
    Zalo.init({
        version: '2.0',
        appId: '1003026734674591233',
        redirectUrl: baseUrl + 'users/zalo_action'
    });
    function shareZaloSuccess() {
        Frontend.shareZaloSuccess();
    }
</script>
<?php echo $this->Html->script('/frontend/libs/jquery/jquery-3.3.1.min'); ?>
<?php echo $this->Html->script('/frontend/libs/bootstrap/js/bootstrap.min'); ?>
<?php echo $this->Html->script('/frontend/libs/bxslider/dist/jquery.bxslider.min'); ?>
<?php echo $this->Html->script('/frontend/libs/lightGallery/dist/js/lightgallery-all.min'); ?>
<?php echo $this->Html->script('/frontend/libs/moment/min/moment.min'); ?>
<?php echo $this->Html->script('/frontend/libs/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min'); ?>
<?php echo $this->Html->script('/backend/vendors/bootstrap-daterangepicker/daterangepicker'); ?>
<?php echo $this->Html->script('/frontend/libs/boostrapselect/js/bootstrap-multiselect'); ?>
<?php echo $this->Html->script('/frontend/libs/icheck/icheck.min'); ?>
<?php echo $this->Html->script('/backend/vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min'); ?>
<?php echo $this->Html->script('/frontend/libs/bootpage/jquery.bootpag.min'); ?>
<?php echo $this->Html->script('/frontend/libs/bootstrap-slider/bootstrap-slider'); ?>
<?= $this->fetch('scriptBottom') ?>
<?php echo $this->Html->script('/frontend/js/common'); ?>
<?php echo $this->Html->script('/frontend/js/socket'); ?>
<?php echo $this->Html->script('/frontend/js/frontend'); ?>
<script type="text/javascript">
    var websocket = new WebSocket('wss://' + window.location.hostname + ':8443/');
    websocket.onopen = function (e) {
        console.log('Current User ID: ' + currentUserId);
        sendMsg({event: 'connect', user_id: currentUserId, fingerprint: fingerPrint});//client o day anh oi uh biet roi
    };

    websocket.onerror = function (e) {
    }

    websocket.onclose = function (e) {
    }

    websocket.onmessage = function (e) {
        var data = JSON.parse(e.data);
        if (data.event == 'connect') {
            eventConnect(data);
        } else if (data.event == 'pick') {
            eventPick(data);
        } else if (data.event == 'reset') {
            eventReset();
        } else if (data.event == 'sendagency') {
            eventShowModalToAgency(data);
        } else if (data.event == 'accept') {
            eventAccept(data);
        } else if (data.event == 'connected') {
            $('#my_avatar').attr('src', data.avatar);
        }
    };
</script>
