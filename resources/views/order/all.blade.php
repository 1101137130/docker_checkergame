@extends('layouts.app')
@section('content')
<div class="container">
    <label for="date_timepicker_start">開始日期</label>
    <input type="text" id="date_timepicker_start" name="date_timepicker_start">
    <label for="date_timepicker_end">結束日期</label>
    <input type="text" id="date_timepicker_end" name="date_timepicker_end">
    <div>
        <span id="useriddiv"></span>
        <label for="betobject">下注對象</label>
        <select id="betobject">
            <option value=""></option>
            <option value="1">莊家</option>
            <option value="2">閒家</option>
        </select>
        <label for="itemid">下單項目</label>
        <select id="itemid">
            <option value=""></option>
        </select>
        <label for="status">注單狀態</label>
        <select id="status">
            <option value=""></option>
            <option value="1">新建</option>
            <option value="2">贏</option>
            <option value="3">輸</option>
            <option value="4">註銷</option>
            <option value="5">作廢</option>
        </select>
        <button class="btn btn-primary" onclick="getOrders()">範圍搜索</button>
    </div>

    <div id="tablelocation">

    </div>
</div>
<link href="{{ asset('css/jquery.css') }}" rel="stylesheet">
<script>
    window.onload = start
    var datatemp = 0;
    var itemIdName;
    userid = null;
    function start() {
        getItemName();
        getUserAuthority();

        jQuery(function() {
            jQuery('#date_timepicker_start').datetimepicker({
                format: 'Y-m-d H:m',
                onShow: function(ct) {
                    this.setOptions({
                        maxDate: jQuery('#date_timepicker_end').val() ? jQuery(
                            '#date_timepicker_end').val() : Date.now()
                    })
                }
            });
            jQuery('#date_timepicker_end').datetimepicker({
                format: 'Y/m/d H:m',
                onShow: function(ct) {
                    this.setOptions({
                        minDate: jQuery('#date_timepicker_start').val() ? jQuery(
                            '#date_timepicker_start').val() : false
                    })
                }
            });
        });
    }

    function getUserAuthority() {
        $.ajax({
            type: "GET",
            url: "{{url('getuser')}}",
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                if (data.view_orders == 1) {
                    userauth = 1;
                    $('#useriddiv').append(
                        '<label for="userid">下單者</label>' +
                        '<select id="userid">' +
                        '<option value=""></option>' +
                        '</select>')
                } else {
                    userid = data.id;
                    userauth = 0;
                }
                getUserName();

            },
            error: function(jqXHR) {
                console.log(jqXHR)
            }
        })
    }

    function getStartEndDateValue(data) {
        data = '#date_timepicker_' + data;
        datavalue = $(data).val()
        datavalue = new Date(datavalue);
        if (datavalue == '') {
            return '';
        }
        return datavalue.getTime() / 1000
    }

    function getUserItemValue(id) {
        id = '#' + id;
        $data = $(id).val();
        return $data;
    }

    function getOrders() {
        table(0);
        startdate = getStartEndDateValue('start');
        enddate = getStartEndDateValue('end');
        itemid = getUserItemValue('itemid');
        status = $('#status').val();
        betobject = $('#betobject').val();

        if (userauth == 0) {
            userid = userid;
            table(2);
        } else {
            userid = getUserItemValue('userid');
            table(1)
        }

        $.ajax({
            type: "GET",
            url: "{{url('getOrdersData')}}",
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                startdate: startdate,
                enddate: enddate,
                temp: datatemp,
                userid: userid,
                itemid: itemid,
                status: status,
                betobject: betobject
            },
            success: function(data) {
                buildData(data, userauth)
            },
            error: function(jqXHR) {
                console.log(jqXHR)
            }
        })
    }

    function table(command) {

        if (command == 1) {
            dataHead =
                '<th style="text-align: center;">單號</th>' +
                '<th style="text-align: center;">下注者</th>' +
                '<th style="text-align: center;">下注方</th>' +
                '<th style="text-align: center;">下注項目</th>' +
                '<th style="text-align: center;">注單狀態</th>' +
                '<th style="text-align: center;">下注金額</th>' +
                '<th style="text-align: center;">當下賠率</th>' +
                '<th style="text-align: center;">下注日期</th>' +
                '<th style="text-align: center;">註銷</th>';
            dataFoot =
                '<th style="text-align: center;"></th>' +
                '<th style="text-align: center;"></th>' +
                '<th style="text-align: center;"></th>' +
                '<th style="text-align: center;"></th>' +
                '<th style="text-align: center;"></th>' +
                '<th style="text-align: center;"></th>' +
                '<th style="text-align: center;"></th>' +
                '<th style="text-align: center;"></th>';
        }
        if (command == 2) {
            dataHead =
                '<th style="text-align: center;">下注方</th>' +
                '<th style="text-align: center;">下注項目</th>' +
                '<th style="text-align: center;">注單狀態</th>' +
                '<th style="text-align: center;">下注金額</th>' +
                '<th style="text-align: center;">當下賠率</th>' +
                '<th style="text-align: center;">下注日期</th>';
            dataFoot =
                '<th style="text-align: center;"></th>' +
                '<th style="text-align: center;"></th>' +
                '<th style="text-align: center;"></th>' +
                '<th style="text-align: center;"></th>' +
                '<th style="text-align: center;"></th>';
        }
        if (command != 0) {
            append = '<table id="DataTalbe" class="display">' +
                ' <thead>' +
                '<tr>';
            append = append + dataHead;

            append = append + '</tr>' +
                '</thead>' +
                '<tbody id="tbody">' +
                '</tbody>' +
                '<tfoot>' +
                '<tr>';
            append = append + dataFoot

            append = append + '<th style="text-align: center;"></th>' +
                '</tr>' +
                '</tfoot>' +
                '</table>' +
                '<div><span id="lastdata"></span><span id="nextdata"></span></div>';

            $('#tablelocation').append(append);
        } else {
            $('#tablelocation').html('');
        }


    }

    function buildData(data, userauth) {
        if (datatemp != 0) {
            buildButton('last');
        }
        $.each(data, function(i, data) {
            if (data.bet_object == 1) {
                data.bet_object = '莊家'
            } else {
                data.bet_object = '閒家'
            }
            if (data.status == 1 | data.status == '1') {
                data.status = '新建'
            } else if (data.status == 2) {
                data.status = '贏'
            } else if (data.status == 3) {
                data.status = '輸'
            } else if (data.status == 4) {
                data.status = '註銷'
            } else if (data.status == 5) {
                data.status = '作廢'
            }

            data.created_at = timeconvert(data.created_at);


            var body = '<tr id="order' + data.id + '">';
            if (userauth == 1) {
                body += '<td style="text-align: center;">' + data.id + "</td>";
                body += '<td style="text-align: center;">' + data.username + "</td>";
            }
            body += '<td style="text-align: center;">' + data.bet_object +
                "</td>";
            body += '<td style="text-align: center;">' + putitemname(data.item_id) +
                "</td>";
            body += '<td id="status' + data.id + '" style="text-align: center;">' + data.status +
                "</td>";
            body += '<td style="text-align: center;">' + parseFloat(data
                .amount) + "</td>";
            body += '<td style="text-align: center;">' + parseFloat(data
                .item_rate) + "</td>";
            body += '<td style="text-align: center;">' + data.created_at +
                "</td>";
            if (userauth == 1) {
                body += '<td style="text-align: center;">' + '<button class="btn btn-danger" onclick="cancel(' +
                    data.id + ')">註銷</button> ' +
                    "</td>";
            }

            body += "</tr>";
            $(body).appendTo($("tbody"));
        });
        $('#DataTalbe').DataTable({
            destroy: true,
            initComplete: function() {
                var api = this.api();

                api.columns().indexes().flatten().each(function(i) {
                    if (userauth == 1) {
                        if (i > 0 && i < 7) {
                            var column = api.column(i);
                            var select = $(
                                    '<select><option value=""></option></select>'
                                )
                                .appendTo($(column.footer()).empty())
                                .on('change', function() {
                                    var val = $.fn.dataTable.util
                                        .escapeRegex(
                                            $(this).val()
                                        );

                                    column
                                        .search(val ? '^' + val +
                                            '$' :
                                            '', true, false)
                                        .draw();
                                });

                            column.data().unique().sort().each(
                                function(d,
                                    j) {

                                    select.append(
                                        '<option value="' +
                                        d + '">' + d +
                                        '</option>')
                                });
                        }
                    }
                    if (userauth == 0) {
                        if (i < 5) {
                            var column = api.column(i);
                            var select = $(
                                    '<select><option value=""></option></select>'
                                )
                                .appendTo($(column.footer()).empty())
                                .on('change', function() {
                                    var val = $.fn.dataTable.util
                                        .escapeRegex(
                                            $(this).val()
                                        );

                                    column
                                        .search(val ? '^' + val +
                                            '$' :
                                            '', true, false)
                                        .draw();
                                });

                            column.data().unique().sort().each(
                                function(d,
                                    j) {

                                    select.append(
                                        '<option value="' +
                                        d + '">' + d +
                                        '</option>')
                                });
                        }

                    }

                });
            }
        });
        $('#DataTalbe').on('page.dt', function() {
            var info = $(this).DataTable().page.info();
            if (info.end == info.recordsTotal) {
                buildButton('next');

            } else {
                $('#nextdata').html('');
            }
        });


    }

    function buildButton(button) {
        if (button == 'next') {
            $('#nextdata').append('<button onclick="nextDatafunction()">下一百筆資料</button>')
        }
        if (button == 'last') {
            $('#lastdata').append('<button onclick="lastDatafunction()">上一百筆資料</button>')
        }
    }

    function lastDatafunction() {
        table(false);
        datatemp = datatemp - 1;
        getOrders();

    }

    function nextDatafunction() {
        table(false);
        datatemp++;
        getOrders()

    }
    var itemname;


    function getItemName() {
        $.ajax({
            type: "GET",
            url: "{{url('getItemname')}}",
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },

            success: function(data) {
                itemIdName = data;
                $.each(data, function(i, data) {
                    itemnNameAppend(data.id, data.itemname)
                })

            },
            error: function(jqXHR) {
                console.log(jqXHR)
            }
        })
    }

    function putitemname(id) {
        for (var i = 0; i <= itemIdName.length; i++) {
            if (itemIdName[i].id == id) {
                return itemIdName[i].itemname;
            }
        }

    }

    function getUserName() {
        $.ajax({
            type: "GET",
            url: "{{url('getOrderUserName')}}",
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },

            success: function(data) {
                $.each(data, function(i, data) {
                    userNameAppend(data.user_id, data.username)
                })

            },
            error: function(jqXHR) {
                console.log(jqXHR)
            }
        })
    }

    function itemnNameAppend(id, value) {
        $("#itemid").append($("<option></option>").attr("value", id).text(value));
    }

    function userNameAppend(id, value) {
        $("#userid").append($("<option></option>").attr("value", id).text(value));
    }

    function cancel(id) {
        $.ajax({
            type: "POST",
            url: "{{url('orderdelete')}}",
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                id: id,
                status: 'cancel'
            },
            success: function(data) {
                var orderid = '#order' + id
                var statusid = '#status' + id

                $(orderid).css("background-color", "#FFB5B5")
                $(statusid).html("註銷")
            },
            error: function(jqXHR) {
                console.log(jqXHR)
            }
        })
    }
</script>
@endsection