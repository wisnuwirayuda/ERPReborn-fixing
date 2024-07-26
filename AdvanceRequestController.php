// LIST DATA FUNCTION FOR SHOW DATA ADVANCE 
public function AdvanceListData(Request $request)
{
  try {
      $cacheKey = "DataListAdvance";
      $sessionID = \App\Helpers\ZhtHelper\System\Helper_Environment::getUserSessionID_System();

      $cachedData = \App\Helpers\ZhtHelper\Cache\Helper_Redis::getValue($sessionID, $cacheKey);

      if ($cachedData === null) {
          $varAPIWebToken = Session::get('SessionLogin');
          $apiResponse = \App\Helpers\ZhtHelper\System\FrontEnd\Helper_APICall::setCallAPIGateway(
              $sessionID,
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

          $cachedData = json_encode($apiResponse);

          \App\Helpers\ZhtHelper\Cache\Helper_Redis::setValue(
              $sessionID,
              $cacheKey,
              $cachedData
          );
      }

      $DataListAdvance = json_decode($cachedData, true);

      // Ensure DataListAdvance is an array
      if (!is_array($DataListAdvance)) {
          $DataListAdvance = [];
      }

      $collection = collect($DataListAdvance);

      $project_id = $request->project_id;
      $site_id = $request->site_id;

      if ($project_id != "") {
          $collection = $collection->where('CombinedBudget_RefID', $project_id);
      }
      if ($site_id != "") {
          $collection = $collection->where('CombinedBudgetSection_RefID', $site_id);
      }

      $collection = $collection->values()->all();

      return response()->json($collection);
  } catch (\Throwable $th) {
      Log::error("Error: " . $th->getMessage());
      return response()->json(['error' => 'Process Error'], 500);
  }
}
