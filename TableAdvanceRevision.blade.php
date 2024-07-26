// HTML
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
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

// SCRIPT ON CLICK PopUpTableAdvanceRevision
$(function() {
  $('.PopUpTableAdvanceRevision').on('click', function(e) {
      e.preventDefault();

      var keys = 0;

      $.ajax({
          type: 'GET',
          url: '{!! route("AdvanceRequest.AdvanceListData") !!}?project_id=' + $('#project_id').val() + '&site_id=' + $('#site_id').val(),
          success: function(data) {
              var no = 1;

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
                      console.log("Processing item:", val); // Log each item
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
          },
          error: function(xhr, status, error) {
              console.error("AJAX error:", status, error);
          }
      });
  });
});
