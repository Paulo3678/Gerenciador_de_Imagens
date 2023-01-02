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

    public function adicionarImagemNaOrdem(int $imagem_id)
    {
        // VERIFICAR SE JÁ TEM 10 ITEMS NA ORDEM
        if ($this->jaPassouDoLimiteDeImagensCadastradas() == true) {
            return false;
        }

        try {
            DB::table('ordem_imagens')->insertGetId(["Id_Imagem" => $imagem_id]);
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
}
