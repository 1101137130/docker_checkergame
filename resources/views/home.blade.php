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
                        <form action="{{url('editUser')}}" method="POST">
                            {{csrf_field()}}
                            <label for="username"> 帳號名稱</label>
                            <div id="username" onclick="openrow(this.id)"></div>
                            <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                                <div id="usernameinput"></div>
                                 @if ($errors->has('username'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('username') }}</strong>
                                </span>
                                @endif
                            </div>
                                <label for="email"> Email</label>
                            <div id="email" onclick="openrow(this.id)"></div>
                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <div id="emailinput"></div>
                                 @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div id="savebtn"></div>
                                <input id="userid" type="hidden" name="id">
                                
                        </form>



                        @if ($manager_editor == true)
                        <a href="{{ url('registerManager') }}">建立管理者</a>
                        @endif
                        @if ($view_orders == true)
                        <a href="{{ url('/orders') }}">查看所有注單</a>
                        @endif
                        <a href="{{ url('/orders') }}">個人注單</a>

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
    var count = 0;

    function openrow(itemnameid) {
        stroeid = itemnameid + 'input'
        itemid = '#' + itemnameid;
        var tdnameid = itemid + 'input';
        var itemnamevalue = $(itemid).text()
        var text = "text"
        delRow(itemnameid)


        if (itemnameid == 'email') {
            text = "email"

        }
        $(tdnameid).append('<input required="required" name=' + itemnameid + '  type="' + text + '" placeholder=' +
            itemnamevalue + ' id=' + itemnameid + ' >');
        if (count == 0) {

            store()
            count++;
        }

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
                $("#userid").val(data['id']);
            },
            error: function (jqXHR) {
                console.log(jqXHR)
            }
        })
    }

    function store() {

        var item = '<input type="submit" class="btn btn-primary" value="儲存">'

        $('#savebtn').append(item);

    }

    function delRow(obj) {
        obj = '#' + obj;
        $(obj).remove();
    }

</script>
