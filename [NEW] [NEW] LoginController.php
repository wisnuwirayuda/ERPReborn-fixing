<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;

class LoginController extends Controller
{
    public function index(Request $request)
    {
        return Session::get('SessionLogin') 
            ? view('Dashboard.index')
            : view('Authentication.login');
    }

    private function getRedisData($sessionId, $key)
    {
        return json_decode(
            \App\Helpers\ZhtHelper\Cache\Helper_Redis::getValue(
                $sessionId,
                $key
            ),
            true
        );
    }

    public function loginStore(Request $request)
    {
        try {
            $username = $request->input('username');
            $password = $request->input('password');
            $branchId = (int)$request->input('branch_id');
            $userRoleId = (int)$request->input('role_id');

            if ($userRoleId !== 0) {
                return $this->setLoginBranchAndUserRole(
                    $request->input('varAPIWebToken'),
                    $branchId,
                    $userRoleId,
                    $request->only(['personName', 'user_RefID', 'workerCareerInternal_RefID', 'organizationalDepartmentName'])
                );
            }

            $authResult = \App\Helpers\ZhtHelper\System\FrontEnd\Helper_APICall::setCallAPIAuthentication(
                \App\Helpers\ZhtHelper\System\Helper_Environment::getUserSessionID_System(),
                $username,
                $password
            );

            if ($authResult['metadata']['HTTPStatusCode'] !== 200) {
                return response()->json(['status_code' => 0]);
            }

            $userData = $authResult['data'];
            $apiWebToken = $userData['APIWebToken'];
            $userRefId = $userData['userIdentity']['user_RefID'];
            
            $sessionId = \App\Helpers\ZhtHelper\System\Helper_Environment::getUserSessionID_System();
            $branches = $this->getRedisData($sessionId, "Branch" . $userRefId);

            if (count($branches) === 1) {
                $roles = $this->getRedisData($sessionId, "Role" . $userRefId);
                
                $this->setLoginSession($apiWebToken, $userData['userIdentity']);
                foreach ($roles as $role) {
                    Session::push('SessionRoleLogin', $role['Sys_ID']);
                }
                
                return response()->json(['status_code' => 1]);
            }

            return response()->json([
                'status_code' => 2,
                'data' => $branches,
                'user_RefID' => $userRefId,
                'varAPIWebToken' => $apiWebToken,
                'personName' => $userData['userIdentity']['personName'],
                'workerCareerInternal_RefID' => $userData['userIdentity']['workerCareerInternal_RefID'],
                'organizationalDepartmentName' => $userData['userIdentity']['organizationalDepartmentName']
            ]);

        } catch (\Throwable $th) {
            Log::error("Login error: " . $th->getMessage());
            return response()->json(['status_code' => 0]);
        }
    }

    private function setLoginSession($apiWebToken, $userIdentity)
    {
        Session::put([
            'SessionLogin' => $apiWebToken,
            'SessionOrganizationalDepartmentName' => $userIdentity['organizationalDepartmentName'],
            'SessionLoginName' => $userIdentity['personName'],
            'SessionWorkerCareerInternal_RefID' => $userIdentity['workerCareerInternal_RefID'],
            'SessionUser_RefID' => $userIdentity['user_RefID']
        ]);
    }

    public function getRoleLogin(Request $request)
    {
        try {
            $branchId = (int)$request->input('branch_id');
            $userRefId = (int)$request->input('user_RefID');
            
            $roles = $this->getRedisData(
                \App\Helpers\ZhtHelper\System\Helper_Environment::getUserSessionID_System(),
                "Role" . $userRefId
            );

            return response()->json([
                'length' => count($roles),
                'data' => $roles,
                'status' => count($roles) > 0 ? 200 : 401
            ]);

        } catch (\Throwable $th) {
            Log::error("Error getting roles: " . $th->getMessage());
            return response()->json(['status' => 401]);
        }
    }

    private function setLoginBranchAndUserRole($apiWebToken, $branchId, $userRoleId, $userData)
    {
        try {
            $result = \App\Helpers\ZhtHelper\System\FrontEnd\Helper_APICall::setCallAPIGateway(
                \App\Helpers\ZhtHelper\System\Helper_Environment::getUserSessionID_System(),
                $apiWebToken,
                'authentication.general.setLoginBranchAndUserRole',
                'latest',
                [
                    'branchID' => $branchId,
                    'userRoleID' => $userRoleId
                ]
            );

            if ($result['metadata']['HTTPStatusCode'] === 200) {
                $this->setLoginSession($apiWebToken, [
                    'organizationalDepartmentName' => $userData['organizationalDepartmentName'],
                    'personName' => $userData['personName'],
                    'workerCareerInternal_RefID' => $userData['workerCareerInternal_RefID'],
                    'user_RefID' => $userData['user_RefID']
                ]);
                Session::push('SessionRoleLogin', $userRoleId);
                return response()->json(['status_code' => 1]);
            }
            
            return response()->json(['status_code' => 0]);

        } catch (\Throwable $th) {
            Log::error("Error setting branch and role: " . $th->getMessage());
            return response()->json(['status_code' => 0]);
        }
    }

    public function logout(Request $request)
    {
        try {
            $apiWebToken = Session::get('SessionLogin');
            Redis::del("ERPReborn::APIWebToken::" . $apiWebToken);

            $status = $request->input('message') === "Session_Expired" ? "error" : "success";
            $message = $status === "error" ? 'Your session expired' : 'Thank you for your visit';

            Cache::flush();
            Session::flush();

            return redirect('/')->with([$status => $message]);
        } catch (\Throwable $th) {
            Log::error("Logout error: " . $th->getMessage());
            return redirect()->back()->with('NotFound', 'Process Error');
        }
    }

    public function SessionCheckingLogout()
    {
        try {

            $varAPIWebToken = Session::has("SessionLogin");

            $compact = [
                'varAPIWebToken' => $varAPIWebToken,
            ];

            return response()->json($compact);
        } catch (\Throwable $th) {

            Log::error("Error at " . $th->getMessage());
            return redirect()->back()->with('NotFound', 'Process Error');
        }
    }

    public function FlushCache()
    {

        Cache::flush();
        Session::flush();
        Redis::flushDB();
        return redirect()->back();
    }
}
