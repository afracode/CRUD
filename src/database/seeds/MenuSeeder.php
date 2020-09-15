<?php

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

        $items = [
            [
                "icon" => "fa-tasks",
                "name" => "users",
                "href" => "users",
                "permission" => "users-read",
            ],
            [
                "icon" => "fa-tasks",
                "name" => "providers",
                "href" => "providers",
                "permission" => "providers-read",

            ],
            [
                "icon" => "fa-tasks",
                "name" => "pages",
                "href" => "pages",
                "permission" => "pages-read",
            ],
            [
                "icon" => "fa-tasks",
                "name" => "posts",
                "href" => "posts",
                "permission" => "posts-read",
            ],
            [
                "icon" => "fa-tasks",
                "name" => "products",
                "href" => "products",
                "permission" => "products-read",
            ],
            [
                "icon" => "fa-tasks",
                "name" => "categories",
                "href" => "categories",
                "permission" => "categories-read",
            ],


            [
                "icon" => "fa-tasks",
                "name" => "services",
                "href" => "services",
                "permission" => "services-read",
            ],

            [
                "icon" => "fa-tasks",
                "name" => "stories",
                "href" => "stories",
                "permission" => "stories-read",
            ],

            [
                "icon" => "fa-tasks",
                "name" => "plans",
                "href" => "plans",
                "permission" => "plans-read",
            ],

            [
                "icon" => "fa-tasks",
                "name" => "variables",
                "href" => "variables",
                "permission" => "variable-read",
            ],

            [
                "icon" => "fa-tasks",
                "name" => "transactions",
                "href" => "transactions",
                "permission" => "transaction-read",
            ],

            [
                "icon" => "fa-tasks",
                "name" => "reports",
                "href" => "reports",
                "permission" => "report-read",
            ],

            [
                "icon" => "fa-tasks",
                "name" => "languages",
                "href" => "languages",
                "permission" => "language-read",
            ],

            [
                "icon" => "fa-tasks",
                "name" => "payments",
                "href" => "payments",
                "permission" => "payment-read",
            ],

        ];


        foreach ($items as $item)
            \App\Menu::create($item);


    }
}
