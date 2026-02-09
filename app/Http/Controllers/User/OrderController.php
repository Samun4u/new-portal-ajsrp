<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ClientOrderConversationRequest;
use App\Http\Services\ClientOrderServices;
use App\Models\ClientOrder;
use App\Models\ClientOrderAssignee;
use App\Models\ClientOrderConversation;
use App\Models\ClientOrderConversationSeen;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Service;
use App\Models\User;
use App\Models\ClientOrderNote;
use App\Models\ClientOrderSubmission;
use Illuminate\Support\Facades\Auth;
use App\Models\FileManager;
use App\Models\Reviews;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    use ResponseTrait;

    public $clientOrderService;

    public function __construct()
    {
        $this->clientOrderService = new ClientOrderServices();
    }

    public function list(Request $request)
    {
        $data['pageTitleParent'] = __('Submission');
        $data['pageTitle'] = __('Submission list');
        $data['activeOrder'] = 'active';
        if ($request->ajax()) {
            return $this->clientOrderService->getClientOrderListData($request);
        }

        if(auth()->user()->role == USER_ROLE_REVIEWER){
            $orderIds = ClientOrderAssignee::where('assigned_to', auth()->id())->pluck('order_id')->toArray();
            $data['orderCount'] = ClientOrder::whereIn('id', $orderIds)->count();
        }else{

            $data['orderCount'] = ClientOrder::where(['client_id' => auth()->id()])->count();


        }

        // $data['orderCount'] = ClientOrder::where(['client_id' => auth()->id()])->count();
        return view('user.orders.list', $data);
    }

    //reviewer assigned orders
    public function reviewer_assigned_order_list(Request $request)
    {
        // $data['pageTitleParent'] = __('Submission');
        $data['pageTitle'] = __('Assigned Reviews');
        $data['activeOrder'] = 'active';
        if ($request->ajax()) {
            return $this->clientOrderService->getReviewerAssignedOrderListData($request);
        }

        if(auth()->user()->role == USER_ROLE_REVIEWER){
            $orderIds = ClientOrderAssignee::where('assigned_to', auth()->id())->pluck('order_id')->toArray();
            $data['orderCount'] = ClientOrder::whereIn('id', $orderIds)->count();
        }else{

            $data['orderCount'] = ClientOrder::where(['client_id' => auth()->id()])->count();


        }

        // $data['orderCount'] = ClientOrder::where(['client_id' => auth()->id()])->count();
        return view('user.orders.reviewer.assigned-order-list', $data);
    }

    public function reviewer_submission_list(Request $request)
    {
        // $data['pageTitleParent'] = __('Submission');
        $data['pageTitle'] = __('My Submissions');
        $data['activeMySubmission'] = 'active';
        if ($request->ajax()) {
            return $this->clientOrderService->getReviewerSubmissionOrderListData($request);
        }

        if(auth()->user()->role == USER_ROLE_REVIEWER){
            // $orderIds = Reviews::where('reviewer_id', auth()->id())->where('status', 'completed')->pluck('client_order_id')->toArray();
            // $data['orderCount'] = ClientOrder::whereIn('order_id', $orderIds)->count();

            $orderIds = ClientOrderAssignee::where('assigned_to', auth()->id())->pluck('order_id')->toArray();
            $data['orderCount'] = ClientOrder::whereIn('id', $orderIds)->count();
        }else{

            $data['orderCount'] = ClientOrder::where(['client_id' => auth()->id()])->count();


        }

        // $data['orderCount'] = ClientOrder::where(['client_id' => auth()->id()])->count();
        return view('user.orders.reviewer.submitted-order-list', $data);
    }

    public function reviewer_assigned_order_status_change($order_submission_id, $status){

        DB::beginTransaction();
        try {
            $orderSubmission = ClientOrderSubmission::where('id',decrypt($order_submission_id))->first();
            if(empty($orderSubmission)){
                return redirect()->back()->with(['error' => 'Order not found']);
            }
            $data = Reviews::where('client_order_submission_id',decrypt($order_submission_id))->where('reviewer_id',auth()->id())->first();
            if(empty($data)){
                $data = new Reviews();
                $data->client_order_submission_id = decrypt($order_submission_id);
                $data->client_order_id = $orderSubmission->client_order_id;
                $data->reviewer_id = auth()->id();
            }else{
                if($status === SUBMISSION_REVIEWER_ORDER_STATUS_COMPLETED){
                    $data->admin_status = SUBMISSION_REVIEWER_ORDER_ADMIN_STATUS_UNDER_REVIEW;
                }
            }

            $data->status = $status;
            $data->save();

            DB::commit();
            return redirect()->back()->with(['success' => 'Status Change successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['error' => SOMETHING_WENT_WRONG]);
        }
    }

    public function getService()
    {
        $data = $this->clientOrderService->getInvoice();
        return $this->success($data);
    }

    public function details($id)
    {
        $data['pageTitleParent'] = __('Order');
        $data['pageTitle'] = __('Order Details');
        $data['activeClientOrderIndex'] = 'active';
        $data['orderDetails'] = ClientOrder::where('id', decrypt($id))->with(['client_order_items'])->first();
        $data['conversationData'] = ClientOrderConversation::where(['order_id'=> decrypt($id), 'type'=> CONVERSATION_TYPE_CLIENT])->get();

        $submission = ClientOrderSubmission::where('client_order_id', optional($data['orderDetails'])->order_id)->first();
        $data['submission'] = $submission;
        $data['hasCompletedReviews'] = false;

        if ($submission) {
            $data['hasCompletedReviews'] = Reviews::where('client_order_submission_id', $submission->id)
                ->where('status', SUBMISSION_REVIEWER_ORDER_STATUS_COMPLETED)
                ->exists();
        }

        $seenUneenData = ClientOrderConversationSeen::where(['order_id'=>decrypt($id), 'created_by'=>auth()->id()])->first();
        if(is_null($seenUneenData)){
            $seenUneenData = new ClientOrderConversationSeen();
            $seenUneenData->order_id = decrypt($id);
            $seenUneenData->created_by = auth()->id();
            $seenUneenData->is_seen = 1;
            $seenUneenData->tenant_id = auth()->user()->tenant_id;
        }else{
            $seenUneenData->is_seen = 1;
        }
        $seenUneenData->save();

        return view('user.orders.details',$data);
    }
    public function conversationStore(ClientOrderConversationRequest $request)
    {
        return $this->clientOrderService->conversationStore($request);
    }

    // public function postsend(Request $request){

    //     $request->validate([
    //         'name' => 'required',
    //         'email' => 'required',
    //         'title' => 'required'
    //     ]);

    //     DB::beginTransaction();
    //     try {
    //         $service = Service::firstOrFail();

    //         $userClient = User::where('email', $request->email)->first();
    //         if( !$userClient || !$userClient->id ){

    //             $userClient = new User();
    //             $userClient->name = $request->name;
    //             $userClient->email = $request->email;
    //             $userClient->mobile = $request->phone;
    //             $userClient->status = 1;
    //             $userClient->role = USER_ROLE_CLIENT;
    //             $userClient->email_verification_status = STATUS_ACTIVE;
    //             $userClient->tenant_id = "zainiklab";

    //             $userClient->save();
    //         }

    //         $userId = $service->user_id;
    //         $tenantId = $userClient->tenant_id;

    //         $amount = $service->price;
    //         $discount = 0;
    //         $discount_type = DISCOUNT_TYPE_FLAT;
    //         $platform_charge = 0.00;
    //         $order_create_type = 0;

    //         $orderItems = [
    //             (object) [
    //                 'service_id' => $service->id,
    //                 'price' => $service->price,
    //                 'quantity' => 1,
    //             ]
    //         ];

    //         $orderData = [
    //             'amount' => $amount,
    //             'discount' => $discount,
    //             'discount_type' => $discount_type,
    //             'platform_charge' => $platform_charge,
    //             'order_create_type' => $order_create_type,
    //             'orderItems' => (object) ($orderItems),
    //             'recurring_type' => $service->recurring_type,
    //             'recurring_payment_type' => $service->payment_type,
    //         ];
    //         $clientOrder = makeClientOrder($orderData, $userClient, $userId, $tenantId)['data'];

    //         $file = new FileManager();
    //         $uploaded = $file->upload('Service', $request->file);

    //         $clientOrder->file = $uploaded->id;
    //         $clientOrder->save();


    //         $data = new ClientOrderNote();
    //         $data->order_id = $clientOrder->id;
    //         $data->details = " عنوان البحث / الدراسة :  " . $request->title;
    //         $data->details .= "\n" . " المجلة : " . $request->journal;
    //         $data->user_id = $userClient->id;
    //         $data->save();


    //         DB::commit();

    //         Auth::login($userClient);
    //         return "تم الرفع بنجاح شكرا لك ....";

    //     } catch (Exception $e) {
    //         DB::rollBack();
    //         return "حصل خطأ ما المرجوا المحاولة مجددا ....";
    //     }
    // }
    public function postsend(Request $request){

        $authCheck = auth()->check() ? true : false;

        $validationData = [
            'title' => 'required',
            'file' => 'required',
            'journal' => 'required'
        ];
        if(!$authCheck){
            $validationData['name'] = 'required';
            $validationData['email'] = 'required';
        }

        $request->validate($validationData);

        DB::beginTransaction();
        try {
            $service = Service::firstOrFail();



            if($authCheck){
                $userClient = User::where('id',auth()->user()->id)->first();
                if(!$userClient){
                    return "Request invalid!";
                }
            }else{
                $userClient = User::where('email', $request->email)->first();
                if( !$userClient || !$userClient->id ){

                    $userClient = new User();
                    $userClient->name = $request->name;
                    $userClient->email = $request->email;
                    $userClient->mobile = $request->phone;
                    $userClient->status = 1;
                    $userClient->role = USER_ROLE_CLIENT;
                    $userClient->email_verification_status = STATUS_ACTIVE;
                    $userClient->tenant_id = "zainiklab";

                    $userClient->save();
                }
            }

            $userId = $service->user_id;
            $tenantId = $userClient->tenant_id;

            $amount = $service->price;
            $discount = 0;
            $discount_type = DISCOUNT_TYPE_FLAT;
            $platform_charge = 0.00;
            $order_create_type = 0;

            $orderItems = [
                (object) [
                    'service_id' => $service->id,
                    'price' => $service->price,
                    'quantity' => 1,
                ]
            ];

            $orderData = [
                'amount' => $amount,
                'discount' => $discount,
                'discount_type' => $discount_type,
                'platform_charge' => $platform_charge,
                'order_create_type' => $order_create_type,
                'orderItems' => (object) ($orderItems),
                'recurring_type' => $service->recurring_type,
                'recurring_payment_type' => $service->payment_type,
            ];
            $clientOrder = makeClientOrder($orderData, $userClient, $userId, $tenantId)['data'];

            $file = new FileManager();
            $uploaded = $file->upload('Service', $request->file);

            $clientOrder->file = $uploaded->id;
            $clientOrder->save();


            $data = new ClientOrderNote();
            $data->order_id = $clientOrder->id;
            $data->details = " عنوان البحث / الدراسة :  " . $request->title;
            $data->details .= "\n" . " المجلة : " . $request->journal;
            $data->user_id = $userClient->id;
            $data->save();


            DB::commit();

            //send email notification
            abstractOrderCreatedEmailNotifyForAuthor($clientOrder,$clientOrder->client);
            abstractOrderCreatedNotifyForAuthor($clientOrder,$clientOrder->client);

            //admin mail sent
            $admins  = User::whereIn('role',[USER_ROLE_ADMIN,USER_ROLE_SUPER_ADMIN])->get();
            foreach($admins as $admin){
                abstractOrderCreatedEmailNotifyForAdmin($clientOrder,$admin);
                abstractOrderCreatedNotifyForAdmin($clientOrder,$admin);
                Log::debug("Admin: " . $admin->email);
            }


            if($authCheck){
                // return redirect()->route('user.orders.list');
                // return response()->json([
                //     CREATED_SUCCESSFULLY => __("Created Successfully"),
                // ]);
                $message = __(CREATED_SUCCESSFULLY);
                return $this->success([], $message);
            }else{

                Auth::login($userClient);
                return redirect()->route('user.orders.list');
                // return "تم الرفع بنجاح شكرا لك ....";
            }

        } catch (Exception $e) {
            DB::rollBack();
            return "حصل خطأ ما المرجوا المحاولة مجددا ....";
        }
    }
    public function send_form(Request $request){
        $data['pageTitleParent'] = __('Order');
        $data['pageTitle'] = __('Order Details');
        return view("user.orders.form",$data);
    }
}
