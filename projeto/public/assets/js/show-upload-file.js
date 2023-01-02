const input_image_desk = document.querySelector("#uploadfile");

input_image_desk.addEventListener("change", e => {
    
    const image = document.getElementById('imagem-escolhida');
    image.src = URL.createObjectURL(e.target.files[0]);

});