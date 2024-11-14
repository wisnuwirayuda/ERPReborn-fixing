<script type="text/javascript">
  $("#brfhide3").hide();
  $("#brfhide4").hide();
  $(".FormTransportDetails").hide();
  $(".brfhide6").hide();
  $(".budgetDetail").hide();
  $("#sitecode2").prop("disabled", true);
  $("#request_name2").prop("disabled", true);
  $("#SaveBrfList").prop("disabled", true);
  $("#dateEnd").prop("disabled", true);
  $("#dateEnd").css("background-color", "white");
  $("#dateArrival").prop("disabled", true);
  $("#dateArrival").css("background-color", "white");
  $("#putProductId2").prop("disabled", true);
  $("#sequenceRequest").prop("disabled", true);
  $("#FollowingCondition").hide();
  $(".TransportDetails").hide();
  // $(".BrfListCart").hide();
  // $(".file-attachment").hide();
  // $(".tableShowHideBOQ3").hide();
  // $(".FollowingCondition").hide();
    $("#requester_icon").hide();
</script>


<script>
  $('#tableGetProject tbody').on('click', 'tr', function() {

    //RESET FORM
    document.getElementById("FormSubmitBusinessTrip").reset();
    $("#dataInput_Log_FileUpload_Pointer_RefID").val("");
    $("#dataInput_Log_FileUpload_Pointer_RefID_Action").val("");
    $('#zhtSysObjDOMTable_Upload_ActionPanel').find('tbody').empty();
    $('.tableBudgetDetail').find('tbody').empty();
    $('.TableBusinessTrip').find('tbody').empty();
    $('#TotalBudgetSelected').html(0);
    $('#GrandTotal').html(0);
    $("#SaveBrfList").prop("disabled", true);
    //END RESET FORM
    
    $("#myProject").modal('toggle');

    var row = $(this).closest("tr");
    var id = row.find("td:nth-child(1)").text();
    var sys_id = $('#sys_id_budget' + id).val();
    var code = row.find("td:nth-child(2)").text();
    var name = row.find("td:nth-child(3)").text();

    $("#projectcode").val(code);
    $("#projectname").val(name);
    $("#sitecode2").prop("disabled", false);

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    var keys = 0;
    $.ajax({
      type: 'GET',
      url: '{!! route("getSite") !!}?project_code=' + sys_id,
      success: function(data) {

        var no = 1;
        var t = $('#tableGetSite').DataTable();
        t.clear();
        $.each(data, function(key, val) {
          keys += 1;
          t.row.add([
            '<tbody><tr><input id="sys_id_site' + keys + '" value="' + val.Sys_ID + '" type="hidden"><td>' + no++ + '</td>',
            '<td>' + val.Code + '</td>',
            '<td>' + val.Name + '</td></tr></tbody>'
          ]).draw();
        });
      }
    });
  });
</script>

<script>
  $('#tableGetSite tbody').on('click', 'tr', function() {

    //RESET FORM
    $('.tableBudgetDetail').find('tbody').empty();
    $('.TableBusinessTrip').find('tbody').empty();
    $('#TotalBudgetSelected').html(0);
    $('#GrandTotal').html(0);
    $("#SaveBrfList").prop("disabled", true);
    //END RESET FORM


    $("#mySiteCode").modal('toggle');

    var row = $(this).closest("tr");
    var id = row.find("td:nth-child(1)").text();
    var sys_ID = $('#sys_id_site' + id).val();
    var code = row.find("td:nth-child(2)").text();
    var name = row.find("td:nth-child(3)").text();

    $("#sitecode").val(code);
    $("#sitename").val(name);

    // $("#sitecode2").prop("disabled", true);
    // $("#projectcode2").prop("disabled", true);

    $("#addToDoDetail").prop("disabled", false);
    $(".tableShowHideBOQ3").show();
    $("#request_name2").prop("disabled", false);
    $("#beneficiary_name2").prop("disabled", false);
    $("#bank_name2").prop("disabled", false);


    // $(".file-attachment").show();
    $(".advance-detail").show();

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $.ajax({
      type: 'GET',
      url: '{!! route("getBudget") !!}?site_code=' + sys_ID,
      // url: '{!! route("getBudget") !!}?sitecode=' + 143000000000305,
      success: function(data) {
        var no = 1;
        applied = 0;
        status = "";
        statusDisplay = [];
        statusDisplay2 = [];
        statusForm = [];
        $.each(data, function(key, val2) {
          var used = val2.quantityAbsorptionRatio * 100;

          if (used == "0.00" && val2.quantity == "0.00") {
            var applied = 0;
          } else {
            var applied = Math.round(used);
          }
          if (applied >= 100) {
            var status = "disabled";
          }
          if (val2.productName == "Unspecified Product") {
            statusDisplay[key] = "";
            statusDisplay2[key] = "none";
            statusForm[key] = "disabled";
          } else {
            statusDisplay[key] = "none";
            statusDisplay2[key] = "";
            statusForm[key] = "";
          }

          var html = '<tr>' +
            // '<input name="getWorkId[]" value="' + val2.combinedBudgetSubSectionLevel1_RefID + '" type="hidden">' +
            // '<input name="getWorkName[]" value="' + val2.combinedBudgetSubSectionLevel1Name + '" type="hidden">' +
            '<input name="getProductId[]" value="' + val2.product_RefID + '" type="hidden">' +
            '<input name="getProductName[]" value="' + val2.productName + '" type="hidden">' +
            '<input name="getQtyId[]" id="budget_qty_id' + key + '" value="' + val2.quantityUnit_RefID + '" type="hidden">' +
            '<input name="getQty[]" id="budget_qty' + key + '" value="' + val2.quantity + '" type="hidden">' +
            '<input name="getPrice[]" id="budget_price' + key + '" value="' + val2.priceBaseCurrencyValue + '" type="hidden">' +
            '<input name="getBudgetTotal[]" id="budget_total' + key + '" value="' + (val2.quantity * val2.priceBaseCurrencyValue) + '" type="hidden">' +
            '<input name="getUom[]" value="' + val2.quantityUnitName + '" type="hidden">' +
            '<input name="getCurrency[]" value="' + val2.priceBaseCurrencyISOCode + '" type="hidden">' +
            '<input name="getCurrencyId[]" value="' + val2.priceCurrency_RefID + '" type="hidden">' +
            '<input name="combinedBudgetSectionDetail_RefID[]" value="' + val2.sys_ID + '" type="hidden">' +
            '<input name="combinedBudget_RefID" value="' + val2.combinedBudget_RefID + '" type="hidden">' +

            '<td style="border:1px solid #e9ecef;display:' + statusDisplay[key] + '";">' +
            '<div class="input-group">' +
            '<input id="putProductId' + key + '" style="border-radius:0;width:130px;background-color:white;" name="putProductId" class="form-control" readonly>' +
            '<div class="input-group-append">' +
            '<span style="border-radius:0;" class="input-group-text form-control" data-id="10">' +
            '<a id="product_id2" data-toggle="modal" data-target="#myProduct" class="myProduct" onclick="KeyFunction(' + key + ')"><img src="{{ asset("AdminLTE-master/dist/img/box.png") }}" width="13" alt=""></a>' +
            '</span>' +
            '</div>' +
            '</div>' +
            '</td>' +

            '<td style="border:1px solid #e9ecef;display:' + statusDisplay2[key] + '">' + '<span>' + val2.product_RefID + '</span>' + '</td>' +
            '<td style="border:1px solid #e9ecef;max-width:15px;overflow: hidden;" title="' + val2.productName + '">' + '<span id="putProductName' + key + '">' + val2.productName + '</span>' + '</td>' +
            '<input id="putUom' + key + '" type="hidden">' +

            '<input id="TotalBudget' + key + '" type="hidden">' +

            '<td style="border:1px solid #e9ecef;">' + '<span>' + currencyTotal(val2.quantity) + '</span>' + '</td>' +
            '<td style="border:1px solid #e9ecef;">' + '<span>' + currencyTotal(val2.quantityRemaining) + '</span>' + '</td>' +
            '<td style="border:1px solid #e9ecef;">' + '<span>' + currencyTotal(val2.priceBaseCurrencyValue) + '</span>' + '</td>' +
            '<td style="border:1px solid #e9ecef;">' + '<span id="total_balance_value2' + key + '">' + currencyTotal(val2.quantity * val2.priceBaseCurrencyValue) + '</span>' + '</td>' +

            '<td class="sticky-col fifth-col-brf" style="border:1px solid #e9ecef;background-color:white;">' + '<input id="allowance_req' + key + '" style="border-radius:0;" name="allowance_req[]" class="form-control allowance_req" onkeypress="return isNumberKey(this, event);" autocomplete="off" ' + statusForm[key] + '>' + '</td>' +
            '<td class="sticky-col forth-col-brf" style="border:1px solid #e9ecef;background-color:white;">' + '<input id="accomodation_req' + key + '" style="border-radius:0;" name="accomodation_req[]" class="form-control accomodation_req" onkeypress="return isNumberKey(this, event);" autocomplete="off" ' + statusForm[key] + '>' + '</td>' +
            '<td class="sticky-col third-col-brf" style="border:1px solid #e9ecef;background-color:white;">' + '<input id="other_req' + key + '" style="border-radius:0;" name="other_req[]" class="form-control total_req" onkeypress="return isNumberKey(this, event);" autocomplete="off" ' + statusForm[key] + '>' + '</td>' +
            '<td class="sticky-col second-col-brf" style="border:1px solid #e9ecef;background-color:white;">' + '<input id="total_req' + key + '" style="border-radius:0;background-color:white;" name="total_req[]" class="form-control total_req" autocomplete="off" disabled>' + '</td>' +
            '<td class="sticky-col first-col-brf" style="border:1px solid #e9ecef;background-color:white;">' + '<input id="total_balance_value' + key + '" style="border-radius:0;width:90px;background-color:white;" name="total_balance_value[]" class="form-control total_balance_value" autocomplete="off" disabled value="' + currencyTotal(val2.quantity * val2.priceBaseCurrencyValue) + '">' + '</td>' +

            '</tr>';
          $('table.tableBudgetDetail tbody').append(html);

          //VALIDASI ALLOWANCE
          $('#allowance_req' + key).keyup(function() {
            val2.quantity
            $(this).val(currency($(this).val()));
            var allowance_req = $(this).val().replace(/,/g, '');
            var budget_total = $("#budget_total" + key).val();
            var accomodation_req = $("#accomodation_req" + key).val().replace(/,/g, '');
            var other_req = $("#other_req" + key).val().replace(/,/g, '');
            var totalWith = +allowance_req + +accomodation_req + +other_req;
            var totalWithout = +accomodation_req + +other_req;

            if (allowance_req == "") {
              $('#total_req' + key).val(currencyTotal(totalWithout));
              $("input[name='allowance_req[]']").css("border", "1px solid #ced4da");
            } else if (parseFloat(totalWith) > parseFloat(budget_total)) {

              swal({
                onOpen: function() {
                  swal.disableConfirmButton();
                  Swal.fire("Error !", "Your request is over budget !", "error");
                }
              });

              $('#allowance_req' + key).val("");
              $('#total_req' + key).val(currencyTotal(totalWithout));
              $('#allowance_req' + key).css("border", "1px solid red");
              $('#allowance_req' + key).focus();
            } else {

              $("input[name='allowance_req[]']").css("border", "1px solid #ced4da");
              $('#total_req' + key).val(currencyTotal(totalWith));
            }

            //MEMANGGIL FUNCTION TOTAL BUDGET SELECTED
            TotalBudgetSelected();
            //MEMANGGIL FUNCTION TOTAL BALANCE QTY SELECTED
            TotalBalanceValueSelected(key);
          });

          //VALIDASI ACCOMODATION
          $('#accomodation_req' + key).keyup(function() {
            $(this).val(currency($(this).val()));
            var accomodation_req = $(this).val().replace(/,/g, '');
            var budget_total = $("#budget_total" + key).val();
            var allowance_req = $("#allowance_req" + key).val().replace(/,/g, '');
            var other_req = $("#other_req" + key).val().replace(/,/g, '');
            var totalWith = +allowance_req + +accomodation_req + +other_req;
            var totalWithout = +allowance_req + +other_req;

            if (accomodation_req == "") {
              $('#total_req' + key).val(currencyTotal(totalWithout));
              $("input[name='accomodation_req[]']").css("border", "1px solid #ced4da");
            } else if (parseFloat(totalWith) > parseFloat(budget_total)) {

              swal({
                onOpen: function() {
                  swal.disableConfirmButton();
                  Swal.fire("Error !", "Your request is over budget !", "error");
                }
              });

              $('#accomodation_req' + key).val("");
              $('#total_req' + key).val(currencyTotal(totalWithout));
              $('#accomodation_req' + key).css("border", "1px solid red");
              $('#accomodation_req' + key).focus();
            } else {

              $("input[name='accomodation_req[]']").css("border", "1px solid #ced4da");
              $('#total_req' + key).val(currencyTotal(totalWith));
            }

            //MEMANGGIL FUNCTION TOTAL BUDGET SELECTED
            TotalBudgetSelected();
            //MEMANGGIL FUNCTION TOTAL BALANCE QTY SELECTED
            TotalBalanceValueSelected(key);
          });

          //VALIDASI OTHER
          $('#other_req' + key).keyup(function() {
            $(this).val(currency($(this).val()));
            var other_req = $(this).val().replace(/,/g, '');
            var budget_total = $("#budget_total" + key).val();
            var allowance_req = $("#allowance_req" + key).val().replace(/,/g, '');
            var accomodation_req = $("#accomodation_req" + key).val().replace(/,/g, '');
            var totalWith = +allowance_req + +accomodation_req + +other_req;
            var totalWithout = +allowance_req + +accomodation_req;

            if (other_req == "") {
              $('#total_req' + key).val(currencyTotal(totalWithout));
              $("input[name='other_req[]']").css("border", "1px solid #ced4da");
            } else if (parseFloat(totalWith) > parseFloat(budget_total)) {

              swal({
                onOpen: function() {
                  swal.disableConfirmButton();
                  Swal.fire("Error !", "Your request is over budget !", "error");
                }
              });

              $('#other_req' + key).val("");
              $('#total_req' + key).val(currencyTotal(totalWithout));
              $('#other_req' + key).css("border", "1px solid red");
              $('#other_req' + key).focus();
            } else {

              $("input[name='other_req[]']").css("border", "1px solid #ced4da");
              $('#total_req' + key).val(currencyTotal(totalWith));
            }

            //MEMANGGIL FUNCTION TOTAL BUDGET SELECTED
            TotalBudgetSelected();
            //MEMANGGIL FUNCTION TOTAL BALANCE QTY SELECTED
            TotalBalanceValueSelected(key);
          });
        });
      }
    });
  });
</script>

<script>
  function addFromDetailtoCartJs() {

    $('#TableBusinessTrip').find('tbody').empty();

    $(".BrfListCart").show();
    var date = new Date().toJSON().slice(0, 10).replace(/-/g, '-');
    // var getWorkId = $("input[name='getWorkId[]']").map(function() {
    //   return $(this).val();
    // }).get();
    // var getWorkName = $("input[name='getWorkName[]']").map(function() {
    //   return $(this).val();
    // }).get();
    var getProductId = $("input[name='getProductId[]']").map(function() {
      return $(this).val();
    }).get();
    var getProductName = $("input[name='getProductName[]']").map(function() {
      return $(this).val();
    }).get();
    var getUom = $("input[name='getUom[]']").map(function() {
      return $(this).val();
    }).get();
    var getQtyId = $("input[name='getQtyId[]']").map(function() {
      return $(this).val();
    }).get();
    var getCurrencyId = $("input[name='getCurrencyId[]']").map(function() {
      return $(this).val();
    }).get();
    var getCurrency = $("input[name='getCurrency[]']").map(function() {
      return $(this).val();
    }).get();
    var allowance_req = $("input[name='allowance_req[]']").map(function() {
      return $(this).val();
    }).get();
    var accomodation_req = $("input[name='accomodation_req[]']").map(function() {
      return $(this).val();
    }).get();
    var other_req = $("input[name='other_req[]']").map(function() {
      return $(this).val();
    }).get();
    var combinedBudgetSectionDetail_RefID = $("input[name='combinedBudgetSectionDetail_RefID[]']").map(function() {
      return $(this).val();
    }).get();
    var combinedBudget_RefID = $("input[name='combinedBudget_RefID']").val();
    var TotalBudgetSelected = 0;
    var TotalAllowance = 0;
    var TotalAccomodation = 0;
    var TotalOther = 0;

    var total_req = $("input[name='total_req[]']").map(function() {
      return $(this).val();
    }).get();
    $.each(total_req, function(index, data) {
      if (total_req[index] != "" && total_req[index] > "0.00" && total_req[index] != "NaN.00") {

        var putProductId = getProductId[index];
        var putProductName = getProductName[index];
        var putUom = getUom[index];

        if (getProductName[index] == "Unspecified Product") {
          var putProductId = $("#putProductId" + index).val();
          var putProductName = $("#putProductName" + index).html();
          var putUom = $("#putUom" + index).val();
        }
        TotalBudgetSelected += +total_req[index].replace(/,/g, '');
        TotalAllowance += +allowance_req[index].replace(/,/g, '');
        TotalAccomodation += +accomodation_req[index].replace(/,/g, '');
        TotalOther += +other_req[index].replace(/,/g, '');

        var html = '<tr>' +

          '<input type="hidden" name="var_product_id[]" value="' + putProductId + '">' +
          '<input type="hidden" name="var_product_name[]" id="var_product_name" value="' + putProductName + '">' +
          '<input type="hidden" name="var_uom[]" value="' + getUom[index] + '">' +
          '<input type="hidden" name="var_qty_id[]" value="' + getQtyId[index] + '">' +
          '<input type="hidden" name="var_currency_id[]" value="' + getCurrencyId[index] + '">' +
          '<input type="hidden" name="var_quantity[]" class="allowance_req2' + index + '" data-id="' + index + '" value="' + currencyTotal(allowance_req[index]).replace(/,/g, '') + '">' +
          '<input type="hidden" name="var_price[]" class="accomodation_req2' + index + '" value="' + currencyTotal(accomodation_req[index]).replace(/,/g, '') + '">' +
          '<input type="hidden" name="var_quantity[]" class="other_req2' + index + '" data-id="' + index + '" value="' + currencyTotal(other_req[index]).replace(/,/g, '') + '">' +
          '<input type="hidden" name="var_total[]" class="total_req2' + index + '" value="' + total_req[index] + '">' +
          '<input type="hidden" name="var_currency[]" value="' + getCurrency[index] + '">' +
          '<input type="hidden" name="var_date" value="' + date + '">' +
          '<input type="hidden" name="var_combinedBudgetSectionDetail_RefID[]" value="' + combinedBudgetSectionDetail_RefID[index] + '">' +
          '<input type="hidden" name="var_combinedBudget_RefID" value="' + combinedBudget_RefID + '">' +
          '<input type="hidden" name="var_allowance[]" value="' + allowance_req[index] + '">' +
          '<input type="hidden" name="var_accomodation[]" value="' + accomodation_req[index] + '">' +
          '<input type="hidden" name="var_other[]" value="' + other_req[index] + '">' +

          // '<td style="padding-top: 10px;padding-bottom: 10px;border:1px solid #e9ecef;">' + getWorkId[index] + '</td>' +
          // '<td style="padding-top: 10px;padding-bottom: 10px;border:1px solid #e9ecef;">' + getWorkName[index] + '</td>' +
          '<td style="padding-top: 10px;padding-bottom: 10px;border:1px solid #e9ecef;">' + putProductId + '</td>' +
          '<td style="padding-top: 10px;padding-bottom: 10px;border:1px solid #e9ecef;">' + putProductName + '</td>' +
          // '<td style="padding-top: 10px;padding-bottom: 10px;border:1px solid #e9ecef;">' + putUom + '</td>' +
          // '<td style="padding-top: 10px;padding-bottom: 10px;border:1px solid #e9ecef;">' + getCurrency[index] + '</td>' +
          '<td style="padding-top: 10px;padding-bottom: 10px;border:1px solid #e9ecef;">' + '<span data-id="' + index + '" class="allowance_req2' + index + '">' + currencyTotal(allowance_req[index]) + '</span>' + '</td>' +
          '<td style="padding-top: 10px;padding-bottom: 10px;border:1px solid #e9ecef;">' + '<span data-id="' + index + '" class="accomodation_req2' + index + '">' + currencyTotal(accomodation_req[index]) + '</span>' + '</td>' +
          '<td style="padding-top: 10px;padding-bottom: 10px;border:1px solid #e9ecef;">' + '<span data-id="' + index + '" class="other_req2' + index + '">' + currencyTotal(other_req[index]) + '</span>' + '</td>' +
          '<td style="padding-top: 10px;padding-bottom: 10px;border:1px solid #e9ecef;">' + '<span data-id="' + index + '" class="total_req2' + index + '">' + currencyTotal(total_req[index]) + '</span>' + '</td>' +
          '</tr>';
        $('table.TableBusinessTrip tbody').append(html);

        $("#GrandTotal").html(currencyTotal(TotalBudgetSelected));
        $("#TotalAllowance").html(currencyTotal(TotalAllowance));
        $("#TotalAccomodation").html(currencyTotal(TotalAccomodation));
        $("#TotalOther").html(currencyTotal(TotalOther));

        $("#SaveBrfList").prop("disabled", false);
      }
    });

  }
</script>

<script type="text/javascript">
  function AddFormTransportDetails() {
    $(".FormTransportDetails").show();
  }

  function CancelFormTransportDetails() {
    $(".FormTransportDetails").hide();
    $("#transportType").val("");
    $("#transportBooking").val("");
    $("#dateDepart").val("");
    $("#dateArrival").val("");
    $("#qoutedFare").val("");
    $("#transportType").css("border", "1px solid #ced4da");
    $("#transportBooking").css("border", "1px solid #ced4da");
    $("#dateDepart").css("border", "1px solid #ced4da");
    $("#dateArrival").css("border", "1px solid #ced4da");
    $("#qoutedFare").css("border", "1px solid #ced4da");
  }

  function UpdateFormTransportDetails() {

    var transportType = $("#transportType").val();
    var transportBooking = $("#transportBooking").val();
    var dateDepart = $("#dateDepart").val();
    var dateArrival = $("#dateArrival").val();
    var qoutedFare = $("#qoutedFare").val();

    if (transportType === "") {
      $("#transportType").focus();
      $("#transportType").attr('required', true);
      $("#transportType").css("border", "1px solid red");
    } else if (transportBooking === "") {
      $("#transportBooking").focus();
      $("#transportBooking").attr('required', true);
      $("#transportBooking").css("border", "1px solid red");
      $("#transportType").css("border", "1px solid #ced4da");
    } else if (dateDepart === "") {
      $("#dateDepart").focus();
      $("#dateDepart").attr('required', true);
      $("#dateDepart").css("border", "1px solid red");
      $("#transportBooking").css("border", "1px solid #ced4da");
    } else if (dateArrival === "") {
      $("#dateArrival").focus();
      $("#dateArrival").attr('required', true);
      $("#dateArrival").css("border", "1px solid red");
      $("#dateDepart").css("border", "1px solid #ced4da");
    } else if (qoutedFare === "") {
      $("#qoutedFare").focus();
      $("#qoutedFare").attr('required', true);
      $("#qoutedFare").css("border", "1px solid red");
      $("#dateArrival").css("border", "1px solid #ced4da");
    } else {
      $("#transportType").css("border", "1px solid #ced4da");
      $("#transportBooking").css("border", "1px solid #ced4da");
      $("#dateDepart").css("border", "1px solid #ced4da");
      $("#dateArrival").css("border", "1px solid #ced4da");
      $("#qoutedFare").css("border", "1px solid #ced4da");
      $(".FormTransportDetails").hide();
      var html = '<tr>' +
        '<td style="border:1px solid #e9ecef;width:10px;">' +
        '&nbsp;<button type="button" class="btn btn-xs" onclick="RemoveTransportDetails(this);" style="border: 1px solid #ced4da;padding-left:2px;padding-right:2px;padding-top:2px;padding-bottom:2px;border-radius:3px;"><img src="AdminLTE-master/dist/img/delete.png" width="18" alt="" title="Remove"></button> ' +
        '<input type="hidden" name="transportType[]" value="' + transportType + '">' +
        '<input type="hidden" name="transportBooking[]" value="' + transportBooking + '">' +
        '<input type="hidden" name="dateDepart[]" value="' + dateDepart + '">' +
        '<input type="hidden" name="dateArrival[]" value="' + dateArrival + '">' +
        '<input type="hidden" name="qoutedFare[]" value="' + qoutedFare + '">' +
        '</td>' +
        '<td style="border:1px solid #e9ecef;">' + transportType + '</td>' +
        '<td style="border:1px solid #e9ecef;">' + transportBooking + '</td>' +
        '<td style="border:1px solid #e9ecef;">' + dateDepart + '</td>' +
        '<td style="border:1px solid #e9ecef;">' + dateArrival + '</td>' +
        '<td style="border:1px solid #e9ecef;">' + qoutedFare + '</td>' +
        '</tr>';

      $('table.TableTransportDetails tbody').append(html);

      $("#transportType").val("");
      $("#transportBooking").val("");
      $("#dateDepart").val("");
      $("#dateArrival").val("");
      $("#qoutedFare").val("");
    }
  }

  function RemoveTransportDetails(tr) {
    var i = tr.parentNode.parentNode.rowIndex;
    document.getElementById("TableTransportDetails").deleteRow(i);
  }
</script>

<script type="text/javascript">
  function CancelBusinessTrip() {
    ShowLoading();
    window.location.href = '/BusinessTripRequest?var=1';
  }
</script>

<script>
  var date = new Date();
  var today = new Date(date.setMonth(date.getMonth() - 3));
  document.getElementById('dateCommance').setAttribute('min', today.toISOString().split('T')[0]);
  document.getElementById('dateDepart').setAttribute('min', today.toISOString().split('T')[0]);
</script>

<script>
  $(document).ready(function() {
    $('#dateCommance').change(function() {
      $("#dateEnd").prop("disabled", false);
      var dateCommance = new Date($("#dateCommance").val());
      document.getElementById('dateEnd').setAttribute('min', dateCommance.toISOString().split('T')[0]);
    });

    $('#dateDepart').change(function() {
      $("#dateArrival").prop("disabled", false);
      var dateDepart = new Date($("#dateDepart").val());
      document.getElementById('dateArrival').setAttribute('min', dateDepart.toISOString().split('T')[0]);
    });
  });
</script>


<script>
  $(document).ready(function() {
    $('#longTerm').click(function() {
      $("#sequenceRequest").prop("disabled", false);
      $("#sequenceRequest").val('0');
      $("#lupsum").prop("disabled", true);
      radiobtn = document.getElementById("nonLupsum");
      radiobtn.checked = true;
    });

    $('#shortTerm').click(function() {
      $("#sequenceRequest").val('1');
      $("#sequenceRequest").prop("disabled", true);
      $("#lupsum").prop("disabled", false);
    });

    $('#dayTripTravel').click(function() {
      $("#sequenceRequest").val('1');
      $("#sequenceRequest").prop("disabled", true);
      $("#lupsum").prop("disabled", false);
    });
  });
</script>

<script>
  $(function() {
    $(".idFollowingCondition").on('click', function(e) {
      $("#transportDetails").hide();
      $("#followingCondition").show();
      $(".FollowingCondition").show();
    });
  });

  $(function() {
    $(".idTransportDetails").on('click', function(e) {
      $("#followingCondition").hide();
      $("#transportDetails").show();
      $(".TransportDetails").show();
    });
  });
</script>

<script>
  $(function() {
    $("#FormSubmitBusinessTrip").on("submit", function(e) { //id of form 
      e.preventDefault();
      var request_name = $("#request_name").val();
      var projectcode = $("#projectcode").val();
      var sitecode = $("#sitecode").val();
      var reasonTravel = $("#reasonTravel").val();
      var dateCommance = $("#dateCommance").val();
      var dateEnd = $("#dateEnd").val();
      var headStationLocation = $("#headStationLocation").val();
      var bussinesLocation = $("#bussinesLocation").val();
      var contactPhone = $("#contactPhone").val();
      var transportApplicable = $(".transportApplicable").val();

      var arrayTransportTypeApplicable = [];
      $.each($("input[name='TransportTypeApplicable']:checked"), function() {
        arrayTransportTypeApplicable.push($(this).val());
      });

      $("#projectcode").css("border", "1px solid #ced4da");
      $("#sitecode").css("border", "1px solid #ced4da");
      $("#request_name").css("border", "1px solid #ced4da");
      $("#contactPhone").css("border", "1px solid #ced4da");
      $("#dateCommance").css("border", "1px solid #ced4da");
      $("#dateEnd").css("border", "1px solid #ced4da");
      $("#headStationLocation").css("border", "1px solid #ced4da");
      $("#bussinesLocation").css("border", "1px solid #ced4da");
      $("#reasonTravel").css("border", "1px solid #ced4da");
      document.getElementsByClassName("form-group")[5].style.border = '1px solid #ced4da';

      // if (projectcode === "") {
      //   $("#projectcode").focus();
      //   $("#projectcode").attr('required', true);
      //   $("#projectcode").css("border", "1px solid red");
      // } else if (sitecode === "") {
      //   $("#sitecode").focus();
      //   $("#sitecode").attr('required', true);
      //   $("#sitecode").css("border", "1px solid red");
      // } else if (request_name === "") {
      //   $("#request_name").focus();
      //   $("#request_name").attr('required', true);
      //   $("#request_name").css("border", "1px solid red");
      // }  else if (contactPhone === "") {
      //   $("#contactPhone").focus();
      //   $("#contactPhone").attr('required', true);
      //   $("#contactPhone").css("border", "1px solid red");
      // }  else if (dateCommance === "") {
      //   $("#dateCommance").focus();
      //   $("#dateCommance").attr('required', true);
      //   $("#dateCommance").css("border", "1px solid red");
      // }  else if (dateEnd === "") {
      //   $("#dateEnd").focus();
      //   $("#dateEnd").attr('required', true);
      //   $("#dateEnd").css("border", "1px solid red");
      // }  else if (headStationLocation === "") {
      //   $("#headStationLocation").focus();
      //   $("#headStationLocation").attr('required', true);
      //   $("#headStationLocation").css("border", "1px solid red");
      // }  else if (bussinesLocation === "") {
      //   $("#bussinesLocation").focus();
      //   $("#bussinesLocation").attr('required', true);
      //   $("#bussinesLocation").css("border", "1px solid red");
      // } else if (reasonTravel === "") {
      //   $("#reasonTravel").focus();
      //   $("#reasonTravel").attr('required', true);
      //   $("#reasonTravel").css("border", "1px solid red");
      // } else if (arrayTransportTypeApplicable.length == 0) {
      //   $(".FollowingCondition").show();
      //   document.getElementsByClassName("form-group")[5].style.border = '1px solid red';
      // }  
      // else {

      var arr = [];
      $.each($("input[name='TransportTypeApplicable']:checked"), function() {
        arr.push($(this).val());
      });

      var html = '<input type="hidden" name="TransportTypeApplicable" value="' + arr + '">';
      $('table.TableBusinessTrip tbody').append(html);

      var action = $(this).attr("action"); //get submit action from form
      var method = $(this).attr("method"); // get submit method
      var form_data = new FormData($(this)[0]); // convert form into formdata 
      var form = $(this);


      const swalWithBootstrapButtons = Swal.mixin({
        confirmButtonClass: 'btn btn-success btn-sm',
        cancelButtonClass: 'btn btn-danger btn-sm',
        buttonsStyling: true,
      })

      swalWithBootstrapButtons.fire({

        title: 'Are you sure?',
        text: "Save this data?",
        type: 'question',

        showCancelButton: true,
        confirmButtonText: '<img src="{{ asset("AdminLTE-master/dist/img/save.png") }}" width="13" alt=""><span style="color:black;">Yes, save it </span>',
        cancelButtonText: '<img src="{{ asset("AdminLTE-master/dist/img/cancel.png") }}" width="13" alt=""><span style="color:black;"> No, cancel </span>',
        confirmButtonColor: '#e9ecef',
        cancelButtonColor: '#e9ecef',
        reverseButtons: true
      }).then((result) => {
        if (result.value) {

          ShowLoading();

          $.ajax({
            url: action,
            dataType: 'json', // what to expect back from the server
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: method,
            success: function(response) {

              HideLoading();

              swalWithBootstrapButtons.fire({

                title: 'Successful !',
                type: 'success',
                html: 'Data has been saved. Your transaction number is ' + '<span style="color:red;">' + response.brfnumber + '</span>',
                showCloseButton: false,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonText: '<span style="color:black;"> Ok </span>',
                confirmButtonColor: '#4B586A',
                confirmButtonColor: '#e9ecef',
                reverseButtons: true
              }).then((result) => {
                if (result.value) {
                  ShowLoading();
                  window.location.href = '/BusinessTripRequest?var=1';
                }
              })
            },

            error: function(response) { // handle the error
              Swal.fire("Cancelled", "Data Cancel Inputed", "error");
            },

          })


        } else if (
          result.dismiss === Swal.DismissReason.cancel
        ) {
          swalWithBootstrapButtons.fire({

            title: 'Cancelled',
            text: "Process Canceled",
            type: 'error',
            confirmButtonColor: '#e9ecef',
            confirmButtonText: '<span style="color:black;"> Ok </span>',

          }).then((result) => {
            if (result.value) {
              ShowLoading();
              window.location.href = '/BusinessTripRequest?var=1';
            }
          })
        }
      })
      // }
    });

  });
</script>
