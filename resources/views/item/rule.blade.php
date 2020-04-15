@extends('layouts.app')

@section('content')

<div class="container">

    <form action="{{url('item/create')}}" method="GET">
        {{csrf_field()}}
        <div>
            @if ($errors->has('itemname'))
            <span class="help-block">
                <strong>{{$errors->first('itemname')}}</strong></br>
            </span>
            @endif
            品項：<input type="text" placeholder="請輸入品項" name="itemname">
            @if ($errors->has('rate'))
            <span class="help-block">
                <strong>{{$errors->first('rate')}}</strong></br>
            </span>
            @endif
            賠率：<input type="number" name="rate" placeholder="請輸入賠率" step="0.1000" min="0.000" max="10000">

        </div>
        @if ($errors->has('winRequire1')||$errors->has('winRequire2')||$errors->has('winRequire3')||$errors->has('winRequire4')||$errors->has('winRequire5'))
        <span class="help-block">
            <strong>{{$errors->first('winRequire1')}}</strong></br>
        </span>
        <span class="help-block">
            <strong>{{$errors->first('winRequire2')}}</strong></br>
        </span>
        <span class="help-block">
            <strong>{{$errors->first('winRequire3')}}</strong></br>
        </span>
        <span class="help-block">
            <strong>{{$errors->first('winRequire4')}}</strong></br>
        </span>
        <span class="help-block">
            <strong>{{$errors->first('winRequire5')}}</strong></br>
        </span>
        @endif
        <input type="radio" id="single" name="compare" value="singleCompare">
        <label for="singleCompare">單局比較</label>
        <div id="singleCompare">
        </div>
        @if ($errors->has('specialCards1')||$errors->has('specialCards2')||$errors->has('specialCards3'))
        <span class="help-block">
            <strong>{{$errors->first('specialCards1')}}</strong></br>
        </span>
        <span class="help-block">
            <strong>{{$errors->first('specialCards2')}}</strong></br>
        </span>
        <span class="help-block">
            <strong>{{$errors->first('specialCards3')}}</strong></br>
        </span>
        @endif
        <input type="radio" id="special" name="compare" value="special">
        <label for="special">特殊牌型</label>
        <div id="specialCards"></div>

    </form>
</div>
<script>
    window.onload = start;

    function start() {
        compareChange();
    }

    function compareChange() {
        $('[name=compare]').change(function() {
            var checked = $('[name=compare]:checked')

            if (checked.val() == 'special') {
                $('#singleCompare').html('');
                specialAppend()
            }
            if (checked.val() == 'singleCompare') {
                $('#specialCards').html('');
                singleAppend();
            }
        })
    }

    function specialAppend() {
        $('#specialCards').append(
            '<table border="1">' +
            '<tr>' +
            '<td style="text-align: center;">局數</td>' +
            '<td style="text-align: center;">1</td>' +
            '<td style="text-align: center;">2</td>' +
            '<td style="text-align: center;">3</td>' +
            '</tr>' +
            '<tr>' +
            '<td style="text-align: center;">我方獲勝所需</td>' +
            '<td style="text-align: center;">' +
            '<label><input type="radio" name="specialCards1" value="1">1</label>' +
            '<label><input type="radio" name="specialCards1" value="2">2</label>' +
            '<label><input type="radio" name="specialCards1" value="3">3</label>' +
            '<label><input type="radio" name="specialCards1" value="4">4</label>' +
            '<label><input type="radio" name="specialCards1" value="5">5</label>' +
            '</td>' +
            '<td style="text-align: center;">' +
            '<label><input type="radio" name="specialCards2" value="1">1</label>' +
            '<label><input type="radio" name="specialCards2" value="2">2</label>' +
            '<label><input type="radio" name="specialCards2" value="3">3</label>' +
            '<label><input type="radio" name="specialCards2" value="4">4</label>' +
            '<label><input type="radio" name="specialCards2" value="5">5</label>' +
            '</td>' +
            '<td style="text-align: center;">' +
            '<label><input type="radio" name="specialCards3" value="1">1</label>' +
            '<label><input type="radio" name="specialCards3" value="2">2</label>' +
            '<label><input type="radio" name="specialCards3" value="3">3</label>' +
            '<label><input type="radio" name="specialCards3" value="4">4</label>' +
            '<label><input type="radio" name="specialCards3" value="5">5</label>' +
            '</td>' +
            '</tr>' +
            '</table>');
        $('#specialCards').append(
            '<input type="hidden" name="special" value="true">' +
            '<input type="submit" class="btn btn-primary" value="確認">')
    }

    function singleAppend() {
        $('#singleCompare').append(
            '<table border="1">' +
            '<tr>' +
            '<td style="text-align: center;">對方結果</td>' +
            '<td style="text-align: center;">1</td>' +
            '<td style="text-align: center;">2</td>' +
            '<td style="text-align: center;">3</td>' +
            '<td style="text-align: center;">4</td>' +
            '<td style="text-align: center;">5</td>' +
            '</tr>' +
            '<tr>' +
            '<td style="text-align: center;">我方獲勝所需</td>' +
            '<td style="text-align: center;">' +
            '<label><input type="checkbox" name="winRequire1[]" value="1">1</label>' +
            '<label><input type="checkbox" name="winRequire1[]" value="2">2</label>' +
            '<label><input type="checkbox" name="winRequire1[]" value="3">3</label>' +
            '<label><input type="checkbox" name="winRequire1[]" value="4">4</label>' +
            '<label><input type="checkbox" name="winRequire1[]" value="5">5</label>' +
            '</td>' +
            '<td style="text-align: center;">' +
            '<label><input type="checkbox" name="winRequire2[]" value="1">1</label>' +
            '<label><input type="checkbox" name="winRequire2[]" value="2">2</label>' +
            '<label><input type="checkbox" name="winRequire2[]" value="3">3</label>' +
            '<label><input type="checkbox" name="winRequire2[]" value="4">4</label>' +
            '<label><input type="checkbox" name="winRequire2[]" value="5">5</label>' +
            '</td>' +
            '<td style="text-align: center;">' +
            '<label><input type="checkbox" name="winRequire3[]" value="1">1</label>' +
            '<label><input type="checkbox" name="winRequire3[]" value="2">2</label>' +
            '<label><input type="checkbox" name="winRequire3[]" value="3">3</label>' +
            '<label><input type="checkbox" name="winRequire3[]" value="4">4</label>' +
            '<label><input type="checkbox" name="winRequire3[]" value="5">5</label>' +
            '</td>' +
            '<td style="text-align: center;">' +
            '<label><input type="checkbox" name="winRequire4[]" value="1">1</label>' +
            '<label><input type="checkbox" name="winRequire4[]" value="2">2</label>' +
            '<label><input type="checkbox" name="winRequire4[]" value="3">3</label>' +
            '<label><input type="checkbox" name="winRequire4[]" value="4">4</label>' +
            '<label><input type="checkbox" name="winRequire4[]" value="5">5</label>' +
            '</td>' +
            '<td style="text-align: center;">' +
            '<label><input type="checkbox" name="winRequire5[]" value="1">1</label>' +
            '<label><input type="checkbox" name="winRequire5[]" value="2">2</label>' +
            '<label><input type="checkbox" name="winRequire5[]" value="3">3</label>' +
            '<label><input type="checkbox" name="winRequire5[]" value="4">4</label>' +
            '<label><input type="checkbox" name="winRequire5[]" value="5">5</label>' +
            '</td>' +
            '</tr>' +
            '</table>');
        $('#singleCompare').append(
            '<input type="hidden" name="singleCompare" value="true">' +
            '<input type="submit" class="btn btn-primary" value="確認">')

    }

    function singleCompareFunction() {

        var t5 = $("input[name='winRequire5[]']:checked").map(function() {
            return $(this).val();
        }).get();
        var t4 = $("input[name='winRequire4[]']:checked").map(function() {
            return $(this).val();
        }).get();
        var t3 = $("input[name='winRequire3[]']:checked").map(function() {
            return $(this).val();
        }).get();
        var t2 = $("input[name='winRequire2[]']:checked").map(function() {
            return $(this).val();
        }).get();
        var t1 = $("input[name='winRequire1[]']:checked").map(function() {
            return $(this).val();
        }).get();

    }

    function specialCardsAjax() {
        var card1 = $("input[name='specialCards1']:checked").val();
        var card2 = $("input[name='specialCards2']:checked").val();
        var card3 = $("input[name='specialCards3']:checked").val();

        if (!card1 || !card2 || !card3) {
            alert('不能為空值')
        } else {

        }
    }
</script>
@endsection