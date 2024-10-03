<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    private const CACHE_TTL = 3600; // 1 hour cache time

    public function index(Request $request)
    {
        return Session::get('SessionLogin') 
            ? view('Dashboard.index')
            : view('Authentication.login');
    }

    private function getUserData($username, $password)
    {
        $cacheKey = 'user_auth_' . md5($username . $password);
        
        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($username, $password) {
            return \App\Helpers\ZhtHelper\System\FrontEnd\Helper_APICall::setCallAPIAuthentication(
                \App\Helpers\ZhtHelper\System\Helper_Environment::getUserSessionID_System(),
                $username,
                $password
            );
        });
    }

    private function getBranchAndRoleData($user_RefID)
    {
        $cacheKey = 'user_branch_role_' . $user_RefID;
        
        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($user_RefID) {
            $sessionID = \App\Helpers\ZhtHelper\System\Helper_Environment::getUserSessionID_System();
            
            $branchData = json_decode(
                \App\Helpers\ZhtHelper\Cache\Helper_Redis::getValue($sessionID, "Branch" . $user_RefID),
                true
            ) ?? [];

            $roleData = [];
            if (count($branchData) == 1) {
                $roleData = json_decode(
                    \App\Helpers\ZhtHelper\Cache\Helper_Redis::getValue($sessionID, "Role" . $user_RefID),
                    true
                ) ?? [];
            }

            return [
                'branches' => $branchData,
                'roles' => $roleData
            ];
        });
    }

    public function loginStore(Request $request)
    {
        try {
            $username = $request->input('username');
            $password = $request->input('password');
            $branchId = (int)$request->input('branch_id');
            $userRoleId = (int)$request->input('role_id');

            if ($userRoleId !== 0) {
                return $this->setLoginBranchAndUserRole($request);
            }

            $userData = $this->getUserData($username, $password);

            if ($userData['metadata']['HTTPStatusCode'] !== 200) {
                return response()->json(['status_code' => 0]);
            }

            $userIdentity = $userData['data']['userIdentity'];
            $apiWebToken = $userData['data']['APIWebToken'];

            $branchAndRoleData = $this->getBranchAndRoleData($userIdentity['user_RefID']);
            $branches = $branchAndRoleData['branches'];

            if (count($branches) === 1) {
                $this->setUserSession($apiWebToken, $userIdentity, $branchAndRoleData['roles']);
                return response()->json(['status_code' => 1]);
            }

            return response()->json([
                'status_code' => 2,
                'data' => $branches,
                'user_RefID' => $userIdentity['user_RefID'],
                'varAPIWebToken' => $apiWebToken,
                'personName' => $userIdentity['personName'],
                'workerCareerInternal_RefID' => $userIdentity['workerCareerInternal_RefID'],
                'organizationalDepartmentName' => $userIdentity['organizationalDepartmentName']
            ]);

        } catch (\Throwable $th) {
            Log::error("Login error: " . $th->getMessage());
            return response()->json(['status_code' => 0]);
        }
    }

    private function setUserSession($apiWebToken, $userIdentity, $roles)
    {
        Session::put('SessionLogin', $apiWebToken);
        Session::put('SessionOrganizationalDepartmentName', $userIdentity['organizationalDepartmentName']);
        Session::put('SessionLoginName', $userIdentity['personName']);
        Session::put('SessionWorkerCareerInternal_RefID', $userIdentity['workerCareerInternal_RefID']);
        Session::put('SessionUser_RefID', $userIdentity['user_RefID']);
        
        foreach ($roles as $role) {
            Session::push('SessionRoleLogin', $role['Sys_ID']);
        }
    }

    private function setLoginBranchAndUserRole(Request $request)
    {
        try {
            $checking = \App\Helpers\ZhtHelper\System\FrontEnd\Helper_APICall::setCallAPIGateway(
                \App\Helpers\ZhtHelper\System\Helper_Environment::getUserSessionID_System(),
                $request->input('varAPIWebToken'),
                'authentication.general.setLoginBranchAndUserRole',
                'latest',
                [
                    'branchID' => (int)$request->input('branch_id'),
                    'userRoleID' => (int)$request->input('role_id')
                ]
            );

            if ($checking['metadata']['HTTPStatusCode'] !== 200) {
                return response()->json(['status_code' => 0]);
            }

            Session::put('SessionLogin', $request->input('varAPIWebToken'));
            Session::put('SessionOrganizationalDepartmentName', $request->input('organizationalDepartmentName'));
            Session::put('SessionLoginName', $request->input('personName'));
            Session::put('SessionWorkerCareerInternal_RefID', $request->input('workerCareerInternal_RefID'));
            Session::put('SessionUser_RefID', $request->input('user_RefID'));
            Session::push('SessionRoleLogin', (int)$request->input('role_id'));

            return response()->json(['status_code' => 1]);

        } catch (\Throwable $th) {
            Log::error("SetLoginBranchAndUserRole error: " . $th->getMessage());
            return response()->json(['status_code' => 0]);
        }
    }

    public function getRoleLogin(Request $request)
    {
        try {
            $branchId = (int)$request->input('branch_id');
            $userId = (int)$request->input('user_RefID');
            
            $cacheKey = "user_roles_{$userId}_{$branchId}";
            
            $roles = Cache::remember($cacheKey, self::CACHE_TTL, function () use ($branchId, $userId) {
                return $this->GetRoleFunction($branchId, $userId);
            });

            return response()->json([
                'length' => count($roles),
                'data' => $roles,
                'status' => count($roles) > 0 ? 200 : 401
            ]);

        } catch (\Throwable $th) {
            Log::error("GetRoleLogin error: " . $th->getMessage());
            return response()->json(['status' => 401]);
        }
    }

    public function logout(Request $request)
    {
        try {
            $apiWebToken = Session::get('SessionLogin');
            Redis::del("ERPReborn::APIWebToken::" . $apiWebToken);
            
            $status = $request->input('message') == "Session_Expired" ? "error" : "success";
            $message = $status == "error" ? 'Your session expired' : 'Thank you for your visit';

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
        return response()->json([
            'varAPIWebToken' => Session::has("SessionLogin")
        ]);
    }

    public function FlushCache()
    {
        Cache::flush();
        Session::flush();
        Redis::flushDB();
        return redirect()->back();
    }
}
