<html>
    <head>
        <title>Voting System - @yield('title')</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    </head>

    <body>
        <script>var base_url = '{{ url('') }}';</script>
        @yield('body-head')
        
        @section('nav')
            @include('layout.nav')
        @show
        
        <div class="container">
            <br/>
            @include('layout.status')
            @yield('content')
        </div>

        <script src="http://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
        <script>$.ajaxSetup({headers:{'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')}});</script>
        @yield('body-foot')
    </body>
</html>