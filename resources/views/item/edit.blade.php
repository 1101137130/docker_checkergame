@extends('layouts.app')

@section('content')

<div class="conrainer">
    <form action="{{url('item/'.$item->id.'edit')}}" method="POST">
        {{csrf_field()}}

        @if ($errors->has('itemname'))
        <span class="help-block">
            <strong>{{$errors->first('itemname')}}</strong></br>
        </span>
        @endif
        <input type="hidden" name="_method" value="PUT">
        品項：<input type="text" value="{{$item->itemname}}" placeholder="{{$item->itemname}}" name="itemname">
        @if ($errors->has('rate'))
        <span class="help-block">
            <strong>{{$errors->first('rate')}}</strong></br>
        </span>
        @endif
        賠率：<input type="number" name="rate" value="{{$item->rate}}" placeholder="{{$item->rate}}" step="0.0001" min="0.000" max="10000">
        <input type="submit" value="送出">
        <a href="{{url('item')}}" role="btn">取消</a>
    </form>
</div>
@endsection

