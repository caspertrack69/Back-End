<div class="footer">
    <div class="row justify-content-between align-items-center">
        <div class="col">
            <p class="font-size-sm mb-0">
                &copy; {{\App\CentralLogics\Helpers::get_business_settings('business_name')}}. <span
                    class="d-none d-sm-inline-block">{{\App\CentralLogics\Helpers::get_business_settings('footer_text')}}</span>
            </p>
        </div>
        <div class="col-auto">
            <div class="d-flex justify-content-end">
                <!-- List Dot -->
                <ul class="list-inline list-separator">
                    <li class="list-inline-item">
                        <a class="list-separator-link" href="{{ route('admin.business-settings.business-setup') }}">{{translate('business')}} {{translate('setup')}}</a>
                    </li>

                    <li class="list-inline-item">
                        <a class="list-separator-link" href="{{ route('admin.settings') }}">{{translate('profile')}}</a>
                    </li>

                    <li class="list-inline-item">
                        <!-- Keyboard Shortcuts Toggle -->
                        <div class="hs-unfold">
                            <a class="js-hs-unfold-invoker btn btn-icon btn-ghost-secondary rounded-circle"
                               href="{{route('admin.dashboard')}}">
                                <i class="tio-home-outlined"></i>
                            </a>
                        </div>
                        <!-- End Keyboard Shortcuts Toggle -->
                    </li>
                </ul>
                <!-- End List Dot -->
            </div>
        </div>
    </div>
</div>
