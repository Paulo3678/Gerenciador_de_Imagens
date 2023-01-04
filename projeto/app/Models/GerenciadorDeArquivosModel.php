<?php

namespace App\Models;

use App\Models\ArquivoModel;
use Illuminate\Support\Facades\DB;

class GerenciadorDeArquivosModel
{
    public function salvarArquivo(ArquivoModel $arquivo)
    {
        $arquivo_enviado_eh_valido = $arquivo->arquivoEnviadoEhValido();
        
        if ($arquivo_enviado_eh_valido) {
            $ordem_imagem = new GerenciadorOrdemDeArquivos();

            $nome_completo_para_salvar = $arquivo->getNome_completo_para_salvar();

            try {
                $upload_do_arquivo = move_uploaded_file(
                    $arquivo->getNome_temporario(),
                    ArquivoModel::PATH_TO_SAVE_DESKTOP_FILES . $nome_completo_para_salvar
                );

                if (!$upload_do_arquivo) {
                    return false;
                }

                date_default_timezone_set('America/Sao_Paulo');
                $data_atual = date('Y-m-d H:i');

                $insert = $this->salvarImagemNoBancoDeDados([
                    "Nome" => $arquivo->getNome(),
                    "Extensao" => $arquivo->getExtensao(),
                    "Caminho" => $arquivo->getCaminho(),
                    "Data_Envio" => $data_atual,
                    "Tamanho_Arquivo" => $arquivo->getTamanho_arquivo(),
                ]);

                if (!$insert) {
                    return false;
                }

                $add_ordem = $ordem_imagem->adicionarImagemNaOrdem($insert);
                return true;
            } catch (\Throwable $e) {
                return false;
            }
        }
    }

    private function salvarImagemNoBancoDeDados(array $dados_para_salvar)
    {
        date_default_timezone_set('America/Sao_Paulo');
        $data_atual = date('Y-m-d H:i');

        try {
            $insert = DB::table("imagens")->insertGetId($dados_para_salvar);

            return $insert;
        } catch (\Throwable $e) {
            return false;
        }
    }
}
