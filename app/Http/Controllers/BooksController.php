<?php

namespace App\Http\Controllers;

use App\Models\Books;
use App\Helper\CustomController;
use Barryvdh\DomPDF\Facade\Pdf;

class BooksController extends CustomController
{
    public function index()
    {
        try {
            $date = $this->request->query->get("date");
            $startDate = $this->request->query->get("start_date");
            $endDate = $this->request->query->get("end_date");
            $query = Books::with(["user"]);
            $data = [];
            if (!$date) {
                if (!$startDate && !$endDate) {
                    $data = $query->orderBy("created_at", "DESC")->get();
                } else {
                    $data = $query
                        ->whereDate("created_at", "<=", $endDate)
                        ->whereDate("created_at", ">=", $startDate)
                        ->orderBy("created_at", "DESC")
                        ->get();
                }
            } else {
                $data = $query
                    ->whereDate("created_at", "=", $date)
                    ->orderBy("created_at", "DESC")
                    ->get();
            }
            if ($data->isEmpty()) {
                return $this->jsonResponse(
                    "Data " . $date . " not found! ",
                    404
                );
            }
            return $this->jsonResponse("success", 200, $data);
        } catch (\Throwable $e) {
            return $this->jsonResponse(
                "internal server error " . $e->getMessage(),
                500
            );
        }
    }

    public function store()
    {
        try {
            $body = $this->parseRequestBody();
            $data = [
                "user_id" => $body["user_id"],
                "judul" => $body["judul"],
                "penulis" => $body["penulis"],
                "penerbit" => $body["penerbit"],
                "tahun_terbit" => $body["tahun_terbit"],
            ];
            $add = Books::create($data);
            return $this->jsonResponse("success", 200, $add);
        } catch (\Throwable $e) {
            return $this->jsonResponse(
                "internal server error " . $e->getMessage(),
                500
            );
        }
    }

    public function getByID($id)
    {
        try {
            $data = Books::with([])->where("id", "=", $id)->first();
            if (!$data) {
                return $this->jsonResponse(" not found", 404);
            }
            if ($this->request->method() === "POST") {
                return $this->patch($data);
            }
            if ($this->request->method() === "DELETE") {
                return $this->delete($data);
            }
            return $this->jsonResponse("success", 200, $data);
        } catch (\Throwable $e) {
            return $this->jsonResponse(
                "internal server error " . $e->getMessage(),
                500
            );
        }
    }

    private function patch($data)
    {
        try {
            $body = $this->parseRequestBody();
            $data_request = [
                "kategori_id" => $body["kategori_id"] ?? 1,
                "judul" => $body["judul"],
                "penulis" => $body["penulis"],
                "penerbit" => $body["penerbit"],
                "tahun_terbit" => $body["tahun_terbit"],
            ];
            $data->update($data_request);
            return $this->jsonResponse("success", 200);
        } catch (\Throwable $e) {
            return $this->jsonResponse(
                "internal server error " . $e->getMessage(),
                500
            );
        }
    }

    private function delete($data)
    {
        try {
            $data->delete();
            return $this->jsonResponse("success", 200);
        } catch (\Throwable $e) {
            return $this->jsonResponse(
                "internal server error " . $e->getMessage(),
                500
            );
        }
    }

    private function deleteAll()
    {
        try {
            Books::truncate();
            return $this->jsonResponse("success", 200);
        } catch (\Throwable $e) {
            return $this->jsonResponse(
                "internal server error " . $e->getMessage(),
                500
            );
        }
    }

    public function cetakPdf()
    {
        try {
            $data["data"] = Books::with(["user:id,nama"])
                ->orderBy("created_at", "DESC")
                ->get();
            $pdf = Pdf::loadview("books_pdf", $data);
            return $pdf->download("laporan-pegawai-pdf"); // to download pdf
            //            return $pdf->stream(); // to show pdf on browser
        } catch (\Throwable $e) {
            return $this->jsonResponse(
                "internal server error " . $e->getMessage(),
                500
            );
        }
    }
}
