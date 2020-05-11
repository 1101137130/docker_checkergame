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
            限制下注金額：<input type="number" name="limit_amount" placeholder="請輸入金額" step="0.5" min="0.000" max="10000000">

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
        <input type="radio" id="totalRadio" name="compare" value="total">
        <label for="totalRadio">總數</label>
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
        var type = "GET";
        var url = "{{url('itemrule')}}";
        ajax(type, url, function(data) {
            putItemRulesData(data);
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
        $('#totalRadio').val('');
        $('#operator').val('');
    }
    //這是從第一次從前端去跟後端要資料的function
    function getItemsData() {
        var type = "POST";
        var url = "{{url('item')}}";
        ajax(type, url, function(data) {
            add_row(data);
        })
    }
    //-----
    // 這是為了可以實現多筆資料一次修改
    function allEdit() {
        var type = "POST";
        var url = "{{url('item/edit')}}";
        var data = {
            temp: temp
        };
        ajaxWithData(type, url, data, function(back) {
            backDataAppend(back)
        });
    }
    //------
    //這是提供單筆資料修改
    function ajaxToEdit(id, itemnameid, inputrateid, inputlimitamountid) {

        var itemname = $(itemnameid).val();
        var itemrate = $(inputrateid).val();
        var limiamount = $(inputlimitamountid).val();
        var type = "PUT";
        var url = "{{url('item/{id}')}}";
        var data = {
            id: id,
            itemname: itemname,
            rate: itemrate,
            limit_amount: limiamount
        };
        ajaxWithData(type, url, data, function(back) {
            backDataAppend(back)
        });
    }
    //-----
    //這是刪除的ajax
    function ajaxToDelete(id) {
        var type = "DELETE";
        var url = "{{url('item/{id}')}}";
        var data = {
            id: id
        };
        ajaxWithData(type, url, data, function(back) {
            backDataAppend(back);
        });
    }
    //-----
    //這是開始時向後端要資料需要建立的td
    function add_row(data) {
        for (var i = 0; i < data.length; i++) {
            var itemid = data[i][0];
            var itemname = data[i][1];
            var itemrate = data[i][2];
            var itemlimit = data[i][3];
            if (data[i][4] == '1') {
                itemstatus = '可用';
                var td4 = '<td id="tdid' + [i] + '">' + '<button class="btn btn-danger" id="delete'+itemid+'" onclick="ajaxToDelete(' +
                itemid + ')">刪除</button></td>';
                var td5 = '';
            }
            if (data[i][4] == '2') {
                var td4 = '';
                itemstatus = '不可用';
                var td5 = '<td id="tdidd' + [i] + '">' +
                    '<a role="btn" class="btn btn-primary" id="reActive'+itemid+'" onclick="ajaxToReactive(' +
                    itemid + ')">重新啟用</a></td>';
            }
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
                ')">限制下注金額：' + itemlimit + '</div></</td>' +
                '<td>狀態：' + itemstatus + '</td>';
            var tr = $('<tr >').append(td1, td2, td3, td4, td5);
            $('#table').append(tr);
        }
    }
    //-----
    function backDataAppend(back) {
        $("#table").html('');
        getItemsData();
        if (back[0] === false) {
            $("#alert-danger-status").remove();
            $("#ajaxCallsBack").append('<div id ="alert-danger-status" class="alert alert-danger">' +
                '<strong>' + back[1] + '' +
                '</div>'
            );
            $("#alert-danger-status").delay(3000).hide(0);
        }
        if (back[0] === true) {
            $("#alert-success-status").remove()
            $("#ajaxCallsBack").append('<div id ="alert-success-status" class="alert alert-success">' +
                '<strong>' + back[1] + '' +
                '</div>'
            )
            $("#alert-success-status").delay(3000).hide(0);
        }
    }

    function ajaxToReactive(id) {
        var type = "POST";
        var url = "{{url('ItemReactive')}}";
        var data = {
            id: id
        };
        ajaxWithData(type, url, data, function(back) {
            backDataAppend(back)
        });
    }
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
            ',' + rateid + ',' + itemid + ',' + limitamountid + ')" type="text" placeholder=' + itemnamevalue +
            ' value=' +
            itemnamevalue + ' id=' + inputnameid + ' >');

        $(tdidid).append('賠率：<input required="required"  name="itemrate" onchange="changeDatatemp(' + inputnameid +
            ',' + rateid + ',' + itemid + ',' + limitamountid +
            ')" type="number" step="0.0001" min="0.000" max="10000" placeholder=' +
            ratevalue + ' value=' + ratevalue + ' id=' + rateid + '>');

        $(tdlimitid).append('限制下注金額：<input required="required"  name="limit_amount" onchange="changeDatatemp(' +
            inputnameid +
            ',' + rateid + ',' + itemid + ',' + limitamountid +
            ')" type="number" step="0.5000" min="0.000" max="10000000000" placeholder=' +
            limitamountvalue + ' value=' + limitamountvalue + ' id=' + limitamountid + '>');

        store(i, itemid, inputnameid, rateid, limitamountid)
    }
    //-----
    //全域變數 count 目的是為了算使用者修改了幾項
    var count = 0;
    //-----
    //全域變數陣列 目的是將修改的資料放進去
    var temp = new Array;
    //-----
    //檢查該項是否被修改過 如果有修改過 則將該項temp陣列變數的資料覆寫  
    function checkDataAltered(id, namevalue, ratevalue, limitamountvalue) {
        limitamountvalue == null ? 10000000000 : limitamountvalue;
        for (var i = 0; i < count; i++) {
            if (temp[i][0] == id) {
                temp.splice(i, 1)
                temp[i] = new Array;
                temp[i].push(id, namevalue, ratevalue, limitamountvalue)

                return false;
            }
        }

        return true;
    }
    //-----
    //當發生修改時觸發將修改的值放入temp變數中
    function changeDatatemp(nameid, rateid, id, limitamountid) {
        var namevalue = $(nameid).val();
        var ratevalue = $(rateid).val();
        var limitamountvalue = $(limitamountid).val()
        if (checkDataAltered(id, namevalue, ratevalue, limitamountvalue)) {
            temp[count] = new Array;
            //temp.push(count)
            temp[count].push(id, namevalue, ratevalue, limitamountvalue)
            count++
        }
    }
    //-----
    //製作一個單向修改的儲存按鈕
    function store(i, itemid, inputnameid, rateid, limitamountid) {
        var tdid = '#tdid' + i;
        $(tdid).append('<a role="btn" class="btn btn-primary" onclick="ajaxToEdit(' + itemid + ',' + inputnameid + ',' +
            rateid + ',' + limitamountid + ')">儲存</a>');
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
            '<input type="hidden" name="typeStatus" value="3">' +
            '<input type="text" style="width:240" placeholder="請輸入數字，若超過一個請用 , 分開" name="total">' +
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
            '<input type="hidden" name="typeStatus" value="4">' +
            '<input type="submit" class="btn btn-primary" value="確認">'
        );
    }
    var tdAlignCenter = '<td style="text-align: center;">';

    function specialAppend() {
        $('#specialCards').append(
            '<table border="1">' +
            '<tr>' +
            tdAlignCenter + '局數</td>' +
            labelAppend(null, 3) +
            '</tr>' +
            '<tr>' +
            tdAlignCenter + '我方獲勝所需</td>' +
            appendLoop('specialCards', 3) +
            '</tr>' +
            '</table>');
        $('#specialCards').append(
            '<input type="hidden" name="typeStatus" value="2">' +
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
    //建立所需label 給item規則
    function labelAppend(inputName, i) {
        var result = '';
        if (inputName == null) {
            for (j = 1; j <= i; j++) {
                result += tdAlignCenter;
                result += j + '</td>';
            }
        } else {
            result += labelinside(result, inputName, i)
        }
        return result;
    }

    function labelinside(result, name, i) {
        result += tdAlignCenter;
        for (j = 1; j <= 5; j++) {
            inputName = name + i;
            id = inputName + j;
            result += '<label><input id="' + id + '" type="checkbox" name="' + inputName + '[]" value="' + j + '">' +
                j + '</label>'
        }
        return result + '</td>';
    }

    function appendLoop(arg1, arg2) {
        var result = '';
        for (i = 1; i <= arg2; i++) {
            result += labelAppend(arg1, i)
        }
        return result;
    }

    function singleAppend() {
        var id = 'id = "winRequire';
        $('#singleCompare').append(
            '<table border="1">' +
            '<tr>' +
            tdAlignCenter + '對方結果</td>' +
            labelAppend(null, 5) +
            '</tr>' +
            '<tr>' +
            tdAlignCenter + '我方獲勝所需</td>' +
            appendLoop('winRequire', 5) +
            '</tr>' +
            '</table>');
        $('#singleCompare').append(
            '<input type="hidden" name="typeStatus" value="1">' +
            '<input type="submit" class="btn btn-primary" value="確認">')
    }
</script>