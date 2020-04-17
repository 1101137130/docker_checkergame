@extends('layouts.app')

@section('content')
<div class="container">
    <a role="btn" href="{{url('Raterecord')}}">查看紀錄</a>
    <table class="table table-hover" id="table">
    </table>

    <form action="{{url('item/create')}}" method="GET">
        {{csrf_field()}}
        <div>
            @if ($errors->has('itemname'))
            <span class="help-block">
                <strong>{{$errors->first('itemname')}}</strong></br>
            </span>
            @endif
            品項：<input type="text" placeholder="請輸入品項" name="itemname"></br>
            @if ($errors->has('rate'))
            <span class="help-block">
                <strong>{{$errors->first('rate')}}</strong></br>
            </span>
            @endif
            賠率：<input type="number" name="rate" placeholder="請輸入賠率" step="0.0005" min="0.000" max="10000">
            @if ($errors->has('limit_amount'))
            <span class="help-block">
                <strong>{{$errors->first('limit_amount')}}</strong></br>
            </span>
            @endif
            限制下注金額：<input type="number" name="limit_amount" placeholder="請輸入金額" step="0.5" min="0.000"
                max="10000000">

        </div>
        @if($errors->has('winRequire1')||$errors->has('winRequire2')||$errors->has('winRequire3')||$errors->has('winRequire4')||$errors->has('winRequire5'))
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
        @if ($errors->has('operator')||$errors->has('total'))
        <span class="help-block">
            <strong>{{$errors->first('operator')}}</strong></br>
        </span>
        <span class="help-block">
            <strong>{{$errors->first('total')}}</strong></br>
        </span>
        @endif
        <input type="radio" id="total" name="compare" value="total">
        <label for="total">總數</label>
        <div id="totalCompare"></div>
        @if ($errors->has('selectFirst')||$errors->has('selectSecond')||$errors->has('selectThird'))
        <span class="help-block">
            <strong>{{$errors->first('selectFirst')}}</strong></br>
        </span>
        <span class="help-block">
            <strong>{{$errors->first('selectSecond')}}</strong></br>
        </span>
        <span class="help-block">
            <strong>{{$errors->first('selectThird')}}</strong></br>
        </span>
        @endif
        <input type="radio" id="extend" name="compare" value="extend">
        <label for="extend">現有規則</label>
        <div id="extendCompare"></div>

    </form>
    <p id="storeButton" hidden><a type=role class="btn btn-primary" onclick="allEdit()">儲存</a></p>
</div>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js">
</script>

<script>
    window.onload = start;
    var ItemEditarray = new Array();
    var EditCount = 0;
    var itemrules = new Map;

    function start() {
        getItemsData();
        compareChange();
    }

    function putItemRulesData(data) {
        $.each(data, function(i, data) {
            $('#selectFirst').append('<option value=' + data.id + '>' + data.itemname + '</option>');
            $('#selectSecond').append('<option value=' + data.id + '>' + data.itemname + '</option>');
            $('#selectThird').append('<option value=' + data.id + '>' + data.itemname + '</option>');
        })

    }

    function getItemRulesData() {
        $.ajax({
            type: "GET",
            url: "{{url('itemrule')}}",
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                putItemRulesData(data);
            },
            error: function(jqXHR) {
                console.log('error')
            }
        })
    }

    function disabledRadio() {
        c1 = $("input[name='specialCards1']:checked").val()
        c2 = $("input[name='specialCards2']:checked").val()
        c3 = $("input[name='specialCards3']:checked").val()
        if (c1 == c2) {
            $("input[name='specialCards2']").prop("checked", false);
        }
        if (c1 == c3) {
            $("input[name='specialCards3']").prop("checked", false);
        }
        if (c2 == c3) {
            $("input[name='specialCards3']").prop("checked", false);
        }

    }

    function setInputOperator() {
        var val;
        val = $('#operator').val()
        $('#inputOpertor').val(val);
    }

    function clearTotal() {
        $('#total').val('');
        $('#operator').val('');
    }
    //這是從第一次從前端去跟後端要資料的function
    function getItemsData() {

        $.ajax({
            type: "POST",
            url: "{{url('item')}}",
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                add_row(data);
            },
            error: function(jqXHR) {
                console.log('error')
            }
        })
    }
    //-----
    // 這是為了可以實現多筆資料一次修改的ajax
    function allEdit() {
        $.ajax({
            type: "POST",
            url: "{{url('item/edit')}}",
            dataType: "json",
            data: {
                temp: temp
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: location.reload(),
            error: function(jqXHR) {
                console.log('error')
            }
        })
    }
    //------
    //這是提供單筆資料修改的ajax
    function ajaxToEdit(id, itemnameid, inputrateid, inputlimitamountid) {

        var itemname = $(itemnameid).val();
        var itemrate = $(inputrateid).val();
        var limiamount =$(inputlimitamountid).val();
        $.ajax({
            type: "PUT",
            url: "{{url('item/{id}')}}",
            dataType: "json",
            data: {
                id: id,
                itemname: itemname,
                rate: itemrate,
                limit_amount :limiamount
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: location.reload(),
            error: function(jqXHR) {
                console.log(jqXHR)
            }
        })
    }
    //-----
    //這是刪除的ajax
    function ajaxToDelete(id) {

        $.ajax({
            type: "DELETE",
            url: "{{url('item/{id}')}}",
            dataType: "json",
            data: {
                id: id,
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: location.reload(),
            error: function(jqXHR) {
                console.log(jqXHR)
            }
        })

    }
    //-----
    //這是開始時向後端要資料需要建立的td
    function add_row(data) {
        for (var i = 0; i < data.length; i++) {

            var itemid = data[i][0];
            var itemname = data[i][1];
            var itemrate = data[i][2];
            var itemlimit = data[i][3];
            var td1 = '<td id="tdnameid' + [i] + '"><div value=' + itemname + ' id=' + 'itemname' + itemid +
                ' onclick="openLabel(' + 'itemname' + itemid + ',' + 'itemid' + itemid + ',' + 'limitamount' + itemid +
                ',' + i + ',' + itemid +
                ')">項目：' + itemname + '</div></td>';
            var td2 = '<td id="tdidid' + [i] + '"><div value=' + itemrate + ' id=' + 'itemid' + itemid +
                ' onclick="openLabel(' + 'itemname' + itemid + ',' + 'itemid' + itemid + ',' + 'limitamount' + itemid +
                ',' + i + ',' + itemid +
                ')">賠率：' + itemrate + '</div></td>';
            var td3 = '<td id="tdlimitid' + [i] + '"><div value=' + itemlimit + ' id=' + 'limitamount' + itemid +
                ' onclick="openLabel(' + 'itemname' + itemid + ',' + 'itemid' + itemid + ',' + 'limitamount' + itemid +
                ',' + i + ',' + itemid +
                ')">限制下注金額：' + itemlimit + '</div></</td>';
            var td4 = '<td id="tdid' + [i] + '">' + '<a role="btn" class="btn btn-danger" onclick="ajaxToDelete(' +
                itemid + ')">刪除</a></td>';
            var tr = $('<tr >').append(td1, td2, td3, td4);
            $('#table').append(tr);
        }
    }
    //-----
    //這是當需要修改時點按觸發的修改function目的將div改成input來輸入資料
    function openLabel(itemnameid, itemidid, limitid, i, itemid) {

        delRow(itemnameid)
        delRow(itemidid)
        delRow(limitid)
        var tdnameid = '#tdnameid' + i;
        var tdidid = '#tdidid' + i;
        var tdlimitid = '#tdlimitid' + i;
        var itemnamevalue = itemnameid.attributes[0].nodeValue;
        var ratevalue = itemidid.attributes[0].nodeValue;
        var limitamountvalue = limitid.attributes[0].nodeValue;
        var inputnameid = itemnameid.attributes[1].nodeValue;
        var rateid = itemidid.attributes[1].nodeValue;
        var limitamountid = limitid.attributes[1].nodeValue;

        $(tdnameid).append('項目：<input required="required" name="itemname" onchange="changeDatatemp(' + inputnameid +
            ',' + rateid + ',' + itemid + ',' + limitamountid + ')" type="text" placeholder=' + itemnamevalue + ' value=' +
            itemnamevalue + ' id=' + inputnameid + ' >');

        $(tdidid).append('賠率：<input required="required"  name="itemrate" onchange="changeDatatemp(' + inputnameid +
            ',' + rateid + ',' + itemid + ',' + limitamountid + ')" type="number" step="0.0001" min="0.000" max="10000" placeholder=' +
            ratevalue + ' value=' + ratevalue + ' id=' + rateid + '>');

        $(tdlimitid).append('限制下注金額：<input required="required"  name="limit_amount" onchange="changeDatatemp(' + inputnameid +
            ',' + rateid + ',' + itemid + ',' + limitamountid + ')" type="number" step="0.5000" min="0.000" max="10000000000" placeholder=' +
            limitamountvalue + ' value=' + limitamountvalue + ' id=' + limitamountid + '>');

        store(i, itemid, inputnameid, rateid,limitamountid)
    }
    //-----
    //全域變數 count 目的是為了算使用者修改了幾項
    var count = 0;
    //-----
    //全域變數陣列 目的是將修改的資料放進去
    var temp = new Array;
    //-----
    //檢查該項是否被修改過 如果有修改過 則將該項temp陣列變數的資料覆寫  
    function checkDataAltered(id, namevalue, ratevalue,limitamountvalue) {
        limitamountvalue == null ? 10000000000: limitamountvalue;
        for (var i = 0; i < count; i++) {
            if (temp[i][0] == id) {
                temp.splice(i, 1)
                temp[i] = new Array;
                temp[i].push(id, namevalue, ratevalue,limitamountvalue)

                return false;
            }
        }

        return true;
    }
    //-----
    //當發生修改時觸發將修改的值放入temp變數中
    function changeDatatemp(nameid, rateid, id , limitamountid) {
        var namevalue = $(nameid).val();
        var ratevalue = $(rateid).val();
        var limitamountvalue = $(limitamountid).val()
        if (checkDataAltered(id, namevalue, ratevalue,limitamountvalue)) {
            temp[count] = new Array;
            //temp.push(count)
            temp[count].push(id, namevalue, ratevalue,limitamountvalue)
            count++
        }
    }
    //-----
    //製作一個單向修改的儲存按鈕
    function store(i, itemid, inputnameid, rateid,limitamountid) {
        var tdid = '#tdid' + i;
        $(tdid).append('<a role="btn" class="btn btn-primary" onclick="ajaxToEdit(' + itemid + ',' + inputnameid + ',' +
            rateid + ','+limitamountid+')">儲存</a>');
        $(storeButton).show();
    }
    //-----
    //刪除一列方法 目的是來產生新的
    function delRow(obj) {
        $(obj).remove();
    }
    //-----

    function compareChange() {
        $('[name=compare]').change(function() {
            var checked = $('[name=compare]:checked')

            if (checked.val() == 'special') {
                $('#singleCompare').html('');
                $('#extendCompare').html('');
                $('#totalCompare').html('');
                specialAppend()
            }
            if (checked.val() == 'singleCompare') {
                $('#specialCards').html('');
                $('#extendCompare').html('');
                $('#totalCompare').html('');
                singleAppend();
            }
            if (checked.val() == 'total') {
                $('#singleCompare').html('');
                $('#specialCards').html('');
                $('#extendCompare').html('');
                totalAppend();
            }
            if (checked.val() == 'extend') {
                $('#singleCompare').html('');
                $('#specialCards').html('');
                $('#totalCompare').html('');
                extendAppend();
                getItemRulesData();
                $('#selectFirst').change(function() {
                    $("input[name='selectFirst']").val($('#selectFirst').val());
                })
                $('#selectSecond').change(function() {
                    $("input[name='selectSecond']").val($('#selectSecond').val());
                })
                $('#selectThird').change(function() {
                    $("input[name='selectThird']").val($('#selectThird').val());
                })


            }
        })
    }

    function totalAppend() {
        $('#totalCompare').append(
            '<select id="operator" onchange="setInputOperator()">' +
            '            <option></option>' +
            '<option value="0">=</option>' +
            '<option value="1"><</option>' +
            '<option value="2"><=</option>' +
            '<option value="3">></option>' +
            '<option value="4">>=</option>' +
            '</select>' +
            '<input id="inputOpertor" name="operator" type="hidden">' +
            '<input type="hidden" name="status" value="3">' +
            '<input type="text" style="width:240" placeholder="請輸入數字，若超過一個請用 , 分開" id="total" name="total">' +
            '<a role="button" class="btn btn-danger" onclick="clearTotal()">清空</a>' +
            '<input type="submit" class="btn btn-primary" value="確認">')

    }

    function extendAppend() {
        $('#extendCompare').append(
            '<table border="1">' +
            '<tr>' +
            '<td>第一局</td>' +
            '<td>第二局</td>' +
            '<td>第三局</td>' +
            '</tr>' +
            '<tr>' +
            '<td>' +
            '<select id="selectFirst">' +
            '<option></option>' +
            '</select>' +
            '</td>' +
            '<td>' +
            '<select id="selectSecond">' +
            '<option></option>' +
            '</select>' +
            '</td>' +
            '<td>' +
            '<select id="selectThird">' +
            '<option></option>' +
            '</select>' +
            '</td>' +
            '</table>' +
            '<input type="hidden" name="selectFirst">' +
            '<input type="hidden" name="selectSecond">' +
            '<input type="hidden" name="selectThird">' +
            '<input type="hidden" name="status" value="4">' +
            '<input type="submit" class="btn btn-primary" value="確認">'

        );
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
            '<label><input type="checkbox" name="specialCards1[]" value="1">1</label>' +
            '<label><input type="checkbox" name="specialCards1[]" value="2">2</label>' +
            '<label><input type="checkbox" name="specialCards1[]" value="3">3</label>' +
            '<label><input type="checkbox" name="specialCards1[]" value="4">4</label>' +
            '<label><input type="checkbox" name="specialCards1[]" value="5">5</label>' +
            '</td>' +
            '<td style="text-align: center;">' +
            '<label><input type="checkbox" name="specialCards2[]" value="1">1</label>' +
            '<label><input type="checkbox" name="specialCards2[]" value="2">2</label>' +
            '<label><input type="checkbox" name="specialCards2[]" value="3">3</label>' +
            '<label><input type="checkbox" name="specialCards2[]" value="4">4</label>' +
            '<label><input type="checkbox" name="specialCards2[]" value="5">5</label>' +
            '</td>' +
            '<td style="text-align: center;">' +
            '<label><input type="checkbox" name="specialCards3[]" value="1">1</label>' +
            '<label><input type="checkbox" name="specialCards3[]" value="2">2</label>' +
            '<label><input type="checkbox" name="specialCards3[]" value="3">3</label>' +
            '<label><input type="checkbox" name="specialCards3[]" value="4">4</label>' +
            '<label><input type="checkbox" name="specialCards3[]" value="5">5</label>' +
            '</td>' +
            '</tr>' +
            '</table>');
        $('#specialCards').append(
            '<input type="hidden" name="status" value="2">' +
            '<input type="submit" class="btn btn-primary" value="確認">')
        $("input[name='specialCards1']").change(function() {
            disabledRadio();
        })
        $("input[name='specialCards2']").change(function() {
            disabledRadio();
        })
        $("input[name='specialCards3']").change(function() {
            disabledRadio();
        })
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
            '<input type="hidden" name="status" value="1">' +
            '<input type="submit" class="btn btn-primary" value="確認">')

    }
</script>