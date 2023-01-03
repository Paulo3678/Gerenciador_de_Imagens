<?php

namespace App\Http\Controllers;

use App\Models\ArquivoModel;
use App\Models\ImagensModel;
use Illuminate\Http\Request;
use App\Models\OrdemImagensModel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

use function PHPUnit\Framework\returnSelf;

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

    public function atualizarPosicao(Request $request)
    {
        $posicao_atual = $request->post("posicao-atual");
        $nova_posicao = $request->post("nova-posicao");

        if (is_null($nova_posicao) || is_null($posicao_atual)) {
            Session::flash("aviso", [
                "type" => "danger",
                "message" => "É preciso indicar a nova posição e a posição atual!"
            ]);

            return redirect()->back();
        }
        $ordem_imagens_manager = new OrdemImagensModel();

        $update = $ordem_imagens_manager->atualizarPosicaoImagem($posicao_atual, $nova_posicao);

        if (!$update) {
            Session::flash("aviso", [
                "type" => "danger",
                "message" => "Posição inválida! Verifique as posições ativas"
            ]);
            return redirect()->back();
        }

        return redirect()->back();
    }

    public function deletarImagem(Request $request)
    {
        $id_imagem = $request->post("id-imagem");

        if(is_null($id_imagem)){
            Session::flash("aviso", [
                "type" => "danger",
                "message" => "É preciso indicar o Id da imagem"
            ]);
            return redirect()->back();
        }

        $ordem_imagens_manager = new OrdemImagensModel();

        $delete = $ordem_imagens_manager->removerImagem($id_imagem);

        return redirect()->back();
    }
}
