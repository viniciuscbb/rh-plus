var nextBtn = document.getElementById('nextBtn');
var btnSave = document.getElementById('save').onclick = function(){

  var salvar = '';
  for(id = 0; id<document.querySelectorAll('input.checkbox').length; id++){
    var checkbox = document.querySelectorAll('input.checkbox')[id];
    if(checkbox.checked){
      salvar = salvar + checkbox.value + "-"; 
    }
  }
  
  var t = document.getElementById('total').value = salvar;
  var saveSuccess = document.getElementById('saveSuccess');
  saveSuccess.style = 'display: block';
  nextBtn.disabled =false;
}



