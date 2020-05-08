<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Checkers</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="{{ asset('js/datetimebuild/jquery.js') }}"></script>


</head>


<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        Checkers
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">

                        <!-- Authentication Links -->

                        @if (Auth::guest())
                        <li><a href="{{ route('login') }}">Login</a></li>
                        <li><a href="{{ route('register') }}">Register</a></li>

                        @else
                        <li>您的金額還有：
                        <li id="amount"></li>
                        </li>
                        <li> || </li>
                        <li>您獲勝的金額：
                        <li id="winamount"></li>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>
                                    <a href="{{ url('amount') }}" onclick="event.preventDefault();
                                                     document.getElementById('amount-form').submit();">
                                        Store
                                    </a>
                                    <form id="amount-form" action="{{ url('amount') }}" method="GET"
                                        style="display: none;">
                                        <input type="submit" id="Store">
                                    </form>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        style="display: none;">
                                        {{ csrf_field() }}
                                        <input type="submit" id="Logout">
                                    </form>
                                </li>
                            </ul>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>
        @if (session('status'))
        <div class="alert alert-success">
            <strong>{{ session('status') }}
        </div>
        @endif
        @if (session('error'))
        <div class="alert alert-danger">
            <strong>{{ session('error') }}
        </div>
        @endif
        @yield('content')
    </div>

    <!-- Scripts -->

    <script src="{{ asset('js/app.js') }}"></script>


    <script src="{{ asset('js/datetimebuild/jquery.datetimepicker.full.min.js') }}"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>


    <script>
        //window.onload = getAmount;
        $(function() {
            getAmount()
        });

        function getAmount() {
            var type = "GET";
            var url = "{{url('getAmount')}}";
            ajax(type, url, function(data) {
                $('#amount').html(parseFloat(data[0]))
                $('#winamount').html(parseFloat(data[1]))
            })
        }

        function timeconvert(unixtimestamp) {
            // Months array
            var months_arr = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

            // Convert timestamp to milliseconds
            var date = new Date(unixtimestamp * 1000);

            // Year
            var year = date.getFullYear();

            // Month
            var month = months_arr[date.getMonth()];

            // Day
            var day = date.getDate();

            // Hours
            var hours = date.getHours();

            // Minutes
            var minutes = "0" + date.getMinutes();

            // Seconds
            var seconds = "0" + date.getSeconds();

            // Display date time in MM-dd-yyyy h:m:s format
            var convdataTime = month + '-' + day + '-' + year + ' ' + hours + ':' + minutes.substr(-2) + ':' +
                seconds
                .substr(-2);

            return convdataTime;

        }

        function ajaxWithData(type, url, data, handleData) {
            $.ajax({
                type: type,
                url: url,
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: data,
                success: function(data) {
                    handleData(data);
                },
                error: function(jqXHR) {
                    console.log(jqXHR)
                }
            })
        }
        function getCsrfToken(){
            return $('meta[name="csrf-token"]').attr('content');
        }
        function ajax(type, url, handleData) {
            $.ajax({
                type: type,
                url: url,
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    handleData(data);
                },
                error: function(jqXHR) {
                    console.log(jqXHR)
                }
            })
        }
    </script>

</body>



</html>