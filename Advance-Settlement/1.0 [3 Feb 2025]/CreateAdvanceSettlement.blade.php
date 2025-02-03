@extends('Partials.app')
@section('main')
@include('Partials.navbar')
@include('Partials.sidebar')
@include('getFunction.getProject')
@include('getFunction.getSite')
@include('getFunction.getProduct')
@include('Process.Advance.AdvanceSettlement.Functions.PopUp.SearchAdvance')
@include('Process.Advance.AdvanceSettlement.Functions.PopUp.PopUpAdvanceSettlementRevision')
<!-- @include('getFunction.getBank')
@include('getFunction.getEntityBankAccount') -->

<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid">
      <div class="row mb-1" style="background-color:#4B586A;">
        <div class="col-sm-6" style="height:30px;">
          <label style="font-size:15px;position:relative;top:7px;color:white;">Advance Settlement</label>
        </div>
      </div>
      @include('Process.Advance.AdvanceSettlement.Functions.Menu.MenuAdvanceSettlement')
      @if($var == 0)
      <div class="card" style="position:relative;bottom:10px;">
        <form method="post" enctype="multipart/form-data" action="{{ route('AdvanceSettlement.store') }}" id="FormStoreAdvanceSettlement">
          @csrf
          <div class="tab-content p-3" id="nav-tabContent">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <label class="card-title">
                      Add New Settlement
                    </label>
                    <div class="card-tools">
                      <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-angle-down btn-sm" style="color:black;"></i>
                      </button>
                    </div>
                  </div>
                  @include('Process.Advance.AdvanceSettlement.Functions.Header.HeaderAdvanceSettlement')
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <label class="card-title">
                      Detail Settlement
                    </label>
                    <div class="card-tools">
                      <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-angle-down btn-sm" style="color:black;"></i>
                      </button>
                    </div>
                  </div>

                  <div class="card-body">
                    <div class="row">

                      <div class="col-md-6">
                        <div class="form-group">
                          <table>
                            <tr>
                              <td style="padding-top: 5px;"><label>&nbsp;&nbsp;&nbsp;Bank Name</label></td>
                              <td>
                                <div class="input-group" style="width: 70%;">
                                  <input id="bank_code" style="border-radius:0;" class="form-control" name="bank_code" hidden>
                                  <input id="bank_name" style="border-radius:0;" name="bank_name" class="form-control" readonly>
                                  <div class="input-group-append">
                                    <span style="border-radius:0;" class="input-group-text form-control">
                                      <a href="#" id="bank_name_popup" data-toggle="modal" data-target="#myGetBank" class="myGetBank"><img src="{{ asset('AdminLTE-master/dist/img/box.png') }}" width="13" alt=""></a>
                                    </span>
                                  </div>
                                </div>
                              </td>
                              <td>
                                <div class="input-group" style="width: 140%;position:relative;right:38%;">
                                  <input id="bank_name_detail" style="border-radius:0;" class="form-control" name="bank_name_full" readonly>
                                </div>
                              </td>
                            </tr>
                            <tr>
                              <td style="padding-top: 5px;"><label>&nbsp;&nbsp;&nbsp;Bank Account</label></td>
                              <td>
                                <div class="input-group" style="width: 70%;">
                                  <input id="bank_account_id" style="border-radius:0;" class="form-control" name="bank_account_id" hidden>
                                  <input id="bank_account" style="border-radius:0;" name="bank_account" class="form-control" readonly>
                                  <div class="input-group-append">
                                    <span style="border-radius:0;" class="input-group-text form-control">
                                      <a href="#" id="bank_account_popup" data-toggle="modal" data-target="#myEntityBankAccount" class="myEntityBankAccount"><img src="{{ asset('AdminLTE-master/dist/img/box.png') }}" width="13" alt=""></a>
                                    </span>
                                  </div>
                                </div>
                              </td>
                              <td>
                                <div class="input-group" style="width: 140%;position:relative;right:38%;">
                                  <input id="bank_account_detail" style="border-radius:0;" class="form-control" name="bank_account_detail" readonly>
                                </div>
                              </td>
                            </tr>
                          </table>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group">
                          <table>
                            <tr>
                              <td style="padding-bottom:20px;"><Label>Remark</Label></td>
                              <td>
                                <div class="input-group">
                                  <textarea name="remark" id="remark" style="border-radius:0;" cols="30" rows="3" class="form-control"></textarea>
                                </div>
                              </td>
                            </tr>
                          </table>
                        </div>
                      </div>

                    </div>
                  </div>
                </div>
              </div>
            </div>

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
                        <?php echo \App\Helpers\ZhtHelper\General\Helper_JavaScript::getSyntaxCreateDOM_DivCustom_InputFile(
                          \App\Helpers\ZhtHelper\System\Helper_Environment::getUserSessionID_System(),
                          $varAPIWebToken,
                          'dataInput_Log_FileUpload_1',
                          null,
                          'dataInput_Return'
                          ).
                        ''; ?>
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
                  @include('Process.Advance.AdvanceSettlement.Functions.Table.TableArfDetail')
                </div>
              </div>
            </div>

            <nav class="w-100">
              <div class="nav nav-tabs" id="product-tab" role="tablist">
                <a class="nav-item nav-link active idExpense" id="product-comments-tab" data-toggle="tab" href="#expense" role="tab" aria-controls="product-comments" aria-selected="true"><span style="font-weight:bold;padding:10px;color:#212529;">Expense Claim Cart</span></a>&nbsp;&nbsp;&nbsp;
                <a class="nav-item nav-link idAmount" id="product-desc-tab" data-toggle="tab" href="#amountdueto" role="tab" aria-controls="product-desc" aria-selected="false"><span style="font-weight:bold;padding:10px;color:#212529;">Amount Due to Company Cart</span></a>
              </div><br>
            </nav>

            <div class="tab-pane fade show active" id="expense" role="tabpanel" aria-labelledby="product-comments-tab">
              <div class="row">
                <div class="col-12">
                  <div class="card">
                    <div class="card-header">
                      <label class="card-title">
                        Expense Claim Cart
                      </label>
                      <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                          <i class="fas fa-angle-down btn-sm" style="color:black;"></i>
                        </button>
                      </div>
                    </div>

                    <div class="card-body table-responsive p-0 expenseCompanyCart" style="height: 180px;" id="expenseCompanyCart">
                      <table class="table text-nowrap table-striped TableExpenseClaim" id="TableExpenseClaim">
                        <thead>
                          <tr>
                            <th style="padding-top: 10px;padding-bottom: 10px;border:1px solid #e9ecef;text-align: center;">Transaction Number</th>
                            <th style="padding-top: 10px;padding-bottom: 10px;border:1px solid #e9ecef;text-align: center;">Product Id</th>
                            <th style="padding-top: 10px;padding-bottom: 10px;border:1px solid #e9ecef;text-align: center;">Product Name</th>
                            <th style="padding-top: 10px;padding-bottom: 10px;border:1px solid #e9ecef;text-align: center;">UOM</th>
                            <th style="padding-top: 10px;padding-bottom: 10px;border:1px solid #e9ecef;text-align: center;">Price</th>
                            <th style="padding-top: 10px;padding-bottom: 10px;border:1px solid #e9ecef;text-align: center;">Qty</th>
                            <th style="padding-top: 10px;padding-bottom: 10px;border:1px solid #e9ecef;text-align: center;">Total</th>
                          </tr>
                        </thead>
                        <tbody>

                        </tbody>
                      </table>
                    </div>

                    <div class="card-body expenseCompanyCart">
                      <table style="float:right;">
                        <tr>
                          <th style="position: relative;right:45px;"> Total Item : <span id="TotalQtyExpense"></span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="GrandTotalExpense"></span></th>
                        </tr>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="tab-pane fade" id="amountdueto" role="tabpanel" aria-labelledby="product-desc-tab">
              <div class="row">
                <div class="col-12">
                  <div class="card">
                    <div class="card-header">
                      <label class="card-title">
                        Amount Due to Company Cart
                      </label>
                      <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                          <i class="fas fa-angle-down btn-sm" style="color:black;"></i>
                        </button>
                      </div>
                    </div>

                    <div class="card-body table-responsive p-0 amountCompanyCart" style="height: 180px;" id="amountCompanyCart">
                      <table class="table text-nowrap table-striped TableAmountDueto" id="TableAmountDueto">
                        <thead>
                          <tr>
                            <th style="padding-top: 10px;padding-bottom: 10px;border:1px solid #e9ecef;text-align: center;">Transaction Number</th>
                            <th style="padding-top: 10px;padding-bottom: 10px;border:1px solid #e9ecef;text-align: center;">Product Id</th>
                            <th style="padding-top: 10px;padding-bottom: 10px;border:1px solid #e9ecef;text-align: center;">Product Name</th>
                            <th style="padding-top: 10px;padding-bottom: 10px;border:1px solid #e9ecef;text-align: center;">UOM</th>
                            <th style="padding-top: 10px;padding-bottom: 10px;border:1px solid #e9ecef;text-align: center;">Price</th>
                            <th style="padding-top: 10px;padding-bottom: 10px;border:1px solid #e9ecef;text-align: center;">Qty</th>
                            <th style="padding-top: 10px;padding-bottom: 10px;border:1px solid #e9ecef;text-align: center;">Total</th>
                          </tr>
                        </thead>
                        <tbody>

                        </tbody>
                      </table>
                    </div>
                    <div class="card-body amountCompanyCart">
                      <table style="float:right;">
                        <tr>
                          <th style="position: relative;right:45px;"> Total Item : <span id="TotalQtyAmount"></span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="GrandTotalAmount"></span></th>
                        </tr>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <a onclick="CancelAdvanceSettlement();" class="btn btn-default btn-sm float-right" style="background-color:#e9ecef;border:1px solid #ced4da;">
              <img src="{{ asset('AdminLTE-master/dist/img/cancel.png') }}" width="13" alt="" title="Cancel Advance List Cart"> Cancel
            </a>
            <button class="btn btn-default btn-sm float-right" type="submit" id="SaveAsfList" style="margin-right: 5px;background-color:#e9ecef;border:1px solid #ced4da;">
              <img src="{{ asset('AdminLTE-master/dist/img/save.png') }}" width="13" alt="" title="Submit"> Submit
            </button>
            <br><br>
          </div>
        </form>
      </div>
      @endif
    </div>
  </section>
</div>
@include('Partials.footer')
@include('Process.Advance.AdvanceSettlement.Functions.Footer.FooterAdvanceSettlement')
@endsection
