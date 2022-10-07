@include('menus.layouts.columnmenu',[
    'links' => [
        [
            'name' => 'Administration',
            'route' => 'adminpanel'
        ],
        [
            'name' => __('Users'),
            'route'   => 'adminpanel.users',
        ],
        [
            'name'=>__('Servers'),
            'route' => 'adminpanel.servers',
        ],
        [
            'name'=>__('Voting sites'),
            'route'=> 'adminpanel.votesites'
        ]
     ]
])
