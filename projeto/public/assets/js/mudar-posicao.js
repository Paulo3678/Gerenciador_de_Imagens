const botoes_mudar_opcao = document.querySelectorAll(".btn-mudar-posicao");
let formulario_ja_ativo = false;


botoes_mudar_opcao.forEach(botao => {
    botao.addEventListener("click", e =>{
        const botao_clicado = e.target;
        const pai_do_botao = botao_clicado.parentNode;

        const formulario = pai_do_botao.querySelector("form.form-mudar-ordem");
        
        if(formulario.classList.contains("active")){
            formulario.classList.remove("active");
        }else{
            if(formulario_ja_ativo !== false){
                formulario_ja_ativo.classList.remove("active");
            }
            formulario.classList.add("active");
            formulario_ja_ativo = formulario; 
        }

    })
});