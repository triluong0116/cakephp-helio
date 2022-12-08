<!DOCTYPE html>
<!--
Template Name: Metronic - Bootstrap 4 HTML, React, Angular 10 & VueJS Admin Dashboard Theme
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: https://1.envato.market/EA4JP
Renew Support: https://1.envato.market/EA4JP
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<html lang="en">
<!--begin::Head-->
<head>
    <base>
    <meta charset="utf-8"/>
    <title>Mustgo | Login</title>
    <meta name="description" content="Login page example"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <link rel="canonical" href="https://keenthemes.com/metronic"/>
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700"/>
    <!--end::Fonts-->
    <!--begin::Page Custom Styles(used by this page)-->
    <?= $this->Html->css('/kt/assets/css/pages/login/classic/login-4.css') ?>
    <!--end::Page Custom Styles-->
    <!--begin::Global Theme Styles(used by all pages)-->
    <?= $this->Html->css('/kt/assets/plugins/global/plugins.bundle.css') ?>
    <?= $this->Html->css('/kt/assets/plugins/custom/prismjs/prismjs.bundle.css') ?>
    <?= $this->Html->css('/kt/assets/css/style.bundle.css') ?>
    <!--end::Global Theme Styles-->
    <!--begin::Layout Themes(used by all pages)-->
    <?= $this->Html->css('/kt/assets/css/themes/layout/header/base/light.css') ?>
    <?= $this->Html->css('/kt/assets/css/themes/layout/header/menu/light.css') ?>
    <?= $this->Html->css('/kt/assets/css/themes/layout/brand/dark.css') ?>
    <?= $this->Html->css('/kt/assets/css/themes/layout/aside/dark.css') ?>
    <!--end::Layout Themes-->
    <link rel="shortcut icon" href="assets/media/logos/favicon.ico"/>
</head>
<!--end::Head-->
<!--begin::Body-->
<body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">
<!--begin::Main-->
<div class="d-flex flex-column flex-root">
    <!--begin::Login-->
    <div class="login login-4 login-signin-on d-flex flex-row-fluid" id="kt_login">
        <div class="d-flex flex-center flex-row-fluid bgi-size-cover bgi-position-top bgi-no-repeat" style="background-image: url('<?= $this->Url->assetUrl('/kt/assets/media/bg/bg-3.jpg') ?>');">
            <div class="login-form text-center p-7 position-relative overflow-hidden">
                <!--begin::Login Header-->
                <div class="d-flex flex-center mb-15">
                    <a href="#">
                        <img src="<?= $this->Url->assetUrl('frontend/img/logo.png') ?>" class="max-h-75px" alt=""/>
                    </a>
                </div>
                <!--end::Login Header-->
                <?php echo $this->fetch('content'); ?>
            </div>
        </div>
    </div>
    <!--end::Login-->
</div>
<!--end::Main-->
<script>var HOST_URL = "https://preview.keenthemes.com/metronic/theme/html/tools/preview";</script>
<!--begin::Global Config(global config for global JS scripts)-->
<script>var KTAppSettings = {"breakpoints": {"sm": 576, "md": 768, "lg": 992, "xl": 1200, "xxl": 1400}, "colors": {"theme": {"base": {"white": "#ffffff", "primary": "#3699FF", "secondary": "#E5EAEE", "success": "#1BC5BD", "info": "#8950FC", "warning": "#FFA800", "danger": "#F64E60", "light": "#E4E6EF", "dark": "#181C32"}, "light": {"white": "#ffffff", "primary": "#E1F0FF", "secondary": "#EBEDF3", "success": "#C9F7F5", "info": "#EEE5FF", "warning": "#FFF4DE", "danger": "#FFE2E5", "light": "#F3F6F9", "dark": "#D6D6E0"}, "inverse": {"white": "#ffffff", "primary": "#ffffff", "secondary": "#3F4254", "success": "#ffffff", "info": "#ffffff", "warning": "#ffffff", "danger": "#ffffff", "light": "#464E5F", "dark": "#ffffff"}}, "gray": {"gray-100": "#F3F6F9", "gray-200": "#EBEDF3", "gray-300": "#E4E6EF", "gray-400": "#D1D3E0", "gray-500": "#B5B5C3", "gray-600": "#7E8299", "gray-700": "#5E6278", "gray-800": "#3F4254", "gray-900": "#181C32"}}, "font-family": "Poppins"};</script>
<!--end::Global Config-->
<!--begin::Global Theme Bundle(used by all pages)-->
<script src="<?= $this->Url->assetUrl('kt/assets/plugins/global/plugins.bundle.js') ?>"></script>
<script src="<?= $this->Url->assetUrl('kt/assets/plugins/custom/prismjs/prismjs.bundle.js') ?>"></script>
<script src="<?= $this->Url->assetUrl('kt/assets/js/scripts.bundle.js') ?>"></script>
<!--end::Global Theme Bundle-->
<!--begin::Page Scripts(used by this page)-->
<script src="<?= $this->Url->assetUrl('kt/assets/js/pages/custom/login/login-general.js') ?>"></script>
<!--end::Page Scripts-->
</body>
<!--end::Body-->
</html>
