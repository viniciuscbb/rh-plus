teste();
function teste() {
  var salvar = '';
  var valorTotalHoras = 0;
  var valorTotalRotas = 0;
  var valorTotalAlimentacao = 0;
  var valorGeral = 0;

  var transporte = document.querySelectorAll('input.transporte');
  var rota = document.querySelectorAll('input.rota');
  var valorRota = document.querySelectorAll('input.valorRota');
  var alimentacao = document.querySelectorAll('input.alimentacao');
  var horas = document.querySelectorAll('input.horas');
  var valorHora = document.querySelectorAll('input.valorHora');

  for (id = 0; id < transporte.length; id++) {
    var transporteCheck = transporte[id];
    var alimentacaoCheck = alimentacao[id];
    var HorasInput = horas[id];
    var valorHoraInput = valorHora[id];

    salvar = salvar + transporteCheck.value + "-";

    //verifica os checkbox de transporte
    if (transporteCheck.checked) {
      result = 1;
      salvar = salvar + result + "-";

    } else {
      result = 0;
      salvar = salvar + result + "-";
    }

    //verifica os checkbox de alimentacao
    if (alimentacaoCheck.checked) {
      valorTotalAlimentacao += 35;
      result = 1;
      salvar = salvar + result + "-";
    } else {
      result = 0;
      salvar = salvar + result + "-";
    }

    //verifica os input de hora extra
    if (HorasInput !== 0) {
      result = HorasInput.value;
      salvar = salvar + result;
      valorTotalHoras = valorTotalHoras + valorHoraInput.value * result;
    }

    if (HorasInput.value == '') {
      HorasInput.value = 0;
      alert('Os campos Horas Extras devem ser preenchidos!\nCaso não haja hora extra, preencha com 0.')
    }

    if (id !== transporte.length - 1) {
      salvar = salvar + ";";
    } else {
      salvar = salvar;

    }
  }

  //verifica quantos transportes estão selecionados por id
  var dados = {};
  for (i = 0; i < rota.length; i++) {
    if (transporte[i].checked) {
      if (dados[rota[i].value] === undefined) {
        dados[rota[i].value] = 1;
      } else {
        dados[rota[i].value] = dados[rota[i].value] + 1;
      }
    }

  }

  //compara cada id e soma o valor
  //var idQuatidade = "";
  for (var prop in dados) {
    valorTotalRotas = parseInt(valorTotalRotas) + parseInt(valorRota[prop].value);
    //idQuatidade = idQuatidade + prop + "-" + dados[prop] + ";";
  }

  /*console.log(idQuatidade);
  console.log(valorTotalRotas);
  console.log(valorTotalAlimentacao);
  console.log(valorTotalHoras);
  */

  valorGeral = valorTotalRotas + valorTotalAlimentacao + valorTotalHoras;
  //console.log(valorGeral);

  //mostra os valores na tela
  var t = document.getElementById('total').value = salvar;
  var inputValorGeral = document.getElementById('valorGeral').value = valorGeral;
  var totalColaboradores = document.getElementById('totalColaboradores').innerHTML = transporte.length;
  var valorTela = document.getElementById('valorTela').innerHTML = valorGeral.toLocaleString('pt-br', { style: 'currency', currency: 'BRL' });
  var totalValor = document.getElementById('totalValor').innerHTML = valorGeral.toLocaleString('pt-br', { style: 'currency', currency: 'BRL' });
  var nextBtn = document.getElementById('nextBtn').disabled = false;

}



