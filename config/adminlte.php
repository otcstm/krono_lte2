<?php

use App\Shared\UserHelper;


return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | The default title of your admin panel, this goes into the title tag
    | of your page. You can override it per page with the title section.
    | You can optionally also specify a title prefix and/or postfix.
    |
    */

    'title' => 'Krono!',

    'title_prefix' => 'Krono! : ',

    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Logo
    |--------------------------------------------------------------------------
    |
    | This logo is displayed at the upper left corner of your admin panel.
    | You can use basic HTML here if you want. The logo has also a mini
    | variant, used for the mini side bar. Make it 3 letters or so
    |
    */

    'logo' => 'KRONO <b>!!</b>',

    // 'logo_mini' => '<b>A</b>LT',
    'logo_mini' => '<i class="fas fa-clock"></i>',

    /*
    |--------------------------------------------------------------------------
    | Skin Color
    |--------------------------------------------------------------------------
    |
    | Choose a skin color for your admin panel. The available skin colors:
    | blue, black, purple, yellow, red, and green. Each skin also has a
    | light variant: blue-light, purple-light, purple-light, etc.
    |
    */

    'skin' => 'yellow',

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Choose a layout for your admin panel. The available layout options:
    | null, 'boxed', 'fixed', 'top-nav'. null is the default, top-nav
    | removes the sidebar and places your menu in the top navbar
    |
    */

    'layout' => null,

    /*
    |--------------------------------------------------------------------------
    | Collapse Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we choose and option to be able to start with a collapsed side
    | bar. To adjust your sidebar layout simply set this  either true
    | this is compatible with layouts except top-nav layout option
    |
    */

    'collapse_sidebar' => false,

    /*
    |--------------------------------------------------------------------------
    | Control Sidebar (Right Sidebar)
    |--------------------------------------------------------------------------
    |
    | Here we have the option to enable a right sidebar.
    | When active, you can use @section('right-sidebar')
    | The icon you configured will be displayed at the end of the top menu,
    | and will show/hide de sidebar.
    | The slide option will slide the sidebar over the content, while false
    | will push the content, and have no animation.
    | You can also choose the sidebar theme (dark or light).
    | The right Sidebar can only be used if layout is not top-nav.
    |
    */

    'right_sidebar' => true,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Register here your dashboard, logout, login and register URLs.
    | This was automatically set on install, only change if you really need.
    | logout URL automatically sends a POST request in Laravel 5.3 or higher.
    | Set register_url to null if you don't want a register link.
    |
    */

    'dashboard_url' => 'home',

    'logout_url' => 'logout',

    'login_url' => 'login',

    'register_url' => null,

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Specify your menu items to display in the left sidebar. Each menu item
    | should have a text and a URL. You can also specify an icon from Font
    | Awesome. A string instead of an array represents a header in sidebar
    | layout. The 'can' is a filter on Laravel's built in Gate functionality.
    */

    'menu' => [
        [
            'text' => 'search',
            'search' => true,
        ],
        ['header' => 'admin_menu'],
        [
            'text' => 'blog',
            'url'  => 'admin/blog',
            'can'  => 'manage-blog',
        ],
        [
            'text' => 'manage_role',
            'url'  => '/admin/role',
            'icon' => 'fas fa-cogs',
            // 'can' => 'admin-nav-menu',
        ],
        [
            'text' => 'user_autho',
            'url'  => '/admin/staff/auth',
            'icon' => 'fas fa-cogs',
            // 'can' => 'admin-nav-menu',
        ],
        [
            'text' => 'User Management',
            'url'  => '/admin/staff',
            'icon' => 'fas fa-cogs',
            // 'can' => 'admin-nav-menu',
        ],
        [
            'text' => 'manage_wdays',
            'url'  => '/admin/workday',
            'icon' => 'fas fa-cogs',
            // 'can' => 'admin-nav-menu',
        ],
        [
          'text' => 'system_config',
          'icon' => 'fas fa-fw fa-tools',
          'submenu' => [
            [
                'text' => 'manage_role',
                'url'  => '/admin/role',
                'icon' => 'fas fa-lock-open',
                // 'can' => 'admin-nav-menu',
            ],
            [
                'text' => 'manage_company',
                'url'  => '/admin/company',
                'icon' => 'fas fa-briefcase',
                // 'can' => 'admin-nav-menu',
            ],
            [
                'text' => 'manage_state',
                'url' => '/admin/state/show',
                'icon' => 'fas fa-map-marked-alt',
                // 'can' => 'admin-nav-menu',
            ],
            [
                'text' => 'manage_psubarea',
                'url' => '/admin/psubarea',
                'icon' => 'fas fa-map-marked-alt',
                // 'can' => 'admin-nav-menu',
            ],
            [
                'text' => 'manage_wdays',
                'url'  => '/admin/workday',
                'icon' => 'far fa-calendar-alt',
                // 'can' => 'admin-nav-menu',
            ],
            [
                'text' => 'shift_template',
                'url'  => '/admin/shift_pattern',
                'icon' => 'far fa-calendar-alt',
                // 'can' => 'ot-nav-menu',
            ],
          ],
        ],
        [
          'text' => 'admin_user_menu',
          'icon' => 'fas fa-fw fa-wheelchair',
          'submenu' => [
            [
                'text' => 'user_autho',
                'url'  => '/admin/staff/auth',
                'icon' => 'fas fa-key',
                // 'can' => 'admin-nav-menu',
            ],
            [
                'text' => 'User Management',
                'url'  => '/admin/staff',
                'icon' => 'far fa-folder-open',
                // 'can' => 'admin-nav-menu',
            ],
            [
                'text' => 'User Logs',
                //'url'  => route('state.show', [], false),
                'url' => '/log/listUserLogs',
                'icon' => 'fas fa-user-secret',
                // 'can' => 'admin-nav-menu',
            ],
          ]
        ],
        [
            'text' => 'User Logs',
            //'url'  => route('state.show', [], false),
            'url' => '/log/listUserLogs',
            'icon' => 'fas fa-cogs',
            // 'can' => 'admin-nav-menu',
        ],
        [ 'header' => 'user_menu',
          // 'can' => 'user-nav-menu',
        ],
        [
            'text' => 'user_home',
            'url'  => '/home',
            'icon' => 'fas fa-home',
            // 'can' => 'user-nav-menu',
        ],
        [
            'text' => 'user_punch',
            'url'  => '/punch',
            'icon' => 'fas fa-clock',
            // 'can' => 'user-nav-menu',
        ],
        [
            'text' => 'staff_list',
            'url'  => '/staff',
            'icon' => 'fas fa-tasks',
            // 'can' => 'ot-nav-menu',
        ],
        ['header' => 'ot_menu'],
        [
            'text' => 'ot_apply',
            'url'  => '#',
            'icon' => 'fas fa-business-time',
            // 'can' => 'ot-nav-menu',
          'text' => 'user_menu',
          'icon' => 'fas fa-fw fa-restroom',
          'submenu' => [
            [
                'text' => 'user_home',
                'url'  => '/home',
                'icon' => 'fas fa-home',
                // 'can' => 'user-nav-menu',
            ],
            [
                'text' => 'user_punch',
                'url'  => '/punch',
                'icon' => 'fas fa-clock',
                // 'can' => 'user-nav-menu',
            ],
          ]
        ],
        [
          'text' => 'ot_menu',
          'icon' => 'fas fa-business-time',
          'submenu' => [
            [
                'text' => 'ot_apply',
                'url'  => '#',
                'icon' => 'fas fa-edit',
                // 'can' => 'ot-nav-menu',
            ],
            [
                'text' => 'ot_history',
                'url'  => '#',
                'icon' => 'fas fa-business-time',
                // 'can' => 'ot-nav-menu',
            ],

            [
                'text' => 'ot_approve',
                'url'  => '#',
                'icon' => 'far fa-thumbs-up',
                'label'       => UserHelper::GetRequireAttCount(),
                'label_color' => 'warning',
                // 'can' => 'ot-nav-menu',
            ],
            [
                'text' => 'ot_verify',
                'url'  => '#',
                'icon' => 'fas fa-eye',
                'label'       => UserHelper::GetRequireAttCount(),
                'label_color' => 'warning',
                // 'can' => 'ot-nav-menu',
            ],
            [
                'text' => 'ot_list',
                'url'  => '#',
                'icon' => 'fas fa-tasks',
                'label_color' => 'warning',
                // 'can' => 'ot-nav-menu',
            ],
          ]
        ],
        [
          'text' => 'shift_menu',
          'icon' => 'far fa-calendar-alt',
          'submenu' => [
            [
                'text' => 'user_shift_sc',
                'url'  => '#',
                'icon' => 'fas fa-calendar-check',
                // 'can' => 'ot-nav-menu',
            ],
            [
                'text' => 'shift_plan',
                'url'  => '/shift_plan',
                'icon' => 'fas fa-calendar-plus',
                // 'can' => 'ot-nav-menu',
            ],
            [
                'text' => 'shift_group',
                'url'  => '/shift_plan/group',
                'icon' => 'fas fa-users',
                // 'can' => 'ot-nav-menu',
            ],
          ]
        ],
        [
            'text' => 'reassign_app',
            'url'  => '#',
            'icon' => 'fas fa-tasks',
            'label_color' => 'warning',
            // 'can' => 'ot-nav-menu',
        ],
        [
            'text' => 'reassign_ver',
            'url'  => '#',
            'icon' => 'fas fa-tasks',
            'label_color' => 'warning',
            // 'can' => 'ot-nav-menu',
        ],
        [
          'header' => 'shift_menu',
          // 'can' => 'admin-nav-menu',
        ],

      [
            'text' => 'shift_plan',
            'url'  => '#',
            'icon' => 'far fa-calendar-alt',
            'label_color' => 'warning',
            // 'can' => 'ot-nav-menu',
        ],
        [
              'text' => 'shift_template',
              'url'  => '#',
              'icon' => 'far fa-calendar-alt',
              'label_color' => 'warning',
              // 'can' => 'ot-nav-menu',
          ],

        [
          'header' => 'rpt_menu',
          'text' => 'user_setting',
          'icon' => 'fas fa-user-cog',
          'submenu' => [
            [
                'text' => 'reassign_app',
                'url'  => '#',
                'icon' => 'fas fa-user-check',
                // 'can' => 'ot-nav-menu',
            ],
            [
                'text' => 'reassign_ver',
                'url'  => '#',
                'icon' => 'fas fa-chalkboard-teacher',
                // 'can' => 'ot-nav-menu',
            ],
          ]
        ],
        [
            'text' => 'staff_list',
            'url'  => '/staff',
            'icon' => 'fas fa-tasks',
            // 'can' => 'ot-nav-menu',
        ],
        [
          'header' => 'information',
          // 'can' => 'rpt-nav-menu',
        ],
        [
            'text' => 'ot_report',
            'url'  => '#',
            'icon' => 'fas fa-print',
            // 'can' => 'rpt-nav-menu',
        ],
        [
            'text' => 'user_guide',
            'icon' => 'fas fa-info-circle',
            'submenu' => [
              [
                'text' => 'admin_guideline',
                'url'  => '#',
                'icon' => 'fas fa-user-ninja',
              ],
              [
                'text' => 'user_guideline',
                'url'  => '#',
                'icon' => 'fas fa-users',
              ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Choose what filters you want to include for rendering the menu.
    | You can add your own filters to this array after you've created them.
    | You can comment out the GateFilter if you don't want to use Laravel's
    | built in Gate functionality
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SubmenuFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Configure which JavaScript plugins should be included. At this moment,
    | DataTables, Select2, Chartjs and SweetAlert are added out-of-the-box,
    | including the Javascript and CSS files from a CDN via script and link tag.
    | Plugin Name, active status and files array (even empty) are required.
    | Files, when added, need to have type (js or css), asset (true or false) and location (string).
    | When asset is set to true, the location will be output using asset() function.
    |
    */

    'plugins' => [
        [
            'name' => 'Datatables',
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    // 'location' => '//cdn.datatables.net/v/bs/dt-1.10.18/datatables.min.js',
                    'location' => '//cdn.datatables.net/v/bs/dt-1.10.20/b-1.6.0/b-html5-1.6.0/fh-3.1.6/datatables.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    // 'location' => '//cdn.datatables.net/v/bs/dt-1.10.18/datatables.min.css',
                    'location' => '//cdn.datatables.net/v/bs/dt-1.10.20/b-1.6.0/b-html5-1.6.0/fh-3.1.6/datatables.min.css',
                ],

            ],
        ],
        [
            'name' => 'Select2',
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css',
                ],
            ],
        ],
        [
            'name' => 'Chartjs',
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js',
                ],
            ],
        ],
        [
            'name' => 'Sweetalert2',
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@8',
                ],
            ],
        ],
        [
            'name' => 'Pace',
            'active' => true,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                ],
            ],
        ],
    ],
];
