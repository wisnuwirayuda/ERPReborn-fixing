<!-- SELECT FOR FILTER BY BUDGET  -->
<script>
    $('#tableGetProject tbody').on('click', 'tr', function() {
        $("#myProject").modal('toggle');
        var row = $(this).closest("tr");
        var id = row.find("td:nth-child(1)").text();
        var sys_id = $('#sys_id_budget' + id).val();
        var code = row.find("td:nth-child(2)").text();
        $("#projectid").val(sys_id);
        $("#projectcode").val(code);
    });
</script>

<!-- SELECT FOR FILTER BY DOCUMENT TYPE  -->
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: 'GET',
        url: '{!! route("getDocumentType") !!}',
        success: function(data) {
            $(".DocumentType").empty();

            var option = "<option value='" + '' + "'>" + 'Select Document Type' + "</option>";
            $(".DocumentType").append(option);

            var len = data.length;
            for (var i = 0; i < len; i++) {
                var ids = data[i].Sys_ID;
                var names = data[i].Name;
                var option2 = "<option value='" + ids + "'>" + names + "</option>";
                $(".DocumentType").append(option2);
            }
        }
    });
</script>

<!-- LOADING DATA MY DOCUMENT -->
<script>
    function SuccessDataMyDocument(data) {
        $('#TableMyDocument').find('tbody').empty();
        var keys = 0;
        var no = 1;
        var t = $('.TableMyDocument').DataTable();
        t.clear().draw();
        $.each(data, function(key, val) {
            const showData = {
                date: val.businessDocumentDateTimeTZ || val.entities.businessDocumentDateTimeTZ,
                remark: val.workFlowPathSubmitterRemarks || val.entities.workFlowPathSubmitterRemarks,
                businessDocument_RefID: val.formDocumentNumber_RefID || val.entities.formDocumentNumber_RefID,
                businessDocumentTypeName: val.businessDocumentTypeName || val.entities.businessDocumentTypeName,
                businessDocumentNumber: val.businessDocumentNumber || val.entities.businessDocumentNumber,
                combinedBudgetCode: val.combinedBudgetCode || val.entities.combinedBudgetCode,
                previousWorkFlowPathApproverName: val.previousWorkFlowPathApproverName || val.entities.previousWorkFlowPathApproverName,
                previousWorkFlowPathActionName: val.previousWorkFlowPathActionName || val.entities.previousWorkFlowPathActionName
            };

            const date = dateFns.format(dateFns.parse(showData.date, "yyyy-MM-dd hh:mm:ss"),
                'DD-MM-YYYY HH:mm');

            var remark = showData.remark;
            if (showData.remark == null) {
                remark = "-";
            }

            keys += 1;
            t.row.add([
                '<tbody><tr><input class="businessDocument_RefID' + keys + '" value="' + showData.businessDocument_RefID + '" type="hidden"><input class="businessDocumentTypeName' + keys + '" value="' + showData.businessDocumentTypeName + '" type="hidden"><td><span style="position:relative;left:10px;">' + no++ + '</span></td>',
                '<td><span style="position:relative;left:10px; color: blue; text-decoration: underline; cursor: pointer;">' + showData.businessDocumentNumber + '</span></td>',
                '<td><span style="position:relative;left:10px;">' + showData.combinedBudgetCode + '</span></td>',
                '<td><span style="position:relative;left:10px;">' + showData.previousWorkFlowPathApproverName + '</span></td>',
                '<td><span style="position:relative;left:10px;">' + date + '</span></td>',
                '<td><span style="position:relative;left:10px;">' + showData.previousWorkFlowPathActionName + '</span></td>',
                '<td><span style="position:relative;left:10px;white-space: pre-line">' + remark + '</span></td></tr></tbody>',
            ]).draw();
        });

        HideLoading();
    }
</script>

<!-- SHOW DATA FOR FIRST LOADING  -->
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var keys = 0;

    $.ajax({
        type: 'GET',
        url: '{!! route("MyDocument.ShowMyDocumentListData") !!}',
        success: function(data) {
            SuccessDataMyDocument(data);
        },
        error: function(response) {
            // CALL FUNCTION ERROR NOTIFICATION
            ErrorNotif("Data Not Found !");
        },
    });
</script>

<!-- SUBMIT FILTER  -->
<script>
    $(function() {
        $("#FormSubmitMyDocument").on("submit", function(e) {
            e.preventDefault();

            var action = $(this).attr("action");
            var method = $(this).attr("method");
            var form_data = new FormData($(this)[0]);
            var form = $(this);

            ShowLoading();

            $('.TableMyDocument').find('tbody').empty();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var keys = 0;

            $.ajax({
                url: action,
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: method,
                success: function(data) {
                    SuccessDataMyDocument(data);
                },
                error: function(response) {
                    HideLoading();
                    // CALL FUNCTION ERROR NOTIFICATION
                    ErrorNotif("Data Not Found !");
                },
            })
        });
    });
</script>

<!-- RESET FILTER  -->
<script type="text/javascript">
    function ResetFilter() {
        $("#trano").val("");
        // $(".DocumentType").empty();
        $("#projectid").val("");
        $("#projectcode").val("");
    }
</script>

<!-- SELECT DATA FOR SHOW DETAIL  -->
<script>
    $('.TableMyDocument tbody').on('click', 'tr', function() {

        var row = $(this).closest("tr");
        var id = row.find("td:nth-child(1)").text();
        var businessDocument_RefID = $('.businessDocument_RefID' + id).val();
        var businessDocumentTypeName = $('.businessDocumentTypeName' + id).val();
        
        ShowLoading();

        window.location.href = '/ShowDocumentByID?businessDocument_RefID=' + businessDocument_RefID + '&businessDocumentTypeName=' + businessDocumentTypeName ;

    });
</script>

<!-- HIDE SEARCHING PLUGIN FROM DATATABLE -->
<script>
    $(document).ready(function() {
        $('.TableMyDocument').DataTable({
            "searching": false,
        });
    });
</script>
