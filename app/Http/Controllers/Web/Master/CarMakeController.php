<?php

namespace App\Http\Controllers\Web\Master;

use App\Base\Filters\Master\CommonMasterFilter;
use App\Base\Libraries\QueryFilter\QueryFilterContract;
use App\Http\Controllers\Web\BaseController;
use App\Models\Master\CarMake;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CarMakeController extends BaseController
{
    protected $make;

    /**
     * CarMakeController constructor.
     *
     * @param \App\Models\Admin\CarMake $car_make
     */
    public function __construct(CarMake $make)
    {
        $this->make = $make;
    }

    public function index()
    {
        $page = trans('pages_names.view_car_make');

        $main_menu = 'master';
        $sub_menu = 'car_make';

        return view('admin.master.carmake.index', compact('page', 'main_menu', 'sub_menu'));
    }

    public function fetch(QueryFilterContract $queryFilter)
    {
        $query = $this->make->query();//->active()
        $results = $queryFilter->builder($query)->customFilter(new CommonMasterFilter)->paginate();

        return view('admin.master.carmake._make', compact('results'));
    }

    public function create()
    {
        $page = trans('pages_names.add_car_make');

        $main_menu = 'master';
        $sub_menu = 'car_make';

        return view('admin.master.carmake.create', compact('page', 'main_menu', 'sub_menu'));
    }

    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required|unique:car_makes,name'
        ])->validate();

        $created_params = $request->only(['name']);
        $created_params['active'] = 1;

        // $created_params['company_key'] = auth()->user()->company_key;

        $this->make->create($created_params);

        $message = trans('succes_messages.car_make_added_succesfully');

        return redirect('carmake')->with('success', $message);
    }

    public function getById(CarMake $make)
    {
        $page = trans('pages_names.edit_car_make');

        $main_menu = 'master';
        $sub_menu = 'car_make';
        $item = $make;

        return view('admin.master.carmake.update', compact('item', 'page', 'main_menu', 'sub_menu'));
    }

    public function update(Request $request, CarMake $make)
    {
        Validator::make($request->all(), [
            'name' => 'required|unique:car_makes,name,'.$make->id
        ])->validate();

        $updated_params = $request->all();
        $make->update($updated_params);
        $message = trans('succes_messages.car_make_updated_succesfully');
        return redirect('carmake')->with('success', $message);
    }

    public function toggleStatus(CarMake $make)
    {
        $status = $make->isActive() ? false: true;
        $make->update(['active' => $status]);

        $message = trans('succes_messages.car_make_status_changed_succesfully');
        return redirect('carmake')->with('success', $message);
    }

    public function delete(CarMake $make)
    {
        $make->delete();

        $message = trans('succes_messages.car_make_deleted_succesfully');
        return redirect('carmake')->with('success', $message);
    }
}
