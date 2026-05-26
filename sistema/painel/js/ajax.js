$(document).ready(function(){
    lista();
});

function listar(){
    $.ajax({
        url: 'paginas_adm' + pag + "/lista_usuarios",
        method: 'POST', 
        data: $('#form').serialize(),
        dataType:"html",

        success:function(result){
            $("#listar").html(result);
            $('#mensagem-excluir').text('');
        }
    })
}