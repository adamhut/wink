@if (config('wink.logo'))
    <h2 class="mr-2 font-semibold font-serif" :class="{'hidden': hideLogoOnSmallScreens, 'sm:block': hideLogoOnSmallScreens}">
        <span class="text-light">MyBCR</span>
    </h2>
@else
    <h2 class="mr-2 font-semibold font-serif" :class="{'hidden': hideLogoOnSmallScreens, 'sm:block': hideLogoOnSmallScreens}">
        <span class="text-light">W</span>ink.
    </h2>
@endif