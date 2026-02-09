@extends('user.layouts.app')
@push('title')
    {{$pageTitle}}
@endpush
@push('style')
    <style>

        /* old style start======================================================================== */
        /* .order-take-part-content { */
            /* box-shadow: 0 2px 10px rgb(0 0 0 / 10%);
            padding: 25px 20px;
            box-sizing: border-box;
            border-radius: 10px; */
            /* color: #333; */
        /* } */

        /* .order-take-part-content h3 {
            font-size: 20px;
            padding-bottom: 40px;
        }

        .row .col-lg-3:last-child .order-take-part-item {
            border-right: none;
        }

        .order-take-part-item {
            text-align: center;
            border-right: 1px solid #dadada;
            padding: 0 10px;
            text-align: center;
            display: flex;
            flex-direction: column;
            gap: 10px;
        } */

        /* .order-take-part-item a {
            width: 100%;
        } */

        /* .order-take-part-item p {
            font-size: 17px;
            line-height: 28px;
            padding: 15px 0;
            font-weight: 500;
        }

        .order-take-part-item h4 {
            font-size: 19px;
            font-weight: 700;
            color: #0073ac;

        }

        .order-take-part-item h4:hover {
            text-decoration: underline;
        } */


        /* media query */
        /* @media(max-width: 1199px) {

            .order-take-part-item p {
                font-size: 14px;
                line-height: 20px;
                padding: 10px 0;
                font-weight: 500;
            }

            .order-take-part-item h4 {
                font-size: 16px;
                font-weight: 700;
                color: #0073ac;
            }

            .order-take-part-content .col-lg-3.col-sm-6{
                padding-top:50px;
            }

        } */

         /* media query */
         /* @media(max-width: 666.99px) {
            .order-take-part-item{
                border-right: none;
            }

            .order-take-part-content h3 {
                font-size: 20px;
                padding-bottom: 0px;
            }

         } */
        /* old style end========================================================================  */

        /* * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        } */

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            /* min-height: 100vh;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: center; */
        }

        .container {
            max-width: 1377px;
            width: 100%;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .header {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 20px 20px 0 0;
            padding: 40px;
            text-align: center;
            box-shadow: 0 -5px 20px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            color: #667eea;
            font-size: 2.5em;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .header p {
            color: #666;
            font-size: 1.1em;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            padding: 40px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px 20px 20px 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .dashboard-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%);
            border-radius: 15px;
            padding: 30px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
        }

        .dashboard-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .dashboard-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.3);
            border-color: #667eea;
        }

        .dashboard-card:hover::before {
            opacity: 1;
        }

        .card-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2em;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
            transition: all 0.3s ease;
        }

        .dashboard-card:hover .card-icon {
            transform: rotate(5deg) scale(1.1);
        }

        .card-title {
            font-size: 1.4em;
            color: #333;
            margin-bottom: 10px;
            font-weight: 600;
            position: relative;
            z-index: 1;
        }

        .card-description {
            color: #666;
            font-size: 0.95em;
            line-height: 1.6;
            position: relative;
            z-index: 1;
        }

        .user-info {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 15px 25px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
        }

        .user-info .welcome {
            font-size: 1.1em;
            font-weight: 500;
        }

        .user-info .logout-btn {
            background: rgba(255, 255, 255, 0.3);
            border: none;
            padding: 8px 20px;
            border-radius: 20px;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .user-info .logout-btn:hover {
            background: rgba(255, 255, 255, 0.5);
            transform: scale(1.05);
        }

        /* Color variations for different cards */
        .card-1 .card-icon { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .card-2 .card-icon { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .card-3 .card-icon { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
        .card-4 .card-icon { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
        .card-5 .card-icon { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }

        @media (max-width: 768px) {
            .header h1 {
                font-size: 2em;
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
                padding: 20px;
            }

            .user-info {
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }
        }

        .stats-bar {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            padding: 0 40px 30px 40px;
            background: rgba(255, 255, 255, 0.95);
        }

        .stat-item {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }

        .stat-number {
            font-size: 2em;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 0.9em;
            opacity: 0.9;
        }
    </style>
@endpush
@section('content')
    {{-- <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
        <div class="home-section"> --}}
            <!--  -->
            {{-- <div class="d-flex align-items-center cg-5 pb-26">
                <h4 class="fs-24 fw-600 lh-29 text-title-black">{{__('Hey')}}, {{auth()->user()->name}}</h4>
                <span class="d-flex"><img src="{{asset('assets/images/icon/hand-wave.svg')}}" alt=""/></span>
            </div> --}}
            <!--  -->
            {{-- <div class="mb-30 bd-one bd-c-stroke bd-ra-10 p-30 bg-white">
                <div class="count-item-one">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-12 d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title">Special title treatment</h5>
                                <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                            </div>
                            <a href="{{route('user.orders.send.form')}}" class="btn btn-primary">Order</a>
                        </div>
                    </div>
                </div>
            </div> --}}
            {{-- @if(auth()->user()->role != USER_ROLE_REVIEWER)
            <section class="">
                <div class="mb-30 bd-one bd-c-stroke bd-ra-10 p-30 bg-white">

                        <div class="order-take-part-content">

                            <h3>{{ __('Take Part in AJSRP') }}</h3>

                            <div class="row">

                                <!-- order-take-part-item -->
                                <div class="col-lg-3 col-sm-6">

                                    <div class="order-take-part-item">
                                        <div class="img">
                                            <img src="https://sso.sciencepg.com/img/icon1.png" alt="">
                                        </div>

                                        <div class="text">

                                            <p>
                                            {{ __('Rigorous, constructive, transparent and fast peer review') }}
                                            </p>

                                            <a href="{{ route('user.submission.select-a-journal', ['by' => 'by-subject']) }}" target="_blank" class="text-decoration-none">
                                                <h4 class="m-0">
                                                {{ __('Submit My Order') }}
                                                    <i class="fas fa-external-link-alt"></i>
                                                </h4>
                                            </a>
                                        </div>
                                    </div>

                                </div>

                                <!-- order-take-part-item -->
                                <div class="col-lg-3 col-sm-6">

                                    <div class="order-take-part-item">
                                        <div class="img">
                                            <img src="https://sso.sciencepg.com/img/icon1.png" alt="">
                                        </div>

                                        <div class="text">

                                            <p>
                                            {{ __('Receive early feedback on interest and feasibility of your research') }}
                                            </p>
                                            <a href="{{route('user.orders.send.form')}}" target="_blank" class="text-decoration-none">
                                                <h4>
                                                {{ __('Submit My Abstract') }}
                                                    <i class="fas fa-external-link-alt"></i>
                                                </h4>
                                            </a>

                                        </div>
                                    </div>

                                </div>

                                <!-- order-take-part-item -->
                                <div class="col-lg-3 col-sm-6">

                                    <div class="order-take-part-item">
                                        <div class="img">
                                            <img src="https://sso.sciencepg.com/img/icon1.png" alt="">
                                        </div>

                                        <div class="text">

                                            <p>
                                            {{ __('Highlight breakthroughs in your field on specific topic') }}
                                            </p>
                                            <a href="{{ route('user.join.application.editorial-board-member.index') }}" target="_blank" class="text-decoration-none">
                                                <h4>
                                                {{ __('Join as Editorial Board Member') }}
                                                    <i class="fas fa-external-link-alt"></i>
                                                </h4>
                                            </a>

                                        </div>
                                    </div>

                                </div>

                                <!-- order-take-part-item -->
                                <div class="col-lg-3 col-sm-6">

                                    <div class="order-take-part-item">
                                        <div class="img">
                                            <img src="https://sso.sciencepg.com/img/icon1.png" alt="">
                                        </div>

                                        <div class="text">

                                            <p>
                                            {{ __('Contribute to the peer review process of cutting-edge research') }}
                                            </p>
                                            <a href="{{ route('user.join.application.reviewer.index') }}" target="_blank" class="text-decoration-none">
                                                <h4>
                                                {{ __('Become a Reviewer') }}  
                                                    <i class="fas fa-external-link-alt"></i>
                                                </h4>
                                            </a>

                                        </div>
                                    </div>

                                </div>

                            </div>

                        </div>

                </div>
            </section>
            @endif
            <!--  -->
            @if(auth()->user()->role != USER_ROLE_REVIEWER)
            <div class="mb-30 bd-one bd-c-stroke bd-ra-10 p-30 bg-white">
                <div class="count-item-one">
                    <div class="row justify-content-xl-between rg-13">
                        <div class="col-sm-6 col-md-4 col-lg-3 col-xl-auto">
                            <div class="item d-flex flex-column flex-sm-row cg-13 rg-13">
                                <div
                                    class="icon w-48 h-48 bd-ra-8 flex-shrink-0 d-flex justify-content-center align-items-center bg-purple-10">
                                    <img src="{{asset('assets/images/icon/bag-dollar.svg')}}" alt=""/>
                                </div>
                                <div class="content">
                                    <h4 class="fs-15 fw-500 lh-18 text-para-text pb-5">{{__("Payment Pending")}}</h4>
                                    <p class="fs-18 fw-500 lh-21 text-title-black"><a href="{{route('user.client-invoice.list')}}">{{$paymentPending}}</a></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4 col-lg-3 col-xl-auto">
                            <div class="item d-flex flex-column flex-sm-row cg-13 rg-13">
                                <div
                                    class="icon w-48 h-48 bd-ra-8 flex-shrink-0 d-flex justify-content-center align-items-center bg-main-color-10">
                                    <img src="{{asset('assets/images/icon/user-multiple.svg')}}" alt=""/>
                                </div>
                                <div class="content">
                                    <h4 class="fs-15 fw-500 lh-18 text-para-text pb-5">{{__("Open Ticket")}}</h4>
                                    <p class="fs-18 fw-500 lh-21 text-title-black"><a href="{{route('user.ticket.list')}}">{{$openTicket}}</a></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4 col-lg-3 col-xl-auto">
                            <div class="item d-flex flex-column flex-sm-row cg-13 rg-13">
                                <div
                                    class="icon w-48 h-48 bd-ra-8 flex-shrink-0 d-flex justify-content-center align-items-center bg-color2-10">
                                    <img src="{{asset('assets/images/icon/user-multiple-2.svg')}}" alt=""/>
                                </div>
                                <div class="content">
                                    <h4 class="fs-15 fw-500 lh-18 text-para-text pb-5">{{__("Completed Ticket")}}</h4>
                                    <p class="fs-18 fw-500 lh-21 text-title-black"><a href="{{route('user.ticket.list')}}">{{$completedTicket}}</a></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4 col-lg-3 col-xl-auto">
                            <div class="item d-flex flex-column flex-sm-row cg-13 rg-13">
                                <div
                                    class="icon w-48 h-48 bd-ra-8 flex-shrink-0 d-flex justify-content-center align-items-center bg-green-10">
                                    <img src="{{asset('assets/images/icon/orders.svg')}}" alt=""/>
                                </div>
                                <div class="content">
                                    <h4 class="fs-15 fw-500 lh-18 text-para-text pb-5">{{__("Open Orders")}}</h4>
                                    <p class="fs-18 fw-500 lh-21 text-title-black"><a href="{{route('user.orders.list')}}">{{$openOrders}}</a></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4 col-lg-3 col-xl-auto">
                            <div class="item d-flex flex-column flex-sm-row cg-13 rg-13">
                                <div
                                    class="icon w-48 h-48 bd-ra-8 flex-shrink-0 d-flex justify-content-center align-items-center bg-color1-10">
                                    <img src="{{asset('assets/images/icon/receipt-check.svg')}}" alt=""/>
                                </div>
                                <div class="content">
                                    <h4 class="fs-15 fw-500 lh-18 text-para-text pb-5">{{__("Complete Orders")}}</h4>
                                    <p class="fs-18 fw-500 lh-21 text-title-black"><a href="{{route('user.orders.list')}}">{{$completedOrders}}</a></p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            @endif
            <!--  -->
            <!--  -->
            @if(auth()->user()->role != USER_ROLE_REVIEWER)
            <div class="row rg-20">
                <div class="col-lg-6">
                    <div class="p-25 bd-one bd-c-stroke bd-ra-10 bg-white">
                        <!-- Title -->
                        <div class="d-flex justify-content-between align-items-center g-10 pb-20">
                            <h4 class="fs-18 fw-500 lh-22 text-title-black">{{__("Order Summery")}}</h4>
                        </div>
                        <!-- Table -->
                        <table class="table zTable zTable-last-item-right" id="orderSummery">
                            <thead>
                            <tr>
                                <th>
                                    <div>{{__("Order ID")}}</div>
                                </th>
                                <th>
                                    <div>{{__("Working Status")}}</div>
                                </th>
                                <th>
                                    <div>{{__("Payment Status")}}</div>
                                </th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="p-25 bd-one bd-c-stroke bd-ra-10 bg-white">
                        <!-- Title -->
                        <div class="d-flex justify-content-between align-items-center g-10 pb-20">
                            <h4 class="fs-18 fw-500 lh-22 text-title-black">{{__('Ticket Summery')}}</h4>
                        </div>
                        <!-- Table -->
                        <table class="table zTable zTable-last-item-right" id="ticketSummery">
                            <thead>
                            <tr>
                                <th>
                                    <div>{{__('Ticket Id')}}</div>
                                </th>
                                <th>
                                    <div>{{__('Order ID')}}</div>
                                </th>
                                <th>
                                    <div>{{__('Status')}}</div>
                                </th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
             @endif --}}
       {{-- </div> 
    </div> --}}

    <div class="container">
        <div class="user-info">
            <div class="welcome">👋 {{__('Welcome back')}}, <strong>{{auth()->user()->name}}</strong></div>
            <button class="logout-btn" onclick="logout()">{{__('Logout')}}</button>
        </div>

        @if(auth()->user()->role != USER_ROLE_REVIEWER)
        {{-- <div class="header">
            <h1>📚 {{__('Welcome to Your Dashboard')}}</h1>
            <p>{{__('Please choose an option to continue')}}</p>
        </div>

        <div class="stats-bar">
            <div class="stat-item">
                <div class="stat-number">{{$totalActiveSubmissions}}</div>
                <div class="stat-label">{{__('Active Submissions')}}</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{$totalPublishedPapers}}</div>
                <div class="stat-label">{{__('Published Papers')}}</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{$totalPendingReviews}}</div>
                <div class="stat-label">{{__('Pending Reviews')}}</div>
            </div>
        </div> --}}

        <div class="dashboard-grid">
            <div class="dashboard-card card-1" onclick="navigate('submit-paper')">
                <div class="card-icon">📝</div>
                <div class="card-title">{{__('Submit a New Research Paper')}}</div>
                <div class="card-description">
                    {{__('Upload your latest research paper for peer review and publication. Supports multiple file formats.')}}
                </div>
            </div>

            <div class="dashboard-card card-2" onclick="navigate('submit-book')">
                <div class="card-icon">📖</div>
                <div class="card-title">{{__('Submit a Book')}}</div>
                <div class="card-description">
                    {{__('Submit your manuscript for book publication. We handle everything from editing to distribution.')}}
                </div>
            </div>

            <div class="dashboard-card card-3" onclick="navigate('editorial-services')">
                <div class="card-icon">✏️</div>
                <div class="card-title">{{__('Request Editorial or Other Services')}}</div>
                <div class="card-description">
                    {{__('Professional editing, proofreading, formatting, and consultation services for your work.')}}
                </div>
            </div>

            <div class="dashboard-card card-4" onclick="navigate('track-submissions')">
                <div class="card-icon">📊</div>
                <div class="card-title">{{__('Track Previous Submissions')}}</div>
                <div class="card-description">
                    {{__('Monitor the status of your submissions, view reviewer comments, and check publication timelines.')}}
                </div>
            </div>

            <div class="dashboard-card card-5" onclick="navigate('support-tickets')">
                <div class="card-icon">🎫</div>
                <div class="card-title">{{__('View or Follow Up on Support Tickets')}}</div>
                <div class="card-description">
                    {{__('Access your support requests, communicate with our team, and resolve any issues.')}}
                </div>
            </div>
        </div> 
        @endif
    </div>



    <input type="hidden" id="ticket-summery-route" value="{{route('user.dashboard')}}">
    <input type="hidden" id="order-summery-route" value="{{route('user.order-summery')}}">
@endsection

@push('script')
    <script>
    // window.translations = {
    //     emptyTable: @json(__('No data available in table')),
    //     searchPlaceholder: @json(__('Search event')),
    //     info: @json(__('Showing _START_ to _END_ of _TOTAL_ entries')),
    //     infoEmpty: @json(__('Showing 0 to 0 of 0 entries'))
    // };


    function navigate(section) {
            // Add a smooth animation before navigation
            event.currentTarget.style.transform = 'scale(0.95)';
            setTimeout(() => {
                // In a real application, you would navigate to the actual page
                // alert(`Navigating to: ${section.replace('-', ' ').toUpperCase()}`);
                let route = "{{route('user.dashboard')}}";
                if(section == 'support-tickets'){
                    route = "{{route('user.ticket.list')}}";
                }else if(section == 'track-submissions'){
                    route = "{{route('user.orders.list')}}";
                }else if(section == 'editorial-services'){
                    route = "{{route('user.services.list')}}";
                }else if(section == 'submit-book'){
                    route = "{{route('user.submit-your-book.index')}}";
                }else if(section == 'submit-paper'){
                    route = "{{route('user.submission.select-a-journal', ['by' => 'by-subject'])}}";
                }else{
                    route = "{{route('user.dashboard')}}";
                }

                window.location.href = route;

            }, 200);
        }

        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                // In a real application, you would handle the logout process
                // alert('Logging out...');
                window.location.href = "{{ route('logout') }}";
            }
        }

        // Add smooth entrance animation
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.dashboard-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
    <script src="{{ asset('user/custom/js/user-dashboard.js') }}"></script>
@endpush

