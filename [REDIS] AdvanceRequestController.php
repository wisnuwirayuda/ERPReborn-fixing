public function AdvanceListData(Request $request)
{
    try {
        // Cache::forget('DataListAdvance');

        // Cek cache terlebih dahulu di Redis
        $redis = \Illuminate\Support\Facades\Redis::connection();
        // Log::info("redis", $redis);
        $dataListAdvanceCache = $redis->get("DataListAdvance");

        // // Jika data cache tidak ada, ambil data dari API
        if (!$dataListAdvanceCache) {
            Log::info("1");
            $varAPIWebToken = Session::get('SessionLogin');
            
            \App\Helpers\ZhtHelper\System\FrontEnd\Helper_APICall::setCallAPIGateway(
                \App\Helpers\ZhtHelper\System\Helper_Environment::getUserSessionID_System(),
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

            $dataListAdvanceCache = json_decode(
                \App\Helpers\ZhtHelper\Cache\Helper_Redis::getValue(
                    \App\Helpers\ZhtHelper\System\Helper_Environment::getUserSessionID_System(),
                    "DataListAdvance"
                ),
                true
            );

            // Simpan data ke Redis dengan durasi 120 menit (7200 detik)
            $redis->setex('DataListAdvance', 7200, json_encode($dataListAdvanceCache));
        } else {
            Log::info("2");
            // Decode JSON string dari Redis
            $dataListAdvanceCache = json_decode($dataListAdvanceCache, true);
        }

        // Pastikan $dataListAdvanceCache tidak null sebelum digunakan
        if (is_null($dataListAdvanceCache)) {
            throw new \Exception("Data List Advance is null");
        }

        // Buat koleksi dari data yang diambil
        $collection = collect($dataListAdvanceCache);

        // Filter data berdasarkan parameter yang diberikan
        $project_id = $request->input('project_id');
        $site_id = $request->input('site_id');

        if (!empty($project_id)) {
            $collection = $collection->where('CombinedBudget_RefID', $project_id);
        }
        if (!empty($site_id)) {
            $collection = $collection->where('CombinedBudgetSection_RefID', $site_id);
        }

        // Ambil semua data yang sudah difilter
        $collection = $collection->values();

        // Kembalikan hasil dalam bentuk JSON
        return response()->json($collection);
    } catch (\Throwable $th) {
        // Log error jika terjadi kesalahan
        Log::error("Error at " . $th->getMessage());
        return redirect()->back()->with('NotFound', 'Process Error');
    }
}
