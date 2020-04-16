@extends('layouts.app')
@section('content')
<div class="container">
    <button class="btn btn-primary" onclick="getData('all')">取所有值</button>
    <label for="getDataByUser">依據使用者</label>
    <select id="getDataByUser"></select>
    <label for="getDataByItem">依據品項</label>
    <select id="getDataByItem"></select>
    <div>
        <label for="date_timepicker_start">開始日期</label>
        <input type="text" id="date_timepicker_start" name="date_timepicker_start">
        <label for="date_timepicker_end">結束日期</label>
        <input type="text" id="date_timepicker_end" name="date_timepicker_end">
        <button class="btn btn-primary" onclick="getData('date')">範圍搜索</button>
    </div>

    <div id="tablelocation"></div>
</div>
<link href="{{ asset('css/jquery.css') }}" rel="stylesheet">
<script>
    var datatemp = 0;
    var datalenth = 0;

    function dateFormater(date) {
        var dd = date.getDate();

        var mm = date.getMonth() + 1;
        var yyyy = date.getFullYear();
        var HH = date.getHours();
        var m = date.getMinutes();
        if (dd < 10) {
            dd = '0' + dd;
        }

        if (mm < 10) {
            mm = '0' + mm;
        }

        return yyyy + '-' + mm + '-' + dd + ' ' + HH + ':' + m;
    }

    function getStartEndDateValue(data) {
        data = '#date_timepicker_' + data;
        datavalue = $(data).val()

        if (datavalue == '') {
            now = new Date();
            datavalue = dateFormater(now)
        }

        return datavalue;
    }

    function getData(condition) {
        table(false);

        if (condition == 'date') {
            startdate = getStartEndDateValue('start');
            enddate = getStartEndDateValue('end');
            $.ajax({
                type: "POST",
                url: "{{url('getRaterecordDataByDate')}}",
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
                type: "GET",
                url: "{{url('getRaterecordDataAll')}}",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    temp: datatemp
                },
                success: function (data) {
                    buildData(data);

                },
                error: function (jqXHR) {
                    console.log(jqXHR)
                }
            })

        }
    }

    function table(command) {
        if (command == false) {
            $('#tablelocation').html('');
        }
        if (command == true) {
            $('#tablelocation').append('<table id="DataTalbe" class="display">' +
                ' <thead>' +
                '<tr>' +
                '<th style="text-align: center;">單號</th>' +
                '<th style="text-align: center;">修改者</th>' +
                '<th style="text-align: center;">修改項目</th>' +
                '<th style="text-align: center;">修改賠率</th>' +
                '<th style="text-align: center;">修改時間</th>' +
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
                '<th id="nextdata" style="text-align: center;"></th>' +
                '</tr>' +
                '</tfoot>' +
                '</table>')
        }

    }

    function buildData(data) {

        table(true);
        $.each(data, function (i, data) {

            data.created_at = timeconvert(data.created_at);


            var body = '<tr id="order' + data.id + '">';
            body += '<td style="text-align: center;">' + data.id + "</td>";
            body += '<td style="text-align: center;">' + data.username + "</td>";
            body += '<td style="text-align: center;">' + data.itemname +
                "</td>";
            body += '<td style="text-align: center;">' + parseFloat(data
                .rate) + "</td>";
            body += '<td style="text-align: center;">' + data.created_at +
                "</td>";

            body += "</tr>";
            $(body).appendTo($("tbody"));
            datalenth++;
        });
        $('#DataTalbe').DataTable({
            destroy: true,
            initComplete: function () {
                var api = this.api();

                api.columns().indexes().flatten().each(function (i) {
                    if (i > 0 && i < 4) {
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
        if (button == 'next' && datalenth > 100) {
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
        datatemp = datatemp - 1;
        getdata();
    }

    function nextDatafunction() {
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

</script>
@endsection
