@extends('layouts.app')
@section('content')

<html>

<head>
    <meta charset="utf-8" />
    <title>Client Side jQuery DataTables</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" />
</head>

<body>
    <table id="DataTalbe" class="display">
        <thead>
            <tr>
                <th style="text-align: center;">單號</th>
                <th style="text-align: center;">下注方</th>
                <th style="text-align: center;">注單狀態</th>
                <th style="text-align: center;">下注金額</th>
                <th style="text-align: center;">當下賠率</th>
                <th style="text-align: center;">下注日期</th>
            </tr>
        </thead>
        <tbody id="tbody">

        </tbody>
        <tfoot>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </tfoot>
    </table>

    <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {


        });
        $(function() {
            $.ajax({
                type: "GET",
                url: "{{url('getOrder')}}",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },

                success: function(data) {
                    $.each(data, function(i, data) {
                        if (data.bet_object == 1) {
                            data.bet_object = '莊家'
                        } else {
                            data.bet_object = '閒家'
                        }
                        if (data.sttus == 1 | data.sttus == '1') {
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
                        var body = "<tr>";
                        body += '<td style="text-align: center;">' + data.id + "</td>";
                        body += '<td style="text-align: center;">' + data.bet_object + "</td>";
                        body += '<td style="text-align: center;">' + data.status + "</td>";
                        body += '<td style="text-align: center;">' + parseFloat(data.amount) + "</td>";
                        body += '<td style="text-align: center;">' + parseFloat(data.item_rate) + "</td>";
                        body += '<td style="text-align: center;">' + data.created_at + "</td>";
                        body += "</tr>";
                        $(body).appendTo($("tbody"));
                    });
                    // $("#DataTalbe").DataTable();
                    $('#DataTalbe').DataTable({
                        initComplete: function() {
                            var api = this.api();

                            api.columns().indexes().flatten().each(function(i) {
                                var column = api.column(i);
                                console.log(column.footer())
                                var select = $('<select><option value=""></option></select>')
                                    .appendTo($(column.footer()).empty())
                                    .on('change', function() {
                                        var val = $.fn.dataTable.util.escapeRegex(
                                            $(this).val()
                                        );

                                        column
                                            .search(val ? '^' + val + '$' : '', true, false)
                                            .draw();
                                    });

                                column.data().unique().sort().each(function(d, j) {
                                    select.append('<option value="' + d + '">' + d + '</option>')
                                });
                            });
                        }
                    });
                },
                error: function(jqXHR) {
                    console.log(jqXHR)
                }
            })

        });

        function timeconvert(unixtimestamp) {
            // Months array
            var months_arr = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

            // Convert timestamp to milliseconds
            var date = new Date(unixtimestamp * 1000);

            // Year
            var year = date.getFullYear();

            // Month
            var month = months_arr[date.getMonth()];

            // Day
            var day = date.getDate();

            // Hours
            var hours = date.getHours();

            // Minutes
            var minutes = "0" + date.getMinutes();

            // Seconds
            var seconds = "0" + date.getSeconds();

            // Display date time in MM-dd-yyyy h:m:s format
            var convdataTime = month + '-' + day + '-' + year + ' ' + hours + ':' + minutes.substr(-2) + ':' + seconds.substr(-2);

            return convdataTime;

        }
    </script>
</body>

</html>
@endsection