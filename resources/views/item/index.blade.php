@extends('layouts.app')

@section('content')
<div class="container">
    <a role="btn" href="{{url('Raterecord')}}">查看紀錄</a>
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

        <div>
            <input type="radio" id="compare" name="compare" value="totalCompare">
            <label for="compare">總比較</label>
        </div>
        <div>
            <input type="radio" id="compare" name="compare" value="singleCompare">
            <label for="compare">逐個比較</label>
        </div>
        <div id=totalCompare></div>
        <div id=singleCompare></div>
        <input name="submit" class="btn btn-primary" type="submit" value="新增">
        <a role="button" href="{{url('itemrule')}}">規則</a>
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

    function start() {
        getData();
        compareChange();
    }

    function compareChange() {
        $('[name=compare]').change(function() {
            var checked = $('[name=compare]:checked')
            console.log(checked.val());
            if (checked.val() == 'totalCompare') {
                totalCompare();
            }
            if (checked.val() == 'singleCompare') {
                singleCompare();
            }

        })
    }

    function singleCompare() {
        var firsround;
        var secondround;
        var thirdround;

        $('#totalCompare').html('');
        $('[name=itemname]').val('');

        var data = '第一局' + comparation('firstround') + '</br>' +
            '第二局' + comparation('secondround') + '</br>' +
            '第三局' + comparation('thirdround') + '</br>'
        $('#singleCompare').append(data)
        $('[name=firstround]').change(function() {
            firsround = $('[name=firstround]:checked').val()
            check();
        })
        $('[name=secondround]').change(function() {
            secondround = $('[name=secondround]:checked').val()
            check();
        })
        $('[name=thirdround]').change(function() {
            thirdround = $('[name=thirdround]:checked').val()
            check();
        })

        function check() {

            if (firsround == secondround && secondround == thirdround) {
                $("input[name='submit']").attr('disabled', 'disabled');
                $('[name=itemname]').val('');
            } else {
                if (firsround != null && secondround != null && thirdround != null) {
                    $("input[name='submit']").removeAttr('disabled');
                    f = converter(firsround);
                    s = converter(secondround);
                    t = converter(thirdround);
                    $('[name=itemname]').val(f + s + t);

                } else {
                    $('[name=itemname]').val('');
                }
            }


        }

    }

    function comparation(name) {
        var win = '<input type="radio" id="total" name="' + name + '" value="win">' + '<label for="total">贏</label>';
        var lost = '<input type="radio" id="total" name="' + name + '" value="lost">' + '<label for="total">輸</label>';
        var single = '<input type="radio" id="total" name="' + name + '" value="single">' +
            '<label for="total">單</label>';
        var double = '<input type="radio" id="total" name="' + name + '" value="double">' +
            '<label for="total">雙</label>';
        var big = '<input type="radio" id="total" name="' + name + '" value="big">' + '<label for="total">大</label>';
        var small = '<input type="radio" id="total" name="' + name + '" value="small">' +
            '<label for="total">小</label>';
        var draw = '<input type="radio" id="total" name="' + name + '" value="draw">' +
            '<label for="total">平</label>';
        return win + lost + draw + single + double + big + small;
    }

    function totalCompare() {
        $('#singleCompare').html('');
        $('[name=itemname]').val('');

        var data = comparation('total');

        $('#totalCompare').append(data)
        $('[name=total]').change(function() {
            var checked = $('[name=total]:checked')
            var val = converter(checked.val())
            $('[name=itemname]').val(val)

        })
    }

    function converter(data) {
        if (data == 'win') {
            return '贏'
        }
        if (data == 'lost') {
            return '輸'
        }
        if (data == 'big') {
            return '大'
        }
        if (data == 'small') {
            return '小'
        }
        if (data == 'single') {
            return '單'
        }
        if (data == 'double') {
            return '雙'
        }
        if (data == 'draw') {
            return '平'
        }

    }
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
    //這是提供單筆資料修改的ajax
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
            success:location.reload(),
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
            var td1 = '<td id="tdnameid' + [i] + '"><div value=' + itemname + ' id=' + 'itemname' + itemid +
                ' onclick="openLabel(' + 'itemname' + itemid + ',' + 'itemid' + itemid + ',' + i + ',' + itemid +
                ')">項目：' + itemname + '</div></td>';
            var td2 = '<td id="tdidid' + [i] + '"><div value=' + iterate + ' id=' + 'itemid' + itemid +
                ' onclick="openLabel(' + 'itemname' + itemid + ',' + 'itemid' + itemid + ',' + i + ',' + itemid +
                ')">賠率：' + iterate + '</div></td>';
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

        $(tdnameid).append('項目：<input required="required" name="itemname" onchange="changeDatatemp(' + inputnameid +
            ',' + rateid + ',' + itemid + ')" type="text" placeholder=' + itemnamevalue + ' value=' +
            itemnamevalue + ' id=' + inputnameid + ' >');
        $(tdidid).append('賠率：<input required="required"  name="itemrate" onchange="changeDatatemp(' + inputnameid +
            ',' + rateid + ',' + itemid + ')" type="number" step="0.0001" min="0.000" max="10000" placeholder=' +
            ratevalue + ' value=' + ratevalue + ' id=' + rateid + '>');

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
        $(tdid).append('<a role="btn" class="btn btn-primary" onclick="ajaxToEdit(' + itemid + ',' + inputnameid + ',' +
            rateid + ')">儲存</a>');
        $(storeButton).show();
    }
    //-----
    //刪除一列方法 目的是來產生新的
    function delRow(obj) {
        $(obj).remove();
    }
    //-----
</script>