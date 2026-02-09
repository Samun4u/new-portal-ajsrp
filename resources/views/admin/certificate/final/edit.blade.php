@extends('admin.layouts.app')
@push('title')
    {{$pageTitle}}
@endpush

@section('content')
    <!-- Content -->
    <div data-aos="fade-up" data-aos-duration="1000" class="overflow-x-hidden">
        <div class="p-sm-30 p-15">
            <div class="max-w-894 m-auto">
                <!--  -->
                <div class="d-flex justify-content-between align-items-center g-10 pb-12">
                    <!--  -->
                    <h4 class="fs-18 fw-600 lh-20 text-title-black">{{__($pageTitle)}}</h4>
                    <!--  -->
                </div>
                <!--  -->
                <form class="ajax reset" action="{{route('admin.certificate.final.store')}}" method="POST"
                      enctype="multipart/form-data" data-handler="commonResponse">
                    @csrf
                    <input type="hidden" name="id" value="{{$finalCertificate->id}}">
                    <div class="px-sm-25 px-15 bd-one bd-c-stroke bd-ra-10 bg-white mb-40">
                        <div class="max-w-713 m-auto py-sm-52 py-15">
                            <!--  -->
                            <div class="bd-b-one bd-c-stroke pb-40 mb-36">
                                <div class="row rg-20">

                                    <div class="col-12">
                                        <label for="rtl" class="zForm-label">{{ __('Order') }}</label>
                                        <select class="sf-select-without-search" name="client_order_id" required>
                                            <option value="">{{ __('Select') }}</option>
                                            @foreach ($orderList as $order)
                                                <option value="{{$order->order_id}}" {{$finalCertificate->client_order_id==$order->order_id?'selected':''}}>{{$order->order_id.' ('.getEmailByUserId($order->client_id).')'}}</option>                                                
                                            @endforeach
                                        </select>
                                    </div>
                                    <input type="hidden" value='{{ route("admin.certificate.final.order-details") }}' id="orderDetailsUrl">
                                    <input type="hidden" id="finalCertificateType" value="edit">
                                    <div class="col-12">
                                        <label for="addAuthorName" class="zForm-label">{{__('Authors')}}</label>
                                        <input name="author_names" type="text" class="form-control zForm-control"
                                               id="addAuthorName" placeholder="{{__('Enter Author Names')}}" value="{{$finalCertificate->author_names}}" required/>
                                    </div>
                                    <div class="col-12">
                                        <label for="addAuthorAffiliations" class="zForm-label">{{__('Affiliations')}}</label>
                                        <input name="author_affiliations" type="text" class="form-control zForm-control"
                                               id="addAuthorAffiliations" placeholder="{{__('Enter Author Affiliations')}}" value="{{$finalCertificate->author_affiliations}}" required/>
                                    </div>
                                    <div class="col-12">
                                        <label for="addPaperTitle" class="zForm-label">{{__('Paper Title')}}</label>
                                        <input name="paper_title" type="text" class="form-control zForm-control"
                                               id="addPaperTitle" placeholder="{{__('Enter Paper Title')}}" value="{{$finalCertificate->paper_title}}" required/>
                                    </div>
                                    <div class="col-12">
                                        <label for="addJournalName" class="zForm-label">{{__('Journal Name')}}</label>
                                        <input name="journal_name" type="text" class="form-control zForm-control"
                                               id="addJournalName" placeholder="{{__('Enter Journal Name')}}" value="{{$finalCertificate->journal_name}}" required/>
                                    </div>
                                    <div class="col-12">
                                        <label for="addVolume" class="zForm-label">{{__('Volume')}}</label>
                                        <input name="volume" type="text" class="form-control zForm-control"
                                               id="addVolume" placeholder="{{__('Enter Volume')}}" value="{{$finalCertificate->volume}}" required/>
                                    </div>
                                    <div class="col-12">
                                        <label for="addIssue" class="zForm-label">{{__('Issue')}}</label>
                                        <input name="issue" type="text" class="form-control zForm-control"
                                               id="addIssue" placeholder="{{__('Enter Issue')}}" value="{{$finalCertificate->issue}}" required/>
                                    </div>
                                    <div class="col-12">
                                        <label for="addDate" class="zForm-label">{{__('Date')}}</label>
                                        <input name="date" type="date" class="form-control zForm-control"
                                               id="addDate" placeholder="{{__('Enter Date')}}" value="{{$finalCertificate->date}}" required/>
                                    </div>
                                    
                                </div>
                            </div>

                        </div>
                    </div>
                    <!--  -->
                    <div class="d-flex g-12 mt-25">
                        <button type="submit"
                            class="py-10 px-26 bg-main-color bd-one bd-c-main-color bd-ra-8 fs-15 fw-600 lh-25 text-white">{{__('Save')}}</button>
                        <a href="{{ URL::previous() }}"
                            class="py-10 px-26 bg-white bd-one bd-c-para-text bd-ra-8 fs-15 fw-600 lh-25 text-para-text">{{__('Cancel')}}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="{{ asset('admin/custom/js/final_certificate.js') }}"></script>
@endpush