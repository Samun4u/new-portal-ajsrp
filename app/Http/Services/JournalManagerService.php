<?php

namespace App\Http\Services;

use App\Models\FileManager;
use App\Models\Journal;
use App\Models\JournalSubject;
use App\Models\Service;
use App\Models\ServiceAssignee;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class JournalManagerService
{
    use ResponseTrait;

    public function getAll()
    {
        return Service::query()
            ->where('user_id', auth()->id())
            ->get();
    }

    public function store($request)
    {

        DB::beginTransaction();
        try {
            if ($request->id) {
                $data = Journal::find($request->id);
                $msg = getMessage(UPDATED_SUCCESSFULLY);
            } else {
                $data = new Journal();
                $msg = getMessage(CREATED_SUCCESSFULLY);
            }
            $data->title = $request->title;
            $data->arabic_title = $request->title_ar;
            $data->slug = Str::slug($request->title);
            $data->website = $request->website;
            //$data->charges = $request->charge;
            // $data->journal_subject_id = $request->journal_subject_id;
            $data->service_id = $request->journal_service_id;
            $data->status = isset($request->status) ? $request->status : JOURNAL_STATUS_INACTIVE;

            // OJS fields (Task 2)
            if ($request->has('short_name')) {
                $data->short_name = $request->short_name;
            }
            if ($request->has('issn_print')) {
                $data->issn_print = $request->issn_print;
            }
            if ($request->has('issn_online')) {
                $data->issn_online = $request->issn_online;
            }
            if ($request->has('ojs_context')) {
                $data->ojs_context = $request->ojs_context;
            }

            // Certificate Details
            $data->impact_factor = $request->impact_factor;
            $data->editor_in_chief = $request->editor_in_chief;
            $data->chief_editor_name_ar = $request->chief_editor_name_ar;
            $data->managing_editor_name_en = $request->managing_editor_name_en;
            $data->managing_editor_name_ar = $request->managing_editor_name_ar;

            // Handle File Uploads
            if ($request->hasFile('signature_file')) {
                if ($data->signature_path && Storage::disk('public')->exists($data->signature_path)) {
                    Storage::disk('public')->delete($data->signature_path);
                }
                $data->signature_path = $request->file('signature_file')->store('journals/signatures', 'public');
            }
            if ($request->hasFile('managing_signature_file')) {
                if ($data->managing_editor_signature_path && Storage::disk('public')->exists($data->managing_editor_signature_path)) {
                    Storage::disk('public')->delete($data->managing_editor_signature_path);
                }
                $data->managing_editor_signature_path = $request->file('managing_signature_file')->store('journals/signatures', 'public');
            }
            if ($request->hasFile('stamp_file')) {
                if ($data->stamp_path && Storage::disk('public')->exists($data->stamp_path)) {
                    Storage::disk('public')->delete($data->stamp_path);
                }
                $data->stamp_path = $request->file('stamp_file')->store('journals/stamps', 'public');
            }

            $data->save();

            $data->subjects()->sync($request->journal_subject_id);

            DB::commit();
            return $this->success([], $msg);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], $e->getMessage());
        }
    }

    public function categoryStore($request)
    {
        DB::beginTransaction();
        try {
            if ($request->id) {
                $data = JournalSubject::find($request->id);
                $msg = getMessage(UPDATED_SUCCESSFULLY);
            } else {
                $data = new JournalSubject();
                $msg = getMessage(CREATED_SUCCESSFULLY);
            }
            $data->name = $request->name;
            $data->arabic_name = $request->name_ar;
            $data->slug = Str::slug($request->name);
            $data->status = isset($request->status) ? $request->status : JOURNAL_CATEGORY_STATUS_INACTIVE;
            $data->save();

            DB::commit();
            return $this->success([], $msg);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], $e->getMessage());
        }
    }

    public function list($dataShowLimit = null)
    {
        $journalList = Journal::query()
            // ->where(['tenant_id' => auth()->user()->tenant_id, 'status' => ACTIVE])
            ->with('subjects')
            ->with('service')
            ->orderBy('id', 'DESC')
            ->paginate($dataShowLimit ?? 10);
        return $journalList;
    }

    public function category_list($dataShowLimit = null)
    {
        $journalCategory = JournalSubject::query()
            //->where(['status' => 'active'])
            ->orderBy('id', 'DESC')
            ->paginate($dataShowLimit ?? 10);
        return $journalCategory;
    }

    public function clientListShow($dataShowLimit = null)
    {
        $service = Service::query()
            ->leftJoin('client_order_items', ['services.id' => 'client_order_items.service_id'])
            ->leftJoin('client_orders', function ($q) {
                $q->on('client_order_items.order_id', 'client_orders.id')->where('client_id', auth()->id());
            })
            ->selectRaw('services.*, COUNT(CASE WHEN client_orders.payment_status = ? THEN client_order_items.id END) as buy_service_count', [PAYMENT_STATUS_PAID])
            ->where('services.tenant_id', auth()->user()->tenant_id)
            ->where('services.status', STATUS_ACTIVE)
            ->orderBy('services.id', 'DESC')
            ->groupBy('services.id')
            ->paginate($dataShowLimit ?? 10);

        return $service;
    }
}
