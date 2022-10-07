@include('menus.layouts.horizontal-menu',[
    'links' => [
        [
            'name' => __('Home'),
            'route' => 'home'
        ],
        [
            'name'=> __('Profile'),
            'route'=>'home.profile'
        ]
     ]
])
