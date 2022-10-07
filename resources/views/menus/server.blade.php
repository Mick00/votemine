@include('menus.layouts.horizontal-menu',[
    'links' => [
        [
            'name' => __('Info'),
            'route' => 'server.edit',
        ],
        [
            'name' => __('Voting sites'),
            'route' => 'server.site',
        ],
        [
            'name' => __('Votifier'),
            'route' => 'server.votifier',
        ],
        [
            'name' => __('API'),
            'route' => 'server.token',
        ],
     ]
])
