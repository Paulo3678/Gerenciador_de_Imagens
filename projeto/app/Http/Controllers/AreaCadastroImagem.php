<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ArquivoModel;
use App\Models\ImagensModel;
use App\Models\OrdemImagensModel;
use Illuminate\Support\Facades\Session;

class AreaCadastroImagem extends Controller
{
    public function index()
    {
        $imagens_manager = new ImagensModel();
        $ordem_para_mostrar = $imagens_manager->buscarImagensOrdenadas();
        return view("/admin/dashboard", compact("ordem_para_mostrar"));
    }

    public function cadastrarImagem()
    {
        $arquivo = new ArquivoModel();

        $salvamento = $arquivo->salvarNovaImagem();
        if (!$salvamento) {
            Session::flash("aviso", [
                "type" => "danger",
                "message" => "Erro ao salvar imagem"
            ]);
            return redirect("/admin/dashboard");
        }

        Session::flash("aviso", [
            "type" => "success",
            "message" => "Imagem salva com sucesso"
        ]);
        return redirect("/admin/dashboard");
    }
}
