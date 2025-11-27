const icon = document.getElementById('profileIcon');
const menu = document.getElementById('profileMenu');
const wrap = document.getElementById('profileWrap');

icon.addEventListener('click', function(event) {
    event.stopPropagation();
    menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
});

document.addEventListener('click', function() {
    menu.style.display = 'none';
});

async function buscarCEP() {
    let cep = document.getElementById("cep").value.replace(/\D/g, "");

if(cep.length !== 8) {
    alert("CEP inválido.");
    return;
}

const url = `https://viacep.com.br/ws/${cep}/json/`;

const resposta = await fetch(url);
const dados = await resposta.json();

if(dados.erro) {
    alert('CEP não encontrado.');
    return;
}

document.getElementById("cidade").value = dados.localidade;
document.getElementById("estado").value = dados.uf;

calcularFrete(dados.localidade, dados.uf);

}

async function calcularFrete(cidade, estado) {
    
    let req = await fetch("/lycanproject/app/frete.php", {
        method: "POST",
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `cidade=${cidade}&estado=${estado}`
    });

    let resposta = await req.json();

    console.log("Resposta completa:", resposta);

    if(!resposta.erro) {
        let freteValor = parseFloat(resposta.frete.replace(/\./g, '').replace(',', '.'));

        document.getElementById('valorFrete').innerText = resposta.frete;

        let subtotalTexto = document.getElementById("subtotalValor").innerText;
        let subtotal = parseFloat(subtotalTexto.replace(/\./g, '').replace(',', '.'));

        let totalFinal = subtotal + freteValor;

        document.getElementById("totalFinal").innerText = totalFinal.toFixed(2).replace('.', ',');
    }
}