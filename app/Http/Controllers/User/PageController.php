<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Services\PagesServices;
use App\Models\Page;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class PageController extends Controller
{
    use ResponseTrait;
    private $pagesServices;
    
    public function __construct()
    {
        $this->pagesServices = new PagesServices();
    }

    public function list(Request $request){
        if ($request->ajax()) {
            return $this->pagesServices->getPageListUserData($request);
        } else {
            $data['pageTitle'] = __('Page list');
            $data['activeInformation'] = 'active';

            $userRole = auth()->user()->role;

            $data['pageList'] = Page::orderBy('id', 'desc')->where('role_id', $userRole)->get();
            return view('user.page.list', $data);
        }
        return view('user.page.list', $data);
    }

    public function view($slug)
    {
        
        $data['pageTitle'] = __('View Page');
        $data['activeInformation'] = 'active';
        $data['page'] = Page::where('slug', $slug)->firstOrFail();
        return view('user.page.details', $data);
    }

    // public function index($slug)
    // {
    //     //if auth not check then redirect to login
    //     if (!auth()->check()) {
    //         return redirect()->route('login');
    //     }

    //     if(auth()->user()->role == USER_ROLE_ADMIN){
    //         return redirect()->route('admin.pages.view', $slug);
    //     }

    //     $data['page'] = Page::where('slug', $slug)->firstOrFail();
      
    //     $data['pageTitle'] = $data['page']->title;
    //     $data[$data['page']->slug] = 'active';
    //     return view('user.page.index', $data);
    // }
}
