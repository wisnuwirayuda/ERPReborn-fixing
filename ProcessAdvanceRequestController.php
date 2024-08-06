public function RevisionAdvanceIndex(Request $request)
{
    try {
        // Validasi input
        $request->validate([
            'advance_RefID' => 'required|integer'
        ]);

        // Mengambil advance_RefID dari request
        $advance_RefID = $request->input('advance_RefID');

        // Mengambil token dari session
        $varAPIWebToken = Session::get('SessionLogin');

        // Key untuk caching
        $cacheKey = "revision_advance_{$advance_RefID}_{$varAPIWebToken}";

        // Memeriksa cache terlebih dahulu
        $filteredArray = Cache::remember($cacheKey, 60, function () use ($varAPIWebToken, $advance_RefID) {
            return \App\Helpers\ZhtHelper\System\FrontEnd\Helper_APICall::setCallAPIGateway(
                \App\Helpers\ZhtHelper\System\Helper_Environment::getUserSessionID_System(),
                $varAPIWebToken,
                'transaction.read.dataList.finance.getAdvanceReport',
                'latest',
                [
                    'parameter' => [
                        'advance_RefID' => (int) $advance_RefID,
                    ],
                    'SQLStatement' => [
                        'pick' => null,
                        'sort' => null,
                        'filter' => null,
                        'paging' => null
                    ]
                ],
                false
            );
        });

        // Memeriksa apakah data yang diambil valid
        if (isset($filteredArray['data'][0]['document'])) {
            $dataHeader = $filteredArray['data'][0]['document']['header'];
            $dataContent = $filteredArray['data'][0]['document']['content']['general'];
            $dataDetail = $filteredArray['data'][0]['document']['content']['details']['itemList'];
        } else {
            return redirect()->back()->with('NotFound', 'Data not found');
        }

        // Menyiapkan data untuk dikirim ke view
        $compact = compact(
            'dataHeader', 
            'dataContent', 
            'dataDetail', 
            'varAPIWebToken'
        );
        $compact['statusRevisi'] = 1;
        $compact['statusFinalApprove'] = "No";

        return view('Process.Advance.AdvanceRequest.Transactions.RevisionAdvanceRequest', $compact);
    } catch (\Throwable $th) {
        Log::error("Error at " . $th->getMessage());
        return redirect()->back()->with('NotFound', 'Process Error');
    }
}
