@extends('Partials.app')
@section('main')
@include('Partials.navbar')
@include('Partials.sidebar')

<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid">
      <!-- TITLE -->
      <div class="row mb-1" style="background-color:#4B586A;">
        <div class="col-sm-6" style="height:30px;">
          <label style="font-size:15px;position:relative;top:7px;color:white;">Check Document on Process</label>
        </div>
      </div>
      
      <!-- CONTENT -->
      <div class="card">
        <div class="tab-content p-3" id="nav-tabContent">
          <div class="row">
            <!-- HEADER -->
            <div class="col-12">
              <div class="card">
                <!-- TITLE -->
                <div class="card-header">
                  <h3 class="text-bold text-center">
                    ADVANCE FORM
                  </h3>
                </div>

                <!-- CONTENT -->
                <div class="card-body">
                  <div class="row" style="margin: .6rem 0rem; gap: 1rem;">
                    <!-- LEFT COLUMN -->
                    <div class="col-12 col-md-6 col-lg-7">
                      <div class="form-group">
                        <!-- ADVANCE FORM -->
                        <div class="row" style="margin-bottom: 1rem;">
                          <div class="col-4 col-sm-4 col-md-3 col-lg-3 text-bold">
                            Advance Form
                          </div>
                          <div class="col">
                            : <?= $dataHeader[0]['DocumentNumber']; ?>
                          </div>
                        </div>

                        <!-- DATE -->
                        <div class="row" style="margin-bottom: 1rem;">
                          <div class="col-4 col-sm-4 col-md-3 col-lg-3 text-bold">
                            Date
                          </div>
                          <div class="col">
                            : <?= $dataHeader[0]['Date']; ?>
                          </div>
                        </div>

                        <!-- CURRENCY -->
                        <div class="row" style="margin-bottom: 1rem;">
                          <div class="col-4 col-sm-4 col-md-3 col-lg-3 text-bold">
                            Currency
                          </div>
                          <div class="col">
                            : <?= $dataHeader[0]['ProductUnitPriceCurrencyISOCode']; ?>
                          </div>
                        </div>

                        <!-- BUDGET CODE -->
                        <div class="row" style="margin-bottom: 1rem;">
                          <div class="col-4 col-sm-4 col-md-3 col-lg-3 text-bold">
                            Budget Code
                          </div>
                          <div class="col">
                            : <?= $dataHeader[0]['CombinedBudgetCode'] . ' - ' . $dataHeader[0]['CombinedBudgetName']; ?>
                          </div>
                        </div>

                        <!-- SUB BUDGET CODE -->
                        <div class="row" style="margin-bottom: 1rem;">
                          <div class="col-4 col-sm-4 col-md-3 col-lg-3 text-bold">
                            Sub Budget Code
                          </div>
                          <div class="col">
                            : <?= $dataHeader[0]['CombinedBudgetSectionCode'] . ' - ' . $dataHeader[0]['CombinedBudgetSectionName']; ?>
                          </div>
                        </div>

                        <!-- FILE ATTACHMENT -->
                        <div class="row">
                          <div class="col-4 col-sm-4 col-md-3 col-lg-3 text-bold">
                            File Attachment
                          </div>
                          <div class="col">
                            : 
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- RIGHT COLUMN -->
                    <div class="col-12 col-md-5 col-lg-4">
                      <div class="form-group">
                        <!-- REVISION -->
                        <div class="row" style="margin-bottom: 1rem;">
                          <div class="col-4 text-bold">
                            Revision
                          </div>
                          <div class="col">
                            : 
                          </div>
                        </div>

                        <!-- REQUESTER -->
                        <div class="row" style="margin-bottom: 1rem;">
                          <div class="col-4 text-bold">
                            Requester
                          </div>
                          <div class="col">
                            : <?= $dataHeader[0]['RequesterWorkerName']; ?>
                          </div>
                        </div>

                        <!-- BENEFICIARY -->
                        <div class="row" style="margin-bottom: 1rem;">
                          <div class="col-4 text-bold">
                            Beneficiary
                          </div>
                          <div class="col">
                            : <?= $dataHeader[0]['BeneficiaryWorkerName']; ?>
                          </div>
                        </div>

                        <!-- BANK NAME -->
                        <div class="row" style="margin-bottom: 1rem;">
                          <div class="col-4 text-bold">
                            Bank Name
                          </div>
                          <div class="col">
                            : <?= $dataHeader[0]['BankAcronym']; ?>
                          </div>
                        </div>

                        <!-- ACCOUNT NAME -->
                        <div class="row" style="margin-bottom: 1rem;">
                          <div class="col-4 text-bold">
                            Account Name
                          </div>
                          <div class="col">
                            : <?= $dataHeader[0]['BankAccountName']; ?>
                          </div>
                        </div>

                        <!-- ACCOUNT NUMBER -->
                        <div class="row">
                          <div class="col-4 text-bold">
                            Account Number
                          </div>
                          <div class="col">
                            : <?= $dataHeader[0]['BankAccountNumber']; ?>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- DETAIL -->
            <div class="col-12">
              <div class="card">
                <div class="card-body table-responsive p-0">
                  <table class="table table-head-fixed text-nowrap">
                    <thead>
                      <tr>
                        <th style="padding-top: 10px;padding-bottom: 10px;border:1px solid #e9ecef;text-align: center;background-color:#4B586A;color:white;">NO</th>
                        <th style="padding-top: 10px;padding-bottom: 10px;border:1px solid #e9ecef;text-align: center;background-color:#4B586A;color:white;">PRODUCT ID</th>
                        <th style="padding-top: 10px;padding-bottom: 10px;border:1px solid #e9ecef;text-align: center;background-color:#4B586A;color:white;">PRODUCT NAME</th>
                        <th style="padding-top: 10px;padding-bottom: 10px;border:1px solid #e9ecef;text-align: center;background-color:#4B586A;color:white;">QTY</th>
                        <th style="padding-top: 10px;padding-bottom: 10px;border:1px solid #e9ecef;text-align: center;background-color:#4B586A;color:white;">UOM</th>
                        <th style="padding-top: 10px;padding-bottom: 10px;border:1px solid #e9ecef;text-align: center;background-color:#4B586A;color:white;">UNIT PRICE</th>
                        <th style="padding-top: 10px;padding-bottom: 10px;border:1px solid #e9ecef;text-align: center;background-color:#4B586A;color:white;">TOTAL</th>
                      </tr>
                    </thead>

                    <tbody>
                      <?php $no = 1; $grand_total = 0; ?>
                      <?php foreach ($dataHeader as $dataDetail) { ?>
                        <?php $grand_total += $dataDetail['PriceBaseCurrencyValue'];  ?>
                        <tr>
                          <td style="border:1px solid #4B586A;color:#4B586A;"><?= $no++; ?></td>
                          <td style="border:1px solid #4B586A;color:#4B586A;"><?= $dataDetail['Product_RefID']; ?></td>
                          <td style="border:1px solid #4B586A;color:#4B586A;"><?= $dataDetail['ProductName']; ?></td>
                          <td style="border:1px solid #4B586A;color:#4B586A;"><?= $dataDetail['Quantity']; ?></td>
                          <td style="border:1px solid #4B586A;color:#4B586A;"><?= $dataDetail['QuantityUnitName']; ?></td>
                          <td style="border:1px solid #4B586A;color:#4B586A;"><?= $dataDetail['ProductUnitPriceBaseCurrencyValue']; ?></td>
                          <td style="border:1px solid #4B586A;color:#4B586A;"><?= $dataDetail['PriceBaseCurrencyValue']; ?></td>
                        </tr>
                      <?php } ?>
                    </tbody>

                    <tfoot>
                      <tr>
                        <th style="padding-top: 10px;padding-bottom: 10px;border:1px solid #4B586A;color:#4B586A;" colspan="6">
                          GRAND TOTAL
                        </th>
                        <td style="border:1px solid #4B586A;color:#4B586A;">
                          <span id="GrandTotal">
                            <?= number_format($grand_total, 2, '.', ''); ?>
                          </span>
                        </td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>
            </div>

            <!-- INTERNAL NOTES -->
            <div class="col-12">
              <div class="card">
                <!-- TITLE -->
                <div class="card-header">
                  <label class="card-title">
                    Internal Notes
                  </label>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-angle-down btn-sm" style="color:black;"></i>
                    </button>
                  </div>
                </div>

                <!-- CONTENT -->
                <div class="card-body">
                  <div class="row" style="margin: .6rem 0rem;">
                    <div class="col">
                      <?= nl2br(e($dataHeader[0]['Remarks'])); ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- BUTTON APPROVAL -->
            <div class="col-12 text-right" style="margin-bottom: 1rem;">
                <?php if ($statusApprover == "YES") { ?>
                    <a onclick="" class="btn btn-default btn-sm" style="background-color:#e9ecef;border:1px solid #ced4da;margin-right:10px;">
                        <img src="{{ asset('AdminLTE-master/dist/img/save.png') }}" width="13" alt="" title="Approve"> Approve
                    </a>

                    <a onclick="" class="btn btn-default btn-sm" style="background-color:#e9ecef;border:1px solid #ced4da;">
                        <img src="{{ asset('AdminLTE-master/dist/img/cancel.png') }}" width="13" alt="" title="Reject"> Reject
                    </a>
                <?php } ?>

                <?php if ($statusApprover == "RESUBMIT") { ?>
                    <button class="btn btn-default btn-sm" style="background-color:#e9ecef;border:1px solid #ced4da;">
                        <img src="{{ asset('AdminLTE-master/dist/img/reset.png') }}" width="13" alt="" title="Resubmit" /> Resubmit
                    </button>
                <?php } ?>
            </div>

            <!-- APPROVAL HISTORY -->
            <div class="col-12">
              <div class="card">
                <!-- TITLE -->
                <div class="card-header">
                  <label class="card-title">
                    Approval History
                  </label>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-angle-down btn-sm" style="color:black;"></i>
                    </button>
                  </div>
                </div>

                <!-- CONTENT -->
                <div class="card-body">
                  <div class="row" style="margin-top: .7rem; gap: 1rem;">
                    <div class="col">
                      <?php foreach ($dataWorkFlows as $dataWorkFlow) { ?>
                        <ul style="padding: 0 1rem;">
                          <li>
                            <div style="margin-bottom: .5rem;">
                              <span style="text-transform:uppercase;font-weight:bold;">
                                <?= $dataWorkFlow['workFlowPathActionName']; ?>
                              </span>
                              <?= date('D, m/d/Y H:m:s', strtotime($dataWorkFlow['approvalDateTimeTZ'])) ?> : <?= $dataWorkFlow['approverEntityName']; ?> (<?= $dataWorkFlow['approverEntityFullJobPositionTitle']; ?>)
                            </div>
                            <div>
                              Comment : <?= nl2br(e($dataWorkFlow['remarks'])); ?>
                            </div>
                          </li>
                        </ul>
                      <?php } ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

@include('Partials.footer')
@endsection
