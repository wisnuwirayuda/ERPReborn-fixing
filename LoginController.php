public function loginStore(Request $request)
{
    try {
        $username = $request->input('username');
        $password = $request->input('password');
        $branchID = (int)$request->input('branch_id');
        $roleID = (int)$request->input('role_id');

        if ($roleID != 0) {
            $apiWebToken = $request->input('varAPIWebToken');
            $personName = $request->input('personName');
            $userRefID = $request->input('user_RefID');
            $workerCareerInternalRefID = $request->input('workerCareerInternal_RefID');
            $organizationalDepartmentName = $request->input('organizationalDepartmentName');

            return $this->setLoginBranchAndUserRole(
                $apiWebToken, $branchID, $roleID, 
                $personName, $workerCareerInternalRefID, 
                $userRefID, $organizationalDepartmentName
            );
        }

        $apiResponse = \App\Helpers\ZhtHelper\System\FrontEnd\Helper_APICall::setCallAPIAuthentication(
            \App\Helpers\ZhtHelper\System\Helper_Environment::getUserSessionID_System(),
            $username,
            $password
        );

        if ($apiResponse['metadata']['HTTPStatusCode'] != 200) {
            return response()->json(['status_code' => 0]);
        }

        $apiWebToken = $apiResponse['data']['APIWebToken'];
        $userIdentity = $apiResponse['data']['userIdentity'];
        $userRefID = $userIdentity['user_RefID'];

        $branchData = $this->getInstitutionBranch($userRefID);

        if (count($branchData) == 1) {
            $roleData = $this->getRole($branchData[0]['Sys_ID'], $userRefID);
            $roleIDs = array_column($roleData, 'Sys_ID');

            session()->put([
                'SessionRoleLogin' => $roleIDs,
                'SessionLogin' => $apiWebToken,
                'SessionOrganizationalDepartmentName' => $userIdentity['organizationalDepartmentName'],
                'SessionLoginName' => $userIdentity['personName'],
                'SessionWorkerCareerInternal_RefID' => $userIdentity['workerCareerInternal_RefID'],
                'SessionUser_RefID' => $userIdentity['user_RefID']
            ]);

            return response()->json(['status_code' => 1]);
        }

        return response()->json([
            'status_code' => 2,
            'data' => $branchData,
            'user_RefID' => $userRefID,
            'varAPIWebToken' => $apiWebToken,
            'personName' => $userIdentity['personName'],
            'workerCareerInternal_RefID' => $userIdentity['workerCareerInternal_RefID'],
            'organizationalDepartmentName' => $userIdentity['organizationalDepartmentName']
        ]);
    } catch (\Throwable $th) {
        return response()->json(['status_code' => 0]);
    }
}
