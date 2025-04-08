<script type="text/javascript">
    var sourceData = <?= $sourceData ?>;

    if (sourceData == 1) {
        $(".ShowDocumentList").show();
        $(".InternalNotes").show();
        $(".FileAttachment").show();
        $(".ApprovalHistory").show();
        $(".ViewDocument").hide();
        $(".DocumentWorkflow").hide();
    } else {
        $(".ShowDocumentList").hide();
        $(".InternalNotes").hide();
        $(".FileAttachment").hide();
        $(".ApprovalHistory").hide();
    }
</script>

<script>
    $('.mySearchCheckDocument').one('click', function() {
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
    });
</script>

<script>
    $('#DocumentType').on("select2:select", function(e) {

        ShowLoading();

        $('#TableCheckDocument').find('tbody').empty();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var keys = 0;

        // var DocumentTypeID = 77000000000057;
        var DocumentTypeID = $('#DocumentType').val();
        var e = document.getElementById("DocumentType");
        var DocumentTypeName = e.options[e.selectedIndex].text;

        $.ajax({
            type: 'GET',
            url: '{!! route("CheckDocument.ShowDocumentListData") !!}?DocumentTypeID=' + DocumentTypeID + '&DocumentTypeName=' + DocumentTypeName,
            success: function(data) {
                var no = 1;
                t = $('#TableCheckDocument').DataTable();
                t.clear().draw();
                $.each(data.data, function(key, val) {
                    keys += 1;
                    t.row.add([
                        '<tbody><tr><td><input id="businessDocument_RefID' + keys + '" value="' + val.Sys_ID + '" type="hidden"><input id="DocumentTypeName" value="' + data.DocumentTypeName + '" type="hidden">' + no++ + '</span></td>',
                        '<td><span style="position:relative;left:10px;">' + val.DocumentNumber + '</span></td>',
                        '<td><span style="position:relative;left:10px;">' + val.CombinedBudgetCode + '-' + val.CombinedBudgetName + '</span></td>',
                        '<td><span style="position:relative;left:10px;">' + val.CombinedBudgetSectionCode + '-' + val.CombinedBudgetSectionName + '</span></td></tr></tbody>',
                    ]).draw();
                });

                HideLoading();
            }
        });
    });
</script>

<script>
    $('#TableCheckDocument tbody').on('click', 'tr', function() {

        $("#mySearchCheckDocument").modal('toggle');

        var row = $(this).closest("tr");
        var documentNumber = row.find("td:nth-child(2)").text();
        var id = row.find("td:nth-child(1)").text();
        var businessDocument_RefID = $('#businessDocument_RefID' + id).val();
        var DocumentTypeName = $('#DocumentTypeName').val();

        $("#businessDocument_RefID").val(businessDocument_RefID);
        $("#businessDocumentNumber").val(documentNumber);
        $("#businessDocumentType_Name").val(DocumentTypeName);
    });
</script>

<script>

    $('.ViewDocument').on('click', function() {
        $(".DocumentWorkflow").hide();
        $(".ShowDocumentList").show();
        $(".InternalNotes").show();
        $(".FileAttachment").show();
        $(".ApprovalHistory").show();

        $(".ViewDocument").hide();
    });

</script>

<!-- VALIDATION IF FORM DOCUMENT NUMBER INPUTED, PURPOSE FOR DELETE DOCUMENT REF ID -->
<script>
    var triggered = 0;
    $('#businessDocumentNumber').on('input', function() {
        if (triggered == 0) {
            $('#businessDocument_RefID').val("");
            triggered++;
        }
    });
    $('#businessDocumentNumber').on('blur', function() {
        // reset the triggered value to 0
        triggered = 0;
    });
</script>

<script>
    $('#submit').on('click', function() {
        ShowLoading();
    });
</script>


<script>
    function LogTransaction(id, docNum, docName) {
        var page = 'http://localhost:20080/LogTransaction?id=' + id + '&docNum=' + docNum + '&docName=' + docName;
        var myWindow = window.open(page, "_blank", "scrollbars=yes,width=400,height=500,top=300");

    }
</script>


<script>
    // function ShowFileAttachment(id) {

    //     ShowLoading();
    var id = $("#Sys_ID_Advance").val();

    $(".ShowFileAttachment").hide();

    $('#TableFileAttachment').find('tbody').empty();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var keys = 0;

    $.ajax({
        type: 'GET',
        url: '{!! route("CheckDocument.FileAttachmentCheckDocument") !!}?businessDocumentForm_RefID=' + id,
        success: function(data) {
            if (data.status == 200) {
                $.each(data.data, function(key, val) {
                    var html = '<tr>' +
                        '<td>' + '<a href="' + val.entities.downloadURL + '">' + val.entities.name + '</td>' +
                        '</tr>';
                    $('table.TableFileAttachment tbody').append(html);

                });
            }
            HideLoading();
        }
    });
    // }z
</script>
