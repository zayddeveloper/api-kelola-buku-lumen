<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Books;
use App\Helper\CustomController;

class BooksController extends CustomController
{

    public function index()
    {
        $data = Books::with(['users:id,nama'])->orderBy('created_at', 'DESC')->get();
        if ($data->isEmpty()) {
            return $this->jsonResponse('data not found!', 404);
        }
        return $this->jsonResponse('success', 200, $data);
    }

    public function store()
    {
        try {
            $body = $this->parseRequestBody();
            $data = [
                'kategori_id' => $body['kategori_id'] ?? 1,
                'judul' => $body['judul'],
                'penulis' => $body['penulis'],
                'penerbit' => $body['penerbit'],
                'tahun_terbit' => $body['tahun_terbit'],
            ];
            $add = Books::create($data);
            return $this->jsonResponse('success', 200, $add);
        } catch (\Throwable $e) {
            return $this->jsonResponse('internal server error ' . $e->getMessage(), 500);
        }
    }

    public function getByID($id)
    {
        try {
            $data = Books::with([])->where('id', '=', $id)->first();
            if (!$data) {
                return $this->jsonResponse(' not found', 404);
            }
            if ($this->request->method() === 'POST') {
                return $this->patch($data);
            }
            if ($this->request->method() === 'DELETE') {
                return $this->delete($data);
            }
            return $this->jsonResponse('success', 200, $data);
        } catch (\Throwable $e) {
            return $this->jsonResponse('internal server error ' . $e->getMessage(), 500);
        }
    }

    private function patch($data)
    {
        try {
            $body = $this->parseRequestBody();
            $data_request = [
                'kategori_id' => $body['kategori_id'] ?? 1,
                'judul' => $body['judul'],
                'penulis' => $body['penulis'],
                'penerbit' => $body['penerbit'],
                'tahun_terbit' => $body['tahun_terbit'],
            ];
            $data->update($data_request);
            return $this->jsonResponse('success', 200);
        } catch (\Throwable $e) {
            return $this->jsonResponse('internal server error ' . $e->getMessage(), 500);
        }
    }

    private function delete($data)
    {
        try {
            $data->delete();
            return $this->jsonResponse('success', 200);
        } catch (\Throwable $e) {
            return $this->jsonResponse('internal server error ' . $e->getMessage(), 500);
        }
    }

    private function deleteAll()
    {
        try {
            Books::truncate();
            return $this->jsonResponse('success', 200);
        } catch (\Throwable $e) {
            return $this->jsonResponse('internal server error ' . $e->getMessage(), 500);
        }
    }

}
