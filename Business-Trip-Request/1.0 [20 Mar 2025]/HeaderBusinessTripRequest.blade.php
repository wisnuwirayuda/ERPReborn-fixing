<div class="row py-3" style="gap: 15px;">
    <div class="col-md-12 col-lg-5">
        <div class="row">
            <label class="col-sm-3 col-md-4 col-lg-4 col-form-label p-0">Budget Code</label>
            <div class="col-sm-9 col-md-8 col-lg-7 d-flex p-0">
                <div>
                    <input id="project_code_second" style="border-radius:0;" name="project_code_second" class="form-control" size="17" readonly>
                    <input id="project_id_second" style="border-radius:0;" name="project_id_second" class="form-control" hidden>
                </div>
                <div>
                    <span style="border-radius:0;" class="input-group-text form-control">
                        <a href="javascript:;" id="myProjectSecondTrigger" data-toggle="modal" data-target="#myProjectSecond">
                            <img src="{{ asset('AdminLTE-master/dist/img/box.png') }}" width="13" alt="myProjectSecondTrigger">
                        </a>
                    </span>
                </div>
                <div style="flex: 100%;">
                    <div class="input-group">
                        <input id="project_name_second" style="border-radius:0;" name="project_name_second" class="form-control" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 col-lg-5">
        <div class="row">
            <label class="col-sm-3 col-md-4 col-lg-4 col-form-label p-0">Sub Budget Code</label>
            <div class="col-sm-9 col-md-8 col-lg-7 d-flex p-0">
                <div>
                    <input id="site_code_second" style="border-radius:0;" name="site_code_second" class="form-control" size="17" readonly>
                    <input id="site_id_second" style="border-radius:0;" name="site_id_second" class="form-control" hidden>
                </div>
                <div>
                    <span style="border-radius:0;" class="input-group-text form-control">
                        <a href="javascript:;" id="mySiteCodeSecondTrigger" data-toggle="modal" data-target="#mySiteCodeSecond">
                            <img src="{{ asset('AdminLTE-master/dist/img/box.png') }}" width="13" alt="mySiteCodeSecondTrigger">
                        </a>
                    </span>
                </div>
                <div style="flex: 100%;">
                    <div class="input-group">
                        <input id="site_name_second" style="border-radius:0;" name="site_name_second" class="form-control" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
