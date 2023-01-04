<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class ArquivoModel
{
    const TIPOS_PERMITIDOS = ["png", "jpg", "jpeg"];
    const MIME_TYPES_PERMITIDOS = ["image/jpeg", "image/png"];
    const PATH_TO_SAVE_DESKTOP_FILES = "./assets/images/uploads/desk/";
    const TAMANHO_MAXIMO_PARA_ARQUIVOS = 1024 * 1024 * 5; // 10MB

    private $nome, $extensao, $caminho, $dataDeEnvio, $tipo_arquivo, $tamanho_arquivo, $nome_temporario, $nome_completo_para_salvar;

    public function carregarNovoParaSalvarArquivo()
    {
        $nome_arquivo = $_FILES['arquivo']['name'];
        $this->extensao = pathinfo($nome_arquivo, PATHINFO_EXTENSION);
        
        $this->tipo_arquivo = $_FILES['arquivo']['type'];
        $this->nome_temporario = $_FILES['arquivo']['tmp_name'];
        $this->tamanho_arquivo = $_FILES['arquivo']['size'];

        $novo_nome_arquivo = uniqid();
        $this->nome = $novo_nome_arquivo;

        $this->nome_completo_para_salvar = $this->nome . "." . $this->extensao;
        $this->caminho = self::PATH_TO_SAVE_DESKTOP_FILES . $this->nome_completo_para_salvar;
    }

    public function carregarArquivoDoBanco(int $arquivo_id)
    {
        
    }

    public function arquivoEnviadoEhValido()
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

    public function mimeTypeEhValido()
    {
        if (!in_array($this->tipo_arquivo, self::MIME_TYPES_PERMITIDOS)) {
            return false;
        }
        return true;
    }

    public function arquivoTemExtensaoValida()
    {
        if (!in_array($this->extensao, self::TIPOS_PERMITIDOS)) {
            return false;
        }
        return true;
    }

    public function getNome_temporario()
    {
        return $this->nome_temporario;
    }

    public function getTamanho_arquivo()
    {
        return $this->tamanho_arquivo;
    }

    public function getTipo_arquivo()
    {
        return $this->tipo_arquivo;
    }

    public function getDataDeEnvio()
    {
        return $this->dataDeEnvio;
    }

    public function getCaminho()
    {
        return $this->caminho;
    }

    public function getExtensao()
    {
        return $this->extensao;
    }

    public function getNome_completo_para_salvar()
    {
        return $this->nome_completo_para_salvar;
    }

    public function getNome()
    {
        return $this->nome;
    }
}
