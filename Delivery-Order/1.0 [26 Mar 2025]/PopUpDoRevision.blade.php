<div id="myPopUpDoRevision" class="modal fade" role="dialog" aria-hidden="true" style="margin-top: 180px;margin-left:6px;">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content" style="width:90%;">
            <div class="modal-header">
                <div class="modal-body">
                    <span style="font-size: 15px;position:relative;left:25%;font-weight:bold;">DELIVERY ORDER REVISION</span><br><br><br>
                    <form action="{{ route('DeliveryOrder.RevisionDeliveryOrderIndex') }}" method="post">
                        @csrf
                        <div class="card" style="margin-left: 8%;">
                            <div class="card-body">
                                <div class="form-group">
                                    <table>
                                        <tr>
                                            <td><label>Revision Number&nbsp;</label></td>
                                            <td>
                                                <div class="input-group">
                                                    <input id="do_RefID" style="border-radius:0;" name="do_RefID" type="hidden" class="form-control" hidden>
                                                    <input id="do_number" style="border-radius:0;" name="do_number" class="form-control">
                                                    <div class="input-group-append">
                                                        <span style="border-radius:0;" class="input-group-text form-control">
                                                            <a data-toggle="modal" data-target="#PopUpTableDoRevision"><img src="{{ asset('AdminLTE-master/dist/img/box.png') }}" width="13" alt=""></a>
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-sm" style="margin-left: 38%;background-color:#e9ecef;border:1px solid #ced4da;">
                            <img src="{{ asset('AdminLTE-master/dist/img/edit.png') }}" width="13" alt="" title="Edit"> Edit
                        </button>
                        <button type="reset" class="btn btn-sm" style="background-color:#e9ecef;border:1px solid #ced4da;">
                            <img src="{{ asset('AdminLTE-master/dist/img/cancel.png') }}" width="13" alt="" title="Edit"> Cancel
                        </button>
                    </form>
                </div>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
        </div>
    </div>
</div>


<div id="PopUpTableDoRevision" class="modal fade" role="dialog" aria-labelledby="contohModalScrollableTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <label class="card-title">Choose Delivery Order</label>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body table-responsive p-0" style="height: 400px;">
                                <table class="table table-head-fixed text-nowrap" id="TableSearchDeliveryOrder">
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
    $('#TableSearchDeliveryOrder tbody').on('click', 'tr', function() {

        $("#PopUpTableDoRevision").modal('toggle');
        var row = $(this).closest("tr");
        var id = row.find("td:nth-child(1)").text();
        var do_RefID = $('#sys_id_dor_revision' + id).val();
        var code = row.find("td:nth-child(2)").text();

        $("#do_RefID").val(do_RefID);
        $("#do_number").val(code);

    });
</script>
