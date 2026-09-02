function openModal() {
    document.getElementById('modal').classList.add('show');
}

function closeModal() {
    document.getElementById('modal').classList.remove('show');
}

function simulate() {
    const fileInput = document.getElementById('file');
    const file = fileInput.files[0];

    if (!file) {
        alert('Selecione uma planilha primeiro.');
        return;
    }

    alert('Protótipo: arquivo "' + file.name + '" selecionado. No backend Laravel, o Maatwebsite processará os dados.');
    closeModal();
}
