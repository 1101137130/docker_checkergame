@extends('layouts.app')

@section('content')

<div class="conrainer">
    @if (session('status'))
    <div class="alert alert-success">
        <strong>{{ session('status') }}
    </div>
    @endif
    <form action="{{url('amount')}}" method="POST">
        {{csrf_field()}}

        @if ($errors->has('itemname'))
        <span class="help-block">
            <strong>{{$errors->first('itemname')}}</strong></br>
        </span>
        @endif

        <div style="text-align: center;">
            您有：{{$total}}</br>
            金額：<input  style="text-align: center;" type="number" min="0" placeholder="請輸入需要儲值金額" name="amount">
            <input class="btn btn-primary" type="submit" value="儲存">
            <a role="btn" href="{{url('/')}}" class="btn btn-danger">回首頁</a>
        </div>

    </form>
</div>

@endsection