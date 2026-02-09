<?php

namespace App\Http\Controllers\Superadmin;

use Exception;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\FaqRequest;
use App\Http\Services\FaqService;
use App\Models\Faq;

class FaqController extends Controller
{
    use ResponseTrait;
    protected $faq;
    public function __construct()
    {
        $this->faq = new FaqService();
    }

    public function index(Request $request){
        $data['title'] = __('Faq Section');
        $data['activeFrontendList'] = 'active';
        $data['faqActiveClass'] = 'active';
        if($request->ajax()){
            return $this->faq->list();
        }
        return view('sadmin.setting.faq.index', $data);
    }

    public function store(FaqRequest $request){
        return $this->faq->faqStore($request);
    }

    public function delete($id){
        return $this->faq->faqDelete($id);
    }

    public function edit($id)
    {
        $data['pageTitle'] = __('Faq Section');

        try {
            $data['faq'] = Faq::find($id);
            if (is_null($data['faq'])) {
                return $this->error([], getMessage(SOMETHING_WENT_WRONG));
            }
        } catch (Exception $exception) {
            return $this->error([], getMessage(SOMETHING_WENT_WRONG));
        }
        return view('sadmin.setting.faq.edit-form', $data);
    }


}
