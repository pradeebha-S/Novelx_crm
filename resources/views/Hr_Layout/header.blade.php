@include('Hr_Layout.links')
<body>
    <div class="layout-wrapper layout-content-navbar  ">
        <div class="layout-container">
            @include('Hr_Layout.side_bar')
            <div class="layout-page">
                @include('Hr_Layout.nav_bar')
                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        @yield('content')
                    </div>