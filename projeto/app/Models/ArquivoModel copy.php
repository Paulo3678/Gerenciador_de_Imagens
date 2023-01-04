<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class ArquivoModel
{
    const TIPOS_PERMITIDOS = ["png", "jpg", "jpeg"];
    const MIME_TYPES_PERMITIDOS = ["image/jpeg", "image/png"];
    const PATH_TO_SAVE_DESKTOP_FILES = "./assets/images/uploads/desk/";
    const TAMANHO_MAXIMO_PARA_ARQUIVOS = 1024 * 1024 * 5; // 10MB

    private $nome, $extensao, $caminho, $dataDeEnvio, $tipo_arquivo, $tamanho_arquivo, $nome_temporario, $nome_arquivo;

    public function carregarArquivo()
    {
        $this->nome_arquivo = $_FILES['arquivo']['name'];
        $this->tipo_arquivo = $_FILES['arquivo']['type'];
        $this->nome_temporario = $_FILES['arquivo']['tmp_name'];
        $this->tamanho_arquivo = $_FILES['arquivo']['size'];
        $this->extensao = pathinfo($this->nome_arquivo, PATHINFO_EXTENSION);
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function getExtensao()
    {
        return $this->extensao;
    }

    public function getCaminho()
    {
        return $this->caminho;
    }

    public function getDataDeEnvio()
    {
        return $this->dataDeEnvio;
    }

    private function carregarDadosParaSalvarImagem()
    {
        $this->nome_arquivo = $_FILES['arquivo']['name'];
        $this->tipo_arquivo = $_FILES['arquivo']['type'];
        $this->nome_temporario = $_FILES['arquivo']['tmp_name'];
        $this->tamanho_arquivo = $_FILES['arquivo']['size'];
        $this->extensao = pathinfo($this->nome_arquivo, PATHINFO_EXTENSION);
    }

    private function validarArquivoEnviado()
    {
        if (!$this->arquivoTemExtensaoValida()) {
            return false;
        }

        if (!$this->mimeTypeEhValido()) {
            return false;
        }

        if (!$this->tamanhoDoArquivoEhValido()) {
            return false;
        }

        return true;
    }



    public function salvarNovaImagem()
    {
        $imagem_enviada_eh_valida = $_FILES['arquivo']['name'] !== '';

        if ($imagem_enviada_eh_valida) {

            $ordem_imagem = new OrdemImagensModel();
            // if ($ordem_imagem->jaPassouDoLimiteDeImagensCadastradas() == true) {
            //     return false;
            // }

            $this->carregarDadosParaSalvarImagem();

            $arquivo_enviado_eh_valido = $this->validarArquivoEnviado();

            if ($arquivo_enviado_eh_valido) {
                $novo_nome_arquivo = uniqid();
                $this->nome = $novo_nome_arquivo;
                $nome_completo_para_salvar = $this->nome . "." . $this->extensao;
                $this->caminho = self::PATH_TO_SAVE_DESKTOP_FILES . $nome_completo_para_salvar;

                try {
                    $upload_do_arquivo = move_uploaded_file(
                        $this->nome_temporario,
                        self::PATH_TO_SAVE_DESKTOP_FILES . $nome_completo_para_salvar
                    );

                    if (!$upload_do_arquivo) {
                        return false;
                    }

                    $insert = $this->salvarImagemNoBancoDeDados();

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
        return false;
    }



    private function salvarImagemNoBancoDeDados()
    {
        date_default_timezone_set('America/Sao_Paulo');
        $data_atual = date('Y-m-d H:i');



        try {
            $insert = DB::table("imagens")->insertGetId([
                "Nome" => $this->nome,
                "Extensao" => $this->extensao,
                "Caminho" => $this->caminho,
                "Data_Envio" => $data_atual,
                "Tamanho_Arquivo" => $this->tamanho_arquivo,
            ]);

            return $insert;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function tamanhoDoArquivoEhValido($tamanho = false)
    {
        if ($tamanho) {
            if ($tamanho > self::TAMANHO_MAXIMO_PARA_ARQUIVOS) {
                return false;
            }
            return true;
        }

        if ($this->tamanho_arquivo > self::TAMANHO_MAXIMO_PARA_ARQUIVOS) {
            return false;
        }
        return true;
    }

    private function mimeTypeEhValido()
    {
        if (!in_array($this->tipo_arquivo, self::MIME_TYPES_PERMITIDOS)) {
            return false;
        }
        return true;
    }

    private function arquivoTemExtensaoValida()
    {
        if (!in_array($this->extensao, self::TIPOS_PERMITIDOS)) {
            return false;
        }
        return true;
    }
}
