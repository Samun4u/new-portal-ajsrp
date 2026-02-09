<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DesignationRequest;
use App\Http\Services\DesignationService;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    private $designationService;

    public function __construct()
    {
        $this->designationService = new DesignationService;
    }

    public function index(Request $request)
    {
        $data['pageTitle'] = __('Designation');
        $data['activeDesignation'] = 'active';
        $data['activeSetting'] = 'active';
        if ($request->ajax()) {
            return $this->designationService->getAllData();
        }
        return view('admin.setting.designation.index', $data);
    }

    public function add()
    {
        $data['pageTitleParent'] = __('Designation');
        $data['pageTitle'] = __('Add Designation');
        $data['activeDesignation'] = 'active';
        $data['activeSetting'] = 'active';
        return view('admin.setting.designation.add', $data);
    }

    public function edit($id)
    {
        $data['pageTitleParent'] = __('Designation');
        $data['pageTitle'] = __('Edit Designation');
        $data['activeDesignation'] = 'active';
        $data['activeSetting'] = 'active';
        $data['designation'] = $this->designationService->getById($id);
        return view('admin.setting.designation.edit', $data);
    }

    public function store(DesignationRequest $request)
    {
        return $this->designationService->store($request);;
    }

    public function delete($id)
    {
        return $this->designationService->deleteById($id);
    }
}
