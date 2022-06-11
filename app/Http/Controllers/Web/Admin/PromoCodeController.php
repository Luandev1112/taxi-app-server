<?php

namespace App\Http\Controllers\Web\Admin;

use App\Base\Filters\Master\CommonMasterFilter;
use App\Base\Libraries\QueryFilter\QueryFilterContract;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\BaseController;
use App\Http\Requests\Admin\Promo\CreatePromoRequest;
use App\Http\Requests\Admin\Promo\UpdatePromoRequest;
use App\Models\Admin\Promo;
use App\Models\Admin\ServiceLocation;
use Illuminate\Http\Request;

class PromoCodeController extends BaseController
{
    protected $promo;

    /**
     * PromoController constructor.
     *
     * @param \App\Models\Admin\Promo $promo
     */
    public function __construct(Promo $promo)
    {
        $this->promo = $promo;
    }

    public function index()
    {
        $page = trans('pages_names.view_promo');

        $main_menu = 'manage-promo';
        $sub_menu = '';

        return view('admin.promo.index', compact('page', 'main_menu', 'sub_menu'));
    }

    public function fetch(QueryFilterContract $queryFilter)
    {
        $query = $this->promo->query();

        $results = $queryFilter->builder($query)->customFilter(new CommonMasterFilter)->paginate();

        return view('admin.promo._promo', compact('results'));
    }

    public function create()
    {
        $page = trans('pages_names.add_promo');
        $cities = ServiceLocation::companyKey()->whereActive(true)->get();
        $main_menu = 'manage-promo';
        $sub_menu = '';

        return view('admin.promo.create', compact('cities', 'page', 'main_menu', 'sub_menu'));
    }

    public function store(CreatePromoRequest $request)
    {
        $created_params = $request->only(['code','service_location_id','minimum_trip_amount','maximum_discount_amount','discount_percent','total_uses','uses_per_user']);

        $created_params['from'] = now()->parse($request->from)->startOfDay()->toDateTimeString();
        $created_params['to'] = now()->parse($request->to)->endOfDay()->toDateTimeString();

        $this->promo->create($created_params);

        $message = trans('succes_messages.promo_added_succesfully');

        return redirect('promo')->with('success', $message);
    }

    public function getById(Promo $promo)
    {
        $page = trans('pages_names.edit_promo');
        $cities = ServiceLocation::whereActive(true)->get();
        $main_menu = 'manage-promo';
        $sub_menu = '';
        $item = $promo;

        return view('admin.promo.update', compact('cities', 'item', 'page', 'main_menu', 'sub_menu'));
    }

    public function update(UpdatePromoRequest $request, Promo $promo)
    {
        $updated_params = $request->all();
        $promo->update($updated_params);

        $message = trans('succes_messages.promo_updated_succesfully');

        return redirect('promo')->with('success', $message);
    }

    public function toggleStatus(Promo $promo)
    {
        $status = $promo->isActive() ? false: true;
        $promo->update(['active' => $status]);

        $message = trans('succes_messages.promo_status_changed_succesfully');
        return redirect('promo')->with('success', $message);
    }

    public function delete(Promo $promo)
    {
        $promo->delete();

        $message = trans('succes_messages.promo_deleted_succesfully');
        return redirect('promo')->with('success', $message);
    }
}
