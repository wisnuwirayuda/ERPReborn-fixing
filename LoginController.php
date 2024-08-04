public function loginStore(Request $request)
{
    try {
        $username = $request->input('username');
        $password = $request->input('password');
        $varBranchID = (int)$request->input('branch_id');
        $varUserRoleID = (int)$request->input('role_id');

        if ($varUserRoleID != 0) {

            $varAPIWebToken = $request->input('varAPIWebToken');
            $personName = $request->input('personName');
            $user_RefID = $request->input('user_RefID');
            $workerCareerInternal_RefID = $request->input('workerCareerInternal_RefID');
            $organizationalDepartmentName = $request->input('organizationalDepartmentName');

            return $this->SetLoginBranchAndUserRoleFunction(
                $varAPIWebToken, $varBranchID, $varUserRoleID, 
                $personName, $workerCareerInternal_RefID, 
                $user_RefID, $organizationalDepartmentName
            );
        } else {
            $dataAwal = \App\Helpers\ZhtHelper\System\FrontEnd\Helper_APICall::setCallAPIAuthentication(
                \App\Helpers\ZhtHelper\System\Helper_Environment::getUserSessionID_System(),
                $username,
                $password
            );        

            if ($dataAwal['metadata']['HTTPStatusCode'] != 200) {
                return response()->json(['status_code' => 0]);
            }

            $varAPIWebToken = $dataAwal['data']['APIWebToken'];
            $varDataBranch = $this->GetInstitutionBranchFunction($dataAwal['data']['userIdentity']['user_RefID']);

            if (count($varDataBranch) == 1) {
                $varDataRole = $this->GetRoleFunction($varDataBranch[0]['Sys_ID'], $dataAwal['data']['userIdentity']['user_RefID']);

                foreach ($varDataRole as $role) {
                    Session::push('SessionRoleLogin', $role['Sys_ID']);
                }

                Session::put('SessionLogin', $varAPIWebToken);
                Session::put('SessionOrganizationalDepartmentName', $dataAwal['data']['userIdentity']['organizationalDepartmentName']);
                Session::put('SessionLoginName', $dataAwal['data']['userIdentity']['personName']);
                Session::put('SessionWorkerCareerInternal_RefID', $dataAwal['data']['userIdentity']['workerCareerInternal_RefID']);
                Session::put('SessionUser_RefID', $dataAwal['data']['userIdentity']['user_RefID']);

                return response()->json(['status_code' => 1]);
            } else {
                return response()->json([
                    'status_code' => 2,
                    'data' => $varDataBranch,
                    'user_RefID' => $dataAwal['data']['userIdentity']['user_RefID'],
                    'varAPIWebToken' => $varAPIWebToken,
                    'personName' => $dataAwal['data']['userIdentity']['personName'],
                    'workerCareerInternal_RefID' => $dataAwal['data']['userIdentity']['workerCareerInternal_RefID'],
                    'organizationalDepartmentName' => $dataAwal['data']['userIdentity']['organizationalDepartmentName']
                ]);
            }
        }
    } catch (\Throwable $th) {
        return response()->json(['status_code' => 0]);
    }
}
