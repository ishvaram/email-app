
$(function() {
    $("#tabs").tabs();
    $("#status-tab").tabs();
    $(".date_pick").datepicker({
        dateFormat: 'mm/dd/yy',
        changeMonth: true,
        changeYear: true
    });
});    
function openPanel(event,class_name,default_value)
{        
    var name_array = Array();
    $("."+class_name).find('input:text, input:radio, input:checkbox, select, textarea').each(function() {
        name_array.push($(this).attr('name'));   
    });        
    setDefaultValue(name_array);
    var isOpen = $(event).val();
    if(isOpen==default_value)
    {
        $("."+class_name).show();
    }
    else
    {
        $("."+class_name).hide();
    }    
}
function openmilitaryPanel(event)
{
    var name_array = Array();
    $(".militaryPanel_1, .militaryPanel_2").find('input:text, input:radio, input:checkbox, select, textarea').each(function() {           
        name_array.push($(this).attr('name'));
    });        
    setDefaultValue(name_array);
    var isOpen = $(event).val();        
    if(isOpen=='NotApplicable')
    {
        $(".militaryPanel_1").hide();
    }
    else
    {
        $(".militaryPanel_1").show();
        if(isOpen=='Active') $(".militaryPanel_2").hide();
    }    
}
function setDefaultValue(name_array)
{
    var name_json = JSON.stringify(name_array);        
    $.ajax({
        url: "<?php echo $this->basePath().'/applicants/ajaxTextEvent'?>",
        type: 'POST',
        dataType: "json",
        data: ({ pkey:'<?php echo $pkey;?>', input_name: name_json }), 
        beforeSend: function(){                
            $("#tabs-1").addClass("ajax-loader");
        }, 
        success: function (response) {
            $.each( response, function( key, value ) {                    
                $("input[name='"+key+"']").val(value);
                $("textarea[name='"+key+"']").val(value);
            });
        },         
        complete: function(){
            $("#tabs-1").removeClass("ajax-loader");          
        }
    });
}

