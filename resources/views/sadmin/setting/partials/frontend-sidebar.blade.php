<ul class="settings-sidebar zList-three">
    <li>
        <a href="{{ route('super-admin.setting.frontend-setting.index') }}"
           class="d-flex justify-content-between align-items-center cg-10 {{ @$sectionSettingsActiveClass }}">
            <span class="fs-18 fw-600 lh-22 text-title-black">{{__('Frontend Setting')}}</span>
            <div class="d-flex text-title-black"><i class="fa-solid fa-angle-right"></i></div>
        </a>
    </li>
    <li>
        <a href="{{ route('super-admin.setting.frontend-setting.section.index') }}"
           class="d-flex justify-content-between align-items-center cg-10 {{ @$subSectionSettingsActiveClass }}">
            <span
                class="fs-18 fw-600 lh-22 text-title-black">{{__('Section
                        Setting')}}</span>
            <div class="d-flex text-title-black"><i class="fa-solid fa-angle-right"></i></div>
        </a>
    </li>
    <li>
        <a href="{{ route('super-admin.setting.features.index') }}"
           class="d-flex justify-content-between align-items-center cg-10 {{ @$featuresActiveClass }}">
            <span
                class="fs-18 fw-600 lh-22 text-title-black">{{__('Features')}}</span>
            <div class="d-flex text-title-black"><i class="fa-solid fa-angle-right"></i></div>
        </a>
    </li>
    <li>
        <a href="{{ route('super-admin.setting.core-features.index') }}"
           class="d-flex justify-content-between align-items-center cg-10 {{ @$coreFeaturesActiveClass }}">
            <span class="fs-18 fw-600 lh-22 text-title-black">{{__('Core
                        Features')}}</span>
            <div class="d-flex text-title-black"><i class="fa-solid fa-angle-right"></i></div>
        </a>
    </li>
    <li>
        <a href="{{ route('super-admin.setting.faq.index') }}"
           class="d-flex justify-content-between align-items-center cg-10 {{ @$faqActiveClass }}">
            <span
                class="fs-18 fw-600 lh-22 text-title-black">{{__('Faq')}}</span>
            <div class="d-flex text-title-black"><i class="fa-solid fa-angle-right"></i></div>
        </a>
    </li>
    <li>
        <a href="{{ route('super-admin.setting.testimonial.index') }}"
           class="d-flex justify-content-between align-items-center cg-10 {{ @$testimonialActiveClass }}">
            <span
                class="fs-18 fw-600 lh-22 text-title-black">{{__('Testimonial')}}</span>
            <div class="d-flex text-title-black"><i class="fa-solid fa-angle-right"></i></div>
        </a>
    </li>
    <li>
        <a href="{{ route('super-admin.setting.service.index') }}"
           class="d-flex justify-content-between align-items-center cg-10 {{ @$serviceActiveClass }}">
            <span
                class="fs-18 fw-600 lh-22 text-title-black">{{__('Service')}}</span>
            <div class="d-flex text-title-black"><i class="fa-solid fa-angle-right"></i></div>
        </a>
    </li>

</ul>
