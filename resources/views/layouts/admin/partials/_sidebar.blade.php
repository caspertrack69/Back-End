<style>
    .nav-sub{
        background: #014F5B!important;
    }
</style>

<div id="sidebarMain" class="d-none">
    <aside
        class="js-navbar-vertical-aside navbar navbar-vertical-aside navbar-vertical navbar-vertical-fixed navbar-expand-xl navbar-bordered  ">
        <div class="navbar-vertical-container">
            <div class="navbar-brand-wrapper justify-content-between">
                <!-- Logo -->
                @php($restaurant_logo=\App\CentralLogics\helpers::get_business_settings('logo'))
                <a class="navbar-brand" href="{{route('admin.dashboard')}}" aria-label="Front">
                    <img class="navbar-brand-logo" style="max-height: 55px; border-radius: 8px;max-width: 100%!important;"
                         onerror="this.src='{{asset('public/assets/admin/img/1920x400/img2.jpg')}}'"
                         src="{{asset('storage/app/public/business/'.$restaurant_logo)}}"
                         alt="Logo">
                    <img class="navbar-brand-logo-mini" style="max-height: 55px; border-radius: 8px;max-width: 100%!important;"
                         onerror="this.src='{{asset('public/assets/admin/img/160x160/img2.jpg')}}'"
                         src="{{asset('storage/app/public/business/'.$restaurant_logo)}}" alt="Logo">
                </a>
                <!-- End Logo -->

                <!-- Navbar Vertical Toggle -->
                <button type="button"
                        class="js-navbar-vertical-aside-toggle-invoker navbar-vertical-aside-toggle btn btn-icon btn-xs btn-ghost-dark">
                    <i class="tio-clear tio-lg"></i>
                </button>
                <!-- End Navbar Vertical Toggle -->
            </div>

            <!-- Content -->
            <div class="navbar-vertical-content" style="background-color: #014F5B;">
                <ul class="navbar-nav navbar-nav-lg nav-tabs">
                    <!-- Dashboards -->
                    <li class="navbar-vertical-aside-has-menu {{Request::is('admin')?'show':''}}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link"
                           href="{{route('admin.dashboard')}}" title="{{translate('dashboard')}}">
                            <i class="tio-home-vs-1-outlined nav-icon"></i>
                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                {{translate('dashboard')}}
                            </span>
                        </a>
                    </li>
                    <!-- End Dashboards -->

                    {{-- Users section --}}
                    <li class="nav-item">
                        <small class="nav-subtitle"
                               title="{{translate('account')}} {{translate('section')}}">{{translate('account')}} {{translate('management')}}</small>
                        <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                    </li>
                    <li class="navbar-vertical-aside-has-menu {{Request::is('admin/emoney*')?'active':''}}">
                        <a class="nav-link " href="{{route('admin.emoney.index')}}"
                           title="{{translate('EMoney')}}">
                            <i class="tio-money nav-icon"></i>
                            <span class="text-truncate">{{translate('E-Money')}}</span>
                        </a>
                    </li>

                    <li class="navbar-vertical-aside-has-menu {{Request::is('admin/transfer*')?'active':''}}">
                        <a class="nav-link " href="{{route('admin.transfer.index')}}"
                           title="{{translate('transfer')}}">
                            <i class="tio-users-switch nav-icon"></i>
                            <span class="text-truncate">{{translate('Transfer')}}</span>
                        </a>
                    </li>

                    <li class="navbar-vertical-aside-has-menu {{Request::is('admin/transaction/index')?'active':''}}">
                        <a class="nav-link " href="{{route('admin.transaction.index')}}"
                           title="{{translate('transaction')}}">
                            <i class="tio-money-vs nav-icon"></i>
                            <span class="text-truncate">{{translate('Transactions')}}</span>
                        </a>
                    </li>

                    <li class="navbar-vertical-aside-has-menu {{Request::is('admin/transaction/request-money')?'active':''}}">
                        <a class="nav-link " href="{{route('admin.transaction.request_money')}}"
                           title="{{translate('Agent Request Money')}}">
                            <i class="tio-pound-outlined nav-icon"></i>
                            <span class="text-truncate">{{translate('Agent Request Money')}}</span>
                        </a>
                    </li>


                    {{-- Users section --}}
                    <li class="nav-item">
                        <small class="nav-subtitle"
                               title="{{translate('user')}} {{translate('section')}}">{{translate('user')}} {{translate('management')}}</small>
                        <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                    </li>

                    {{-- agent --}}
                    <li class="navbar-vertical-aside-has-menu {{Request::is('admin/agent*')?'active':''}}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:"
                        >
                            <i class="tio-user-big-outlined nav-icon"></i>
                            <span
                                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('agent')}}</span>
                        </a>
                        <ul class="js-navbar-vertical-aside-submenu nav nav-sub" style="display: {{Request::is('admin/agent*')?'block':'none'}}">
                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/agent/add')?'active':''}}">
                                <a class="nav-link " href="{{route('admin.agent.add')}}"
                                   title="{{translate('add')}}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate">{{translate('register')}}</span>
                                </a>
                            </li>
                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/agent/list')?'active':''}}">
                                <a class="nav-link " href="{{route('admin.agent.list')}}"
                                   title="{{translate('list')}}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate">{{translate('list')}}</span>
                                </a>
                            </li>
                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/agent/kyc-requests')?'active':''}}">
                                <a class="nav-link " href="{{route('admin.agent.kyc_requests')}}"
                                   title="{{translate('Verification Requests')}}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate">{{translate('Verification Requests')}}</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    {{-- customer --}}
                    <li class="navbar-vertical-aside-has-menu {{Request::is('admin/customer*')?'active':''}}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:"
                        >
                            <i class="tio-group-senior nav-icon"></i>
                            <span
                                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('customer')}}</span>
                        </a>
                        <ul class="js-navbar-vertical-aside-submenu nav nav-sub" style="display: {{Request::is('admin/customer*')?'block':'none'}}">
                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/customer/add')?'active':''}}">
                                <a class="nav-link " href="{{route('admin.customer.add')}}"
                                   title="{{translate('add')}}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate">{{translate('register')}}</span>
                                </a>
                            </li>
                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/customer/list')?'active':''}}">
                                <a class="nav-link " href="{{route('admin.customer.list')}}"
                                   title="{{translate('list')}}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate">{{translate('list')}}</span>
                                </a>
                            </li>
                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/customer/kyc-requests')?'active':''}}">
                                <a class="nav-link " href="{{route('admin.customer.kyc_requests')}}"
                                   title="{{translate('Verification Requests')}}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate">{{translate('Verification Requests')}}</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <small class="nav-subtitle"
                               title="{{translate('Business')}} {{translate('section')}}">{{translate('business')}} {{translate('management')}}</small>
                        <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                    </li>

                    <li class="nav-item {{Request::is('admin/banner*')?'active':''}}">
                        <a class="nav-link "
                           href="{{route('admin.banner.index')}}">
                            <span class="tio-image nav-icon"></span>
                            <span class="text-truncate">{{translate('Banner')}}</span>
                        </a>
                    </li>

                    <li class="nav-item {{Request::is('admin/helpTopic/list')?'active':''}}">
                        <a class="nav-link" href="{{route('admin.helpTopic.list')}}">
                            <span class="tio-bookmark-outlined nav-icon"></span>
                            <span class="text-truncate">{{translate('faq')}}</span>
                        </a>
                    </li>

                    <li class="nav-item {{Request::is('admin/purpose*')?'active':''}}">
                        <a class="nav-link "
                           href="{{route('admin.purpose.index')}}">
                            <span class="tio-add-square-outlined nav-icon"></span>
                            <span class="text-truncate">{{translate('Purpose')}}</span>
                        </a>
                    </li>

                    <li class="nav-item {{Request::is('admin/linked-website')?'active':''}}">
                        <a class="nav-link "
                           href="{{route('admin.linked-website')}}">
                            <span class="tio-website nav-icon"></span>
                            <span class="text-truncate">{{translate('Linked Website')}}</span>
                        </a>
                    </li>

                    <!-- Pages -->
                    <li class="navbar-vertical-aside-has-menu {{Request::is('admin/notification*')?'active':''}}">
                        <a class="js-navbar-vertical-aside-menu-link nav-link"
                           href="{{route('admin.notification.add-new')}}"
                        >
                            <i class="tio-notifications nav-icon"></i>
                            <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{translate('send')}} {{translate('notification')}}
                                </span>
                        </a>
                    </li>
                    <!-- End Pages -->

                    <!-- Pages -->
                    <li class="navbar-vertical-aside-has-menu {{Request::is('admin/business-settings*')?'active':''}} mb-4">
                        <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:"
                        >
                            <i class="tio-settings nav-icon"></i>
                            <span
                                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{translate('settings')}}</span>
                        </a>
                        <ul class="js-navbar-vertical-aside-submenu nav nav-sub" style="display: {{Request::is('admin/business-settings*')?'block':'none'}}">
                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/business-settings/business-setup')?'active':''}}">
                                <a class="nav-link " href="{{route('admin.business-settings.business-setup')}}"
                                   title="{{translate('business')}} {{translate('setup')}}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate">{{translate('business')}} {{translate('setup')}}</span>
                                </a>
                            </li>
                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/business-settings/sms-module')?'active':''}}">
                                <a class="nav-link " href="{{route('admin.business-settings.sms-module')}}"
                                   title="{{translate('sms')}} {{translate('module')}}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate">{{translate('sms')}} {{translate('module')}}</span>
                                </a>
                            </li>
                            <li class="nav-item {{Request::is('admin/business-settings/payment-method')?'active':''}}">
                                <a class="nav-link " href="{{route('admin.business-settings.payment-method')}}"
                                >
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span
                                        class="text-truncate">{{translate('payment')}} {{translate('methods')}}</span>
                                </a>
                            </li>
                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/business-settings/fcm-index')?'active':''}}">
                                <a class="nav-link " href="{{route('admin.business-settings.fcm-index')}}"
                                   title="{{translate('push')}} {{translate('notification')}}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span
                                        class="text-truncate">{{translate('notification')}} {{translate('settings')}}</span>
                                </a>
                            </li>

                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/business-settings/app-settings')?'active':''}}">
                                <a class="nav-link " href="{{route('admin.business-settings.app_settings')}}"
                                   title="{{translate('App Settings')}}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span
                                        class="text-truncate">{{translate('App Settings')}}</span>
                                </a>
                            </li>

                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/business-settings/recaptcha_index*')?'active':''}}">
                                <a class="nav-link " href="{{route('admin.business-settings.recaptcha_index')}}"
                                   title="{{translate('languages')}}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate">{{translate('reCaptcha')}}</span>
                                </a>
                            </li>

                            <li class="navbar-vertical-aside-has-menu {{Request::is('admin/business-settings/language*')?'active':''}}">
                                <a class="nav-link " href="{{route('admin.business-settings.language.index')}}"
                                   title="{{translate('languages')}}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate">{{translate('languages')}}</span>
                                </a>
                            </li>

                            <li class="nav-item {{Request::is('admin/business-settings/terms-and-conditions')?'active':''}}">
                                <a class="nav-link "
                                   href="{{route('admin.business-settings.terms-and-conditions')}}"
                                >
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate">{{translate('terms_and_condition')}}</span>
                                </a>
                            </li>

                            <li class="nav-item {{Request::is('admin/business-settings/privacy-policy')?'active':''}}">
                                <a class="nav-link "
                                   href="{{route('admin.business-settings.privacy-policy')}}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate">{{translate('privacy_policy')}}</span>
                                </a>
                            </li>

                            <li class="nav-item {{Request::is('admin/business-settings/about-us')?'active':''}}">
                                <a class="nav-link "
                                   href="{{route('admin.business-settings.about-us')}}">
                                    <span class="tio-circle nav-indicator-icon"></span>
                                    <span class="text-truncate">{{translate('about_us')}}</span>
                                </a>
                            </li>

                        </ul>
                    </li>
                    <!-- End Pages -->
                </ul>
            </div>
            <!-- End Content -->
        </div>
    </aside>
</div>

<div id="sidebarCompact" class="d-none">

</div>
