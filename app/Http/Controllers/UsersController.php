<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;
use App\Helper\CustomController;
use Illuminate\Support\Facades\Hash;

class UsersController extends CustomController
{

    public function store()
    {
        try {
            $body = $this->parseRequestBody();
            $data = [
                'nama' => $body['nama'],
                'username' => $body['username'],
                'password' => Hash::make($body['password']),
            ];
            $user = Users::create($data);
            return $this->jsonResponse('success', 200, $user);
        } catch (\Throwable $e) {
            return $this->jsonResponse('internal server error ' . $e->getMessage(), 500);
        }
    }

    public function login()
    {
        try {
            $username = $this->postField('username');
            $password = $this->postField('password');

            $user = Users::with([])
                ->where('username', '=', $username)
                ->first();
            if (!$user) {
                return $this->jsonResponse('user not found', 404);
            }

            $isPasswordValid = Hash::check($password, $user->password);
            if (!$isPasswordValid) {
                return $this->jsonResponse('password did not match', 401);
            }

            return $this->jsonResponse('Login Success', 200, $user);
        } catch (\Throwable $e) {
            return $this->jsonResponse('internal server error ' . $e->getMessage(), 500);
        }
    }

    public function getByID($id)
    {
        try {
            $data = Users::where('id', '=', $id)->first();
            if (!$data) {
                return $this->jsonResponse(' not found', 404);
            }
            return $this->jsonResponse('success', 200, $data);
        } catch (\Throwable $e) {
            return $this->jsonResponse('internal server error ' . $e->getMessage(), 500);
        }
    }

    public function changePassword()
    {
        $user = Users::find($this->postField('id'));

        if (!Hash::check($this->postField('old_password'), $user->password)) {
           
            return $this->jsonResponse('Password lama tidak valid ', 403);
        }

        $user->password =  Hash::make($this->postField('new_password'));
        $user->save();

        return $this->jsonResponse('Password berhasil diubah ', 200);
    }
}
