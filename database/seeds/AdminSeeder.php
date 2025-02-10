<?php

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Admin::where('username', 'superadmin')->first();

        if (is_null($admin)) {
            $admin           = new Admin();
            $admin->per_id     = "";
            $admin->nombre     = "";
            $admin->apellidos     = "";
            $admin->correo    = "";
            $admin->cargo    = "";
            $admin->username = "";
            $admin->password = Hash::make('');
            $admin->save();
        }
    }
}
