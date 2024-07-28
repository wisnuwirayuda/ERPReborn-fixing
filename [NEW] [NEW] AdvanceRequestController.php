public function AdvanceListData(Request $request)
    {
        try {
            $varAPIWebToken = Session::get('SessionLogin');
            $userSessionID = \App\Helpers\ZhtHelper\System\Helper_Environment::getUserSessionID_System();

            // Cek apakah data ada di cache
            $cachedData = \App\Helpers\ZhtHelper\Cache\Helper_Redis::getValue($userSessionID, "DataListAdvance");

            if ($cachedData === null) {
                // Panggil API Gateway jika data tidak ada di cache
                \App\Helpers\ZhtHelper\System\FrontEnd\Helper_APICall::setCallAPIGateway(
                    $userSessionID,
                    $varAPIWebToken,
                    'transaction.read.dataList.finance.getAdvance',
                    'latest',
                    [
                        'parameter' => null,
                        'SQLStatement' => [
                            'pick' => null,
                            'sort' => null,
                            'filter' => null,
                            'paging' => null
                        ]
                    ],
                    false
                );

                // Ambil data yang baru dari cache setelah memanggil API
                $cachedData = \App\Helpers\ZhtHelper\Cache\Helper_Redis::getValue($userSessionID, "DataListAdvance");
            }

            // Decode data dari cache
            $DataListAdvance = json_decode($cachedData, true);

            // Filter data menggunakan Collection Laravel
            $collection = collect($DataListAdvance);
            $project_id = $request->project_id;
            $site_id = $request->site_id;

            if ($project_id != "") {
                $collection = $collection->where('CombinedBudget_RefID', $project_id);
            }
            if ($site_id != "") {
                $collection = $collection->where('CombinedBudgetSection_RefID', $site_id);
            }

            $filteredData = $collection->values()->all();

            return response()->json($filteredData);
        } catch (\Throwable $th) {
            Log::error("Error at " . $th->getMessage());
            return redirect()->back()->with('NotFound', 'Process Error');
        }
    }
