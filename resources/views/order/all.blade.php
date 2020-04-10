@extends('layouts.app')
@section('content')
<div class="container">
    <button class="btn btn-primary" onclick="getOrders('all')">取所有值</button>
    <label for="date_timepicker_start">開始日期</label>
    <input type="text" id="date_timepicker_start" name="date_timepicker_start">
    <label for="date_timepicker_end">結束日期</label>
    <input type="text" id="date_timepicker_end" name="date_timepicker_end">
    <button class="btn btn-primary" onclick="getOrders('date')">範圍搜索</button>
    <div id="tablelocation"></div>
</div>
<link href="{{ asset('css/jquery.css') }}" rel="stylesheet">
<script>
    window.onload = getItemName
    var datatemp = 0;

    function getStartEndDateValue(data) {
        data = '#date_timepicker_' + data;
        datavalue = $(data).val()
        datavalue = new Date(datavalue);
        if (datavalue == '') {
            datavalue = new Date();
        }
        return datavalue.getTime() / 1000
    }

    function getOrders(condition) {
        table(false);
        if (condition == 'date') {
            startdate = getStartEndDateValue('start');
            enddate = getStartEndDateValue('end');

            $.ajax({
                type: "GET",
                url: "{{url('getOrdersDataBytime')}}",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    startdate: startdate,
                    enddate: enddate
                },
                success: function (data) {
                    buildData(data)

                },
                error: function (jqXHR) {
                    console.log(jqXHR)
                }
            })
        }
        if (condition == 'all') {
            $.ajax({
                type: "POST",
                url: "{{url('ordersAll')}}",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    buildData(data)
                },
                error: function (jqXHR) {
                    console.log(jqXHR)
                }
            })

        }
    }

    function table(command) {
        if (command == 0) {
            $('#tablelocation').html('');
        }
        if (command == 1) {
            $('#tablelocation').append('<table id="DataTalbe" class="display">' +
                ' <thead>' +
                '<tr>' +
                ' <th style="text-align: center;">單號</th>' +
                '<th style="text-align: center;">下注者</th>' +
                '<th style="text-align: center;">下注方</th>' +
                '<th style="text-align: center;">下注項目</th>' +
                '<th style="text-align: center;">注單狀態</th>' +
                '<th style="text-align: center;">下注金額</th>' +
                '<th style="text-align: center;">當下賠率</th>' +
                '<th style="text-align: center;">下注日期</th>' +
                '<th style="text-align: center;">註銷</th>' +
                '</tr>' +
                '</thead>' +
                '<tbody id="tbody">' +
                '</tbody>' +
                '<tfoot>' +
                '<tr>' +
                '<th id="lastdata" style="text-align: center;"></th>' +
                '<th style="text-align: center;"></th>' +
                '<th style="text-align: center;"></th>' +
                '<th style="text-align: center;"></th>' +
                '<th style="text-align: center;"></th>' +
                '<th style="text-align: center;"></th>' +
                '<th style="text-align: center;"></th>' +
                '<th style="text-align: center;"></th>' +
                '<th id="nextdata" style="text-align: center;"></th>' +
                '</tr>' +
                '</tfoot>' +
                '</table>')
        }

    }

    function buildData(data) {

        table(true);
        $.each(data, function (i, data) {
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
            body += '<td style="text-align: center;">' + data.id + "</td>";
            body += '<td style="text-align: center;">' + data.username + "</td>";
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
            body += '<td style="text-align: center;">' + '<button class="btn btn-danger" onclick="cancel(' +
                data.id + ')">註銷</button> ' +
                "</td>";
            body += "</tr>";
            $(body).appendTo($("tbody"));
        });
        // $("#DataTalbe").DataTable();
        $('#DataTalbe').DataTable({
            destroy: true,
            initComplete: function () {
                var api = this.api();

                api.columns().indexes().flatten().each(function (i) {
                    if (i > 0 && i < 7) {
                        var column = api.column(i);
                        var select = $(
                                '<select><option value=""></option></select>'
                            )
                            .appendTo($(column.footer()).empty())
                            .on('change', function () {
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
                            function (d,
                                j) {

                                select.append(
                                    '<option value="' +
                                    d + '">' + d +
                                    '</option>')
                            });
                    }

                });
            }
        });
        $('#DataTalbe').on('page.dt', function () {
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

    function getdata() {
        $.ajax({
            type: "POST",
            url: "{{url('tempData')}}",
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                temp: datatemp
            },
            success: function (data) {

                buildData(data);
                if (datatemp != 0) {
                    buildButton('last')
                }

            },
            error: function (jqXHR) {
                console.log(jqXHR)
            }
        })
    }

    function lastDatafunction() {
        table(false);
        datatemp = datatemp - 1;
        getdata();
    }

    function nextDatafunction() {
        table(false);
        datatemp++;
        getdata()

    }
    var itemname;
    jQuery(function () {
        jQuery('#date_timepicker_start').datetimepicker({
            format: 'Y-m-d H:m',
            onShow: function (ct) {
                this.setOptions({
                    maxDate: jQuery('#date_timepicker_end').val() ? jQuery(
                        '#date_timepicker_end').val() : Date.now()
                })
            }
        });
        jQuery('#date_timepicker_end').datetimepicker({
            format: 'Y/m/d H:m',
            onShow: function (ct) {
                this.setOptions({
                    minDate: jQuery('#date_timepicker_start').val() ? jQuery(
                        '#date_timepicker_start').val() : false
                })
            }
        });
    });

    function getItemName() {
        $
        $.ajax({
            type: "GET",
            url: "{{url('itemname')}}",
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },

            success: function (data) {
                itemname = data
            },
            error: function (jqXHR) {
                console.log(jqXHR)
            }
        })
    }

    function putitemname(id) {
        for (var i = 0; i <= itemname.length; i++) {
            if (itemname[i].id == id) {
                return itemname[i].itemname;
            }
        }

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
            success: function (data) {
                var orderid = '#order' + id
                var statusid = '#status' + id

                $(orderid).css("background-color", "#FFB5B5")
                $(statusid).html("註銷")
                console.log(data)
            },
            error: function (jqXHR) {
                console.log(jqXHR)
            }
        })
    }

</script>
@endsection
