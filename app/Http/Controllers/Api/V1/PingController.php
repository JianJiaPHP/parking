<?php


namespace App\Http\Controllers\Api\V1;


use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Models\AdminMenu;
use App\Models\AdminRoleAdministrator;
use App\Models\Coupon;
use App\Services\AdministratorService;
use App\Services\ConfigService;
use App\Services\UserCouponService;
use App\Utils\OfficialAccount;
use App\Utils\Result;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class PingController extends Controller
{


    public function index()
    {

        AdminMenu::delAdminByAdministratorId(1);
        dd(1);

    }

    public function callback()
    {
        $user = OfficialAccount::getUser();
        dd($user);
    }

}
