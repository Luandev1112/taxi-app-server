<?php

namespace App\Http\Controllers\Web\Master;

use App\Base\Filters\Master\CommonMasterFilter;
use App\Base\Libraries\QueryFilter\QueryFilterContract;
use App\Http\Controllers\Web\BaseController;
use App\Models\Master\CarMake;
use App\Models\Master\CarModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CarModelController extends BaseController
{
    protected $model;

    /**
     * CarModelController constructor.
     *
     * @param \App\Models\Admin\CarModel $car_model
     */
    public function __construct(CarModel $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        $page = trans('pages_names.view_car_model');

        $main_menu = 'master';
        $sub_menu = 'car_model';

        return view('admin.master.carmodel.index', compact('page', 'main_menu', 'sub_menu'));
    }

    public function fetch(QueryFilterContract $queryFilter)
    {
        $query = $this->model->query();//->active()
        $results = $queryFilter->builder($query)->customFilter(new CommonMasterFilter)->paginate();

        return view('admin.master.carmodel._model', compact('results'));
    }

    public function create()
    {
        $page = trans('pages_names.add_car_model');

        $main_menu = 'master';
        $sub_menu = 'car_model';
        $makes = CarMake::active()->get();

        return view('admin.master.carmodel.create', compact('page', 'main_menu', 'sub_menu', 'makes'));
    }

    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required|unique:car_models,name'
        ])->validate();

        $created_params = $request->only(['name','make_id']);
        $created_params['active'] = 1;

        // $created_params['company_key'] = auth()->user()->company_key;

        $this->model->create($created_params);

        $message = trans('succes_messages.car_model_added_succesfully');

        return redirect('carmodel')->with('success', $message);
    }

    public function getById(CarModel $model)
    {
        $page = trans('pages_names.edit_car_model');

        $main_menu = 'master';
        $sub_menu = 'car_model';
        $item = $model;
        $makes = CarMake::active()->get();

        return view('admin.master.carmodel.update', compact('item', 'page', 'main_menu', 'sub_menu', 'makes'));
    }

    public function update(Request $request, CarModel $model)
    {
        Validator::make($request->all(), [
            'name' => 'required|unique:car_models,name,'.$model->id
        ])->validate();

        $updated_params = $request->all();
        $model->update($updated_params);
        $message = trans('succes_messages.car_model_updated_succesfully');
        return redirect('carmodel')->with('success', $message);
    }

    public function toggleStatus(CarModel $model)
    {
        $status = $model->isActive() ? false: true;
        $model->update(['active' => $status]);

        $message = trans('succes_messages.car_model_status_changed_succesfully');
        return redirect('carmodel')->with('success', $message);
    }

    public function delete(CarModel $model)
    {
        $model->delete();

        $message = trans('succes_messages.car_model_deleted_succesfully');
        return redirect('carmodel')->with('success', $message);
    }
}
