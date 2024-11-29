<?php

namespace Database\Seeders;

use App\Models\Menu; // Correct import
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->datas() as $key => $value) {
            $this->createMenu($value);
        }
    }

    private function createMenu($data, $parent_id = null)
    {
        $menu = new Menu([
            'name' => $data['name'],
            'icon' => $data['icon'],
            'route' => $data['route'],
            'description' => $data['description'],
            'sorting' => $data['sorting'],
            'parent_id' => $parent_id,
            'permission_name' => $data['permission_name'],
            'status' => $data['status'],
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ]);

        $menu->save();

        if (isset($data['children']) && is_array($data['children'])) {
            foreach ($data['children'] as $child) {
                $this->createMenu($child, $menu->id);
            }
        }
    }

    private function datas()
    {
        return [
            [
                'name' => 'Dashboard',
                'icon' => 'home',
                'route' => 'backend.dashboard',
                'description' => null,
                'sorting' => 1,
                'permission_name' => 'dashboard',
                'status' => 'Active',
            ],
            [
                'name' => 'User Manage',
                'icon' => 'list',
                'route' => null,
                'description' => null,
                'sorting' => 1,
                'permission_name' => 'user-management',
                'status' => 'Active',
                'children' => [
                    [
                        'name' => 'User Add',
                        'icon' => 'plus-circle',
                        'route' => 'backend.admin.create',
                        'description' => null,
                        'sorting' => 1,
                        'permission_name' => 'Admin-add',
                        'status' => 'Active',
                    ],
                    [
                        'name' => 'User List',
                        'icon' => 'list',
                        'route' => 'backend.admin.index',
                        'description' => null,
                        'sorting' => 1,
                        'permission_name' => 'Admin-list',
                        'status' => 'Active',
                    ],
                ],
            ],

            [
                'name' => 'Event Manage',
                'icon' => 'list',
                'route' => null,
                'description' => null,
                'sorting' => 1,
                'permission_name' => 'event-management',
                'status' => 'Active',
                'children' => [
                    [
                        'name' => 'Event Add',
                        'icon' => 'plus-circle',
                        'route' => 'backend.event.create',
                        'description' => null,
                        'sorting' => 1,
                        'permission_name' => 'Event-add',
                        'status' => 'Active',
                    ],
                    [
                        'name' => 'Event List',
                        'icon' => 'list',
                        'route' => 'backend.event.index',
                        'description' => null,
                        'sorting' => 1,
                        'permission_name' => 'Event-list',
                        'status' => 'Active',
                    ],
                ],
            ],

            [
                'name' => 'Booking Manage',
                'icon' => 'list',
                'route' => null,
                'description' => null,
                'sorting' => 1,
                'permission_name' => 'booking-management',
                'status' => 'Active',
                'children' => [
                    [
                        'name' => 'Booking Add',
                        'icon' => 'plus-circle',
                        'route' => 'backend.booking.create',
                        'description' => null,
                        'sorting' => 1,
                        'permission_name' => 'booking-add',
                        'status' => 'Active',
                    ],
                    [
                        'name' => 'Booking List',
                        'icon' => 'list',
                        'route' => 'backend.booking.index',
                        'description' => null,
                        'sorting' => 1,
                        'permission_name' => 'booking-list',
                        'status' => 'Active',
                    ],
                ],
            ],


            [
                'name' => 'Role Manage',
                'icon' => 'layers',
                'route' => null,
                'description' => null,
                'sorting' => 1,
                'permission_name' => 'role-management',
                'status' => 'Active',
                'children' => [
                    [
                        'name' => 'Role Add',
                        'icon' => 'plus-circle',
                        'route' => 'backend.role.create',
                        'description' => null,
                        'sorting' => 1,
                        'permission_name' => 'role-add',
                        'status' => 'Active',
                    ],
                    [
                        'name' => 'Role List',
                        'icon' => 'list',
                        'route' => 'backend.role.index',
                        'description' => null,
                        'sorting' => 1,
                        'permission_name' => 'role-list',
                        'status' => 'Active',
                    ],
                ],
            ],


        


            //don't remove this comment from menu seeder
        ];
    }
}
