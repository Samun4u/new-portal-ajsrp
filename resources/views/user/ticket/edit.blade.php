@extends('user.layouts.app')
@push('title')
    {{$pageTitle}}
@endpush
@section('content')
    <div data-aos="fade-up" data-aos-duration="1000" class="overflow-x-hidden">
        <div class="p-sm-30 p-15">
            <div class="max-w-894 m-auto">
                <!--  -->
                <div class="d-flex justify-content-between align-items-center g-10 pb-12">
                    <!--  -->
                    <h4 class="fs-18 fw-600 lh-20 text-title-black">{{__("Edit Ticket")}}</h4>
                    <!--  -->
                </div>
                <!--  -->
                <form class="ajax reset" action="{{route('user.ticket.store')}}" method="POST"
                      enctype="multipart/form-data" data-handler="commonResponse">
                    @csrf
                    <div class="px-sm-25 px-15 bd-one bd-c-stroke bd-ra-10 bg-white mb-40">
                        <div class="max-w-713 m-auto py-sm-52 py-15">
                            <input type="hidden" value="{{$ticketDetails->id}}" name="id">
                            <!--  -->
                            <div class="row rg-20">
                                <div class="col-12">
                                    <label for="addTicketFieldSelectOrder"
                                           class="zForm-label">{{__("Select Order")}} / {{ __("General Inquiry") }}</label>
                                    <select class="sf-select-two" name="order_id">
                                        @foreach($clientOrderList as $order)
                                            <option
                                                value="{{$order->order_id}}" {{$ticketDetails->order_id==$order->order_id?'selected':''}}>{{$order->order_id.' ('.getEmailByUserId($order->client_id).')'}}</option>
                                        @endforeach
                                        <option value="general_inquery" {{$ticketDetails->order_id=="general_inquery"?'selected':''}}>{{ __("General Inquiry") }}</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="title"
                                           class="zForm-label">{{__("Title")}}</label>
                                    <input type="text"  name="ticket_title" id="title"
                                           class="form-control zForm-control"
                                           placeholder="{{__("Title")}}" value="{{$ticketDetails->ticket_title}}"/>
                                </div>
                                <div class="col-12">
                                    <label for="addTicketFieldDescription"
                                           class="zForm-label">{{__("Description")}}</label>
                                    <textarea id="addTicketFieldDescription" name="description"
                                              class="form-control zForm-control min-h-175"
                                              placeholder="{{__("Write description here")}}....">{{$ticketDetails->ticket_description}}</textarea>
                                </div>

                                <div class="col-12">
                                    <div class="pb-25">
                                        <p class="fs-15 fw-600 lh-24 text-para-text pb-12">{{__("Upload Image (JPG,JPEG,PNG)")}} </p>
                                        <div class="file-upload-one">
                                            <label for="mAttachment">
                                                <p class="fs-12 fw-500 lh-16 text-para-text">{{__("Choose Image to upload")}}</p>
                                                <p class="fs-12 fw-500 lh-16 text-white">{{__("Browse File")}}</p>
                                            </label>
                                            <input type="file" name="file[]" id="mAttachment"
                                                   class="invisible position-absolute" multiple=""/>
                                        </div>
                                        <!-- Attachment preview -->
                                        <div id="files-area" class="">
                                          <span id="filesList">
                                            <span id="files-names">
                                                @if($ticketDetails->file_id !=null)
                                                    @foreach (json_decode($ticketDetails->file_id) as $item)
                                                        <span class="file-block"><span class="file-icon"><i
                                                                    class="fa-solid fa-file"></i></span>
                                                    <input type="hidden" name="oldFiles[]" value="{{$item }}">
                                                    <p class="name">{{ getFileData($item,'file_name') }}</p>
                                                    <span class="file-delete"><i class="fa-solid fa-xmark"></i></span>
                                                </span>
                                                    @endforeach
                                                @endif
                                            </span>
                                          </span>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                    <!--  -->
                    <div class="d-flex g-12 mt-25">
                        <button type="submit"
                                class="py-10 px-26 bg-main-color bd-one bd-c-main-color bd-ra-8 fs-15 fw-600 lh-25 text-white">{{__("Save")}}</button>
                        <a href="{{ URL::previous() }}"
                           class="py-10 px-26 bg-white bd-one bd-c-para-text bd-ra-8 fs-15 fw-600 lh-25 text-para-text">{{__("Cancel")}}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('admin/custom/js/ticket.js') }}"></script>
@endpush

