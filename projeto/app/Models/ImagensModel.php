<?php

namespace App\Models;

use App\Models\OrdemImagensModel;
use Illuminate\Support\Facades\DB;

class ImagensModel
{

    public function buscarImagens()
    {
        return DB::select("SELECT * FROM imagens");
    }


    public function buscarImagensOrdenadas()
    {
        $ordem_imagens_manager = new OrdemImagensModel();
        $ordem = $ordem_imagens_manager->buscarOrdem();

        $todas_imagens = $this->buscarImagens();

        // ORGANIZANDO PARA IMPRIMIR NO FRONT
        $ordem_para_mostrar = [];
        foreach ($ordem as $key => $elemento) {
            foreach ($todas_imagens as $key => $imagem) {
                if($imagem->Id == $elemento->Id_Imagem){
                    if(isset($elemento->Id_Ordem)){
                        array_push($ordem_para_mostrar, [
                            "Posicao" => $elemento->Id_Ordem,
                            "Caminho" => $imagem->Caminho,
                            "Id_Imagem" => $imagem->Id
                        ]);
                    }
                }
            }
        }

        return $ordem_para_mostrar;

    }
    
}
