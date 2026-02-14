<!DOCTYPE html>
<html lang="en">
<head>

    @include('layouts.header')

    <!-- STYLE GLOBAL -->
    @include('layouts.styles')

    <!-- STYLE PAGE -->
    @stack('style')

</head>

<body>
<div class="container-scroller">

    @include('layouts.navbar')

    <div class="container-fluid page-body-wrapper">

        @include('layouts.sidebar')

        <div class="main-panel">
            <div class="content-wrapper">
                @yield('content')
            </div>

            @include('layouts.footer')
        </div>

    </div>
</div>

<!-- JAVASCRIPT GLOBAL -->
@include('layouts.scripts')

<!-- JAVASCRIPT PAGE -->
@stack('script')

</body>
</html>
