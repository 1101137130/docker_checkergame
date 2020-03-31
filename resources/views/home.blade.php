@extends('layouts.app')

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Chekers</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <style>
        html,
        body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Raleway', sans-serif;
            font-weight: 100;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links>a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }

    </style>
</head>

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Home Page</div>

                <div class="panel-body">
                    <div class="links">
                        <a href="{{url('show')}}">Play</a>
                        @if ($manage_rate == true)
                        <a href="{{ url('/item') }}">ItemManage</a>
                        @endif
                        @if ($manager_editor == true)
                        <a href="{{ url('registerManager') }}">Create a Manager User</a>
                        @endif

                    </div>
                </div>

            </div>
            <div class="panel panel-default">
                <div class="panel-heading">帳戶資料</div>

                <div class="panel-body">
                    <div class="links">
                        <label for="username"> 帳號名稱</label>
                        <div id="username" onclick="openrow(this.id)"></div>
                        <label for="email"> Email</label>
                        <div id="email" onclick="openrow(this.id)">
                            </>
                        </div>

                        @if ($manager_editor == true)
                        <a href="{{ url('registerManager') }}">Create a Manager User</a>
                        @endif
                        <a href="{{ url('/order') }}">Orders</a>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
</div>
@endsection
<script>
    window.onload = getData;
    var userId;

    function openrow(itemnameid) {

        delRow(itemnameid)

        var tdnameid = '#' + itemnameid;
        var itemnamevalue = $(tdnameid).text()

        $(tdnameid).append('<input required="required" name=' + itemnameid + '  type="text" placeholder=' +
            itemnamevalue + ' id=' + itemnameid + ' >');

        store(itemnameid)
    }

    function ajaxToEdit(itemnameid) {

        var itemval = $(itemnameid).val()

        $.ajax({
            type: "PUT",
            url: "{{url('editUser')}}",
            dataType: "json",
            data: {
                id: userId,
                item: itemnameid,
                data: itemval

            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: location.reload(),
            error: function (jqXHR) {
                console.log(jqXHR)
            }
        })
    }

    function getData() {

        $.ajax({
            type: "GET",
            url: "{{url('getuser')}}",
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                $("#username").html(data['username'])
                $("#email").html(data['email'])
                userId = data['id'];
                console.log(data['id']);
            },
            error: function (jqXHR) {
                console.log('error')
            }
        })
    }

    function store(id) {
        var tdid = "#" + id;
        $(tdid).append('<a role="btn" class="btn btn-primary" onclick="ajaxToEdit(' + id + ')">儲存</a>');
    }

    function delRow(obj) {
        obj = '#' + obj;
        $(obj).remove();
    }

</script>
