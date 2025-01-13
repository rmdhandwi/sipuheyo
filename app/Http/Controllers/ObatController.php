<?php

namespace App\Http\Controllers;

use App\Http\Requests\ObatRequest;
use App\services\ObatService;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ObatController extends Controller
{

    public function all(ObatService $obatService)
    {
        try {
            $result = $obatService->all();
        } catch (\Throwable $th) {
            return Redirect()->with("error", $th->getMessage());
        }
    }


    public function getById(ObatService $obatService, $id)
    {
        try {
            $result = $obatService->getById($id);
        } catch (\Throwable $th) {
            return Redirect()->with("error", $th->getMessage());
        }
    }

    public function post(ObatRequest $req, ObatService $obatService)
    {
        try {
            $result = $obatService->post($req);
            if (!$result) {
                throw new Error("Data tidak berhasil disimpan !");
            }
            return Redirect()->with("success");
        } catch (\Throwable $th) {
            return Redirect()->with("error", $th->getMessage());
        }
    }

    public function put(ObatRequest $req, ObatService $obatService, $id)
    {
        try {
            $result = $obatService->put($req, $id);
            if (!$result) {
                throw new Error("Data tidak berhasil disimpan !");
            }
            return Redirect()->with("success");
        } catch (\Throwable $th) {
            return Redirect()->with("error", $th->getMessage());
        }
    }


    public function delete(ObatService $obatService, $id)
    {
        try {
            $result  = $obatService->delete($id);
            if (!$result)
                throw new Error("Data tidak berhasil dihapus !");
            return Redirect()->with("success");
        } catch (\Throwable $th) {
            return Redirect()->with("error", $th->getMessage());
        }
    }
}
