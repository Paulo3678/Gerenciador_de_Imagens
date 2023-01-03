<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class OrdemImagensModel
{
    const NUMERO_MAXIMOS_DE_IMAGENS = 10;

    public function buscarOrdem()
    {
        $ordem = DB::select("SELECT * FROM ordem_imagens");
        return $ordem;
    }

    private function buscarImagensNaoAtivas()
    {
        $ordem = DB::select("SELECT * FROM ordem_imagens WHERE Id_Imagem=0;");
        return $ordem;
    }

    public function adicionarImagemNaOrdem(int $imagem_id)
    {
        $imagens_nao_ativas = $this->buscarImagensNaoAtivas();

        if (empty($imagens_nao_ativas)) {
            return false;
        }
        $posicao_para_salvar = $imagens_nao_ativas[0];

        try {
            DB::update("UPDATE ordem_imagens SET Id_Imagem={$imagem_id} WHERE Id_Ordem={$posicao_para_salvar->Id_Ordem}");
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function jaPassouDoLimiteDeImagensCadastradas()
    {
        $total_imagens_cadastradas = $this->buscarTotalImagensCadastradas();

        if ($total_imagens_cadastradas + 1 > self::NUMERO_MAXIMOS_DE_IMAGENS) {
            return true;
        }
        return false;
    }

    public function buscarTotalImagensCadastradas()
    {
        $busca = DB::select("SELECT * FROM ordem_imagens");
        return count($busca);
    }


    public function buscarImagens()
    {
        return DB::select("SELECT * FROM imagens");
    }

    public function atualizarPosicaoImagem(int $posicao_antiga, int $posicao_nova)
    {
        $imagem_da_antiga_posicao = DB::selectOne("SELECT * FROM ordem_imagens WHERE Id_Ordem={$posicao_antiga}");
        $imagem_da_nova_posicao = DB::selectOne("SELECT * FROM ordem_imagens WHERE Id_Ordem={$posicao_nova}");

        if (empty($imagem_da_nova_posicao) || empty($imagem_da_antiga_posicao)) {
            return false;
        }

        $id_da_imagem_antiga = $imagem_da_antiga_posicao->Id_Imagem;
        $id_da_imagem_nova = $imagem_da_nova_posicao->Id_Imagem;

        try {
            DB::update("UPDATE ordem_imagens SET Id_Imagem={$id_da_imagem_nova} WHERE Id_Ordem={$posicao_antiga}");
            DB::update("UPDATE ordem_imagens SET Id_Imagem={$id_da_imagem_antiga} WHERE Id_Ordem={$posicao_nova}");

            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function removerImagem(int $id_da_imagem)
    {
        // REMOVENDO A IMAGEM
        DB::delete("DELETE FROM imagens WHERE Id = {$id_da_imagem}");

        // REMOVENDO A IMAGEM DA ORDEM
        DB::update("UPDATE ordem_imagens SET Id_Imagem=0 WHERE Id_Imagem=$id_da_imagem");

        // ATUALIZANDO A ORDEM
        $imagens_na_ordem = DB::select("SELECT * FROM ordem_imagens");
        $nova_ordem = [];

        $ordem = 1;
        foreach ($imagens_na_ordem as $key => $value) {
            if ($value->Id_Imagem !== 0) {
                array_push($nova_ordem, [
                    "ordem" => $ordem,
                    "imagem" => $value->Id_Imagem
                ]);
                $ordem++;
            }
        }

        foreach ($nova_ordem as $item) {
            $query = "UPDATE ordem_imagens SET Id_Imagem={$item['imagem']} WHERE Id_Ordem={$item['ordem']}";
            echo $query;
            echo "<br>";
            DB::table("ordem_imagens")->where(["Id_Ordem" => $item["ordem"]])
                ->update(["Id_Imagem" => $item['imagem']]);
        }
        for ($i=(count($nova_ordem)+1); $i <=10 ; $i++) { 
            DB::table("ordem_imagens")->where(["Id_Ordem" => $i])
                ->update(["Id_Imagem" => 0]);
        }
    }
}
