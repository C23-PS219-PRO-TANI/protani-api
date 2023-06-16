<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TestController extends Controller
{
    private string $failedConnectMessage;

    public function __construct()
    {
        $this->failedConnectMessage = 'Failed to get Database Name. Connection may have intrusion.';
    }

    private function tryPingDatabase(): bool
    {
        $err = false;
        try {
            DB::connection()->getDatabaseName();
        } catch (\Exception $e) {
            $err = true;
        }

        return $err;
    }
    public function pingDatabase()
    {
        $err = $this->tryPingDatabase();
        $name = DB::connection()->getDatabaseName();
        if ($err) {
            return response(
                [
                    'message' => $this->failedConnectMessage,
                    'status' => 'error',
                    'code' => 500
                ],
                500
            );
        }

        return response(
            [
                'data' => 'DB Name : ' . $name,
                'message' => 'Connection Success',
                'status' => 'success',
                'code' => 200
            ],
            200
        );
    }

    public function createTest()
    {
        $err = $this->tryPingDatabase();

        if ($err) {
            return response(
                [
                    'message' => $this->failedConnectMessage,
                    'status' => 'error',
                    'code' => 500
                ],
                500
            );
        }

        $start = hrtime(true);
        for ($i = 0; $i < 150; $i++) {
            $name = Str::random(8) . (string)$i;

            User::create([
                'name'       => $name,
                'email'      => $name . "@protani.io",
                'password'   => Hash::make('passw0rd#2023'),
                'updated_at' => now(),
                'created_at' => now(),
            ]);
        }
        $end = hrtime(true);
        $times = number_format(($end - $start) / 1000000000, 1);

        return response(
            [
                'message' => 'Success run CreateTest : 150 Insert Query (create users) in ' . $times . 'second/s',
                'status' => 'success',
                'code' => 200
            ],
            200
        );
    }

    public function updateTest()
    {
        $err = $this->tryPingDatabase();

        if ($err) {
            return response(
                [
                    'message' => $this->failedConnectMessage,
                    'status' => 'error',
                    'code' => 500
                ],
                500
            );
        }

        $firstID = 0;
        $lastID = 0;
        for ($i = 0; $i < 150; $i++) {
            $name = Str::random(14) . (string)$i;
            $user = new User;
            $user->name = $name;
            $user->email = $name . "@protani.io";
            $user->password = Hash::make('passw0rd#2023');
            $user->save();
            if ($i == 0) {
                $firstID = $user->id;
            }
            if ($i == 49) {
                $lastID = $user->id;
            }
        }

        $start = hrtime(true);
        for ($i = $firstID; $i <= $lastID; $i++) {
            $user = User::where('id', '=', $i)->first();
            $user->name = 'saved-00' . $i;
            $user->updated_at = now();
            $user->save();
        }
        $end = hrtime(true);
        $times = number_format(($end - $start) / 1000000000, 1);

        return response(
            [
                'message' => 'Success run UpdateTest : 150 Update Query (update users) in ' . $times . 'second/s',
                'status' => 'success',
                'code' => 200
            ],
            200
        );
    }

    public function deleteTest()
    {
        $err = $this->tryPingDatabase();

        if ($err) {
            return response(
                [
                    'message' => $this->failedConnectMessage,
                    'status' => 'error',
                    'code' => 500
                ],
                500
            );
        }

        $firstID = 0;
        $lastID = 0;
        for ($i = 0; $i < 150; $i++) {
            $name = Str::random(14) . (string)$i;
            $user = new User;
            $user->name = $name;
            $user->email = $name . "@protani.io";
            $user->password = Hash::make('passw0rd#2023');
            $user->save();
            if ($i == 0) {
                $firstID = $user->id;
            }
            if ($i == 49) {
                $lastID = $user->id;
            }
        }

        $start = hrtime(true);
        for ($i = $firstID; $i <= $lastID; $i++) {
            $user = User::where('id', '=', $i)->first();
            $user->delete();
        }
        $end = hrtime(true);
        $times = number_format(($end - $start) / 1000000000, 1);

        return response(
            [
                'message' => 'Success run DeleteTest : 150 Delete Query (delete users) in ' . $times . 'second/s',
                'status' => 'success',
                'code' => 200
            ],
            200
        );
    }
}
