@props(['name'])
<svg {{ $attributes->merge(['class' => 'h-5 w-5', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '1.8', 'viewBox' => '0 0 24 24', 'aria-hidden' => 'true']) }}>
@switch($name)
@case('dashboard')<path stroke-linecap="round" stroke-linejoin="round" d="M4 4h6v6H4V4zm10 0h6v6h-6V4zM4 14h6v6H4v-6zm10 0h6v6h-6v-6z"/>@break
@case('communication')<path stroke-linecap="round" stroke-linejoin="round" d="M3 6.75A2.25 2.25 0 015.25 4.5h13.5A2.25 2.25 0 0121 6.75v10.5a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 17.25V6.75zm.5-.75 7.3 5.1a2 2 0 002.4 0L20.5 6"/>@break
@case('companies')<path stroke-linecap="round" stroke-linejoin="round" d="M3.5 21h17M5 3h14v18M8 7h2m4 0h2M8 11h2m4 0h2M9 21v-5h6v5"/>@break
@case('usage')<path stroke-linecap="round" stroke-linejoin="round" d="M4 20v-7h4v7H4zm6-12h4v12h-4V8zm6-5h4v17h-4V3z"/>@break
@case('monitoring')<path stroke-linecap="round" stroke-linejoin="round" d="M3 12h4l2.2-5 4.1 10 2.2-5H21M4 21h16a1 1 0 001-1V4a1 1 0 00-1-1H4a1 1 0 00-1 1v16a1 1 0 001 1z"/>@break
@case('templates')<path stroke-linecap="round" stroke-linejoin="round" d="M6 3h8l4 4v14H6V3zm8 0v5h4M9 12h6m-6 4h6"/>@break
@case('invoices')<path stroke-linecap="round" stroke-linejoin="round" d="M6 3h12v18l-2-1.5-2 1.5-2-1.5-2 1.5-2-1.5L6 21V3zm3 5h6m-6 4h6m-6 4h3"/>@break
@case('profile')<path stroke-linecap="round" stroke-linejoin="round" d="M12 12a4 4 0 100-8 4 4 0 000 8zm-7 9a7 7 0 0114 0"/>@break
@case('website')<path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18zm0 0c2-2.4 3-5.4 3-9s-1-6.6-3-9m0 18c-2-2.4-3-5.4-3-9s1-6.6 3-9M3.5 9h17m-17 6h17"/>@break
@case('logout')<path stroke-linecap="round" stroke-linejoin="round" d="M14 8V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h7a2 2 0 002-2v-3m-3-4h10m0 0-3-3m3 3-3 3"/>@break
@case('chevron')<path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/>@break
@endswitch
</svg>
