@extends('layouts.app')

@section('content')
<div class="container">

    <table class="table table-hover" id="table">
    </table>

    <form action="{{url('item/create')}}" method="GET">
        {{csrf_field()}}
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
        <input type="submit" value="新增">

    </form>
    <p id="storeButton" hidden><a type=role class="btn btn-primary" onclick="allEdit()">儲存</a></p>
</div>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js">
</script>

<script>
    window.onload = getData;
    var ItemEditarray = new Array();
    var EditCount = 0;
    //這是從第一次從前端去跟後端要資料的function
    function getData() {

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
    //這是提供單比資料修改的ajax
    function ajaxToEdit(id, itemnameid, inputrateid) {

        var itemname = $(itemnameid).val()
        var itemrate = $(inputrateid).val();

        $.ajax({
            type: "PUT",
            url: "{{url('item/{id}')}}",
            dataType: "json",
            data: {
                id: id,
                itemname: itemname,
                rate: itemrate
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
            var iterate = data[i][2];
            var td1 = '<td id="tdnameid' + [i] + '"><div value=' + itemname + ' id=' + 'itemname' + itemid + ' onclick="openLabel(' + 'itemname' + itemid + ',' + 'itemid' + itemid + ',' + i + ',' + itemid + ')">項目：' + itemname + '</div></td>';
            var td2 = '<td id="tdidid' + [i] + '"><div value=' + iterate + ' id=' + 'itemid' + itemid + ' onclick="openLabel(' + 'itemname' + itemid + ',' + 'itemid' + itemid + ',' + i + ',' + itemid + ')">賠率：' + iterate + '</div></td>';
            var td3 = '<td id="tdid' + [i] + '">';
            var td4 = '<a role="btn" class="btn btn-danger" onclick="ajaxToDelete(' + itemid + ')">刪除</a></td>';
            var tr = $('<tr >').append(td1, td2, td3, td4);
            $('#table').append(tr);
        }
    }
    //-----
    //這是當需要修改時點按觸發的修改function目的將div改成input來輸入資料
    function openLabel(itemnameid, itemidid, i, itemid) {

        delRow(itemnameid)
        delRow(itemidid)
        var tdnameid = '#tdnameid' + i;
        var tdidid = '#tdidid' + i;
        var itemnamevalue = itemnameid.attributes[0].nodeValue;
        var ratevalue = itemidid.attributes[0].nodeValue;
        var inputnameid = itemnameid.attributes[1].nodeValue;
        var rateid = itemidid.attributes[1].nodeValue;

        $(tdnameid).append('項目：<input required="required" name="itemname" onchange="changeDatatemp(' + inputnameid + ',' + rateid + ',' + itemid + ')" type="text" placeholder=' + itemnamevalue + ' value=' + itemnamevalue + ' id=' + inputnameid + ' >');
        $(tdidid).append('賠率：<input required="required"  name="itemrate" onchange="changeDatatemp(' + inputnameid + ',' + rateid + ',' + itemid + ')" type="number" step="0.0001" min="0.000" max="10000" placeholder=' + ratevalue + ' value=' + ratevalue + ' id=' + rateid + '>');

        store(i, itemid, inputnameid, rateid)
    }
    //-----
    //全域變數 count 目的是為了算使用者修改了幾項
    var count = 0;
    //-----
    //全域變數陣列 目的是將修改的資料放進去
    var temp = new Array;
    //-----
    //檢查該項是否被修改過 如果有備修改過 則將該項temp陣列變數存過得資料覆寫  
    function checkDataAltered(id, namevalue, ratevalue) {
        for (var i = 0; i < count; i++) {
            if (temp[i][0] == id) {
                temp.splice(i, 1)
                temp[i] = new Array;
                temp[i].push(id, namevalue, ratevalue)

                return false;
            }
        }

        return true;
    }
    //-----
    //當發生修改時觸發將修改的值放入temp變數中
    function changeDatatemp(nameid, rateid, id) {
        var namevalue = $(nameid).val();
        var ratevalue = $(rateid).val();
        if (checkDataAltered(id, namevalue, ratevalue)) {
            temp[count] = new Array;
            //temp.push(count)
            temp[count].push(id, namevalue, ratevalue)
            count++
        }
    }
    //-----
    //製作一個單向修改的儲存按鈕
    function store(i, itemid, inputnameid, rateid) {
        var tdid = '#tdid' + i;
        $(tdid).append('<a role="btn" class="btn btn-primary" onclick="ajaxToEdit(' + itemid + ',' + inputnameid + ',' + rateid + ')">儲存</a>');
        $(storeButton).show();
    }
    //-----
    //刪除一列方法 目的是來產生新的
    function delRow(obj) {
        $(obj).remove();
    }
    //-----
</script>