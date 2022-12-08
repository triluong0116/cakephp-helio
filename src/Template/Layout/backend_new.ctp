<html lang="en">
<!--begin::Head-->
<head><base href="">
    <meta charset="utf-8" />
    <title>Mustgo - Quản trị</title>
    <meta name="description" content="Metronic admin dashboard live demo. Check out all the features of the admin panel. A large number of settings, additional services and widgets." />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="canonical" href="https://keenthemes.com/metronic" />
    <!--begin::Fonts-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <!--end::Fonts-->
    <!--begin::Page Vendors Styles(used by this page)-->
    <link href="<?= $this->Url->assetUrl('kt/assets/plugins/custom/fullcalendar/fullcalendar.bundle.css') ?>" rel="stylesheet" type="text/css" />
    <!--end::Page Vendors Styles-->
    <!--begin::Global Theme Styles(used by all pages)-->
    <link href="<?= $this->Url->assetUrl('/kt/assets/plugins/global/plugins.bundle.css'); ?>" rel="stylesheet" type="text/css" />
    <link href="<?= $this->Url->assetUrl('/kt/assets/plugins/custom/prismjs/prismjs.bundle.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= $this->Url->assetUrl('/kt/assets/css/style.bundle.css') ?>" rel="stylesheet" type="text/css" />
    <!--end::Global Theme Styles-->
    <!--begin::Layout Themes(used by all pages)-->
    <link href="<?= $this->Url->assetUrl('/kt/assets/css/themes/layout/header/base/light.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= $this->Url->assetUrl('/kt/assets/css/themes/layout/header/menu/light.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= $this->Url->assetUrl('/kt/assets/css/themes/layout/brand/dark.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= $this->Url->assetUrl('/kt/assets/css/themes/layout/aside/dark.css') ?>" rel="stylesheet" type="text/css" />
    <!--end::Layout Themes-->
    <?php
    echo $this->Html->css('/backend/css/style');
    echo $this->Html->css('/kt/assets/css/mustgo.custom');
    echo $this->fetch('meta');
    echo $this->fetch('css');
    ?>
    <link rel="shortcut icon" href="assets/media/logos/favicon.ico" />
</head>
<body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">
<!--begin::Main-->
<?php echo $this->element('KT/header_mobile'); ?>
<div class="d-flex flex-column flex-root">
    <!--begin::Page-->
    <div class="d-flex flex-row flex-column-fluid page">
        <?php echo $this->element('KT/aside'); ?>
        <!--begin::Wrapper-->
        <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
            <?php echo $this->element('KT/header'); ?>
            <?php echo $this->element('KT/content'); ?>
            <?php echo $this->element('KT/footer'); ?>
        </div>
        <!--end::Wrapper-->
    </div>
    <!--end::Page-->
</div>
<!--end::Main-->
<?php echo $this->element('KT/quick_user'); ?>
<?php echo $this->element('KT/quick_panel'); ?>
<?php echo $this->element('KT/chat_modal'); ?>
<!--begin::Scrolltop-->
<div id="kt_scrolltop" class="scrolltop">
			<span class="svg-icon">
				<!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Up-2.svg-->
				<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
					<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
						<polygon points="0 0 24 0 24 24 0 24" />
						<rect fill="#000000" opacity="0.3" x="11" y="10" width="2" height="10" rx="1" />
						<path d="M6.70710678,12.7071068 C6.31658249,13.0976311 5.68341751,13.0976311 5.29289322,12.7071068 C4.90236893,12.3165825 4.90236893,11.6834175 5.29289322,11.2928932 L11.2928932,5.29289322 C11.6714722,4.91431428 12.2810586,4.90106866 12.6757246,5.26284586 L18.6757246,10.7628459 C19.0828436,11.1360383 19.1103465,11.7686056 18.7371541,12.1757246 C18.3639617,12.5828436 17.7313944,12.6103465 17.3242754,12.2371541 L12.0300757,7.38413782 L6.70710678,12.7071068 Z" fill="#000000" fill-rule="nonzero" />
					</g>
				</svg>
                <!--end::Svg Icon-->
			</span>
</div>
<!--end::Scrolltop-->

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
<script>var HOST_URL = "https://preview.keenthemes.com/metronic/theme/html/tools/preview";</script>
<!--begin::Global Config(global config for global JS scripts)-->
<script>var KTAppSettings = { "breakpoints": { "sm": 576, "md": 768, "lg": 992, "xl": 1200, "xxl": 1400 }, "colors": { "theme": { "base": { "white": "#ffffff", "primary": "#3699FF", "secondary": "#E5EAEE", "success": "#1BC5BD", "info": "#8950FC", "warning": "#FFA800", "danger": "#F64E60", "light": "#E4E6EF", "dark": "#181C32" }, "light": { "white": "#ffffff", "primary": "#E1F0FF", "secondary": "#EBEDF3", "success": "#C9F7F5", "info": "#EEE5FF", "warning": "#FFF4DE", "danger": "#FFE2E5", "light": "#F3F6F9", "dark": "#D6D6E0" }, "inverse": { "white": "#ffffff", "primary": "#ffffff", "secondary": "#3F4254", "success": "#ffffff", "info": "#ffffff", "warning": "#ffffff", "danger": "#ffffff", "light": "#464E5F", "dark": "#ffffff" } }, "gray": { "gray-100": "#F3F6F9", "gray-200": "#EBEDF3", "gray-300": "#E4E6EF", "gray-400": "#D1D3E0", "gray-500": "#B5B5C3", "gray-600": "#7E8299", "gray-700": "#5E6278", "gray-800": "#3F4254", "gray-900": "#181C32" } }, "font-family": "Poppins" };</script>
<!--end::Global Config-->
<!--begin::Global Theme Bundle(used by all pages)-->
<script src="<?= $this->Url->assetUrl('/kt/assets/plugins/global/plugins.bundle.js') ?>"></script>
<script src="<?= $this->Url->assetUrl('/kt/assets/plugins/custom/prismjs/prismjs.bundle.js') ?>"></script>
<script src="<?= $this->Url->assetUrl('/kt/assets/js/scripts.bundle.js') ?>"></script>
<?php
echo $this->Html->script('/kt/assets/plugins/global/plugins.bundle.js');
echo $this->Html->script('/kt/assets/plugins/custom/prismjs/prismjs.bundle.js');
echo $this->Html->script('/kt/assets/js/scripts.bundle.js');
echo $this->Html->script('/backend/js/backend');
echo $this->Html->script('/backend/js/common');
echo $this->fetch('script');
?>
<?= $this->fetch('scriptBottom') ?>
</body>
</html>
