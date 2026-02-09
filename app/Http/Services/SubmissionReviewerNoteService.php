<?php

namespace App\Http\Services;

use App\Models\ClientOrder;
use App\Models\FileManager;
use App\Models\SubmissionReviewerNotes;
use App\Models\SubmissionReviewerNotesConversation;
use App\Models\User;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;

class SubmissionReviewerNoteService
{
    use ResponseTrait;

    public function store($request)
    {
        DB::beginTransaction();
        try {
            if ($request->id) {
                $data = SubmissionReviewerNotes::find($request->id);
                $msg = getMessage(UPDATED_SUCCESSFULLY);
            } else {
                $data = new SubmissionReviewerNotes();
                $msg = getMessage(CREATED_SUCCESSFULLY);
            }

            $reviwer = User::where('id', $request->reviewer_id)->where('role', USER_ROLE_REVIEWER)->first();

            if (!$reviwer) {
                return $this->error(null, getMessage(USER_NOT_FOUND), 400);
            }

            $data->client_id = $request->reviewer_id;
            
            
            $data->order_id = $request->order_id;
            $data->title = $request->title;
            $data->description = $request->description;
            $data->priority = $request->priority != null?$request->priority:SUBMISSION_REVIEWER_NOTE_PRIORITY_LOW;
            $data->created_by = auth()->id();
            $data->tenant_id = auth()->user()->tenant_id;

            /*File Manager Call upload*/
                if ($request->file && $request->oldFiles) {
                    $fileId = [];
                    foreach ($request->file as $singlefile) {
                        $new_file = new FileManager();
                        $uploaded = $new_file->upload('submission-reviewer-note-documents', $singlefile);
                        array_push($fileId, (string)$uploaded->id);
                    }
                    $fileArray =  array_merge($request->oldFiles, $fileId);
                    $data->file_id = json_encode($fileArray);

                }else if($request->file && !$request->oldFiles){
                    $fileId = [];
                    foreach ($request->file as $singlefile) {
                        $new_file = new FileManager();
                        $uploaded = $new_file->upload('submission-reviewer-note-documents', $singlefile);
                        array_push($fileId, $uploaded->id);
                    }
                    $data->file_id = json_encode($fileId);
                }else if(!$request->file && $request->oldFiles){
                    $data->file_id = json_encode($request->oldFiles);
                }else{
                    $data->file_id = null;
                }
            /*File Manager Call upload*/

            $data->save();

            if (!$request->id) {
                $submissionReviewerNoteId = 'SR' . sprintf('%06d', $data->id);
                if ($data->id) {
                    SubmissionReviewerNotes::where('id', $data->id)->update(['submission_reviewer_note_id' => $submissionReviewerNoteId]);
                }
            }

            DB::commit();

            if(!$request->id){
                newSubmissionReviewerNoteEmailNotify($data->id);
                newSubmissionReviewerNoteNotify($data->id);
            }

            return $this->success([], $msg);

        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], $e->getMessage());
        }
    }

    public function ticketCount()
    {
        return SubmissionReviewerNotes::where(['tenant_id' => auth()->user()->tenant_id])->count();
    }

    public function ticketList($status)
    {
        // $data = null;
        // if ($status == 'all') {
        //     $data = SubmissionReviewerNotes::leftJoin('users', 'tickets.client_id', '=', 'users.id')
        //         ->leftJoin('ticket_seen_unseens', function($join){
        //             $join->on('tickets.id', '=', 'ticket_seen_unseens.ticket_id');
        //             $join->on('ticket_seen_unseens.created_by', '=', DB::raw(auth()->id()));
        //         })
        //         ->where(function ($query) {
        //             if (auth()->user()->role == USER_ROLE_CLIENT){
        //                 $query->where(['tickets.client_id' => auth()->id()]);
        //             }else{
        //                 $query->where(['tickets.tenant_id' => auth()->user()->tenant_id]);
        //             }
        //         })
        //         ->orderBy('tickets.last_reply_time', 'DESC')
        //         ->orderBy('tickets.id','DESC')
        //         ->select([
        //             'tickets.*',
        //             'users.name as client_name',
        //             'users.email as client_email',
        //             'ticket_seen_unseens.is_seen'
        //         ])->with(['assignee']);
        // } elseif ($status == TICKET_STATUS_TRASHED) {
        //     $data = SubmissionReviewerNotes::leftJoin('users', 'submission_reviewer_notes.client_id', '=', 'users.id')
        //         ->where(function ($query) {
        //             if (auth()->user()->role == USER_ROLE_CLIENT){
        //                 $query->where(['submission_reviewer_notes.client_id' => auth()->id()]);
        //             }else{
        //                 $query->where(['submission_reviewer_notes.tenant_id' => auth()->user()->tenant_id]);
        //             }
        //         })
        //         ->orderBy('submission_reviewer_notes.last_reply_time', 'DESC')
        //         ->select([
        //             'submission_reviewer_notes.*',
        //             'users.name as client_name',
        //             'users.email as client_email',
        //         ])->onlyTrashed()->with(['assignee']);
        // } else {
        //     $data = SubmissionReviewerNotes::leftJoin('users', 'submission_reviewer_notes.client_id', '=', 'users.id')
        //         ->where(function ($query) use($status){
        //             if (auth()->user()->role == USER_ROLE_CLIENT){
        //                 $query->where(['submission_reviewer_notes.client_id' => auth()->id(), 'tickets.status' => $status]);
        //             }else{
        //                 $query->where(['submission_reviewer_notes.tenant_id' => auth()->user()->tenant_id, 'submission_reviewer_notes.status' => $status]);
        //             }
        //         })
        //         ->orderBy('submission_reviewer_notes.last_reply_time', 'DESC')
        //         ->select([
        //             'submission_reviewer_notes.*',
        //             'users.name as client_name',
        //             'users.email as client_email',
        //         ])->with(['assignee']);
        // }
        // $memberData = User::where(['role' => USER_ROLE_TEAM_MEMBER, 'tenant_id' => auth()->user()->tenant_id])->get();
        // $route_pre_fixed =  auth()->user()->role == USER_ROLE_CLIENT?'user':'admin';

        // return datatables($data)
            // ->addIndexColumn()
            // ->editColumn('order_id', function ($data) {
            //     $orderID = $data->order_id;
            //     return "<p>$orderID</p>";
            // })
            // ->editColumn('ticket_id', function ($data) {
            //     return getTicketIdHtml($data);
            // })
            // ->editColumn('client', function ($data) {
            //     return "<p>$data->client_name</p>";
            // })
            // ->editColumn('priority', function ($data) {
            //     if ($data->priority == TICKET_PRIORITY_HIGH) {
            //         return "<p>" . __("High") . "</p>";
            //     } else if ($data->priority == TICKET_PRIORITY_MEDIUM) {
            //         return "<p>" . __("Medium") . "</p>";
            //     } else {
            //         return "<p>" . __("Low") . "</p>";
            //     }
            // })
            // ->editColumn('status', function ($data) use ($status) {
            //     if ($status == TICKET_STATUS_TRASHED) {
            //         return "<p class='zBadge zBadge-cancel'>" . __("Deleted") . "</p>";
            //     } else {
            //         if ($data->status == TICKET_STATUS_OPEN) {
            //             return "<p class='zBadge zBadge-open'>" . __("Open") . "</p>";
            //         } else if ($data->status == TICKET_STATUS_IN_PROGRESS) {
            //             return "<p class='zBadge zBadge-onHold'>" . __("In Progress") . "</p>";
            //         } else if ($data->status == TICKET_STATUS_RESOLVED) {
            //             return "<p class='zBadge zBadge-complete'>" . __("Resolved") . "</p>";
            //         } else if ($data->status == TICKET_STATUS_CLOSED) {
            //             return "<p class='zBadge zBadge-closed'>" . __("Closed") . "</p>";
            //         }
            //     }
            // })
            // ->editColumn('updated_at', function($data){
            //     return "<span data-timestamp='{$data->updated_at}'>".$data->updated_at->format('l, F jS, Y (H:i)')."</span>";
            // })
            // ->addColumn('action', function ($data) use ($status, $route_pre_fixed) {
            //     if ($data->status != TICKET_STATUS_OPEN) {
            //         return "<div class='dropdown dropdown-one'>
            //                <button class='dropdown-toggle p-0 bg-transparent w-30 h-30 ms-auto bd-one bd-c-stroke rounded-circle d-flex justify-content-center align-items-center' type='button' data-bs-toggle='dropdown' aria-expanded='false'><i class='fa-solid fa-ellipsis'></i></button>
            //                <ul class='dropdown-menu dropdownItem-two'>
            //                   <li>
            //                      <a class='d-flex align-items-center cg-8' href='" . route($route_pre_fixed.'.ticket.details', encrypt($data->id)) . "'>
            //                         <div class='d-flex'>
            //                            <svg width='15' height='12' viewBox='0 0 15 12' fill='none' xmlns='http://www.w3.org/2000/svg'>
            //                               <path d='M7.5 8C8.60457 8 9.5 7.10457 9.5 6C9.5 4.89543 8.60457 4 7.5 4C6.39543 4 5.5 4.89543 5.5 6C5.5 7.10457 6.39543 8 7.5 8Z' fill='#5D697A' />
            //                               <path d='M14.9698 5.83C14.3817 4.30882 13.3608 2.99331 12.0332 2.04604C10.7056 1.09878 9.12953 0.561286 7.49979 0.5C5.87005 0.561286 4.29398 1.09878 2.96639 2.04604C1.6388 2.99331 0.617868 4.30882 0.0297873 5.83C-0.00992909 5.93985 -0.00992909 6.06015 0.0297873 6.17C0.617868 7.69118 1.6388 9.00669 2.96639 9.95396C4.29398 10.9012 5.87005 11.4387 7.49979 11.5C9.12953 11.4387 10.7056 10.9012 12.0332 9.95396C13.3608 9.00669 14.3817 7.69118 14.9698 6.17C15.0095 6.06015 15.0095 5.93985 14.9698 5.83ZM7.49979 9.25C6.857 9.25 6.22864 9.05939 5.69418 8.70228C5.15972 8.34516 4.74316 7.83758 4.49718 7.24372C4.25119 6.64986 4.18683 5.99639 4.31224 5.36596C4.43764 4.73552 4.74717 4.15642 5.20169 3.7019C5.65621 3.24738 6.23531 2.93785 6.86574 2.81245C7.49618 2.68705 8.14965 2.75141 8.74351 2.99739C9.33737 3.24338 9.84495 3.65994 10.2021 4.1944C10.5592 4.72886 10.7498 5.35721 10.7498 6C10.7485 6.86155 10.4056 7.68743 9.79642 8.29664C9.18722 8.90584 8.36133 9.24868 7.49979 9.25Z' fill='#5D697A'/>
            //                            </svg>
            //                         </div>
            //                         <p class='fs-14 fw-500 lh-17 text-para-text'>" . __("Ticket Details") . "</p>
            //                      </a>
            //                   </li>
            //                </ul>
            //             </div>";
            //     } else {
            //             return "<div class='dropdown dropdown-one'>
            //                <button class='dropdown-toggle p-0 bg-transparent w-30 h-30 ms-auto bd-one bd-c-stroke rounded-circle d-flex justify-content-center align-items-center' type='button' data-bs-toggle='dropdown' aria-expanded='false'><i class='fa-solid fa-ellipsis'></i></button>
            //                <ul class='dropdown-menu dropdownItem-two'>
            //                   <li>
            //                      <a class='d-flex align-items-center cg-8' href='" . route($route_pre_fixed.'.ticket.details', encrypt($data->id)) . "'>
            //                         <div class='d-flex'>
            //                            <svg width='15' height='12' viewBox='0 0 15 12' fill='none' xmlns='http://www.w3.org/2000/svg'>
            //                               <path d='M7.5 8C8.60457 8 9.5 7.10457 9.5 6C9.5 4.89543 8.60457 4 7.5 4C6.39543 4 5.5 4.89543 5.5 6C5.5 7.10457 6.39543 8 7.5 8Z' fill='#5D697A' />
            //                               <path d='M14.9698 5.83C14.3817 4.30882 13.3608 2.99331 12.0332 2.04604C10.7056 1.09878 9.12953 0.561286 7.49979 0.5C5.87005 0.561286 4.29398 1.09878 2.96639 2.04604C1.6388 2.99331 0.617868 4.30882 0.0297873 5.83C-0.00992909 5.93985 -0.00992909 6.06015 0.0297873 6.17C0.617868 7.69118 1.6388 9.00669 2.96639 9.95396C4.29398 10.9012 5.87005 11.4387 7.49979 11.5C9.12953 11.4387 10.7056 10.9012 12.0332 9.95396C13.3608 9.00669 14.3817 7.69118 14.9698 6.17C15.0095 6.06015 15.0095 5.93985 14.9698 5.83ZM7.49979 9.25C6.857 9.25 6.22864 9.05939 5.69418 8.70228C5.15972 8.34516 4.74316 7.83758 4.49718 7.24372C4.25119 6.64986 4.18683 5.99639 4.31224 5.36596C4.43764 4.73552 4.74717 4.15642 5.20169 3.7019C5.65621 3.24738 6.23531 2.93785 6.86574 2.81245C7.49618 2.68705 8.14965 2.75141 8.74351 2.99739C9.33737 3.24338 9.84495 3.65994 10.2021 4.1944C10.5592 4.72886 10.7498 5.35721 10.7498 6C10.7485 6.86155 10.4056 7.68743 9.79642 8.29664C9.18722 8.90584 8.36133 9.24868 7.49979 9.25Z' fill='#5D697A'/>
            //                            </svg>
            //                         </div>
            //                         <p class='fs-14 fw-500 lh-17 text-para-text'>" . __("Ticket Details") . "</p>
            //                      </a>
            //                   </li>
            //                   <li>
            //                      <a class='d-flex align-items-center cg-8' href='" . route($route_pre_fixed.'.ticket.edit', encrypt($data->id)) . "'>
            //                         <div class='d-flex'>
            //                            <svg width='12' height='13' viewBox='0 0 12 13' fill='none' xmlns='http://www.w3.org/2000/svg'>
            //                               <path d='M11.8067 3.19354C12.0667 2.93354 12.0667 2.5002 11.8067 2.25354L10.2467 0.693535C10 0.433535 9.56667 0.433535 9.30667 0.693535L8.08 1.91354L10.58 4.41354M0 10.0002V12.5002H2.5L9.87333 5.1202L7.37333 2.6202L0 10.0002Z' fill='#5D697A' />
            //                            </svg>
            //                         </div>
            //                         <p class='fs-14 fw-500 lh-17 text-para-text'>" . __("Edit") . "</p>
            //                      </a>
            //                   </li>
            //                   <li>
            //                      <button class='d-flex align-items-center cg-8 border-0 bg-transparent' onclick='deleteItem(\"" . route($route_pre_fixed.'.ticket.delete', encrypt($data->id)) . "\", \"ticketTable-$status\")'>
            //                         <div class='d-flex'>
            //                            <svg width='14' height='15' viewBox='0 0 14 15' fill='none' xmlns='http://www.w3.org/2000/svg'>
            //                               <path fill-rule='evenodd'clip-rule='evenodd'd='M5.76256 2.51256C6.09075 2.18437 6.53587 2 7 2C7.46413 2 7.90925 2.18437 8.23744 2.51256C8.4448 2.71993 8.59475 2.97397 8.67705 3.25H5.32295C5.40525 2.97397 5.5552 2.71993 5.76256 2.51256ZM3.78868 3.25C3.89405 2.57321 4.21153 1.94227 4.7019 1.4519C5.3114 0.84241 6.13805 0.5 7 0.5C7.86195 0.5 8.6886 0.84241 9.2981 1.4519C9.78847 1.94227 10.106 2.57321 10.2113 3.25H13C13.4142 3.25 13.75 3.58579 13.75 4C13.75 4.41422 13.4142 4.75 13 4.75H12V13C12 13.3978 11.842 13.7794 11.5607 14.0607C11.2794 14.342 10.8978 14.5 10.5 14.5H3.5C3.10217 14.5 2.72064 14.342 2.43934 14.0607C2.15804 13.7794 2 13.3978 2 13V4.75H1C0.585786 4.75 0.25 4.41422 0.25 4C0.25 3.58579 0.585786 3.25 1 3.25H3.78868ZM5 6.37646C5.34518 6.37646 5.625 6.65629 5.625 7.00146V11.003C5.625 11.3481 5.34518 11.628 5 11.628C4.65482 11.628 4.375 11.3481 4.375 11.003V7.00146C4.375 6.65629 4.65482 6.37646 5 6.37646ZM9.625 7.00146C9.625 6.65629 9.34518 6.37646 9 6.37646C8.65482 6.37646 8.375 6.65629 8.375 7.00146V11.003C8.375 11.3481 8.65482 11.628 9 11.628C9.34518 11.628 9.625 11.3481 9.625 11.003V7.00146Z'fill='#5D697A'/>
            //                            </svg>
            //                         </div>
            //                         <p class='fs-14 fw-500 lh-17 text-para-text'>" . __("Delete") . "</p>
            //                      </button>
            //                   </li>
            //                </ul>
            //             </div>";
            //     }

            // })
            // ->rawColumns(['order_id', 'ticket_id', 'client', 'priority', 'assignee', 'status','updated_at', 'action'])
            // ->make(true);

    }

    public function ticketDetails($id)
    {
        return  SubmissionReviewerNotes::leftJoin('users', 'submission_reviewer_notes.created_by', '=', 'users.id')
//            ->leftJoin('client_orders', 'tickets.order_id', '=', 'client_orders.id')
//            ->leftJoin('services', 'client_orders.service_id', '=', 'services.id')
            ->where(['submission_reviewer_notes.id' => $id])
            ->select([
                'submission_reviewer_notes.*',
                'users.name as client_name',
                'users.email as client_email',
                'users.image as client_image',
                'users.role as client_role',
            ])->withTrashed()->first();


//        return Ticket::leftJoin('users', 'tickets.client_id', '=', 'users.id')
//            ->where(['tickets.id' => $id])
//            ->select([
//                'tickets.*',
//                'users.name as client_name',
//                'users.email as client_email',
//            ])->with(['assignee'])->first();

    }
    public function ticketConversations($id)
    {
        return SubmissionReviewerNotesConversation::leftJoin('users', 'submission_reviewer_notes_conversations.user_id', '=', 'users.id')
            ->where(['submission_reviewer_notes_conversations.submission_reviewer_note_id' => $id])
            ->select([
                'submission_reviewer_notes_conversations.*',
                'users.name as client_name',
                'users.email as client_email',
                'users.image as client_image',
                'users.role as client_role',
            ])->get();

//        return Ticket::leftJoin('users', 'tickets.client_id', '=', 'users.id')
//            ->where(['tickets.id' => $id])
//            ->select([
//                'tickets.*',
//                'users.name as client_name',
//                'users.email as client_email',
//            ])->with(['assignee'])->first();
    }

    public function conversationsStore($request){
        DB::beginTransaction();
        try {
            if ($request->id) {
                $data = SubmissionReviewerNotesConversation::find($request->id);
                $msg = "Send Successfully";  //getMessage(UPDATED_SUCCESSFULLY);
            } else {
                $data = new SubmissionReviewerNotesConversation();
                $msg = "Send Successfully"; // getMessage(CREATED_SUCCESSFULLY);
            }
            
            $data->submission_reviewer_note_id = decrypt($request->ticket_id);
            $data->conversation_text = $request->conversation_text;
            $data->user_id = auth()->id();
            $data->tenant_id = auth()->user()->tenant_id;
            

            /*File Manager Call upload*/
            if ($request->file && $request->oldFiles) {
                $fileId = [];
                foreach ($request->file as $singlefile) {
                    $new_file = new FileManager();
                    $uploaded = $new_file->upload('reviewer-note-conversation-documents', $singlefile);
                    array_push($fileId, (string)$uploaded->id);
                }
                $fileArray =  array_merge($request->oldFiles, $fileId);
                $data->attachment = json_encode($fileArray);

            }else if($request->file && !$request->oldFiles){
                $fileId = [];
                foreach ($request->file as $singlefile) {
                    $new_file = new FileManager();
                    $uploaded = $new_file->upload('reviewer-note-conversation-documents', $singlefile);
                    array_push($fileId, $uploaded->id);
                }
                $data->attachment = json_encode($fileId);
            }else if(!$request->file && $request->oldFiles){
                $data->attachment = json_encode($request->oldFiles);
            }else{
                $data->attachment = null;
            }
            /*File Manager Call upload*/

            $data->save();

            // TicketSeenUnseen::where('ticket_id', decrypt($request->ticket_id))
            //     ->where('created_by', '!=', auth()->id())
            //     ->update(['is_seen' => 0]);

            SubmissionReviewerNotes::where(['id' => decrypt($request->ticket_id), 'tenant_id' => auth()->user()->tenant_id])
                ->update([
                    'last_reply_id' => $data->id,
                    'last_reply_by' => auth()->id(),
                    'last_reply_time' => now(),

                ]);

            DB::commit();
            if (auth()->user()->role == USER_ROLE_REVIEWER){
                submissionReviewerNoteConversationEmailNotifyToAdmin(decrypt($request->ticket_id));
                submissionReviewerNoteConversationNotifyToAdmin(decrypt($request->ticket_id));
            }else{
                submissionReviewerNoteConversationEmailNotifyForCustomer(decrypt($request->ticket_id));
                SubmissionReviewerNoteConversationNotifyForCustomer(decrypt($request->ticket_id));
            }
            return $this->success([
                'redirectUrl' => route('admin.client-orders.list'),
            ], $msg);
        }catch (Exception $exception){
            DB::rollBack();
            return $this->error([], $exception->getMessage());
        }
    }

}
