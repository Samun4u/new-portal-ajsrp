<div data-aos="fade-right" data-aos-duration="1000" class="zSidebar">
    <div class="zSidebar-overlay"></div>
    <div class="zSidebar-wrap h-100">
        <div class="zSidebar-leftBar"></div>
        <!-- Logo -->
        <a href="{{ route('admin.dashboard') }}" class="zSidebar-logo">
            <img class="max-h-35" src="{{ getSettingImage('app_logo') }}" alt="" /></a>
        <!-- Menu & Logout -->
        <div class="zSidebar-fixed">
            <ul class="zSidebar-menu" id="sidebarMenu">

                <!-- Dashboard -->
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center cg-10 {{ @$activeDashboard }}">
                        <div class="d-flex">
                            <i class="fas fa-home"></i>
                        </div>
                        <span class="">{{ __('Dashboard') }}</span>
                    </a>
                </li>

                <!-- Editorial Dashboard -->
                <li>
                    <a href="{{ route('admin.editor.dashboard') }}" class="d-flex align-items-center cg-10 {{ @$activeEditorDashboard }}">
                        <div class="d-flex">
                            <i class="fas fa-edit"></i>
                        </div>
                        <span class="">{{ __('Editorial Dashboard') }}</span>
                    </a>
                </li>

                <!-- Editorial Workflow Section -->
                <li class="sidebar-divider">
                    <p class="fs-10 fw-600 lh-12 text-para-text">{{ __('Editorial Workflow Section') }}</p>
                    <div class="d-flex"><img src="{{ asset('assets') }}/images/icon/double-angle-right.svg" alt="" /></div>
                </li>

                <!-- Submission & Registration (Orders) -->
                @can('orders')
                <li>
                    <a href="{{ route('admin.client-orders.list') }}" class="d-flex align-items-center cg-10 {{ @$activeClientOrderIndex }}">
                        <div class="d-flex"><i class="fas fa-clipboard-list"></i></div>
                        <span class="">{{ __('Submission & Registration (Orders)') }}</span>
                    </a>
                </li>
                @endcan

                <!-- Initial Check (New submissions) -->
                <li>
                    <a href="{{ route('admin.client-orders.list', ['submission_status' => 'under_primary_review']) }}" class="d-flex align-items-center cg-10">
                        <div class="d-flex"><i class="fas fa-search"></i></div>
                        <span class="">{{ __('Initial Check (New submissions)') }}</span>
                    </a>
                </li>

                <!-- Peer Review (Under Review) -->
                <li class="has-submenu">
                    <a href="javascript:void(0)" class="d-flex align-items-center justify-content-between cg-10">
                        <div class="d-flex align-items-center cg-10">
                            <div class="d-flex"><i class="fas fa-users"></i></div>
                            <span class="">{{ __('Peer Review (Under Review)') }}</span>
                        </div>
                        <i class="fas fa-chevron-right fs-10 transition"></i>
                    </a>
                    <ul class="submenu-list" style="display: none; padding-left: 30px; margin-top: 5px;">
                        <li>
                            <a href="{{ route('admin.client-orders.list', ['submission_status' => 'under_peer_review', 'reviewer_assigned' => 'yes']) }}" class="fs-13 py-5 d-block text-para-text">
                                <i class="fas fa-check-circle me-1"></i> {{ __('Assigned') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.client-orders.list', ['submission_status' => 'under_peer_review', 'reviewer_assigned' => 'no']) }}" class="fs-13 py-5 d-block text-para-text">
                                <i class="fas fa-times-circle me-1"></i> {{ __('Not Assigned') }}
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Thank Reviewers (Reviewers Certificate) -->
                @can('certificates')
                <li class="has-submenu">
                    <a href="javascript:void(0)" class="d-flex align-items-center justify-content-between cg-10">
                        <div class="d-flex align-items-center cg-10">
                            <div class="d-flex"><i class="fas fa-hand-holding-heart"></i></div>
                            <span class="">{{ __('Thank Reviewers (Reviewers Certificate)') }}</span>
                        </div>
                        <i class="fas fa-chevron-right fs-10 transition"></i>
                    </a>
                    <ul class="submenu-list" style="display: none; padding-left: 30px; margin-top: 5px;">
                        <li>
                            <a href="{{ route('admin.reviewer.list', ['notes_submitted' => 'yes']) }}" class="fs-13 py-5 d-block text-para-text">
                                <i class="fas fa-comment-alt me-1"></i> {{ __('Submitted Notes') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.certificate.reviewer.list') }}" class="fs-13 py-5 d-block text-para-text">
                                <i class="fas fa-award me-1"></i> {{ __('Reviewers Certificate') }}
                            </a>
                        </li>
                    </ul>
                </li>
                @endcan

                <!-- Final Acceptance -->
                @can('certificates')
                <li>
                    <a href="{{ route('admin.client-orders.list', ['submission_status' => 'accepted_for_publication']) }}" class="d-flex align-items-center cg-10">
                        <div class="d-flex"><i class="fas fa-check-circle"></i></div>
                        <span class="">{{ __('Final Acceptance') }}</span>
                    </a>
                </li>
                @endcan

                <!-- Proofreading & Metadata (Proofreading) -->
                @can('orders')
                <li class="has-submenu">
                    <a href="javascript:void(0)" class="d-flex align-items-center justify-content-between cg-10">
                        <div class="d-flex align-items-center cg-10">
                            <div class="d-flex"><i class="fas fa-pencil-alt"></i></div>
                            <span class="">{{ __('Proofreading & Metadata') }}</span>
                        </div>
                        <i class="fas fa-chevron-right fs-10 transition"></i>
                    </a>
                    <ul class="submenu-list" style="display: none; padding-left: 30px; margin-top: 5px;">
                        <li>
                            <a href="{{ route('admin.proofreading.index', ['status' => 'pending']) }}" class="fs-13 py-5 d-block text-para-text">
                                <i class="fas fa-clock me-1"></i> {{ __('Under Proofreading') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.proofreading.index', ['metadata_status' => 'pending']) }}" class="fs-13 py-5 d-block text-para-text">
                                <i class="fas fa-database me-1"></i> {{ __('Require Metadata Approval') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.proofreading.index', ['status' => 'approved']) }}" class="fs-13 py-5 d-block text-para-text">
                                <i class="fas fa-check-double me-1"></i> {{ __('Completed Proofreading') }}
                            </a>
                        </li>
                    </ul>
                </li>
                @endcan

                <!-- Payment (APC) (Invoice) -->
                @can('invoice')
                <li class="has-submenu">
                    <a href="javascript:void(0)" class="d-flex align-items-center justify-content-between cg-10">
                        <div class="d-flex align-items-center cg-10">
                            <div class="d-flex"><i class="fas fa-file-invoice-dollar"></i></div>
                            <span class="">{{ __('Payment (APC) (Invoice)') }}</span>
                        </div>
                        <i class="fas fa-chevron-right fs-10 transition"></i>
                    </a>
                    <ul class="submenu-list" style="display: none; padding-left: 30px; margin-top: 5px;">
                        <li>
                            <a href="{{ route('admin.client-invoice.list', ['status' => 1]) }}" class="fs-13 py-5 d-block text-para-text">
                                <i class="fas fa-money-bill-wave me-1"></i> {{ __('Paid') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.client-invoice.list', ['status' => 0]) }}" class="fs-13 py-5 d-block text-para-text">
                                <i class="fas fa-exclamation-circle me-1"></i> {{ __('Not Paid') }}
                            </a>
                        </li>
                    </ul>
                </li>
                @endcan

                <!-- Acceptance Certificate (Final Acceptance Certificates) -->
                @can('orders')
                <li>
                    <a href="{{ route('admin.submissions.final-acceptance-certificates.index') }}" class="d-flex align-items-center cg-10 {{ @$activeFinalAcceptanceCertificates }}">
                        <div class="d-flex"><i class="fas fa-certificate"></i></div>
                        <span class="">{{ __('Acceptance Certificate') }}</span>
                    </a>
                </li>
                @endcan

                <!-- Final Production (Galley (Final Layout) -->
                @can('orders')
                <li>
                    <a href="{{ route('admin.galley.index') }}" class="d-flex align-items-center cg-10 {{ @$activeGalley }}">
                        <div class="d-flex"><i class="fas fa-print"></i></div>
                        <span class="">{{ __('Final Production (Galley)') }}</span>
                    </a>
                </li>
                @endcan

                <!-- Publish Online (OJS QuickSubmit Integration) -->
                @can('orders')
                <li>
                    <a href="{{ route('admin.ojs.index') }}" class="d-flex align-items-center cg-10 {{ @$activeOjsIntegration }}">
                        <div class="d-flex"><i class="fas fa-globe"></i></div>
                        <span class="">{{ __('Publish Online (OJS)') }}</span>
                    </a>
                </li>
                @endcan

                <!-- Reviewers Section -->
                <li class="sidebar-divider">
                    <p class="fs-10 fw-600 lh-12 text-para-text">{{ __('Reviewers Section') }}</p>
                    <div class="d-flex"><img src="{{ asset('assets') }}/images/icon/double-angle-right.svg" alt="" /></div>
                </li>

                @can('reviewers')
                <li>
                    <a href="{{ route('admin.reviewer.list') }}" class="d-flex align-items-center cg-10 {{ @$activeReviewerIndex }}">
                        <div class="d-flex"><i class="fas fa-user-edit"></i></div>
                        <span class="">{{ __('Reviewers') }}</span>
                    </a>
                </li>
                @endcan

                <li>
                    <a href="{{ route('admin.reviewer-application.index') }}" class="d-flex align-items-center cg-10 {{ @$activeReviewerApplication }}">
                        <div class="d-flex"><i class="fas fa-file-signature"></i></div>
                        <span class="">{{ __('Reviewer Applications') }}</span>
                    </a>
                </li>

                @can('certificates')
                <li>
                    <a href="{{ route('admin.certificate.reviewer.list') }}" class="d-flex align-items-center cg-10 {{ @$activeReviewerCert }}">
                        <div class="d-flex"><i class="fas fa-award"></i></div>
                        <span class="">{{ __('Reviewers Certificate') }}</span>
                    </a>
                </li>
                @endcan

                <!-- Users and Roles Section -->
                <li class="sidebar-divider">
                    <p class="fs-10 fw-600 lh-12 text-para-text">{{ __('Users and Roles Section') }}</p>
                    <div class="d-flex"><img src="{{ asset('assets') }}/images/icon/double-angle-right.svg" alt="" /></div>
                </li>

                @can('team-member')
                <li>
                    <a href="{{ route('admin.team-member.index') }}" class="d-flex align-items-center cg-10 {{ @$activeTeamMember }}">
                        <div class="d-flex"><i class="fas fa-users-cog"></i></div>
                        <span class="">{{ __('Users') }}</span>
                    </a>
                </li>
                @endcan

                <li>
                    <a href="{{ route('admin.setting.role-permission.list') }}" class="d-flex align-items-center cg-10 {{ @$activeRolePermission }}">
                        <div class="d-flex"><i class="fas fa-user-shield"></i></div>
                        <span class="">{{ __('Roles') }}</span>
                    </a>
                </li>

                <!-- Books Section -->
                <li class="sidebar-divider">
                    <p class="fs-10 fw-600 lh-12 text-para-text">{{ __('Books Section') }}</p>
                    <div class="d-flex"><img src="{{ asset('assets') }}/images/icon/double-angle-right.svg" alt="" /></div>
                </li>

                @can('books')
                <li>
                    <a href="{{ route('admin.submitted-books.list') }}" class="d-flex align-items-center cg-10 {{ @$activeSubmittedBooks }}">
                        <div class="d-flex"><i class="fas fa-book"></i></div>
                        <span class="">{{ __('Books') }}</span>
                    </a>
                </li>
                @endcan

                <!-- Correspondence Section -->
                <li class="sidebar-divider">
                    <p class="fs-10 fw-600 lh-12 text-para-text">{{ __('Correspondence Section') }}</p>
                    <div class="d-flex"><img src="{{ asset('assets') }}/images/icon/double-angle-right.svg" alt="" /></div>
                </li>

                @can('send-email')
                <li>
                    <a href="{{ route('admin.send-email.index') }}" class="d-flex align-items-center cg-10 {{ @$activeSendEmail }}">
                        <div class="d-flex"><i class="fas fa-envelope"></i></div>
                        <span class="">{{ __('Send Email') }}</span>
                    </a>
                </li>
                @endcan

                <li>
                    <a href="{{ route('admin.research-submission.index') }}" class="d-flex align-items-center cg-10 {{ @$activeResearchSubmission }}">
                        <div class="d-flex"><i class="fas fa-database"></i></div>
                        <span class="">{{ __('Metadata (Research Submissions)') }}</span>
                    </a>
                </li>

                <!-- Setup Section -->
                <li class="sidebar-divider">
                    <p class="fs-10 fw-600 lh-12 text-para-text">{{ __('Setup Section') }}</p>
                    <div class="d-flex"><img src="{{ asset('assets') }}/images/icon/double-angle-right.svg" alt="" /></div>
                </li>

                @can('service')
                <li>
                    <a href="{{ route('admin.services.list') }}" class="d-flex align-items-center cg-10 {{ @$activeService }}">
                        <div class="d-flex"><i class="fas fa-cogs"></i></div>
                        <span class="">{{ __('Services') }}</span>
                    </a>
                </li>
                @endcan

                @can('journal')
                <li>
                    <a href="{{ route('admin.journals.category.list') }}" class="d-flex align-items-center cg-10 {{ @$activeJournalCategory }}">
                        <div class="d-flex"><i class="fas fa-tags"></i></div>
                        <span class="">{{ __('Journal Category') }}</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.journals.list') }}" class="d-flex align-items-center cg-10 {{ @$activeJournal }}">
                        <div class="d-flex"><i class="fas fa-book-open"></i></div>
                        <span class="">{{ __('Journal List') }}</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.issues.journal-issues') }}" class="d-flex align-items-center cg-10 {{ @$activeJournalIssues }}">
                        <div class="d-flex"><i class="fas fa-newspaper"></i></div>
                        <span class="">{{ __('Journal Issues Overview') }}</span>
                    </a>
                </li>
                @endcan

                @can('order-form')
                <li>
                    <a href="{{ route('admin.order-form.index') }}" class="d-flex align-items-center cg-10 {{ @$activeOrderForm }}">
                        <div class="d-flex"><i class="fas fa-list-alt"></i></div>
                        <span class="">{{ __('Order Forms') }}</span>
                    </a>
                </li>
                @endcan

                @can('quotation')
                <li>
                    <a href="{{ route('admin.quotation.list') }}" class="d-flex align-items-center cg-10 {{ @$activeQuatationList }}">
                        <div class="d-flex"><i class="fas fa-file-invoice"></i></div>
                        <span class="">{{ __('Quotation') }}</span>
                    </a>
                </li>
                @endcan
@push('script')
<script>
    $(document).ready(function() {
        $('.has-submenu > a').on('click', function(e) {
            e.preventDefault();
            var $submenu = $(this).next('.submenu-list');
            var $icon = $(this).find('.fa-chevron-right');

            // Close other open submenus if needed (optional)
            // $('.submenu-list').not($submenu).slideUp().prev('a').find('.fa-chevron-right').removeClass('rotate-90');

            $submenu.slideToggle();
            $icon.toggleClass('rotate-90');
        });

        // Optional: Style for rotation
        $('<style>')
            .prop('type', 'text/css')
            .html('.rotate-90 { transform: rotate(90deg); } .transition { transition: transform 0.3s; }')
            .appendTo('head');

        // Keep active submenu open
        $('.submenu-list a.active').parents('.submenu-list').show().prev('a').find('.fa-chevron-right').addClass('rotate-90');
    });
</script>
@endpush
                @can('pages')
                <li>
                    <a href="{{ route('admin.pages.list') }}" class="d-flex align-items-center cg-10 {{ @$activePages }}">
                        <div class="d-flex"><i class="fas fa-file"></i></div>
                        <span class="">{{ __('Pages') }}</span>
                    </a>
                </li>
                @endcan

                <!-- Others -->
                <li class="sidebar-divider">
                    <p class="fs-10 fw-600 lh-12 text-para-text">{{ __('Others') }}</p>
                    <div class="d-flex"><img src="{{ asset('assets') }}/images/icon/double-angle-right.svg" alt="" /></div>
                </li>

                <li>
                    <a href="{{ route('admin.setting.profile.index') }}" class="d-flex align-items-center cg-10 {{ @$activeSetting }}">
                        <div class="d-flex"><i class="fas fa-cog"></i></div>
                        <span class="">{{ __('Settings') }}</span>
                    </a>
                </li>

                @can('email-template')
                <li>
                    <a href="{{ route('admin.setting.email-template') }}" class="d-flex align-items-center cg-10 {{ $activeEmailSetting ?? '' }}">
                        <div class="d-flex"><i class="fas fa-envelope-open-text"></i></div>
                        <span class="">{{ __('Email Template') }}</span>
                    </a>
                </li>
                @endcan

                @can('filemanager')
                <li>
                    <a href="{{ route('admin.unisharp.lfm.show') }}" class="d-flex align-items-center cg-10 {{ $activeFileManager ?? '' }}">
                        <div class="d-flex"><i class="fas fa-folder-open"></i></div>
                        <span class="">{{ __('File Manager') }}</span>
                    </a>
                </li>
                @endcan

                @if(auth()->user()->role == USER_ROLE_ADMIN)
                <li>
                    <a href="{{ route('admin.setting.configuration-settings') }}" class="d-flex align-items-center cg-10 {{ $activeConfigurationSetting ?? '' }}">
                        <div class="d-flex"><i class="fas fa-tools"></i></div>
                        <span class="">{{ __('App Configuration') }}</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.file-version-update') }}" class="d-flex align-items-center cg-10 {{ $activeVersionUpdate ?? '' }}">
                        <div class="d-flex"><i class="fas fa-sync"></i></div>
                        <span class="">{{ __('Version Update') }}</span>
                    </a>
                </li>
                @endif
            </ul>

            <a href="{{ route('logout') }}" class="d-inline-flex align-items-center cg-15 pt-17 pb-30 px-25">
                <img src="{{ asset('assets/images/icon/logout.svg') }}" alt="" />
                <p class="fs-15 fw-500 lh-18 text-para-text">{{ __('Logout') }}</p>
            </a>
        </div>
    </div>
</div>
