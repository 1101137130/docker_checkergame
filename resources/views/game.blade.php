@extends('layouts.app')
@section('content')
<div class="container">
    <div id="log"></div>
    <table class="table table-hover">
        <tbody>
            <tr>
                <td></td>
                <td style="width: 25.4878%; text-align: center;">第一局</td>
                <td style="width: 28.0894%; text-align: center;">第二局</td>
                <td style="width: 25%; text-align: center;">第三局</td>
            </tr>
            <tr>
                <td style="text-align: center;">莊家 ：</td>
                <td id="ob1one" style="text-align: center;"></td>
                <td id="ob1two" style="text-align: center;"></td>
                <td id="ob1three" style="text-align: center;"></td>
            </tr>
            <tr>
                <td style="text-align: center;">閒家 ：</td>
                <td id="ob2one" style="text-align: center;"></td>
                <td id="ob2two" style="text-align: center;"></td>
                <td id="ob2three" style="text-align: center;"></td>
            </tr>
            <tr>
                <td style="text-align: center;">賽果：</td>
                <td id="win1" style="text-align: center;"></td>
                <td id="win2" style="text-align: center;"></td>
                <td id="win3" style="text-align: center;"></td>
            </tr>
            <tr>
                <td style="text-align: center;">總賽果：</td>
                <td></td>
                <td id="finalresult" style="text-align: center;"></td>
                <td></td>

            </tr>
        </tbody>
    </table>

    <form action="{{url('game')}}" method="POST">
        {{csrf_field()}}
        <table class="table table-hover">
            <tbody id="tbody">

                <tr>
                    <td style="text-align: center;">
                        <h1>莊家</h1>
                    </td>
                    <td style="text-align: center;">
                        <h1>閒家</h1>
                    </td>
                </tr>

            </tbody>
        </table>
        <div style="text-align: center;">
            <a role="btn" class="btn btn-primary" id="action" onclick="action()"> 下單/開始</a>
            <a class="btn btn-danger" href="{{url('show')}}" role="btn">清空</a>
        </div>
    </form>

</div>
@endsection


<script>
    window.onload = start;

    function start() {
        show();
    }

    function show() {
        var type = "POST";
        var url = "{{url('show')}}";
        ajax(type, url, function(data) {
            for (var i = 0; i <= data.length; i++) {
                $('#tbody').append(
                    '<tr>' +
                    '<td style="text-align: center;">' +
                    '<label for="bankerS' + data[i][0] +
                    '">' + data[i][1] + '：' + data[i][2] +
                    '</label>' +
                    '<input onchange="dataToMap(bankerS' + data[i][0] +
                    ')" id="bankerS' + data[i][0] +
                    '"  type="number" style="width:180"  placeholder="金額限制：' + data[i][3] +
                    '" min="0" max="' + data[i][3] + '">' +
                    '</td>' +
                    '<td style="text-align: center;">' +
                    '<label for="playerS' + data[i][0] +
                    '">' + data[i][1] + '：' + data[i][2] +
                    '</label>' +
                    '<input onchange="dataToMap(playerS' + data[i][0] +
                    ')" id="playerS' + data[i][0] +
                    '"  type="number"  style="width:180"  placeholder="金額限制：' + data[i][3] +
                    '" min="0" max="' + data[i][3] + '">' +
                    '</td>' +
                    '</tr>'
                )
            }
        })
    }
    const order = new Map(); //建立一個外部map來存放使用者選擇的資料

    function alertcontents() { //建立確認訊息來提供使用者作確認是否下注用
        var array = new Array();
        var posibleWinAmount = 0;
        for (var i = 0; i < ordersarray.length; i++) {
            array.push('\n' + '項目：' + convertObjectToName(ordersarray[i][4]) + ordersarray[i][0] + ' | ' +
                '賠率為：' + ordersarray[i][2] + ' | ' + '金額：' + ordersarray[i][3])
            posibleWinAmount += ordersarray[i][3] * ordersarray[i][2];
        }

        array.push('\n' + '預估獲利：' + posibleWinAmount)
        return array;
    }

    function convertObjectToName(object) {
        if (object == 1) {
            return '莊家'
        }
        if (object == 2) {
            return '閒家'
        }
    }

    function mapToString() { //建立一個string來接map裡的資料 
        var ordersarray = new Array(); //因為map不能轉成json 所以不能傳至後台
        var i = 0;
        for (let entry of order.keys()) {
            var j = 0;
            ordersarray[i] = new Array();
            ordersarray[i][j] = new Array();

            for (let e of order.get(entry)) {
                ordersarray[i][j] = e[1]
                j++;
            }
            i++
        }
        return ordersarray
    }

    function ajaxBack(ordersarray) {
        var type = "POST";
        var url = "{{url('game')}}";
        var data = {
            order: ordersarray
        };
        ajaxWithData(type, url, data, function(data) {
            if (data[0] === false) {
                //放在layout.app.blade
                backDataAppend(data);
            }
            if (data[0] === true) {
                data.shift();
                output(data);
            }

        })
    }

    function output(data) {
        $('#ob1one').html(data[0][0]);
        $('#ob1two').html(data[1][0]);
        $('#ob1three').html(data[2][0]);
        $('#ob2one').html(data[0][1]);
        $('#ob2two').html(data[1][1]);
        $('#ob2three').html(data[2][1]);
        $('#win1').html(data[0][2]);
        $('#win2').html(data[1][2]);
        $('#win3').html(data[2][2]);
        $('#finalresult').html(toChinese(data[3]));
        $('#winamount').html(data[5]);
        getAmount();
        var array = new Array;
        for (i = 4; i < data.length - 1; i++) {
            if (data[i] != null) {
                array[i] = new Array;
                array[i].push('項目：' + data[i][0] + ' ' + toChinese(data[i][4]) +
                    ' 結果：' + toWinLost(data[i][5]) + '</br>')
                logAppend(array)
            }
        }
    }

    function logAppend(data) {
        $('#log').html('')
        data.forEach(element => $('#log').append('<p>' + element + '</p>'));
    }

    function toWinLost($object) {
        if ($object == 1) {
            return '贏';
        }
        if ($object == 0) {
            return '輸';
        }
    }

    function toChinese($object) {
        if ($object == 1) {
            return '莊家';
        }
        if ($object == 2) {
            return '閒家';
        }
        if ($object == 3) {
            return '平手';
        }
    }

    function action() {
        ordersarray = mapToString();
        if (ordersarray.length > 0) {
            var array = alertcontents();

            var yes = confirm(array + '\n' + '你確定嗎？')
            if (yes) {
                ajaxBack(ordersarray);
            } else {
                location.reload();
            }
        } else {

            var testrun = 'true'
            ajaxBack(testrun);

        }
    }

    function dataToMap(id) {
        var max = parseInt(id.max, 10)
        if (id.value > max) {
            id.value = max
        }
        var objectId = id.id.split("S");
        var object = objectId[0] == 'banker' ? 1 : 2;
        var itemid = objectId[1];
        var itemNameAndRate = $('label[for=' + id.id + ']').text().split("：")

        itemName = itemNameAndRate[0];
        itemRate = itemNameAndRate[1];
        //金額不為0則新增一個map 並放入 外部map-order
        //金額為0則判斷外部map有無此id 有則做刪除
        if (id.value != 0) {
            const ord = new Map();
            ord.set('itemname', itemName)
            ord.set('itemid', itemid)
            ord.set('rate', itemRate)
            ord.set('amount', id.value)
            ord.set('object', object)
            order.set(id.id, ord)
        } else {
            if (order.has(id)) {
                order.delete(id)
            }
        }
    }

    function compute(id, amount, rateid) {
        var rateid = '#' + rateid;
        var id = '#' + id
        rate = $(rateid).val()
        if (rate != null) {
            var arry = rate.split(',')
            $(id).attr('title', '預估可贏 ：' + amount * arry[2]);
        }
    }
</script>