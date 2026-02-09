@extends('user.layouts.app')
@push('title')
    {{$pageTitle}}
@endpush
@push('style')
<style>

form{
  direction: rtl; /*rtl*/
  padding: 10px;
}
form >div{
  margin-bottom: 20px;
}
form .zForm-label{
  font-weight: bold;
}
form .zForm-control{
  display: block;
  width: 98%;
  padding: 10px;
  border: solid 1px #aaa;
  margin: 5px 0;
}
form .gchoice{
  cursor: pointer;
  padding: 5px;
}
form .gchoice label{
  cursor: pointer;
}
form button{
  background: #204ce5;
  color: #fff;
  padding: 10px;
  border: solid 1px #ccc;
  min-width: 100px;
  font-weight: bold;
}

</style>
@endpush
@section('content')
    <!-- Content -->
    <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
        <div class="max-w-894 m-auto">
            <!-- Order title -->
            <h4 class="fs-18 fw-600 lh-20 text-title-black pb-11 text-md-start text-center">{{__("Order Submit")}}</h4>
            <!-- Order info - Note + Assign + Status -->
            
            <!-- Order info - Note/Message -->
            <div class="row">
                
                <div class="col-md-12">
                    <!-- <div class="bd-one bd-c-stroke bd-ra-8 bg-white pt-12 pb-18 max-w-700 m-auto">
                    </div> -->
                    <div class="p-3 bg-light border rounded shadow-sm">
                        <form class="ajax reset" id="abstractOrderForm" method="POST" action="{{ route('postsend') }}" enctype="multipart/form-data" data-handler="commonResponse">
                                @csrf
                                {{-- <div class="pb-20">
                                    <label class="zForm-label">الاسم <span class="req"></span></label>
                                    <input type="text" name="name" class="form-control zForm-control" />
                                </div>
                                <div class="pb-20">
                                    <label class="zForm-label">البريد الالكترونى <span class="req"></span></label>
                                    <input type="email" name="email" class="form-control zForm-control" />
                                </div>
                                <div class="pb-20">
                                    <label class="zForm-label">رقم الجوال مع مقدمة الدولة<span class="req"></span></label>
                                    <input type="text" name="phone" class="form-control zForm-control" />
                                </div> --}}
                                <div class="pb-20">
                                    <label class="zForm-label">عنوان البحث / الدراسة<span class="req"></span></label>
                                    <input type="text" name="title" class="form-control zForm-control" />
                                </div>
                                <div class="pb-20">
                                    <label class="zForm-label">من فضلك حدد المجلة التي تود النشر بها<span class="req"></span></label>
                                    <div class="gfield_radio" id="input_2_9">
                                    <div class="gchoice">
                                        <input name="journal" type="radio" value="مجلة العلوم التربوية والنفسية" id="choice_2_9_0">
                                        <label for="choice_2_9_0">مجلة العلوم التربوية والنفسية</label>
                                    </div>
                                    <div class="gchoice">
                                        <input name="journal" type="radio" value="مجلة المناهج وطرق التدريس" id="choice_2_9_1">
                                        <label for="choice_2_9_1">مجلة المناهج وطرق التدريس</label>
                                    </div>
                                    <div class="gchoice">
                                        <input name="journal" type="radio" value="مجلة العلوم الانسانية والاجتماعية" id="choice_2_9_2">
                                        <label for="choice_2_9_2">مجلة العلوم الانسانية والاجتماعية</label>
                                    </div>
                                    <div class="gchoice">
                                        <input name="journal" type="radio" value="مجلة العلوم الاقتصادية والادارية والقانونية" id="choice_2_9_3">
                                        <label for="choice_2_9_3">مجلة العلوم الاقتصادية والادارية والقانونية</label>
                                    </div>
                                    <div class="gchoice">
                                        <input name="journal" type="radio" value="مجلة العلوم الهندسية وتكنولوجيا المعلومات" id="choice_2_9_4">
                                        <label for="choice_2_9_4">مجلة العلوم الهندسية وتكنولوجيا المعلومات</label>
                                    </div>
                                    <div class="gchoice">
                                        <input name="journal" type="radio" value="مجلة العلوم الطبية والصيدلانية" id="choice_2_9_5">
                                        <label for="choice_2_9_5">مجلة العلوم الطبية والصيدلانية</label>
                                    </div>
                                    <div class="gchoice">
                                        <input name="journal" type="radio" value="مجلة علوم اللغة العربية وآدابها" id="choice_2_9_6">
                                        <label for="choice_2_9_6">مجلة علوم اللغة العربية وآدابها</label>
                                    </div>
                                    <div class="gchoice">
                                        <input name="journal" type="radio" value="مجلة العلوم الاسلامية" id="choice_2_9_7">
                                        <label for="choice_2_9_7">مجلة العلوم الاسلامية</label>
                                    </div>
                                    <div class="gchoice">
                                        <input name="journal" type="radio" value="مجلة العلوم الطبيعية والحياتية والتطبيقية" id="choice_2_9_8">
                                        <label for="choice_2_9_8">مجلة العلوم الطبيعية والحياتية والتطبيقية</label>
                                    </div>
                                    <div class="gchoice">
                                        <input name="journal" type="radio" value="مجلة العلوم الزراعية والبيئية والبيطرية" id="choice_2_9_9">
                                        <label for="choice_2_9_9">مجلة العلوم الزراعية والبيئية والبيطرية</label>
                                    </div>
                                    <div class="gchoice">
                                        <input name="journal" type="radio" value="مجلة إدارة المخاطر والأزمات" id="choice_2_9_10">
                                        <label for="choice_2_9_10">مجلة إدارة المخاطر والأزمات</label>
                                    </div>
                                    <div class="gchoice">
                                        <input name="journal" type="radio" value="المجلة العربية للعلوم و نشر الأبحاث - المجلة العامة" id="choice_2_9_11">
                                        <label for="choice_2_9_11">المجلة العربية للعلوم و نشر الأبحاث - المجلة العامة</label>
                                    </div>
                                    </div>
                                </div>
                                <div class="pb-20">
                                    <label class="zForm-label">من فضلك رفع ملف البحث<span class="req"></span></label>
                                    <input type="file" name="file" class="form-control zForm-control" />
                                </div>
                                <div class="pb-30 d-flex justify-content-between align-items-center flex-wrap g-10">
                                    <button type="submit" id="abstractOrderFormSubmitButton" class="border-0 d-flex justify-content-center align-items-center w-50 p-15 bd-ra-10 bg-main-color fs-14 fw-500 lh-20 text-white">  
                                        <span class="submit-text">إرسال</span>
                                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                    </button>
                                </div>
                        </form>
                        </div>
                </div>
                
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script src="{{ asset('user/custom/js/client-orders.js') }}"></script>
    <script>
        $('#abstractOrderForm').on('submit', function () {
        const submitBtn = $('#abstractOrderFormSubmitButton');
        const spinner = submitBtn.find('.spinner-border');
        const submitText = submitBtn.find('.submit-text');
        
        // Show spinner and disable button
        spinner.removeClass('d-none');
        submitText.text("جارٍ الإرسال...");
        submitBtn.prop('disabled', true);
        
    });
    </script>
    <script>
        // এই পেজে override বা extend করে নিচে redirect যোগ করলাম
        const originalCommonResponse = window.commonResponse;

        window.commonResponse = function(response) {
            // আগের মতো সব কাজ করুক
            originalCommonResponse(response);

            const submitBtn = $('#abstractOrderFormSubmitButton');
            const spinner = submitBtn.find('.spinner-border');
            const submitText = submitBtn.find('.submit-text');
            
            // Hide spinner and re-enable button
            spinner.addClass('d-none');
            submitText.text("إرسال");
            submitBtn.prop('disabled', false);

            // সফল হলে ৫ সেকেন্ড পরে redirect হোক
            if (response['status'] === true) {
                setTimeout(function () {
                    window.location.href = "{{ route('user.orders.list') }}"; // 🔁 এখানে আপনার redirect URL দিন
                }, 5000);
            }
        }
    </script>
@endpush
