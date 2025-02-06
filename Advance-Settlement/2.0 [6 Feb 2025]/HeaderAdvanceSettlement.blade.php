<div class="card-body">
  <div class="row py-3" style="gap: 15px;">
    <div class="col-md-12 col-lg-5">
      <div class="row">
        <label class="col-sm-3 col-md-4 col-lg-4 col-form-label p-0">Advance Number</label>
        <div class="col-sm-9 col-md-8 col-lg-7 d-flex p-0">
          <div>
            <input id="modal_advance_document_number" style="border-radius:0;" name="modal_advance_document_number" class="form-control" size="34" readonly>
            <input id="modal_advance_id" style="border-radius:0;" name="modal_advance_id" class="form-control" hidden>
          </div>
          <div>
            <span style="border-radius:0;" class="input-group-text form-control">
              <a href="javascript:;" id="myGetModalAdvanceTrigger" data-toggle="modal" data-target="#myGetModalAdvance">
                <img src="{{ asset('AdminLTE-master/dist/img/box.png') }}" width="13" alt="myGetModalAdvanceTrigger">
              </a>
            </span>
          </div>
          <div class="d-sm-none d-md-none d-lg-block">
            <input id="modal_advance_budget_code" style="border-radius:0;" name="modal_advance_budget_code" class="form-control invisible">
          </div>
        </div>
      </div>
    </div>
    
    <div class="col-md-12 col-lg-5">
      <div class="row">
        <label class="col-sm-3 col-md-4 col-lg-4 col-form-label p-0">Beneficiary</label>
        <div class="col-sm-9 col-md-8 col-lg-7 d-flex p-0">
          <div>
            <input id="beneficiary_second_person_name" style="border-radius:0;" name="beneficiary_second_person_name" class="form-control" size="34" value="<?= $dataReport['beneficiaryName'] ?? ''; ?>" readonly>
            <input id="beneficiary_second_id" style="border-radius:0;" name="beneficiary_second_id" class="form-control" value="<?= $dataReport['beneficiaryId'] ?? ''; ?>" hidden>
          </div>
          <div>
            <span style="border-radius:0;" class="input-group-text form-control invisible">
              <a href="javascript:;" id="myBeneficiarySecondTrigger" data-toggle="modal" data-target="#myBeneficiarySecond">
                <img src="{{ asset('AdminLTE-master/dist/img/box.png') }}" width="13" alt="myBeneficiarySecondTrigger">
              </a>
            </span>
          </div>
          <div class="d-sm-none d-md-none d-lg-block">
            <input id="beneficiary_second_person_position" style="border-radius:0;" name="beneficiary_second_person_position" class="form-control invisible">
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
