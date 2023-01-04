<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Gerenciador de imagens</title>

    {{-- BOOTSTRAP --}}
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
        integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="/assets/bootstrap/js/bootstrap.bundle.min.js"></script>

    {{-- CUSTOM --}}
    <link rel="stylesheet" href="/assets/css/custom.css">

    {{-- ICONS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body>

    <div class="container">
        <div class="row">

            <div class="col-12">
                <div class="area-info">
                    <h2>Área do admin</h2>
                    <div class="area-info-content">
                        <div class="area-form-adicionar mb-4">
                            <h3><i class="fa-solid fa-caret-right"></i> Adicionar imagem</h3>

                            @if (session()->has('aviso'))
                                <div class="alert alert-{{ session()->get('aviso')['type'] }}" role="alert">
                                    {{ session()->get('aviso')['message'] }}
                                </div>
                            @endif

                            <form action="/admin/dashboard/cadastrar-imagem" method="POST"
                                class="form-adicionar form-group row" enctype="multipart/form-data">
                                @csrf

                                <div class="custom-file col-8 p-0">
                                    <input type="file" name="arquivo" class="custom-file-input" id="uploadfile">
                                    <label class="custom-file-label" for="uploadfile">Escolha um arquivo</label>
                                    <button>Enviar</button>
                                </div>
                                <div class="col-4">
                                    <img src="" id="imagem-escolhida" alt="Imagem escolhida">
                                </div>
                            </form>
                        </div>

                        <hr>

                        <div class="area-images-info">
                            <h3><i class="fa-solid fa-caret-right"></i> Informações para as imagens</h3>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <span>Tipos permitidos: </span>
                                </li>
                                <li class="list-group-item">
                                    <span>Limite de imagens para cadastrar: </span>
                                </li>
                                <li class="list-group-item">
                                    <span>Total de imagens cadastradas: </span>
                                </li>
                                <li class="list-group-item">
                                    <span>Quantas imagens posso cadastrar: </span>
                                </li>
                                <li class="list-group-item">
                                    <span>Tamanho (MB) máximo permitido: </span>
                                </li>
                            </ul>
                        </div>

                        <hr>

                        <div class="area-images-info">
                            <h3><i class="fa-solid fa-caret-right"></i> Ordem do Banner</h3>
                            <div>
                                @foreach ($ordem_para_mostrar as $item)
                                    <div class="card">

                                        <h6>
                                            <span>Posicao: {{ $item['Posicao'] }}</span>
                                            <form action="/admin/dashboard/deletar-imagem" method="POST"
                                                class="form-deletar-imagem">
                                                @csrf
                                                <input type="hidden" name="id-imagem" value="{{ $item['Id_Imagem'] }}">
                                                <button><i class="fa-solid fa-trash"></i></button>
                                            </form>

                                        </h6>
                                        <img src="{{ str_replace('./assets', '/assets', $item['Caminho']) }}"
                                            alt="">
                                        <button class="btn-mudar-posicao">Mudar posicao</button>

                                        <form action="/admin/dashboard/mudar-posicao" class="form-mudar-ordem"
                                            method="POST">
                                            @csrf
                                            <input type="hidden" name="posicao-atual" value="{{ $item['Posicao'] }}">
                                            <input type="number" min="1" max="10" name="nova-posicao"
                                                placeholder="Nova Posição" />
                                            <div class="area-botao">
                                                <button>Mudar</button>
                                            </div>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>

                    <div class="link-left mt-2">
                        <a href="/">Voltar para home <i class="fa-solid fa-arrow-up-right-from-square"></i></a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- BOOTSTRAP --}}
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="/assets/bootstrap/js/bootstrap.min.js"></script>

    {{-- CUSTOM --}}
    <script src="/assets/js/show-upload-file.js"></script>
    <script src="/assets/js/mudar-posicao.js"></script>
</body>

</html>
