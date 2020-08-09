teste();
function teste() {
  var salvar = '';
  var eFeriado = '';
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
  var feriado = document.getElementById('feriado').value;
  var data = document.getElementById('data').value;
  var horasMesTotal = document.querySelectorAll('input.horasMesTotal');

  //Verifica se é final de semana ou feriado e adiciona o numero pra multiplicar
  var Minha_data = new Date(data);
  if (Minha_data.getDay() == 0 || Minha_data.getDay() == 6 || feriado == 'true') {
    var finalDeSemana = true
    eFeriado = 2;
  } else {
    var finalDeSemana = false
    eFeriado = 1;
  }

  for (id = 0; id < transporte.length; id++) {
    var transporteCheck = transporte[id];
    var alimentacaoCheck = alimentacao[id];
    var HorasInput = horas[id];
    var valorHoraInput = valorHora[id];
    var horasMesTotalInput = horasMesTotal[id];

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
      result = HorasInput.value * eFeriado; //Adiciona a hora extra 100%
      salvar += HorasInput.value;
      valorTotalHoras += valorHoraInput.value * result;
    }

    //Se campo hora extra está vazio ou menor q 0 da erro
    if (HorasInput.value == '' || HorasInput.value < 0) {
      HorasInput.value = 0;
      alert('Os campos Horas Extras devem ser preenchidos!\nCaso não haja hora extra, preencha com 0.');
    }
    
    var horasDisponivel = parseInt(HorasInput.value) + parseInt(horasMesTotalInput.value);
    if (horasDisponivel > 22){
      HorasInput.value = 0;
      horasDisponivel = parseInt(horasMesTotalInput.value) - 22;
      alert('Limite de horas no mês excedido!\nDisponível '+ Math.abs(horasDisponivel) +'h.');
    }
    
    if (finalDeSemana == false & HorasInput.value > 1.12) {
      HorasInput.value = 0;
      alert('Somente 1.12 horas extras permitido no meio de semana!');
    }else if (finalDeSemana == true & HorasInput.value > 6) {
      HorasInput.value = 0;
      alert('Somente 6 horas extras permitido nos finais de semanas!');
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
  for (var prop in dados) {

    valorTotalRotas += parseInt(valorRota[conte].value);
    conte++;
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

function selectFood(){
  var alimentacao = document.querySelectorAll('input.alimentacao');
  for (id = 0; id < alimentacao.length; id++) {
    if (alimentacao[id].checked){
      alimentacao[id].checked = false;
    }else{
      alimentacao[id].checked = true;
    }
  }
}

function selectTransport(){
  var transporte = document.querySelectorAll('input.transporte');
  for (id = 0; id < transporte.length; id++) {
    if (transporte[id].checked){
      transporte[id].checked = false;
    }else{
      transporte[id].checked = true;
    }
  }
}


