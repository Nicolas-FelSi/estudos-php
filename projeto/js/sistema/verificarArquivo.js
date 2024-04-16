const formSubmit = document.querySelector("form");

formSubmit.addEventListener("submit", (evento)=>{
    validarFormulario(evento);
})

function validarFormulario(evento) {
    const arquivoInput = document.getElementById('idArquivo');
    if (arquivoInput.files.length === 0) {
        evento.preventDefault();
        alert('Por favor, selecione um arquivo.');

        return false;
    }
    validarArquivo(evento)
    return true;
}

function validarArquivo(evento) { 
    const arquivoInput = document.getElementById('idArquivo');
    const arquivo = arquivoInput.files[0];
    const extensoesPermitidas = ['xls', 'xlsx'];

    if (arquivo) {
        const nomeArquivo = arquivo.name;
        const extensao = nomeArquivo.split('.').pop().toLowerCase();

        if (extensoesPermitidas.indexOf(extensao) === -1) {
            alert('Por favor, selecione um arquivo do Excel (.xls ou .xlsx).');
            evento.preventDefault();
            return false;
        }
    }

    return true;
}

