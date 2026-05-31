<div class="p-6 lg:p-8 bg-white border-b border-gray-200">
    <x-application-logo class="block h-12 w-auto" />

    <h1 class="mt-8 text-2xl font-medium text-gray-900">
        Welcome to your Inventory Management System!
    </h1>

    <p class="mt-6 text-gray-500 leading-relaxed">
        This system is designed to help you manage your warehouse locations, zones, aisles, and bins efficiently. 
        Track your products and monitor stock levels across multiple warehouses with ease.
    </p>
</div>

<div class="bg-gray-200 bg-opacity-25 grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 p-6 lg:p-8">
    <div>
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="size-6 stroke-gray-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205l3 1m1.5-10.5l-22.5 9.041" />
            </svg>
            <h2 class="ms-3 text-xl font-semibold text-gray-900">
                <a href="{{ route('warehouses.index') }}">Warehouse Management</a>
            </h2>
        </div>

        <p class="mt-4 text-gray-500 text-sm leading-relaxed">
            Manage your physical warehouse locations. Define zones, aisles, and bins to organize your inventory effectively.
        </p>
    </div>

    <div>
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="size-6 stroke-gray-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
            </svg>
            <h2 class="ms-3 text-xl font-semibold text-gray-900">
                Inventory Tracking
            </h2>
        </div>

        <p class="mt-4 text-gray-500 text-sm leading-relaxed">
            Quickly locate items using barcode scanning and real-time location tracking. Keep your stock levels accurate and up-to-date.
        </p>
    </div>

    <div>
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="size-6 stroke-gray-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
            </svg>
            <h2 class="ms-3 text-xl font-semibold text-gray-900">
                <a href="{{ route('users.index') }}">User Permissions</a>
            </h2>
        </div>

        <p class="mt-4 text-gray-500 text-sm leading-relaxed">
            Control access to sensitive inventory data using robust role-based permissions. Assign staff to specific warehouses or zones.
        </p>
    </div>

    <div>
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="size-6 stroke-gray-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z" />
            </svg>
            <h2 class="ms-3 text-xl font-semibold text-gray-900">
                Activity Logs
            </h2>
        </div>

        <p class="mt-4 text-gray-500 text-sm leading-relaxed">
            Every change is tracked. Monitor who moved what and when with comprehensive activity logging for full auditability.
        </p>
    </div>
</div>
