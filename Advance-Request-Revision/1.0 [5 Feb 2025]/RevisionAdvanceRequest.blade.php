@extends('Partials.app')
@section('main')
@include('Partials.navbar')
@include('Partials.sidebar')
@include('getFunction.getSite')
@include('getFunction.getProduct')
@include('Process.Advance.AdvanceRequest.Functions.PopUp.PopUpAdvanceRevision')
@include('Process.Advance.AdvanceRequest.Functions.Table.TableAdvanceRevision')
@include('getFunction.getProject')
@include('getFunction.getWorker')
@include('getFunction.getBeneficiary')
@include('getFunction.getBank')
@include('getFunction.getEntityBankAccount')
@include('getFunction.getWorkFlow')

<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid">
      <div class="row mb-1" style="background-color:#4B586A;">
        <div class="col-sm-6" style="height:30px;">
          <label style="font-size:15px;position:relative;top:7px;color:white;">Advance Request Revision</label>
        </div>
      </div>
      @include('Process.Advance.AdvanceRequest.Functions.Menu.MenuAdvanceRequest')

      <div class="card" style="position:relative;bottom:10px;">
        <form method="post" enctype="multipart/form-data" action="{{ route('SelectWorkFlow') }}" id="FormUpdateAdvance">
          @csrf

          <input type="hidden" name="statusFinalApprove" value="{{ $statusFinalApprove }}" id="statusFinalApprove">
          <input type="hidden" name="DocumentTypeID" value="{{ $dataHeader['businessDocumentType_RefID'] }}" id="DocumentTypeID">
          <input type="hidden" name="Sys_ID_Advance" value="{{ $dataHeader['recordID'] }}" id="Sys_ID_Advance">

          <div class="tab-content p-3" id="nav-tabContent">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <label class="card-title">
                      Advance Request Revision
                    </label>
                    <div class="card-tools">
                      <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-angle-down btn-sm" style="color:black;"></i>
                      </button>
                    </div>
                  </div>
                  @include('Process.Advance.AdvanceRequest.Functions.Header.HeaderAdvanceRevision')
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <label class="card-title">
                      Advance Request Detail
                    </label>
                    <div class="card-tools">
                      <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-angle-down btn-sm" style="color:black;"></i>
                      </button>
                    </div>
                  </div>
                  @include('Process.Advance.AdvanceRequest.Functions.Header.HeaderAdvanceDetailRevision')
                </div>
              </div>
            </div>

            <div class="tab-pane fade show active" id="product-desc" role="tabpanel" aria-labelledby="product-desc-tab">
              <div class="row">
                <div class="col-12">
                  <div class="card">
                    <div class="card-header">
                      <label class="card-title">
                        File Attachment
                      </label>
                      <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                          <i class="fas fa-angle-down btn-sm" style="color:black;"></i>
                        </button>
                      </div>
                    </div>
                    <div class="card-body file-attachment">
                      <div class="row">
                        <div class="col-md-12">
                          <input type="text" id="dataInput_Log_FileUpload_Pointer_RefID" value="{{ $dataContent['attachmentFiles']['main']['log_FileUpload_Pointer_RefID'] }}" readonly="true" name="dataInput_Log_FileUpload_Pointer_RefID" hidden>
                          <input type="file" id="dataInput_Log_FileUpload_Pointer_RefID_Action" name="dataInput_Log_FileUpload_Pointer_RefID_Action" multiple="multiple" onchange="javascript: @php echo \App\Helpers\ZhtHelper\General\Helper_JavaScript::getSyntaxFunc_DOMInputFileContent(\App\Helpers\ZhtHelper\System\Helper_Environment::getUserSessionID_System(), $varAPIWebToken, 'Upload', 'dataInput_Log_FileUpload_Pointer_RefID', 'dataInput_Log_FileUpload_Pointer_RefID_Action', 'dataShow_ActionPanel', 'dataShow_MasterFileRecord'); @endphp;" />
                        </div>
                        <br><br>
                        <div class="col-md-12">
                          <div class="card-body table-responsive p-0" style="height:125px;">
                            <table class="table table-head-fixed table-sm text-nowrap">
                              <div class="form-group input_fields_wrap">
                                <div class="input-group control-group">
                                  <div id="dataShow_ActionPanel"></div>
                                </div>
                              </div>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="tab-pane fade show active" id="product-desc" role="tabpanel" aria-labelledby="product-desc-tab">
              <div class="row">
                <div class="col-12">
                  <div class="card">
                    <div class="card-header">
                      <label class="card-title">
                        Data Advance Detail
                      </label>
                      <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                          <i class="fas fa-angle-down btn-sm" style="color:black;"></i>
                        </button>
                      </div>
                    </div>
                    @include('Process.Advance.AdvanceRequest.Functions.Table.getBOQ')
                  </div>
                </div>
              </div>
            </div>



            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <label class="card-title">
                      Advance List (Cart)
                    </label>
                    <div class="card-tools">
                      <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-angle-down btn-sm" style="color:black;"></i>
                      </button>
                    </div>
                  </div>

                  <div class="card-body table-responsive p-0" style="height: 135px;" id="detailArfList">
                    <table class="table table-head-fixed table-sm text-nowrap TableAdvance" id="TableAdvance">
                      <thead>
                        <tr>
                          <th style="padding-top: 10px;padding-bottom: 10px;border:1px solid #e9ecef;text-align: center;">Transaction Number</th>
                          <th style="padding-top: 10px;padding-bottom: 10px;border:1px solid #e9ecef;text-align: center;">Product Id</th>
                          <th style="padding-top: 10px;padding-bottom: 10px;border:1px solid #e9ecef;text-align: center;">Product Name</th>
                          <th style="padding-top: 10px;padding-bottom: 10px;border:1px solid #e9ecef;text-align: center;">Uom</th>
                          <th style="padding-top: 10px;padding-bottom: 10px;border:1px solid #e9ecef;text-align: center;">Currency</th>
                          <th style="padding-top: 10px;padding-bottom: 10px;border:1px solid #e9ecef;text-align: center;">Price</th>
                          <th style="padding-top: 10px;padding-bottom: 10px;border:1px solid #e9ecef;text-align: center;">Qty</th>
                          <th style="padding-top: 10px;padding-bottom: 10px;border:1px solid #e9ecef;text-align: center;">Total</th>
                        </tr>
                      </thead>
                      <tbody>

                      </tbody>
                    </table>
                  </div>

                  <div class="card-body AdvanceListCart">
                    <table style="float:right;">
                      <tr>
                        <th> Total Item :
                          <!-- <span id="TotalQty"></span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; -->
                          <span id="GrandTotal"></span>
                        </th>
                      </tr>
                    </table>
                  </div>

                </div>
              </div>
            </div>
            <div class="tab-pane fade show active" id="product-desc" role="tabpanel" aria-labelledby="product-desc-tab">
              <div class="row">
                <div class="col-12">
                  <div class="card">
                    <div class="card-header">
                      <label class="card-title">
                        Remark
                      </label>
                      <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                          <i class="fas fa-angle-down btn-sm" style="color:black;"></i>
                        </button>
                      </div>
                    </div>
                    <div class="card-body Remark">
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <table>
                              <tr>
                                <td>
                                  <textarea name="var_remark" id="remark" rows="2" cols="150" class="form-control" required>{{ $dataContent['remarks'] }}</textarea>
                                </td>
                              </tr>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <a onclick="CancelAdvance()" class="btn btn-default btn-sm float-right" style="background-color:#e9ecef;border:1px solid #ced4da;">
                    <img src="{{ asset('AdminLTE-master/dist/img/cancel.png') }}" width="13" alt="" title="Cancel Advance List Cart"> Cancel
                  </a>
                  <button class="btn btn-default btn-sm float-right" type="submit" id="submitArf" style="margin-right: 5px;background-color:#e9ecef;border:1px solid #ced4da;">
                    <img src="{{ asset('AdminLTE-master/dist/img/save.png') }}" width="13" alt="" title="Submit to Advance"> Submit
                  </button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>
</div>
@include('Partials.footer')
@include('Process.Advance.AdvanceRequest.Functions.Footer.FooterAdvanceRequestRevision')
@endsection
