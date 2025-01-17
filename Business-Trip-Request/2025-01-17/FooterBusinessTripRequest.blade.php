<script type="text/javascript">
  // var currentModalSource = '';
  const initialValue = 0;
  const totalBusinessTrip = [];
  const transportInputs = [
    'taxi',
    'airplane',
    'train',
    'bus',
    'ship',
    'tol_road',
    'park',
    'access_bagage',
    'fuel'
  ];
  const accomodationInputs = [
    'hotel',
    'mess',
    'guest_house',
    'other_accomodation',
  ];
  const businessTripInputs = [
    'allowance',
    'entertainment',
    'other',
  ];
  const paymentsInputs = [
    'direct_to_vendor',
    'by_corp_card',
    'to_other',
  ];

  var date = new Date();
  var today = new Date(date.setMonth(date.getMonth() - 3));

  document.getElementById('dateCommance').setAttribute('min', today.toISOString().split('T')[0]);
  document.getElementById('dateEnd').setAttribute('min', today.toISOString().split('T')[0]);

  // FUNGSI UNTUK BUTTON CANCEL FORM
  function CancelBusinessTrip() {
    ShowLoading();
    window.location.href = '/BusinessTripRequest?var=1';
  }

  // FUNGSI UNTUK MENANGANI CHECKBOX PADA BUDGET DETAILS TABLE
  function handleCheckboxSelection() {
    const checkboxes = document.querySelectorAll('#budgetTable tbody input[type="checkbox"]');
    
    checkboxes.forEach((checkbox, index) => {
      checkbox.addEventListener('change', function() {
        if (this.checked) {
          checkboxes.forEach((otherCheckbox, otherIndex) => {
            if (otherIndex !== index) {
              otherCheckbox.disabled = true;
              otherCheckbox.checked = false;
            }
          });
          getSelectedRowData();
        } else {
          checkboxes.forEach(otherCheckbox => {
            otherCheckbox.disabled = false;
          });
          document.getElementById('budgetDetailsData').value = '';
        }
      });
    });
  }

  // FUNGSI UNTUK MENGUBAH STRING ANGKA DENGAN FORMAT KE NUMBER
  function parseFormattedNumber(strNumber) {
    return parseFloat(strNumber.replace(/,/g, ''));
  }

  // FUNGSI UNTUK MENDAPATKAN DATA BARIS YANG DICENTANG DAN MENYIMPAN KE INPUT
  function getSelectedRowData() {
    const selectedCheckbox = document.querySelector('#budgetTable tbody input[type="checkbox"]:checked');
    const budgetDetailsInput = document.getElementById('budgetDetailsData');
    const totalBusinessTripInput = document.getElementById('total_business_trip');
    const totalPaymentBusinessTripInput = document.getElementById('total_payment');
    
    if (selectedCheckbox) {
      const row = selectedCheckbox.closest('tr');
      const datas = {
        productId: row.cells[1].textContent.trim(),
        productName: row.cells[2].textContent.trim(),
        totalBudget: row.cells[3].textContent.trim(),
        qtyBudget: row.cells[4].textContent.trim(),
        qtyAvail: row.cells[5].textContent.trim(),
        price: row.cells[6].textContent.trim(),
        currency: row.cells[7].textContent.trim(),
        balanceBudget: row.cells[8].textContent.trim(),
      };
      
      budgetDetailsInput.value = JSON.stringify(datas);

      const balanceBudget = parseFormattedNumber(datas.balanceBudget);
      const totalBusinessTrip = parseFormattedNumber(totalBusinessTripInput.value || '0');
      const totalPaymentBusinessTrip = parseFormattedNumber(totalPaymentBusinessTripInput.value || '0');

      if (totalBusinessTrip > balanceBudget) {
        Swal.fire("Error", `Total Business Trip must not exceed the selected Balanced Budget`, "error");
      }

      if (totalPaymentBusinessTrip > balanceBudget) {
        Swal.fire("Error", `Total Payment must not exceed the selected Balanced Budget`, "error");
      }
    } else {
      budgetDetailsInput.value = '';
    }
  }

  // FUNGSI TOTAL TRANSPORT
  function calculateTotalTransport() {
    const taxi = parseFloat(document.getElementById('taxi').value.replace(/,/g, '')) || 0;
    const airplane = parseFloat(document.getElementById('airplane').value.replace(/,/g, '')) || 0;
    const train = parseFloat(document.getElementById('train').value.replace(/,/g, '')) || 0;
    const bus = parseFloat(document.getElementById('bus').value.replace(/,/g, '')) || 0;
    const ship = parseFloat(document.getElementById('ship').value.replace(/,/g, '')) || 0;
    const tolRoad = parseFloat(document.getElementById('tol_road').value.replace(/,/g, '')) || 0;
    const park = parseFloat(document.getElementById('park').value.replace(/,/g, '')) || 0;
    const accessBagage = parseFloat(document.getElementById('access_bagage').value.replace(/,/g, '')) || 0;
    const fuel = parseFloat(document.getElementById('fuel').value.replace(/,/g, '')) || 0;

    let newFormatBudget = 0;
    let budgetDetailsDataJSON = null;
    try {
      budgetDetailsDataJSON = document.getElementById('budgetDetailsData').value;
      if (budgetDetailsDataJSON) {
        const parsedData = JSON.parse(budgetDetailsDataJSON);
        newFormatBudget = parseFloat(parsedData.balanceBudget.replace(/,/g, '')) || 0;
      } else {
        // console.warn('Budget details data is empty');
      }
    } catch (error) {
      console.error('Error parsing budget details JSON:', error);
      return;
    }

    const total = taxi + airplane + train + bus + ship + tolRoad + park + accessBagage + fuel;
    totalBusinessTrip[0] = total;

    const sumTotalBusinessTrip = totalBusinessTrip.reduce((accumulator, currentValue) => accumulator + currentValue, initialValue);

    document.getElementById('total_transport').value = numberFormatPHPCustom(total, 2);
    document.getElementById('total_business_trip').value = numberFormatPHPCustom(sumTotalBusinessTrip, 2);

    if (budgetDetailsDataJSON && sumTotalBusinessTrip > newFormatBudget) {
      Swal.fire("Error", `Total Business Trip must not exceed the selected Balanced Budget`, "error");
    }
  }

  transportInputs.forEach(id => {
    const inputElement = document.getElementById(id);
    if (inputElement) {
      inputElement.addEventListener('input', calculateTotalTransport);
    }
  });

  // FUNGSI TOTAL ACCOMMODATION
  function calculateTotalAccomodation() {
    const hotel = parseFloat(document.getElementById('hotel').value.replace(/,/g, '')) || 0;
    const mess = parseFloat(document.getElementById('mess').value.replace(/,/g, '')) || 0;
    const guest_house = parseFloat(document.getElementById('guest_house').value.replace(/,/g, '')) || 0;
    const other_accomodation = parseFloat(document.getElementById('other_accomodation').value.replace(/,/g, '')) || 0;

    let newFormatBudget = 0;
    let budgetDetailsDataJSON = null;
    try {
      budgetDetailsDataJSON = document.getElementById('budgetDetailsData').value;
      if (budgetDetailsDataJSON) {
        const parsedData = JSON.parse(budgetDetailsDataJSON);
        newFormatBudget = parseFloat(parsedData.balanceBudget.replace(/,/g, '')) || 0;
      } else {
        // console.warn('Budget details data is empty');
      }
    } catch (error) {
      console.error('Error parsing budget details JSON:', error);
      return;
    }

    const total = hotel + mess + guest_house + other_accomodation;
    totalBusinessTrip[1] = total;

    const sumTotalBusinessTrip = totalBusinessTrip.reduce((accumulator, currentValue) => accumulator + currentValue,initialValue);
    
    document.getElementById('total_accomodation').value = numberFormatPHPCustom(total, 2);
    document.getElementById('total_business_trip').value = numberFormatPHPCustom(sumTotalBusinessTrip, 2);

    if (budgetDetailsDataJSON && sumTotalBusinessTrip > newFormatBudget) {
      Swal.fire("Error", `Total Business Trip must not exceed the selected Balanced Budget`, "error");
    }
  }

  accomodationInputs.forEach(id => {
    const inputElement = document.getElementById(id);
    if (inputElement) {
      inputElement.addEventListener('input', calculateTotalAccomodation);
    }
  });

  // FUNGSI TOTAL BUSINESS TRIP (TOTAL TRANSPORT + TOTAL ACCOMMODATION + ALLOWANCE + ENTERTAINMENT + OTHER)
  function calculateTotalBusinessTrip() {
    const allowance = parseFloat(document.getElementById('allowance').value.replace(/,/g, '')) || 0;
    const entertainment = parseFloat(document.getElementById('entertainment').value.replace(/,/g, '')) || 0;
    const other = parseFloat(document.getElementById('other').value.replace(/,/g, '')) || 0;

    let newFormatBudget = 0;
    let budgetDetailsDataJSON = null;
    try {
      budgetDetailsDataJSON = document.getElementById('budgetDetailsData').value;
      if (budgetDetailsDataJSON) {
        const parsedData = JSON.parse(budgetDetailsDataJSON);
        newFormatBudget = parseFloat(parsedData.balanceBudget.replace(/,/g, '')) || 0;
      } else {
        // console.warn('Budget details data is empty');
      }
    } catch (error) {
      console.error('Error parsing budget details JSON:', error);
      return;
    }

    const total = allowance + entertainment + other;
    totalBusinessTrip[2] = total;

    const sumTotalBusinessTrip = totalBusinessTrip.reduce((accumulator, currentValue) => accumulator + currentValue,initialValue);

    document.getElementById('total_business_trip').value = numberFormatPHPCustom(sumTotalBusinessTrip, 2);

    if (budgetDetailsDataJSON && sumTotalBusinessTrip > newFormatBudget) {
      Swal.fire("Error", `Total Business Trip must not exceed the selected Balanced Budget`, "error");
    }
  }

  businessTripInputs.forEach(id => {
    const inputElement = document.getElementById(id);
    
    if (inputElement) {
      inputElement.addEventListener('input', calculateTotalBusinessTrip);
    }
  });

  // FUNGSI TOTAL PAYMENT (DIRECT TO VENDOR + BY CORP CARD + TO OTHER)
  function calculateTotalPayments() {
    const directToVendor = parseFloat(document.getElementById('direct_to_vendor').value.replace(/,/g, '')) || 0;
    const byCorpCard = parseFloat(document.getElementById('by_corp_card').value.replace(/,/g, '')) || 0;
    const toOther = parseFloat(document.getElementById('to_other').value.replace(/,/g, '')) || 0;

    let newFormatBudget = 0;
    let budgetDetailsDataJSON = null;
    try {
      budgetDetailsDataJSON = document.getElementById('budgetDetailsData').value;
      if (budgetDetailsDataJSON) {
        const parsedData = JSON.parse(budgetDetailsDataJSON);
        newFormatBudget = parseFloat(parsedData.balanceBudget.replace(/,/g, '')) || 0;
      } else {
        // console.warn('Budget details data is empty');
      }
    } catch (error) {
      console.error('Error parsing budget details JSON:', error);
      return;
    }

    const total = directToVendor + byCorpCard + toOther;

    document.getElementById('total_payment').value = numberFormatPHPCustom(total, 2);

    if (budgetDetailsDataJSON && total > newFormatBudget) {
      Swal.fire("Error", `Total Payments must not exceed the selected Balanced Budget`, "error");
    }
  }

  paymentsInputs.forEach(id => {
    const inputElement = document.getElementById(id);
    
    if (inputElement) {
      inputElement.addEventListener('input', calculateTotalPayments);
    }
  });

  $("#myWorker").prop("disabled", true);
  $("#requester_popup").prop("disabled", true);
  $("#sitecode2").prop("disabled", true);
  $("#dateEnd").prop("disabled", true);
  $("#dateEnd").css("background-color", "white");
  $(".loading").hide();

  // DIRECT TO VENDOR
  $("#bank_list_popup_vendor").prop("disabled", true);
  $("#bank_accounts_popup_vendor").prop("disabled", true);

  // BY CORP CARD
  $("#bank_list_popup_corp_card").prop("disabled", true);
  $("#bank_accounts_popup_corp_card").prop("disabled", true);

  // TO OTHER
  $("#beneficiary_second_popup").prop("disabled", true);
  $("#bank_list_popup_second").prop("disabled", true);
  $("#bank_accounts_third_popup").prop("disabled", true);

  // BUDGET CODE
  $('#tableGetProject tbody').on('click', 'tr', function() {
    $("#sitecode").val("");
    $("#sitename").val("");
    $("#sitecode2").prop("disabled", false);

    $("#myProject").modal('toggle');

    var row = $(this).closest("tr");
    var id = row.find("td:nth-child(1)").text();
    var sys_id = $('#sys_id_budget' + id).val();
    var code = row.find("td:nth-child(2)").text();
    var name = row.find("td:nth-child(3)").text();

    $("#projectcode").val(code);
    $("#projectname").val(name);

    adjustInputSize(document.getElementById("projectcode"), "string");
    
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

  // SUB BUDGET CODE
  $('#tableGetSite tbody').on('click', 'tr', function() {
    $("#budgetDetailsData").val("");
    $("#myWorker").prop("disabled", false);
    $("#requester_popup").prop("disabled", false);
    $("#beneficiary_second_popup").prop("disabled", false);
    $("#bank_name_popup").prop("disabled", false);
    $("#bank_account_popup").prop("disabled", false);
    $("#bank_list_popup_vendor").prop("disabled", false);
    $("#bank_list_popup_corp_card").prop("disabled", false);
    $('table#budgetTable tbody').empty();
    $(".loading").show();
    
    $("#mySiteCode").modal('toggle');
    
    const searchBudgetBtn = document.getElementById('budget_detail_search');
    
    var row = $(this).closest("tr");
    var id = row.find("td:nth-child(1)").text();
    var sys_ID = $('#sys_id_site' + id).val();
    var code = row.find("td:nth-child(2)").text();
    var name = row.find("td:nth-child(3)").text();
    
    $("#sitecode").val(code);
    $("#sitename").val(name);

    adjustInputSize(document.getElementById("sitecode"));

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $.ajax({
      type: 'GET',
      url: '{!! route("getBudget") !!}?site_code=' + sys_ID,
      success: function(data) {
        const datas = [
          {
            product_RefID: "710006-0000",
            productName: "Transportation & Operational",
            quantity: 2,
            priceBaseCurrencyValue: 3000000,
            quantityRemaining: 1,
            priceBaseCurrencyISOCode: "IDR",
            currentBudget: 6000000,
          },
          {
            product_RefID: "820005-0000",
            productName: "Travel & Fares/Business Trip",
            quantity: 1,
            priceBaseCurrencyValue: 2500000,
            quantityRemaining: 0,
            priceBaseCurrencyISOCode: "IDR",
            currentBudget: 2500000,
          },
          // {
          //   product_RefID: 88000000003488,
          //   productName: "Transportasi Lokal",
          //   quantity: 3,
          //   priceBaseCurrencyValue: 150000,
          //   quantityRemaining: 1,
          //   priceBaseCurrencyISOCode: "IDR",
          //   currentBudget: 450000,
          // },
          // {
          //   product_RefID: 88000000003489,
          //   productName: "Makan Siang Selama Perjalanan",
          //   quantity: 5,
          //   priceBaseCurrencyValue: 50000,
          //   quantityRemaining: 2,
          //   priceBaseCurrencyISOCode: "IDR",
          //   currentBudget: 250000,
          // },
          // {
          //   product_RefID: 88000000003490,
          //   productName: "Meeting Room Rental",
          //   quantity: 1,
          //   priceBaseCurrencyValue: 1000000,
          //   quantityRemaining: 0,
          //   priceBaseCurrencyISOCode: "IDR",
          //   currentBudget: 1000000,
          // },
          // {
          //   product_RefID: 88000000003491,
          //   productName: "Sim Card dengan Paket Data",
          //   quantity: 1,
          //   priceBaseCurrencyValue: 200000,
          //   quantityRemaining: 0,
          //   priceBaseCurrencyISOCode: "IDR",
          //   currentBudget: 200000,
          // },
          // {
          //   product_RefID: 88000000003492,
          //   productName: "Tiket Kereta Antar Kota",
          //   quantity: 2,
          //   priceBaseCurrencyValue: 500000,
          //   quantityRemaining: 1,
          //   priceBaseCurrencyISOCode: "IDR",
          //   currentBudget: 1000000,
          // },
          // {
          //   product_RefID: 88000000003493,
          //   productName: "Pengeluaran Lain-Lain",
          //   quantity: 1,
          //   priceBaseCurrencyValue: 300000,
          //   quantityRemaining: 0,
          //   priceBaseCurrencyISOCode: "IDR",
          //   currentBudget: 300000,
          // },
          // {
          //   product_RefID: 88000000003494,
          //   productName: "Biaya Overweight Bagasi",
          //   quantity: 1,
          //   priceBaseCurrencyValue: 100000,
          //   quantityRemaining: 0,
          //   priceBaseCurrencyISOCode: "IDR",
          //   currentBudget: 100000,
          // },
          // {
          //   product_RefID: 88000000003495,
          //   productName: "Souvenir untuk Klien",
          //   quantity: 4,
          //   priceBaseCurrencyValue: 250000,
          //   quantityRemaining: 2,
          //   priceBaseCurrencyISOCode: "IDR",
          //   currentBudget: 1000000,
          // }
        ];

        $(".loading").hide();
        searchBudgetBtn.style.display = 'block';

        $.each(datas, function(key, val2) {
          var html = 
            '<tr>' +
              '<td style="padding-top: 10px !important; padding-bottom: 10px !important; text-align: center !important; border: 1px solid #e9ecef !important; padding-left: 10px !important; padding-right: 10px !important;">' +
                '<input type="checkbox" aria-label="Checkbox for following text input">' +
              '</td>' +
              '<td style="padding-top: 10px !important; padding-bottom: 10px !important; text-align: center !important; border: 1px solid #e9ecef !important; padding-left: 10px !important; padding-right: 10px !important;">' +
                val2.product_RefID +
              '</td>' +
              '<td style="padding-top: 10px !important; padding-bottom: 10px !important; text-align: center !important; border: 1px solid #e9ecef !important; padding-left: 10px !important; padding-right: 10px !important;">' +
                val2.productName +
              '</td>' +
              '<td style="padding-top: 10px !important; padding-bottom: 10px !important; text-align: center !important; border: 1px solid #e9ecef !important; padding-left: 10px !important; padding-right: 10px !important;">' +
                numberFormatPHPCustom(val2.quantity * val2.priceBaseCurrencyValue, 2) +
              '</td>' +
              '<td style="padding-top: 10px !important; padding-bottom: 10px !important; text-align: center !important; border: 1px solid #e9ecef !important; padding-left: 10px !important; padding-right: 10px !important;">' +
                numberFormatPHPCustom(val2.quantity, 2) +
              '</td>' +
              '<td style="padding-top: 10px !important; padding-bottom: 10px !important; text-align: center !important; border: 1px solid #e9ecef !important; padding-left: 10px !important; padding-right: 10px !important;">' +
                numberFormatPHPCustom(val2.quantityRemaining, 2) +
              '</td>' +
              '<td style="padding-top: 10px !important; padding-bottom: 10px !important; text-align: center !important; border: 1px solid #e9ecef !important; padding-left: 10px !important; padding-right: 10px !important;">' +
                numberFormatPHPCustom(val2.priceBaseCurrencyValue, 2) +
              '</td>' +
              '<td style="padding-top: 10px !important; padding-bottom: 10px !important; text-align: center !important; border: 1px solid #e9ecef !important; padding-left: 10px !important; padding-right: 10px !important;">' +
                val2.priceBaseCurrencyISOCode +
              '</td>' +
              '<td style="padding-top: 10px !important; padding-bottom: 10px !important; text-align: center !important; border: 1px solid #e9ecef !important; padding-left: 10px !important; padding-right: 10px !important;">' +
                numberFormatPHPCustom(val2.currentBudget, 2) +
                // numberFormatPHPCustom(50000, 2) +
              '</td>' +
            '</tr>';

          $('table#budgetTable tbody').append(html);
        });

        handleCheckboxSelection();
      }
    });
  });

  // SEARCH BUDGET DETAILS
  $('#budget_detail_search').on('input', function() {
    const searchValue = $(this).val().toLowerCase();
    
    const rows = $('#budgetTable tbody tr');

    rows.each(function() {
      const row = $(this);
      const productId = row.find('td:eq(1)').text().trim().toLowerCase();
      const productName = row.find('td:eq(2)').text().trim().toLowerCase();
      
      if (productId.includes(searchValue) || productName.includes(searchValue)) {
        row.show();
      } else {
        row.hide();
      }
    });
  });

  // SEARCH BUDGET DETAILS
  $('#budget_detail_search').on('change', function() {
    if ($(this).val() === '') {
      $('#budgetTable tbody tr').show();
    }
  });

  // DATE COMMANCE
  $('#dateCommance').change(function() {
    $("#dateEnd").prop("disabled", false);
    var dateCommance = new Date($("#dateCommance").val());
    document.getElementById('dateEnd').setAttribute('min', dateCommance.toISOString().split('T')[0]);
  });

  // ========== VENDOR ==========
  // GET BANK ACCOUNT VENDOR KETIKA MODAL BANK NAME VENDOR KE CLOSE
  $('#myGetBankList').on('hidden.bs.modal', function () {
    const bankVendorID = document.getElementById('bank_list_code');
    const bankAccountsID = document.getElementById('bank_accounts_id');

    // CEK APAKAH BANK NAME VENDOR SUDAH TERISI
    if (bankVendorID.value && !bankAccountsID.value) {
      $("#bank_accounts_popup_vendor").prop("disabled", false);
      $("#bank_accounts").removeAttr("readonly");
      $("#bank_accounts_detail").removeAttr("readonly");

      getBankAccountData(bankVendorID.value);
    }
  });

  // KETIKA MODAL BANK NAME VENDOR DIPILIH, MAKA MENGHAPUS VALUE BANK ACCOUNT VENDOR
  $('#tableGetBankList').on('click', 'tbody tr', function() {
    $("#bank_accounts").val("");
    $("#bank_accounts_id").val("");
    $("#bank_accounts_detail").val("");
  });

  // MENAMBAHKAN READ-ONLY PADA KOMPONEN BANK ACCOUNT VENDOR
  $('#tableGetBankAccount').on('click', 'tbody tr', function() {
    var sysID       = $(this).find('input[type="hidden"]').val();
    var bankAccount = $(this).find('td:nth-child(3)').text();
    var accountName = $(this).find('td:nth-child(4)').text();

    $("#bank_accounts_duplicate_id").val(sysID);
    $("#bank_accounts_duplicate").val(bankAccount);
    $("#bank_accounts_duplicate_detail").val(accountName);
  });

  $('#bank_accounts').on('input', function() {
    var bankAccount                 = document.getElementById('bank_accounts');
    var bankAccountDuplicate        = document.getElementById('bank_accounts_duplicate');
    var bankAccountDuplicateId      = document.getElementById('bank_accounts_duplicate_id');
    var bankAccountDetail           = document.getElementById('bank_accounts_detail');
    var bankAccountDuplicateDetail  = document.getElementById('bank_accounts_duplicate_detail');

    if (bankAccount.value !== bankAccountDuplicate.value || bankAccountDetail.value !== bankAccountDuplicateDetail.value) {
      $("#bank_accounts_id").val("");
    } else {
      $("#bank_accounts_id").val(bankAccountDuplicateId.value);
    }
  });

  $('#bank_accounts_detail').on('input', function() {
    var bankAccountDetail           = document.getElementById('bank_accounts_detail');
    var bankAccountDuplicateDetail  = document.getElementById('bank_accounts_duplicate_detail');
    var bankAccountDuplicateId      = document.getElementById('bank_accounts_duplicate_id');
    var bankAccount                 = document.getElementById('bank_accounts');
    var bankAccountDuplicate        = document.getElementById('bank_accounts_duplicate');

    if (bankAccountDetail.value !== bankAccountDuplicateDetail.value || bankAccount.value !== bankAccountDuplicate.value) {
      $("#bank_accounts_id").val("");
    } else {
      $("#bank_accounts_id").val(bankAccountDuplicateId.value);
    }
  });
  
  // ========== VENDOR ==========

  // ========== CORP CARD ==========
  // GET BANK ACCOUNT CORP CARD KETIKA MODAL BANK NAME CORP CARD KE CLOSE
  $('#myGetBankListSecond').on('hidden.bs.modal', function () {
    const bankCorpCardID = document.getElementById('bank_list_second_code');
    const bankAccountsCorpCardID = document.getElementById('bank_accounts_id_second');

    // CEK APAKAH BANK NAME CORP CARD SUDAH TERISI
    if (bankCorpCardID.value && !bankAccountsCorpCardID.value) {
      $("#bank_accounts_popup_corp_card").prop("disabled", false);
      $("#bank_accounts_second").removeAttr("readonly");
      $("#bank_accounts_detail_second").removeAttr("readonly");

      getBankAccountData(bankCorpCardID.value, "second_modal");
    }
  });

  // KETIKA MODAL BANK NAME CORP CARD DIPILIH, MAKA MENGHAPUS VALUE BANK ACCOUNT CORP CARD
  $('#tableGetBankListSecond').on('click', 'tbody tr', function() {
    $("#bank_accounts_second").val("");
    $("#bank_accounts_id_second").val("");
    $("#bank_accounts_detail_second").val("");
  });

  // MENAMBAHKAN READ-ONLY PADA KOMPONEN BANK ACCOUNT CORP CARD
  $('#tableGetBankAccountSecond').on('click', 'tbody tr', function() {
    var sysID       = $(this).find('input[type="hidden"]').val();
    var bankAccount = $(this).find('td:nth-child(3)').text();
    var accountName = $(this).find('td:nth-child(4)').text();

    $("#bank_accounts_duplicate_id_second").val(sysID);
    $("#bank_accounts_duplicate_second").val(bankAccount);
    $("#bank_accounts_detail_duplicate_second").val(accountName);
  });

  $('#bank_accounts_second').on('input', function() {
    var bankAccountSecond                 = document.getElementById('bank_accounts_second');
    var bankAccountSecondDuplicate        = document.getElementById('bank_accounts_duplicate_second');
    var bankAccountSecondDuplicateId      = document.getElementById('bank_accounts_duplicate_id_second');
    var bankAccountDetailSecond           = document.getElementById('bank_accounts_detail_second');
    var bankAccountDuplicateDetailSecond  = document.getElementById('bank_accounts_detail_duplicate_second');

    if (bankAccountSecond.value !== bankAccountSecondDuplicate.value || bankAccountDetailSecond.value !== bankAccountDuplicateDetailSecond.value) {
      $("#bank_accounts_id_second").val("");
    } else {
      $("#bank_accounts_id_second").val(bankAccountSecondDuplicateId.value);
    }
  });

  $('#bank_accounts_detail_second').on('input', function() {
    var bankAccountDetailSecond           = document.getElementById('bank_accounts_detail_second');
    var bankAccountDuplicateDetailSecond  = document.getElementById('bank_accounts_detail_duplicate_second');
    var bankAccountDuplicateIdSecond      = document.getElementById('bank_accounts_duplicate_id_second');
    var bankAccountSecond                 = document.getElementById('bank_accounts_second');
    var bankAccountSecondDuplicate        = document.getElementById('bank_accounts_duplicate_second');

    if (bankAccountDetailSecond.value !== bankAccountDuplicateDetailSecond.value || bankAccountSecond.value !== bankAccountSecondDuplicate.value) {
      $("#bank_accounts_id_second").val("");
    } else {
      $("#bank_accounts_id_second").val(bankAccountDuplicateIdSecond.value);
    }
  });
  // ========== CORP CARD ==========

  // ========== TO OTHER ==========
  $('#myBeneficiarySecond').on('hidden.bs.modal', function () {
    const beneficiaryRefID = document.getElementById('beneficiary_second_id');
    const beneficiaryPersonRefID = document.getElementById('beneficiary_second_person_ref_id');

    if (beneficiaryRefID.value && beneficiaryPersonRefID.value) {
      $("#bank_list_popup_second").prop("disabled", false);
      // $("#bank_accounts_third_popup").prop("disabled", false);
    }
  });

  $('#tableGetBeneficiarySecond').on('click', 'tbody tr', function() {
    const bankCorpCardID = document.getElementById('beneficiary_second_person_ref_id');
    
    if (bankCorpCardID.value) {
      // $("#bank_list_third_name").val("");
      // $("#bank_list_third_code").val("");
      // $("#bank_list_third_detail").val("");

      // $("#bank_accounts_third").val("");
      // $("#bank_accounts_third_id").val("");
      // $("#bank_accounts_third_detail").val("");
    }

    adjustInputSize(document.getElementById("beneficiary_second_person_position"), "string");
  });

  $('#myGetBankListThird').on('hidden.bs.modal', function () {
    const bankListThirdCode = document.getElementById('bank_list_third_code');

    if (bankListThirdCode.value) {
      getBankAccountData(bankListThirdCode.value,'third_modal');

      $("#bank_accounts_third").val("");
      $("#bank_accounts_third_id").val("");
      $("#bank_accounts_third_detail").val("");

      $("#bank_accounts_third").removeAttr("readonly");
      $("#bank_accounts_third_detail").removeAttr("readonly");

      $("#bank_accounts_third_popup").prop("disabled", false);
    }
  });

  $('#tableGetBankAccountThird').on('click', 'tbody tr', function() {
    var sysID       = $(this).find('input[type="hidden"]').val();
    var bankAccount = $(this).find('td:nth-child(3)').text();
    var accountName = $(this).find('td:nth-child(4)').text();

    $("#bank_accounts_duplicate_third_id").val(sysID);
    $("#bank_accounts_duplicate_third").val(bankAccount);
    $("#bank_accounts_duplicate_third_detail").val(accountName);
  });

  $('#bank_accounts_third').on('input', function() {
    var bankAccountThird                  = document.getElementById('bank_accounts_third');
    var bankAccountThirdDuplicate         = document.getElementById('bank_accounts_duplicate_third');
    var bankAccountThirdDuplicateId       = document.getElementById('bank_accounts_duplicate_third_id');
    var bankAccountDetailThird            = document.getElementById('bank_accounts_third_detail');
    var bankAccountDuplicateDetailThird   = document.getElementById('bank_accounts_duplicate_third_detail');

    if (bankAccountThird.value !== bankAccountThirdDuplicate.value || bankAccountDetailThird.value !== bankAccountDuplicateDetailThird.value) {
      $("#bank_accounts_third_id").val("");
    } else {
      $("#bank_accounts_third_id").val(bankAccountThirdDuplicateId.value);
    }
  });

  $('#bank_accounts_third_detail').on('input', function() {
    var bankAccountDetailThird           = document.getElementById('bank_accounts_third_detail');
    var bankAccountDuplicateDetailThird  = document.getElementById('bank_accounts_duplicate_third_detail');
    var bankAccountDuplicateIdThird      = document.getElementById('bank_accounts_duplicate_third_id');
    var bankAccountThird                 = document.getElementById('bank_accounts_third');
    var bankAccountThirdDuplicate        = document.getElementById('bank_accounts_duplicate_third');

    if (bankAccountDetailThird.value !== bankAccountDuplicateDetailThird.value || bankAccountThird.value !== bankAccountThirdDuplicate.value) {
      $("#bank_accounts_third_id").val("");
    } else {
      $("#bank_accounts_third_id").val(bankAccountDuplicateIdThird.value);
    }
  });

  // $('#myGetBankSecond').on('hidden.bs.modal', function () {
  //   const bank_RefID = document.getElementById('bank_name_second_id');
  //   const person_RefID = document.getElementById('beneficiary_second_person_ref_id');

  //   if (bank_RefID.value && person_RefID.value) {
  //     getBankAccountData(bank_RefID.value, "third_modal", person_RefID.value);
  //   }
  // });

  // $('#tableGetBankSecond').on('click', 'tbody tr', function() {
  //   $("#bank_accounts_third_popup").prop("disabled", false);
  // });
  // ========== TO OTHER ==========

  // SUBMIT FORM
  $("#FormSubmitBusinessTrip").on("submit", function(e) {
    e.preventDefault();

    const swalWithBootstrapButtons = Swal.mixin({
      confirmButtonClass: 'btn btn-success',
      cancelButtonClass: 'btn btn-danger',
      buttonsStyling: true,
    });

    swalWithBootstrapButtons.fire({
      title: 'Are you sure?',
      text: "Please confirm to save this data.",
      type: 'question',
      showCancelButton: true,
      confirmButtonText: 'Yes, submit it!',
      cancelButtonText: 'No, cancel!',
      reverseButtons: true
    }).then((result) => {
      if (result.value) {
        
      } else if (result.dismiss === Swal.DismissReason.cancel) {
        swalWithBootstrapButtons.fire({
          title: 'Cancelled',
          text: "The action has been canceled.",
          type: 'error',
        });
      }
    });
  });
</script>
