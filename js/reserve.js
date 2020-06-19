teste();
function teste() {
  var salvar = '';
  var valorTotalHoras = 0;
  var valorTotalRotas = 0;
  var valorTotalAlimentacao = 0;
  var valorGeral = 0;

  var transporte = document.querySelectorAll('input.transporte');
  var rota = document.querySelectorAll('input.rota');
  var alimentacao = document.querySelectorAll('input.alimentacao');
  var horas = document.querySelectorAll('input.horas');
  var valorHora = document.querySelectorAll('input.valorHora');
  var valorRota = document.querySelectorAll('input.valorRota');

  for (id = 0; id < transporte.length; id++) {
    var transporteCheck = transporte[id];
    var alimentacaoCheck = alimentacao[id];
    var HorasInput = horas[id];
    var valorHoraInput = valorHora[id];

    salvar += transporteCheck.value + "-";

    //verifica os checkbox de transporte
    if (transporteCheck.checked) {
      result = 1;
      salvar += result + "-";

    } else {
      result = 0;
      salvar += result + "-";
    }

    //verifica os checkbox de alimentacao
    if (alimentacaoCheck.checked) {
      valorTotalAlimentacao += 35; //Valor da alimentação
      result = 1;
      salvar += result + "-";
    } else {
      result = 0;
      salvar += result + "-";
    }

    //verifica os input de hora extra
    if (HorasInput !== 0) {
      result = HorasInput.value * 2; //Adiciona a hora extra 100%
      salvar += result;
      valorTotalHoras += valorHoraInput.value * result;
    }

    if (HorasInput.value == '' || HorasInput.value < 0) {
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
        dados[rota[i].value] += 1;
      }
    }

  }

  //compara cada id e soma o valor
  //var idQuatidade = "";
  var conte = 0;
  for (var prop  in dados) {

    valorTotalRotas += parseInt(valorRota[conte].value);
    conte ++;
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
  const t = document.getElementById('total').value = salvar;
  const inputValorGeral = document.getElementById('valorGeral').value = valorGeral;
  const inputValorTransporte = document.getElementById('valorTransporte').value = valorTotalRotas;
  const inputValorAlimentacao = document.getElementById('valorAlimentacao').value = valorTotalAlimentacao;
  const inputValorHoras = document.getElementById('valorHoras').value = valorTotalHoras;
  const totalColaboradores = document.getElementById('totalColaboradores').innerHTML = transporte.length;
  const valorTela = document.getElementById('valorTela').innerHTML = valorGeral.toLocaleString('pt-br', { style: 'currency', currency: 'BRL' });
  const totalValor = document.getElementById('totalValor').innerHTML = valorGeral.toLocaleString('pt-br', { style: 'currency', currency: 'BRL' });
  const nextBtn = document.getElementById('nextBtn').disabled = false;

}



