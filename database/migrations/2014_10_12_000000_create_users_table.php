<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('role', ['superadmin', 'admin', 'user'])->default('user');
            $table->string('email')->unique();
            $table->string('provider_id')->nullable()->default(null);
            $table->string('avatar')->nullable()->default(null);
            $table->timestamp('email_verified_at')->nullable()->default(null);
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        foreach ([1, 2, 3, 4, 5] as $value) {
            $name = 'system0' . $value;

            User::create([
                'name'     => $name,
                'role'     => 'admin',
                'email'    => $name . "@protani.io",
                'password' => Hash::make('passw0rd#2023'),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
