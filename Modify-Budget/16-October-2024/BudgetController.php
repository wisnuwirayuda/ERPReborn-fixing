public function ModifyBudget(Request $request) {
    $varAPIWebToken = $request->session()->get('SessionLogin');

    // dd('Testing');

    $compact = [
        'varAPIWebToken' => $varAPIWebToken
    ];

    return view('Budget.Budget.Transactions.ModifyBudget', $compact);
}

public function UpdateModifyBudget(Request $request) {
    try {
        $varAPIWebToken     = $request->session()->get('SessionLogin');

        $compact = [
            'varAPIWebToken'    => $varAPIWebToken,
            'files'             => json_decode($request->input('files'), true) == [] ? null : json_decode($request->input('files'), true),
            'budgetID'          => $request->budgetID,
            'budgetCode'        => $request->budgetCode,
            'budgetName'        => $request->budgetName,
            'subBudgetID'       => $request->subBudgetID,
            'subBudgetCode'     => $request->subBudgetCode,
            'subBudgetName'     => $request->subBudgetName,
            'reason'            => $request->reason,
            'additionalCO'      => $request->additionalCO,
            'currencyID'        => $request->currencyID,
            'currencySymbol'    => $request->currencySymbol,
            'currencyName'      => $request->currencyName,
            'idrRate'           => $request->valueIDRRate,
            'valueAdditionalCO' => $request->valueAdditionalCO,
            'valueDeductiveCO'  => $request->valueDeductiveCO,
            'totalAdditional'   => $request->totalAdditional,
            'totalSaving'       => $request->totalSaving,
            'dataModifyBudget'  => json_decode($request->input('dataModifyBudget'), true),
            'parsedData'        => json_decode($request->input('parsedData'), true),
            'hiddenBudgetData'  => json_decode($request->input('hiddenBudgetData'), true),
        ];
        
        // dump($compact);
        
        return view('Budget.Budget.Transactions.UpdateModifyBudget', $compact);
    } catch (\Throwable $th) {
        Log::error("Error at UpdateModifyBudget: " . $th->getMessage());
        return redirect()->back()->with('NotFound', 'Process Error');
    }
}

public function PreviewModifyBudget(Request $request) {
    try {
        $varAPIWebToken = $request->session()->get('SessionLogin');

        // dd('Testing 1');

        // PIC
        $PIC                = $request->session()->get("SessionLoginName");

        // BUDGET CODE
        $budgetID           = $request->project_id;
        $budgetCode         = $request->project_code;
        $budgetName         = $request->project_name;

        // SUB BUDGET CODE
        $subBudgetID        = $request->site_id;
        $subBudgetCode      = $request->site_code;
        $subBudgetName      = $request->site_name;

        // dd($subBudgetID, $subBudgetCode, $subBudgetName, $request->site_code, $request->subBudgetCode);

        // REASON FOR MODIFY
        $reason             = $request->reason_modify;

        // ADDITIONAL CO
        $additionalCO       = $request->additional_co;

        // CURRENCY
        $currencyID         = $request->currency_id;
        $currencySymbol     = $request->currency_symbol ?? '';
        $currencyName       = $request->currency_name ?? '-';

        $hiddenBudgetData   = $request->input('hiddenBudgetData');

        $parsedData         = json_decode($hiddenBudgetData, true);

        // dump($hiddenBudgetData, $parsedData);
        
        // IDR RATE
        $idrRate            = floatval($request->value_idr_rate);
        
        // VALUE ADDITIONAL CO
        $valueAdditionalCO  = floatval($request->value_co_additional);

        // VALUE DEDUCTIVE CO
        $valueDeductiveCO   = floatval($request->value_co_deductive);

        // FILES
        $files              = $request->dataInput_Log_FileUpload_1 ?? [];

        // MODIFY BUDGET LIST TABLE (CART)
        $productIds         = $request->input('product_id');
        $productName        = $request->input('product_name');
        $qtyBudget          = $request->input('qty_budget');
        // $qtyAvail           = $request->input('qty_avail');
        $price              = $request->input('price');
        // $currency           = $request->input('currency');
        // $balanceBudget      = $request->input('balance_budget');
        $totalBudget        = $request->input('total_budget');
        $qtyAdditionals     = $request->input('qty_additional');
        $priceAdditionals   = $request->input('price_additional');
        $totalAdditionals   = $request->input('total_additional');
        $qtySavings         = $request->input('qty_saving');
        $priceSavings       = $request->input('price_saving');
        $totalSavings       = $request->input('total_saving');
        $type               = $request->input('type');

        // dd(
        //     $budgetID, 
        //     $budgetCode, 
        //     $budgetName, 
        //     $subBudgetID, 
        //     $subBudgetCode, 
        //     $subBudgetName, 
        //     $reason, 
        //     $additionalCO, 
        //     $currencyID, 
        //     $currencySymbol,
        //     $currencyName,
        //     $parsedData,
        //     $idrRate,
        //     $valueAdditionalCO,
        //     $valueDeductiveCO,
        //     $files,
        // );

        // dd(
        //     $productIds,
        //     $productName,
        //     $qtyBudget,
        //     $price,
        //     $totalBudget,
        //     $qtyAdditionals,
        //     $priceAdditionals,
        //     $totalAdditionals,
        //     $qtySavings,
        //     $priceSavings,
        //     $totalSavings,
        //     $type
        // );

        $addSubtSectionOne = 0;
        if ($currencySymbol !== "IDR") {
            if ($additionalCO == "yes") {
                if ($valueAdditionalCO) {
                    $addSubtSectionOne = '+' . ($valueAdditionalCO * $idrRate);
                } else {
                    $addSubtSectionOne = '-' . ($valueDeductiveCO * $idrRate);
                }
            } else {
                $addSubtSectionOne = 0;
            }
        } else {
            if ($additionalCO == "yes") {
                if ($valueAdditionalCO) {
                    $addSubtSectionOne = '+' . $valueAdditionalCO;
                } else {
                    $addSubtSectionOne = '-' . $valueDeductiveCO;
                }
            } else {
                $addSubtSectionOne = 0;
            }
        }

        $i = 0;
        $dataModifyBudget = [];
        $totalAdditional = 0;
        $totalSaving = 0;
        foreach ($productIds as $index => $productId) {
            $totalAdditional                            += $totalAdditionals[$index];
            $totalSaving                                += $totalSavings[$index];

            $dataModifyBudget[$i]['no']                 = $i + 1;
            $dataModifyBudget[$i]['productID']          = $productIds[$index];
            $dataModifyBudget[$i]['productName']        = $productName[$index];
            $dataModifyBudget[$i]['qtyBudget']          = $qtyBudget[$index];
            // $dataModifyBudget[$i]['qtyAvail']           = number_format($qtyAvail[$index], 2);
            $dataModifyBudget[$i]['price']              = $price[$index];
            // $dataModifyBudget[$i]['currency']           = $currency[$index];
            // $dataModifyBudget[$i]['balanceBudget']      = number_format($balanceBudget[$index], 2);
            $dataModifyBudget[$i]['totalBudget']        = $totalBudget[$index];
            $dataModifyBudget[$i]['qtyAdditionals']     = number_format($qtyAdditionals[$index], 2);
            $dataModifyBudget[$i]['priceAdditionals']   = number_format($priceAdditionals[$index], 2);
            $dataModifyBudget[$i]['totalAdditionals']   = number_format($totalAdditionals[$index], 2);
            $dataModifyBudget[$i]['qtySavings']         = number_format($qtySavings[$index], 2);
            $dataModifyBudget[$i]['priceSavings']       = number_format($priceSavings[$index], 2);
            $dataModifyBudget[$i]['totalSavings']       = number_format($totalSavings[$index], 2);
            $dataModifyBudget[$i]['type']               = $type[$index] ?? 'formBudgetDetails';
            
            $i++;
        }

        // dd($dataModifyBudget);

        $compact = [
            'varAPIWebToken'    => $varAPIWebToken,
            'pic'               => $PIC,
            'budgetID'          => $budgetID,
            'budgetCode'        => $budgetCode,
            'budgetName'        => $budgetName,
            'subBudgetID'       => $subBudgetID,
            'subBudgetCode'     => $subBudgetCode,
            'subBudgetName'     => $subBudgetName,
            'reason'            => $reason ? $reason : '-',
            'additionalCO'      => $additionalCO,
            'currencyID'        => $currencyID,
            'currencySymbol'    => $currencySymbol,
            'currencyName'      => $currencyName,
            'idrRate'           => $idrRate ? number_format($idrRate, 2) : '-',
            'valueIDRRate'      => $idrRate,
            'valueAdditionalCO' => $valueAdditionalCO,
            'valueDeductiveCO'  => $valueDeductiveCO,
            'files'             => $files,
            'dataModifyBudget'  => $dataModifyBudget,
            'totalAdditional'   => number_format($totalAdditional, 2),
            'totalSaving'       => number_format($totalSaving, 2),
            'parsedData'        => $parsedData,
            'hiddenBudgetData'  => $hiddenBudgetData,
            'dataTable'         => [
                'sectionOne'    => [
                    'firstRow'  => [
                        'description'   => 'Customer Oder (CO)',
                        'valuta'        => 'IDR',
                        'origin'        => 465000000,
                        'previous'      => 465000000,
                        'addSubt'       => $addSubtSectionOne,
                        'totalCurrent'  => $additionalCO == "yes" ? $valueAdditionalCO ? 465000000 + $addSubtSectionOne : 465000000 - $addSubtSectionOne : 465000000
                    ],
                    'secondRow' => [
                        'description'   => '',
                        'valuta'        => 'Foreign Currency',
                        'origin'        => 0,
                        'previous'      => 0,
                        'addSubt'       => $currencySymbol != "IDR" && $additionalCO == "yes" ? $valueAdditionalCO ? '+' . $valueAdditionalCO : '-' . $valueDeductiveCO : 0,
                        'totalCurrent'  => 0
                    ],
                    'thirdRow' => [
                        'description'   => 'Total Ekuivalen',
                        'valuta'        => 'IDR',
                        'origin'        => 0,
                        'previous'      => 0,
                        'addSubt'       => 0,
                        'totalCurrent'  => 0
                    ],
                ],
                'sectionTwo'    => [
                    'firstRow'  => [
                        'description'   => 'Add(Subt) Cost',
                        'valuta'        => 'IDR',
                        'origin'        => 376712000,
                        'previous'      => 376712000,
                        'addSubt'       => $addSubtSectionOne,
                        // 'addSubt'       => $totalAdditional - $totalSaving,
                        'totalCurrent'  => 376712000 + $addSubtSectionOne
                    ],
                    'secondRow' => [
                        'description'   => '',
                        'valuta'        => 'Foreign Currency',
                        'origin'        => 0,
                        'previous'      => 0,
                        'addSubt'       => $currencySymbol != "IDR" && $additionalCO == "yes" ? $valueAdditionalCO ? '+' . $valueAdditionalCO : '-' . $valueDeductiveCO : 0,
                        // 'addSubt'       => 0,
                        'totalCurrent'  => 0
                    ],
                    'thirdRow' => [
                        'description'   => '',
                        'valuta'        => '',
                        'origin'        => 'Recorded Cost',
                        'previous'      => 0,
                        'addSubt'       => '',
                        'totalCurrent'  => ''
                    ],
                    'fourthRow' => [
                        'description'   => '',
                        'valuta'        => '',
                        'origin'        => 'Balanced Budget',
                        'previous'      => 0,
                        'addSubt'       => '',
                        'totalCurrent'  => ''
                    ],
                    'fifthRow' => [
                        'description'   => 'Total Ekuivalen',
                        'valuta'        => 'IDR',
                        'origin'        => 0,
                        'previous'      => 376712000,
                        'addSubt'       => 0,
                        'totalCurrent'  => 376712000
                    ]
                ],
                'sectionThree'  => [
                    'firstRow'  => [
                        'description'   => 'Gross Margin',
                        'valuta'        => 'IDR',
                        'origin'        => 0,
                        'previous'      => 79288000,
                        'addSubt'       => 0,
                        'totalCurrent'  => 79288000
                    ],
                    'secondRow' => [
                        'description'   => '',
                        'valuta'        => 'Foreign Currency',
                        'origin'        => 0,
                        'previous'      => 0,
                        'addSubt'       => 0,
                        'totalCurrent'  => 0
                    ],
                    'thirdRow' => [
                        'description'   => 'Total Ekuivalen',
                        'valuta'        => 'IDR',
                        'origin'        => 0,
                        'previous'      => 79288000,
                        'addSubt'       => 0,
                        'totalCurrent'  => 79288000
                    ],
                ],
                'sectionFour'  => [
                    'firstRow'  => [
                        'description'   => 'Gross Margin',
                        'valuta'        => '%',
                        'origin'        => 0,
                        'previous'      => 17.39,
                        'addSubt'       => '',
                        'totalCurrent'  => 17.39
                    ],
                    'secondRow' => [
                        'description'   => 'Gross Margin Movement',
                        'valuta'        => '%',
                        'origin'        => 17.39,
                        'previous'      => 0,
                        'addSubt'       => '',
                        'totalCurrent'  => ''
                    ],
                ],
                'sectionFive'  => [
                    'firstRow'  => [
                        'description'   => 'Recorded Cost',
                        'valuta'        => 'IDR',
                        'origin'        => '',
                        'previous'      => '',
                        'addSubt'       => '',
                        'totalCurrent'  => 0
                    ],
                    'secondRow' => [
                        'description'   => '',
                        'valuta'        => 'Foreign Currency',
                        'origin'        => '',
                        'previous'      => '',
                        'addSubt'       => '',
                        'totalCurrent'  => 0
                    ],
                    'thirdRow' => [
                        'description'   => 'Total Ekuivalen',
                        'valuta'        => 'IDR',
                        'origin'        => '',
                        'previous'      => '',
                        'addSubt'       => '',
                        'totalCurrent'  => 0
                    ],
                    'fourthRow' => [
                        'description'   => 'Actual Gross Margin',
                        'valuta'        => '%',
                        'origin'        => '',
                        'previous'      => '',
                        'addSubt'       => '',
                        'totalCurrent'  => 0
                    ],
                ],
            ],
        ];

        // dd($compact);

        return view('Budget.Budget.Transactions.PreviewModifyBudget', $compact);
    } catch (\Throwable $th) {
        Log::error("Error at PreviewModifyBudget: " . $th->getMessage());
        return redirect()->back()->with('NotFound', 'Process Error');
    }
}
