public function loginStore(Request $request)
{
    try {
        $username = $request->input('username');
        $password = $request->input('password');
        $branchID = (int)$request->input('branch_id');
        $roleID = (int)$request->input('role_id');

        if ($roleID != 0) {
            return $this->handleRoleLogin($request, $branchID, $roleID);
        } else {
            return $this->handleUserLogin($username, $password);
        }
    } catch (\Throwable $th) {
        return response()->json(['status_code' => 0]);
    }
}

private function handleRoleLogin($request, $branchID, $roleID)
{
    $varAPIWebToken = $request->input('varAPIWebToken');
    $personName = $request->input('personName');
    $user_RefID = $request->input('user_RefID');
    $workerCareerInternal_RefID = $request->input('workerCareerInternal_RefID');
    $organizationalDepartmentName = $request->input('organizationalDepartmentName');

    return $this->SetLoginBranchAndUserRoleFunction(
        $varAPIWebToken,
        $branchID,
        $roleID,
        $personName,
        $workerCareerInternal_RefID,
        $user_RefID,
        $organizationalDepartmentName
    );
}

private function handleUserLogin($username, $password)
{
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
        return $this->singleBranchLogin($varAPIWebToken, $varDataBranch[0]['Sys_ID'], $dataAwal['data']['userIdentity']);
    } else {
        return $this->multiBranchLogin($varDataBranch, $dataAwal['data']['userIdentity'], $varAPIWebToken);
    }
}

private function singleBranchLogin($varAPIWebToken, $branchID, $userIdentity)
{
    $varDataRole = $this->GetRoleFunction($branchID, $userIdentity['user_RefID']);

    foreach ($varDataRole as $role) {
        Session::push('SessionRoleLogin', $role['Sys_ID']);
    }

    Session::put('SessionLogin', $varAPIWebToken);
    Session::put('SessionOrganizationalDepartmentName', $userIdentity['organizationalDepartmentName']);
    Session::put('SessionLoginName', $userIdentity['personName']);
    Session::put('SessionWorkerCareerInternal_RefID', $userIdentity['workerCareerInternal_RefID']);
    Session::put('SessionUser_RefID', $userIdentity['user_RefID']);

    return response()->json(['status_code' => 1]);
}

private function multiBranchLogin($varDataBranch, $userIdentity, $varAPIWebToken)
{
    return response()->json([
        'status_code' => 2,
        'data' => $varDataBranch,
        'user_RefID' => $userIdentity['user_RefID'],
        'varAPIWebToken' => $varAPIWebToken,
        'personName' => $userIdentity['personName'],
        'workerCareerInternal_RefID' => $userIdentity['workerCareerInternal_RefID'],
        'organizationalDepartmentName' => $userIdentity['organizationalDepartmentName']
    ]);
}
