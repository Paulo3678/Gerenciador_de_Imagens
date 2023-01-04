<?php

namespace App\Models;

use App\Factory\OrdemFactory;
use Illuminate\Support\Facades\DB;

class GerenciadorOrdemDeArquivos
{
    const NUMERO_MAXIMOS_DE_IMAGENS = 10;

    private $ordem_factory;

    public function __construct()
    {
        $this->ordem_factory = new OrdemFactory();
    }

    public function possoSalvarMaisArquivos()
    {
        $imagens_nao_ativas = $this->ordem_factory->buscarImagensNaoAtivas();

        if (count($imagens_nao_ativas) >= 10) {
            return false;
        }
        return true;
    }

    public function adicionarImagemNaOrdem(int $imagem_id)
    {
        $imagens_nao_ativas = $this->ordem_factory->buscarImagensNaoAtivas();

        if (empty($imagens_nao_ativas)) {
            return false;
        }
        $posicao_para_salvar = $imagens_nao_ativas[0];

        $this->ordem_factory->adicionarImagemNaOrdem($imagem_id, $posicao_para_salvar->Id_Ordem);
        return true;
    }

    public function jaPassouDoLimiteDeImagensCadastradas()
    {
        $total_imagens_cadastradas = $this->ordem_factory->buscarTotalImagensCadastradas();
        if ($total_imagens_cadastradas + 1 > self::NUMERO_MAXIMOS_DE_IMAGENS) {
            return true;
        }
        return false;
    }

    public function atualizarPosicaoImagem(int $posicao_antiga, int $posicao_nova)
    {

        $imagem_da_antiga_posicao = $this->ordem_factory->buscarArquivoDaOrdem($posicao_antiga);
        $imagem_da_nova_posicao = $this->ordem_factory->buscarArquivoDaOrdem($posicao_nova);

        if (empty($imagem_da_nova_posicao) || empty($imagem_da_antiga_posicao)) {
            return false;
        }

        $id_da_imagem_antiga = $imagem_da_antiga_posicao->Id_Imagem;
        $id_da_imagem_nova = $imagem_da_nova_posicao->Id_Imagem;

        $this->ordem_factory->atualizarPosicaoDaImagem($id_da_imagem_antiga, $id_da_imagem_nova, $posicao_antiga, $posicao_nova);

        return true;
    }

    public function removerImagem(int $id_da_imagem)
    {
        $this->ordem_factory->removerArquivoDaOrdem($id_da_imagem);
    }
}
