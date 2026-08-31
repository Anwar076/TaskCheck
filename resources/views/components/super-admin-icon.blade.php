@props(['name'])
<svg {{ $attributes->merge(['class' => 'h-5 w-5', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '1.75', 'viewBox' => '0 0 24 24', 'aria-hidden' => 'true']) }}>
@switch($name)
@case('dashboard')<path stroke-linecap="round" stroke-linejoin="round" d="M4.5 3.75h5.25V9H4.5V3.75zm9.75 0h5.25V9h-5.25V3.75zM4.5 15h5.25v5.25H4.5V15zm9.75 0h5.25v5.25h-5.25V15z"/>@break
@case('communication')<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75A2.25 2.25 0 016 4.5h12a2.25 2.25 0 012.25 2.25v10.5A2.25 2.25 0 0118 19.5H6a2.25 2.25 0 01-2.25-2.25V6.75zM4.5 6l6.18 4.326a2.25 2.25 0 002.64 0L19.5 6"/>@break
@case('companies')<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M5.25 21V5.25A2.25 2.25 0 017.5 3h9a2.25 2.25 0 012.25 2.25V21M8.25 7.5h1.5m4.5 0h1.5m-7.5 4.5h1.5m4.5 0h1.5m-6 9v-4.5h4.5V21"/>@break
@case('users')<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6.75a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a7.5 7.5 0 0115 0M18 9.75a3 3 0 012.25 5M6 9.75a3 3 0 00-2.25 5"/>@break
@case('usage')<path stroke-linecap="round" stroke-linejoin="round" d="M4.5 20.25v-6.5h3.75v6.5H4.5zm5.625 0V9.5h3.75v10.75h-3.75zm5.625 0V4.25h3.75v16h-3.75z"/>@break
@case('monitoring')<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h4l2.25-5.25L14 17.25 16.25 12h4M5.25 3.75h13.5a1.5 1.5 0 011.5 1.5v13.5a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5V5.25a1.5 1.5 0 011.5-1.5z"/>@break
@case('templates')<path stroke-linecap="round" stroke-linejoin="round" d="M7.5 3.75h7.25l3.75 3.75v12.75H7.5V3.75zm7.25 0V7.5h3.75M10.5 12h5m-5 3.75h5M5.25 6v15h10.5"/>@break
@case('invoices')<path stroke-linecap="round" stroke-linejoin="round" d="M6 3.75h12v16.5l-2.25-1.5-2.25 1.5-2.25-1.5L9 20.25l-3-2V3.75zM9 8.25h6m-6 3.75h6m-6 3.75h3"/>@break
@case('subscriptions')<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75A2.25 2.25 0 016 4.5h12a2.25 2.25 0 012.25 2.25v10.5A2.25 2.25 0 0118 19.5H6a2.25 2.25 0 01-2.25-2.25V6.75zM3.75 9h16.5M7.5 15.75h3.75"/>@break
@case('profile')<path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18zm3.75-5.25a5.25 5.25 0 00-7.5 0M15 9a3 3 0 11-6 0 3 3 0 016 0z"/>@break
@case('website')<path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18zm0 0c2-2.4 3-5.4 3-9s-1-6.6-3-9m0 18c-2-2.4-3-5.4-3-9s1-6.6 3-9M3.5 9h17m-17 6h17"/>@break
@case('logout')<path stroke-linecap="round" stroke-linejoin="round" d="M14 8V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h7a2 2 0 002-2v-3m-3-4h10m0 0-3-3m3 3-3 3"/>@break
@case('chevron')<path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/>@break
@endswitch
</svg>
