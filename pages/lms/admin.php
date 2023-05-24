<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
    <base href="">
    <meta charset="utf-8" />
    <title>LMS</title>
    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <link rel="canonical" href="https://boti.education" />
    <!--begin::Fonts-->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900%7CRoboto+Mono:500%7CMaterial+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Tajawal:200,300,400,500,700,800,900&amp;subset=arabic" />
    <!--end::Fonts-->
    <!--begin::Page Vendors Styles(used by this page)-->
    <link href="<?= assets('spa/lms/plugins/custom/fullcalendar/fullcalendar.bundle.css') ?>" rel="stylesheet" type="text/css" />
    <!--end::Page Vendors Styles-->
    <!--begin::Global Theme Styles(used by all pages)-->
    <!--end::Global Theme Styles-->
    <!--begin::Layout Themes(used by all pages)-->
    <link href="<?= assets('spa/lms/css/pages/wizard/wizard-1.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= assets('spa/lms/css/pages/login/login-2.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= assets('spa/lms/css/themes/layout/header/base/light.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= assets('spa/lms/css/themes/layout/header/menu/light.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= assets('spa/lms/css/themes/layout/brand/dark.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= assets('spa/lms/css/themes/layout/aside/dark.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= assets('spa/lms/css/themes/layout/aside/lms.css?v=63545737') ?>" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!--end::Layout Themes-->
    <link rel="shortcut icon" href="<?= assets('spa/lms/media/logos/favicon_lms.png') ?>" />
    <meta name="base_api" content="<?php echo URL::absolute() . URL::base() ?>" />
    <meta name="base_path" content="<?php echo URL::base() ?>" />




</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">

    <div id="app"></div>
    <!--begin::Global Config(global config for global JS scripts)-->
    <script>
        var KTAppSettings = {
            "breakpoints": {
                "sm": 576,
                "md": 768,
                "lg": 992,
                "xl": 1200,
                "xxl": 1400
            },
            "colors": {
                "theme": {
                    "base": {
                        "white": "#ffffff",
                        "primary": "#3699FF",
                        "secondary": "#E5EAEE",
                        "success": "#1BC5BD",
                        "info": "#8950FC",
                        "warning": "#FFA800",
                        "danger": "#F64E60",
                        "light": "#E4E6EF",
                        "dark": "#181C32"
                    },
                    "light": {
                        "white": "#ffffff",
                        "primary": "#E1F0FF",
                        "secondary": "#EBEDF3",
                        "success": "#C9F7F5",
                        "info": "#EEE5FF",
                        "warning": "#FFF4DE",
                        "danger": "#FFE2E5",
                        "light": "#F3F6F9",
                        "dark": "#D6D6E0"
                    },
                    "inverse": {
                        "white": "#ffffff",
                        "primary": "#ffffff",
                        "secondary": "#3F4254",
                        "success": "#ffffff",
                        "info": "#ffffff",
                        "warning": "#ffffff",
                        "danger": "#ffffff",
                        "light": "#464E5F",
                        "dark": "#ffffff"
                    }
                },
                "gray": {
                    "gray-100": "#F3F6F9",
                    "gray-200": "#EBEDF3",
                    "gray-300": "#E4E6EF",
                    "gray-400": "#D1D3E0",
                    "gray-500": "#B5B5C3",
                    "gray-600": "#7E8299",
                    "gray-700": "#5E6278",
                    "gray-800": "#3F4254",
                    "gray-900": "#181C32"
                }
            },
            "font-family": "Poppins"
        };
    </script>
    <!--end::Global Config-->
    <!--begin::Global Theme Bundle(used by all pages)-->
    <script src="<?= assets('spa/lms/plugins/global/plugins.bundle.js') ?>"></script>
    <script src="<?= assets('spa/lms/plugins/custom/prismjs/prismjs.bundle.js') ?>"></script>
    <!--end::Global Theme Bundle-->
    <!--begin::Page Vendors(used by this page)-->
    <script src="<?= assets('spa/lms/js/pages/custom/login/login-general.js') ?>"></script>
    <script src="<?= assets('spa/lms/plugins/custom/fullcalendar/fullcalendar.bundle.js') ?>"></script>
    <script src="<?= assets('spa/lms/plugins/custom/fullcalendar/fullcalendar.bundle.js') ?>"></script>
    <!--end::Page Vendors-->

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBlVpE5qrfME14Xvezd2K_c6lPI7Jq8wa8&sensor=false&.js">
    </script>
    <script src="<?= assets('spa/lms/plugins/custom/gmaps/gmaps.js') ?>" type="text/javascript"></script>
    <!--begin::Page Scripts(used by this page)-->
    <script src="<?= assets('spa/lms/js/pages/widgets.js') ?>"></script>

    <script src="<?= assets('spa/lms/js/pages/custom/wizard/wizard-1.js') ?>"></script>

    <!--begin::Page Scripts(used by this page) -->
    <script src="<?= assets('spa/lms/js/scripts.bundle.js') ?>"></script>
    <script src="<?= assets('spa/lms/js/pages/dashboard.js') ?>"></script>

    <link href="<?= assets('spa/lms/plugins/global/plugins.bundle.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= assets('spa/lms/plugins/custom/prismjs/prismjs.bundle.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= assets('spa/lms/css/style.bundle.css?v=63545737') ?>" rel="stylesheet" type="text/css" />

    <script src="<?= assets('spa/lms/js/app.' . time() . '.js') ?>"></script>
    <script>
        $('.carousel').carousel({
            interval: false,
        });
    </script>
    <!--end::Page Scripts-->
</body>
<!--end::Body-->

</html>