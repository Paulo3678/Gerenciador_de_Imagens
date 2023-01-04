<?php

namespace App\Factory;

use Illuminate\Support\Facades\DB;

class OrdemFactory
{

    public function adicionarImagemNaOrdem(int $id_imagem, int $id_ordem)
    {
        DB::update("UPDATE ordem_imagens SET Id_Imagem={$id_imagem} WHERE Id_Ordem={$id_ordem}");
    }

    public function buscarTotalImagensCadastradas()
    {
        $busca = DB::select("SELECT * FROM ordem_imagens");
        return count($busca);
    }

    public function buscarOrdem()
    {
        $ordem = DB::select("SELECT * FROM ordem_imagens");
        return $ordem;
    }

    public function buscarArquivoDaOrdem(int $posicao_na_ordem)
    {
        return DB::selectOne("SELECT * FROM ordem_imagens WHERE Id_Ordem={$posicao_na_ordem}");
    }

    public function buscarImagensNaoAtivas()
    {
        $ordem = DB::select("SELECT * FROM ordem_imagens WHERE Id_Imagem=0;");
        return $ordem;
    }

    public function buscarImagens()
    {
        return DB::select("SELECT * FROM imagens");
    }

    public function atualizarPosicaoDaImagem(int $id_imagem_antiga, int $id_imagem_nova, int $id_ordem_antiga, int $id_ordem_nova)
    {
        DB::update("UPDATE ordem_imagens SET Id_Imagem={$id_imagem_nova} WHERE Id_Ordem={$id_ordem_antiga}");
        DB::update("UPDATE ordem_imagens SET Id_Imagem={$id_imagem_antiga} WHERE Id_Ordem={$id_ordem_nova}");
    }


    public function removerArquivoDaOrdem(int $id_imagem)
    {
        // REMOVENDO A IMAGEM
        DB::delete("DELETE FROM imagens WHERE Id = {$id_imagem}");

        // REMOVENDO A IMAGEM DA ORDEM
        DB::update("UPDATE ordem_imagens SET Id_Imagem=0 WHERE Id_Imagem=$id_imagem");

        $this->atualizarOrdemDepoisDeRemoverImagem();
    }


    private function atualizarOrdemDepoisDeRemoverImagem()
    {
        $imagens_na_ordem = $this->buscarOrdem();
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
        for ($i = (count($nova_ordem) + 1); $i <= 10; $i++) {
            DB::table("ordem_imagens")->where(["Id_Ordem" => $i])
                ->update(["Id_Imagem" => 0]);
        }
    }
}
