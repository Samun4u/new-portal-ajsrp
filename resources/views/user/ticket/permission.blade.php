@extends('admin.layouts.app')
@push('title')
    {{$pageTitle}}
@endpush

@section('content')
    <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
        <div class="row rg-20">
            <div class="col-xl-3">
                <div class="bg-white p-sm-25 p-15 bd-one bd-c-stroke bd-ra-8">
                    @include("admin.setting.sidebar")
                </div>
            </div>
            <div class="col-xl-9">
                <form class="ajax " action="{{route('admin.setting.role-permission.permission-update')}}" method="POST"
                      enctype="multipart/form-data" data-handler="commonResponse">
                    @csrf
                    <div class="p-sm-25 p-15 bd-one bd-c-stroke bd-ra-10 bg-white mb-25">
                        <!--  -->
                        <input type="hidden" name="role" value="{{encrypt($roleData->id)}}">
                        <div class="d-flex cg-5">
                            <h4 class="fs-14 fw-600 lh-20 text-title-black pb-25"> {{__("Role Name: ")}}</h4>
                            <h4 class="fs-14 fw-500 lh-20 text-title-black pb-25 al"> {{$roleData->name}}</h4>
                        </div>

                        <ul class="zList-pb-20">

                            @foreach($permissionList as $key=>$item)
                                @if(count($rolePermissions) > 0)
                                    @php($flag=0)
                                    @foreach($rolePermissions as $rolePermisonItem)
                                        @if($rolePermisonItem->name == $item->name)
                                            <li>
                                                <div class="zForm-wrap-checkbox-2">
                                                    <input type="checkbox" class="form-check-input" id="projectManager{{$key}}" value="{{$item->name}}" name="permission[]" checked {{$roleData->id == 1?'disabled':''}}/>
                                                    <label for="projectManager{{$key}}">{{$item->name}}</label>
                                                </div>
                                            </li>
                                            @php($flag=0)
                                            @break
                                        @else

                                            @php($flag=1)
                                        @endif
                                    @endforeach
                                    @if($flag == 1)
                                        <li>
                                            <div class="zForm-wrap-checkbox-2">
                                                <input type="checkbox" class="form-check-input" id="projectManager{{$key}}" value="{{$item->name}}" name="permission[]" {{$roleData->id == 1?'disabled':''}}/>
                                                <label for="projectManager{{$key}}">{{$item->name}}</label>
                                            </div>
                                        </li>
                                        @php($flag == 1)
                                    @endif
                                @else
                                    <li>
                                        <div class="zForm-wrap-checkbox-2">
                                            <input type="checkbox" class="form-check-input" id="projectManager{{$key}}" value="{{$item->name}}" name="permission[]" />
                                            <label for="projectManager{{$key}}">{{$item->name}}</label>
                                        </div>
                                    </li>
                                @endif

                            @endforeach
                        </ul>
                        <!--  -->
                    </div>
                    <div class="d-flex g-12 flex-wrap">
                        <button type="submit" class="py-10 px-26 bg-main-color bd-one bd-c-main-color bd-ra-8 fs-15 fw-600 lh-25 text-white" {{$roleData->id == 1?'disabled':''}}>{{__("Save Changes")}}</button>
                        <a href="{{ URL::previous() }}" class="py-10 px-26 bg-white bd-one bd-c-para-text bd-ra-8 fs-15 fw-600 lh-25 text-para-text">{{__("Cancel")}}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <input type="hidden" id="roleListRoute" value="{{route('admin.setting.role-permission.list')}}">
@endsection

@push('script')
    <script src="{{ asset('admin/custom/js/role_permission.js') }}"></script>
@endpush

