<footer class="text-white overflow-hidden bg-linear-to-br from-primary-800 to-primary-600 relative">
    <div class="bg-body-bg dark:bg-body-bg-dark absolute start-1/2 -translate-x-1/2"
        style="width: 110%;height: 30px;border-bottom-left-radius: 50%;border-bottom-right-radius: 50%;"></div>
    <div class="pb-6 container max-w-7xl mx-auto" style="padding-top: calc(30px + 2rem);">
        <div class="md:flex md:gap-6 md:justify-evenly">
            <div>
                <x-logo theme="light" />
                <p class="mt-6">{{ get_option('description') }}</p>
            </div>
            <div>
                <h3 class="text-xl">{{ __('Site') }}</h3>
                @menu('footer1', [
                    'class' => 'nav vertical',
                    'atts' => [
                        'data-theme' => 'dark',
                    ],
                ])
            </div>
            <div>
                <h3 class="text-xl">{{ __('About') }}</h3>
                @menu('footer2', [
                    'class' => 'nav vertical',
                    'atts' => [
                        'data-theme' => 'dark',
                    ],
                ])
            </div>
            <div>
                <h3 class="text-xl">{{ __('Follow us') }}</h3>
                @menu('social', [
                    'class' => 'nav',
                    'atts' => [
                        'data-theme' => 'dark',
                    ],
                ])
            </div>
            <div>
                <h3 class="text-xl">{{ __('Subscribe') }}</h3>
                <x-subscribe-form class="pill sm" />
            </div>
        </div>
        <div class="text-white text-center pt-3 pb-3">
            {!! footer_copyrights() !!}
        </div>
    </div>
</footer>
<x-button-back-top class="bottom-4 end-4" />
