<div id="PopUpTableAdvanceRevision" class="modal fade" role="dialog" aria-labelledby="contohModalScrollableTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <label class="card-title">Choose Advance Request</label>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body table-responsive p-0" style="height: 430px;">
                                <table class="table table-head-fixed text-nowrap" id="TableSearchArfRevision">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Trano</th>
                                            <th>Budget Code</th>
                                            <th>Budget Name</th>
                                            <th>Sub Budget Code</th>
                                            <th>Sub Budget Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function debounce(func, wait) {
        let timeout;
        return function() {
            const context = this, args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(context, args), wait);
        };
    }

    $(function() {
        $('.PopUpTableAdvanceRevision').on('click', debounce(function(e) {
            e.preventDefault();

            var project_id = $('#project_id').val();
            var site_id = $('#site_id').val();
            var cacheKey = 'AdvanceListData_' + project_id + '_' + site_id;

            // Check if data is in sessionStorage
            var cachedData = sessionStorage.getItem(cacheKey);
            var parsedData = JSON.parse(cachedData);

            if (Array.isArray(parsedData) && parsedData.length > 0) {
                console.log('1');
                renderTable(JSON.parse(cachedData));
                return;
            }

            $.ajax({
                type: 'GET',
                url: '{!! route("AdvanceRequest.AdvanceListData") !!}?project_id=' + project_id + '&site_id=' + site_id,
                success: function(data) {
                    console.log('2');
                    // Store data in sessionStorage
                    sessionStorage.setItem(cacheKey, JSON.stringify(data));

                    renderTable(data);
                },
                error: function(xhr, status, error) {
                    console.error("AJAX error:", status, error);
                }
            });
        }, 300)); // Adjust debounce time as needed
    });

    function renderTable(data) {
        var no = 1;
        var keys = 0;

        // Check if DataTable is already initialized
        if ($.fn.DataTable.isDataTable('#TableSearchArfRevision')) {
            // If it is, destroy the existing instance
            $('#TableSearchArfRevision').DataTable().clear().destroy();
        }

        // Initialize DataTable with pagination
        var t = $('#TableSearchArfRevision').DataTable({
            "paging": true, // Enable pagination
            "pageLength": 10 // Adjust this number as needed
        });

        // Ensure data is an array
        if (Array.isArray(data)) {
            $.each(data, function(key, val) {
                keys += 1;
                t.row.add([
                    '<input id="sys_id_advance_revision' + keys + '" value="' + val.Sys_ID + '" type="hidden"><td>' + no++ + '</td>',
                    '<td>' + val.DocumentNumber + '</td>',
                    '<td>' + val.CombinedBudgetCode + '</td>',
                    '<td>' + val.CombinedBudgetName + '</td>',
                    '<td>' + val.CombinedBudgetSectionCode + '</td>',
                    '<td>' + val.CombinedBudgetSectionName + '</td>'
                ]).draw();
            });
        } else {
            console.error("Data is not an array:", data);
        }
    }
</script>

<script>
    $('#TableSearchArfRevision tbody').on('click', 'tr', function() {

        $('#advance_number').css("border", "1px solid #ced4da");
        $('#advance_number_icon').css("border", "1px solid #ced4da");

        $("#PopUpTableAdvanceRevision").modal('toggle');
        var row = $(this).closest("tr");
        var id = row.find("td:nth-child(1)").text();
        var advance_RefID = $('#sys_id_advance_revision' + id).val();
        var code = row.find("td:nth-child(2)").text();

        $("#advance_RefID").val(advance_RefID);
        $("#advance_number").val(code);

    });
</script>



<script>
    $('.btn-edit').on('click', function() {

        var advance_RefID = $('#advance_RefID').val();

        if (advance_RefID) {

            ShowLoading();
            window.location.href = '/RevisionAdvanceIndex?advance_RefID=' + advance_RefID;
        } else {
            $('#advance_number').focus();
            $('#advance_number').css("border", "1px solid red");
            $('#advance_number_icon').css("border", "1px solid red");
        }

    });
</script>

<script>
    $('.btn-cancel').on('click', function() {
        $('#advance_RefID').val("");
        $('#advance_number').val("");

    });
</script>
