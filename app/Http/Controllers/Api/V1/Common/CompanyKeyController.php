<?php

namespace App\Http\Controllers\Api\V1\Common;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Common\AdminUsersCompanyKey;
use App\Http\Controllers\Api\V1\BaseController;

/**
 * @group company Key validation
 * APIs for company key validation
 */
class CompanyKeyController extends BaseController
{
    protected $faq;

    public function __construct(AdminUsersCompanyKey $company_key)
    {
        $this->company_key = $company_key;
    }

    /**
    * Company Key Validation
    * @bodyParam company_key string required company key of the user
    * @hideFromAPIDocumentation
    ** @response {
     * "success":true,
     * "message":"success",
     * }
    */
    public function validateCompanyKey(Request $request)
    {
        // $request->validate([
        // 'company_key' => 'required|exists:admin_users_company_keys,id',
        // ]);
        // Validate Company key
        $today = Carbon::today()->toDateString();

        $company_key = $this->company_key->where('company_key', $request->company_key)->where('active', 1)->first();
        if (!$company_key) {
            $this->throwCustomException('provided company key is invalid');
        }
        $ExpiryDate = Carbon::parse($company_key->expiry_date)->toDateString();
        if ($ExpiryDate < $today) {
            $this->throwCustomException('provided company key is expired');
        }

        return $this->respondSuccess();
    }
}
