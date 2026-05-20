<!doctype html>
<html lang="en" class=" layout-navbar-fixed layout-menu-fixed layout-compact " dir="ltr" data-skin="default"
    data-bs-theme="light" data-assets-path="{{ asset('/assets') }}/" data-template="vertical-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="robots" content="noindex, nofollow" />
    <title>NovelX</title>
    <meta name="description" content="" />
    <style>
        .red {
            color: red;
        }

        .out {
            font-weight: 370;
            font-size: 15px;
            color: red;
        }

        .dt-search {
            display: none;
        }

        .link {
            text-decoration: underline;
            text-underline-offset: 2px;
        }
    </style>
    <script>
        (function () {
            const themeKey = 'templateCustomizer-vertical-menu-template--Theme';
            const savedTheme = localStorage.getItem(themeKey);

            if (savedTheme) {
                document.documentElement.setAttribute('data-bs-theme', savedTheme);
            }
        })();
    </script>
    <link rel="icon" type="image/x-icon" href="{{ asset('/assets/img') }}/n.png" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('/assets') }}/vendor/fonts/iconify-icons.css" />
    <script src="{{ asset('/assets') }}/vendor/libs/@algolia/autocomplete-js.js"></script>
    <link rel="stylesheet" href="{{ asset('/assets') }}/vendor/libs/node-waves/node-waves.css" />
    <link rel="stylesheet" href="{{ asset('/assets') }}/vendor/css/core.css" />
    <link rel="stylesheet" href="{{asset('/assets/css/demo.css')}}" />
    <link rel="stylesheet" href="{{ asset('/assets') }}/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="{{ asset('/assets') }}/vendor/libs/apex-charts/apex-charts.css" />
    <link rel="stylesheet" href="{{ asset('/assets') }}/vendor/libs/swiper/swiper.css" />
    <link rel="stylesheet" href="{{ asset('/assets') }}/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
    <link rel="stylesheet"
        href="{{ asset('/assets') }}/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
    <link rel="stylesheet" href="{{ asset('/assets') }}/vendor/fonts/flag-icons.css" />
    <link rel="stylesheet" href="{{ asset('/assets') }}/vendor/css/pages/cards-advance.css" />
    <script src="{{ asset('/assets') }}/vendor/js/helpers.js"></script>
    <script src="{{ asset('/assets') }}/js/config.js"></script>