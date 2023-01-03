<?php

use App\Http\Controllers\AreaCadastroImagem;
use App\Http\Controllers\Home;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [Home::class, "index"]);
Route::get('/admin/dashboard', [AreaCadastroImagem::class, "index"]);
Route::post('/admin/dashboard/cadastrar-imagem', [AreaCadastroImagem::class, "cadastrarImagem"]);
Route::post("/admin/dashboard/mudar-posicao", [AreaCadastroImagem::class, "atualizarPosicao"]);
Route::post("/admin/dashboard/deletar-imagem", [AreaCadastroImagem::class, "deletarImagem"]);