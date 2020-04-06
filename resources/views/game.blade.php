@extends('layouts.app')
@section('content')
<div class="container">
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
            <tbody>

                <tr>
                    <td style="text-align: center;">
                        <h1>莊家</h1>
                    </td>
                    <td style="text-align: center;">
                        <h1>閒家</h1>
                    </td>
                </tr>

                <tr>
                    <td style="text-align: center;">

                        <select class="form-control" id="object1winlost">
                        </select>

                        <input data-toggle="tooltip" data-placement="bottom" id="object1winlostamount"
                            class="form-control" type="number" onchange="dataToNap('object1winlost',this.id,1)"
                            onmouseover="compute(this.id, this.value, 'object1winlost')" placeholder="請輸入金額" step="5"
                            min="0">

                    </td>
                    <td style="text-align: center;">

                        <select class="form-control" id="object2winlost">
                        </select>

                        <input data-toggle="tooltip" data-placement="bottom"
                            onmouseover="compute(this.id, this.value, 'object2winlost')" type="number"
                            class="form-control" id="object2winlostamount"
                            onchange="dataToNap('object2winlost',this.id,2)" placeholder="請輸入金額" step="5" min="0">

                    </td>
                </tr>

                <tr>
                    <td style="text-align: center;">

                        <select class="form-control" id="object1bigsmall">
                        </select>

                        <input data-toggle="tooltip" data-placement="bottom"
                            onmouseover="compute(this.id, this.value, 'object1bigsmall')" type="number"
                            class="form-control" id="object1bigsmallamount"
                            onchange="dataToNap('object1bigsmall',this.id,1)" placeholder="請輸入金額" step="5" min="0">
                    </td>

                    <td style="text-align: center;">

                        <select class="form-control" id="object2bigsmall">
                        </select>

                        <input data-toggle="tooltip" data-placement="bottom"
                            onmouseover="compute(this.id, this.value, 'object2bigsmall')" type="number"
                            class="form-control" id="object2bigsmallamount"
                            onchange="dataToNap('object2bigsmall',this.id,2)" placeholder="請輸入金額" step="5" min="0">

                    </td>
                </tr>

                <tr>
                    <td style="text-align: center;">

                        <select class="form-control" id="object1singledouble">
                        </select>

                        <input data-toggle="tooltip" data-placement="bottom"
                            onmouseover="compute(this.id, this.value, 'object1singledouble')" type="number"
                            class="form-control" id="object1singledoubleamount"
                            onchange="dataToNap('object1singledouble',this.id,1)" placeholder="請輸入金額" step="5" min="0">

                    </td>
                    <td style="text-align: center;">

                        <select class="form-control" id="object2singledouble">
                        </select>

                        <input data-toggle="tooltip" data-placement="bottom"
                            onmouseover="compute(this.id, this.value, 'object2singledouble')" type="number"
                            class="form-control" id="object2singledoubleamount"
                            onchange="dataToNap('object2singledouble',this.id,2)" placeholder="請輸入金額" step="5" min="0">

                    </td>
                </tr>
            </tbody>
        </table>
        <div style="text-align: center;">
            <a role="btn" class="btn btn-primary" onclick="action()"> 下單/開始</a>
            <a class="btn btn-danger" href="{{url('show')}}" role="btn">清空</a>
        </div>
    </form>

</div>
@endsection


<script>
    window.onload = show;

    function show() {
        $.ajax({
            type: "POST",
            url: "{{url('show')}}",
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {

                var object1winlost = document.getElementById('object1winlost')
                var object2winlost = document.getElementById('object2winlost')
                var object1bigsmall = document.getElementById('object1bigsmall')
                var object2bigsmall = document.getElementById('object2bigsmall')
                var object1singledouble = document.getElementById('object1singledouble')
                var object2singledouble = document.getElementById('object2singledouble')

                object1winlost.options.add(new Option(data[0][1] + '：' + data[0][2], data[0]))
                object1winlost.options.add(new Option(data[1][1] + '：' + data[1][2], data[1]))
                object2winlost.options.add(new Option(data[0][1] + '：' + data[0][2], data[0]))
                object2winlost.options.add(new Option(data[1][1] + '：' + data[1][2], data[1]))

                object1bigsmall.options.add(new Option(data[2][1] + '：' + data[2][2], data[2]))
                object1bigsmall.options.add(new Option(data[3][1] + '：' + data[3][2], data[3]))
                object2bigsmall.options.add(new Option(data[2][1] + '：' + data[2][2], data[2]))
                object2bigsmall.options.add(new Option(data[3][1] + '：' + data[3][2], data[3]))

                object1singledouble.options.add(new Option(data[4][1] + '：' + data[4][2], data[4]))
                object1singledouble.options.add(new Option(data[5][1] + '：' + data[5][2], data[5]))
                object2singledouble.options.add(new Option(data[4][1] + '：' + data[4][2], data[4]))
                object2singledouble.options.add(new Option(data[5][1] + '：' + data[5][2], data[5]))

            },
            error: function (jqXHR) {
                console.log('error')
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

        array.push('\n' + '預估獲利：' + Math.round(posibleWinAmount))
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
        var ordersarray = new Array(); //因為map不能轉成json 所以不能傳道後台
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
        $.ajax({
            type: "POST",
            url: "{{url('game')}}",
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                order: ordersarray
            },
            success: function (data) {
                console.log(data)
                if (typeof data == typeof 'string') {
                    location.reload();
                } else {
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
                    if (data[4] != null) {
                        for (var i = 0; i < data[4].length; i++) {
                            array[i] = new Array;
                            array[i].push('\n' + '項目：' + toChinese(data[4][i][4]) + ' ' + data[4][i][0] +
                                ' 結果：' + toWinLost(data[4][i][5]))
                        }

                        alert(array)
                    }
                }
            },
            error: function (jqXHR) {
                console.log(jqXHR)
            }
        })
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

            $testrun = 'true'
            ajaxBack($testrun);

        }
    }

    function dataToNap(id, amountid, object) {
        var item = document.getElementById(id).value

        var item = item.split(",");
        var amount = document.getElementById(amountid).value

        //金額不為0則新增一個map 並放入 外部map-order
        //金額為0則判斷外部map有無此id 有則做刪除
        if (amount != 0) {
            const ord = new Map();
            ord.set('itemname', item[1])
            ord.set('itemid', item[0])
            ord.set('rate', item[2])
            ord.set('amount', amount)
            ord.set('object', object)
            order.set(id, ord)
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
            $(id).attr('title', '預估可贏 ：' + Math.round(amount * arry[2]));
        }
    }

</script>
