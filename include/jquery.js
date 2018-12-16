$(document).ready(function(){
  var codeDispo = 0;
  $('.legende li').click(function(){
    switch(this.id)
    {
      case "indispo" : codeDispo = 0
      break;
      case "dispo" : codeDispo = 1
      break;
      case "travail" : codeDispo = 2
      break;
    }
    console.log(codeDispo);
  })
  $('.dispo').click(function(event)
  {
    $(event.target).attr("value", codeDispo);
  })

  $("#listLibre option").click(function()
  {
    $(this).attr('hidden','true');
    $("#listEquipe option[value='"+$(this).val()+"']").removeAttr('hidden').addClass('selec');
  });

  $("#listEquipe option").click(function()
  {
    $(this).attr('hidden','true');
    $("#listLibre option[value='"+$(this).val()+"']").removeAttr('hidden').removeAttr('selec');
  });

  $("#vldEquip").click(function()
    {
      $(".selec").each(function()
      {
        $(this).attr('selected','true');
      })
      $("#formInter").submit();
    }
  )
  $(".inter_grid").click(function(){
    $(this).children(".inter_participants").toggleClass("inter_participants__hidden");
  })
})
