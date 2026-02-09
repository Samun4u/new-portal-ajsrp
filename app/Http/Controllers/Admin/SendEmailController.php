<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SendEmailRequest;
use App\Http\Services\BulkEmailSentServices;
use App\Http\Services\ClientOrderServices;
use App\Models\BulkEmailTemplate;
use App\Models\BulkEmailTemplateHistory;
use App\Models\ClientOrder;
use App\Services\BrevoService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SendEmailController extends Controller
{
    use ResponseTrait;
    
    public $bulkEmailSentService;
    public $brevoService;

    public function __construct()
    {
        $this->bulkEmailSentService = new BulkEmailSentServices();
        $this->brevoService = new BrevoService();
    }

    public function index(Request $request)
    {
        $data['pageTitle'] = __('Send Email');
        $data['activeSendEmail'] = 'active';
        $data['templates'] = BulkEmailTemplate::get();
        return view('admin.send-email.index', $data);
    }

    public function template_list(Request $request)
    {
        if ($request->ajax()) {
            return $this->bulkEmailSentService->getTemplateListData($request);
        }
        return $this->sendResponse([], __('Invalid request'));
    }

    public function template_details(Request $request)
    {
        return $this->bulkEmailSentService->details($request);
    }

    public function template_store(Request $request)
    {
         return $this->bulkEmailSentService->store($request);
    }

    public function template_edit(Request $request)
    {
        // dd("template edit");
        return $this->bulkEmailSentService->edit($request);
    }

    public function get_email_template(Request $request)
    {
        // dd("template edit");
        return $this->bulkEmailSentService->get_template($request);
    }

    public function template_delete($id)
    {
         try {
            DB::beginTransaction();
            $bulkEmailData = BulkEmailTemplate::where('id', decrypt($id))->first();
            $bulkEmailData->delete();
            DB::commit();
            return $this->success([], getMessage(DELETED_SUCCESSFULLY));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], getErrorMessage($e, $e->getMessage()));
        }
    }

    public function send(SendEmailRequest $request)
    {
        try {

            DB::beginTransaction();

            $result = $this->brevoService->sendEmail(
                $request->to,
                $request->bcc,
                $request->subject,
                $request->body
            );

            // Log the email history
            BulkEmailTemplateHistory::create([
                'to' => $request->to,
                'bcc' => $request->bcc,
                'subject' => $request->subject,
                'body' => $request->body,
                'status' => $result['success'] ? 'sent' : 'failed',
                'api_response' => $result['success'] ? 
                    json_encode(['message_id' => $result['message_id']]) : 
                    $result['error'],
                'admin_id' => auth()->id()
            ]);
            DB::commit();
            if ($result['success']) {
                return $this->success([], __('Email sent successfully'));
            } else {
                return $this->error([], __('Failed to send email: ') . $result['error']);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], getErrorMessage($e, $e->getMessage()));
        }
    }

    public function history(Request $request)
    {
        if ($request->ajax()) {
            return $this->bulkEmailSentService->getSentHistoryData($request);
        }
        return $this->sendResponse([], __('Invalid request'));
    }
        
        
}
