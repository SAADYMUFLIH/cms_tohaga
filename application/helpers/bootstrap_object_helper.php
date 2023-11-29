<?php

function generateStandardInputBox($caption, $objid='', $objname, $value='', $event='',  $required = true, $max_length=0, $help_text='', $add_class='', $readonly= false, $min_length=0){

    $textvalue = (!empty($value) ?  "value ='".htmlspecialchars($value, ENT_QUOTES)."'": '' );
    $textid = (!empty($objid) ? $objid : $objname );
    $placeholder = "Input ". $caption;
    $required = ($required==true)? 'required' : '';
    $readonly = ($readonly==true)? 'readonly' : '';
    $max_length = ($max_length!=0)?'maxlength="'.$max_length.'"' : '';
    $min_length = ($min_length!=0)?'minlength="'.$min_length.'"' : '';
    $help_text = ($help_text!='')?'<small class="form-text text-info">'.$help_text.'</small>' : '';    
    
    $standarInputBox ='<div class="form-group">
    <label for="'.$objname.'">'.$caption.'</label>
    <input type="text"  '.$required.' '.$readonly.' '.$textvalue.' '.$max_length.' '.$min_length.'    class="form-control form-control-sm '.$add_class.'" '.$event.' id="'.$textid.'" name="'.$objname.'" placeholder="'.$placeholder.'">
    '.$help_text.'
    </div>';

    return  $standarInputBox;
}

function generateEmailInputBox( $caption,  $objid='',  $objname,  $value='',  $event='',  $required = true ){

    $textvalue = (!empty($value) ?  "value ='$value'": '' );
    $textid = (!empty($objid) ? $objid : $objname );
    $placeholder = "Input ". $caption;
    $required = ($required==true)? 'required' : '';

    $emailInputBox ='<div class="form-group">
    <label for="'.$objname.'">'.$caption.'</label>
    <input type="email"  '.$required.' '.$textvalue.'  class="form-control form-control-sm text-lowercase" '.$event.' id="'.$textid.'" name="'.$objname.'" placeholder="'.$placeholder.'">
    </div>';

    return  $emailInputBox;
}

function generatePasswordInputBox( $caption,  $objid='',  $objname,  $value='',  $event='',  $required = true ){

    $textvalue = (!empty($value) ?  "value ='$value'": '' );
    $textid = (!empty($objid) ? $objid : $objname );
    $placeholder = "Input ". $caption;
    $required = ($required==true)? 'required' : '';

    $passwordInputBox ='<div class="form-group">
    <label for="'.$objname.'">'.$caption.'</label>
    <input type="password"  '.$required.' '.$textvalue.'  class="form-control form-control-sm text-lowercase" '.$event.' id="'.$textid.'" name="'.$objname.'" placeholder="'.$placeholder.'">
    </div>';

    return  $passwordInputBox;
}

function generateDatePicker($caption,  $objid='',  $objname,  $value='',  $event='',  $required = true, $date_format='dd/mm/yyyy'){
    $textvalue = (!empty($value) ?  "value ='$value'": '' );
    $textid = (!empty($objid) ? $objid : $objname );
    $required = ($required==true)? 'required' : '';
    $date_picker = ' 
    <div class="form-group">
        <label for="'.$objname.'">'.$caption.'</label>
        <div class="input-group">
            <input type="text" '.$required.' '.$textvalue.' class="form-control form-control-sm datepicker" name="'.$objname.'" id="'.$textid.'">
            <div class="input-group-append">
                <span class="input-group-text"><i class="fas fa-calendar-alt  "></i></span>
            </div>
        </div>
    </div>';
    $date_picker.='
    <script>
    $(document).ready(function() {
        $("#'.$textid.'").datepicker({
            format: "'.$date_format.'",
            autoclose: true,
            todayBtn: "linked",
            todayHighlight: true,
            orientation: "auto"
        })
    });
    </script>';

    return $date_picker;
}

function generateDisplayOnlyObject($col_width, $label_caption, $text_value){
    $displayOnlyObject ='
    <div class="col-md-'.$col_width.'">
    <div class="mt-2"></div>
        <label style="font-size:14px" class="text-muted font-italic">'.$label_caption.'</label>
        <div class="border-bottom">'.$text_value.'</div>
        <div class="mt-2"></div>
    </div>
    ';
    return $displayOnlyObject;
}

function generateInlineDisplayOnlyObject($col_width, $label_caption, $text_value){
    $inlineDisplayOnlyObject = '
    <div class="form-group row" style="margin-bottom:0px">
        <label for="inputName" class="col-sm-2 col-form-label" style="font-size:14px" >'.$label_caption.'</label>
        <div style="line-height: 40px;">:</div>
        <div class="col-sm-'.$col_width.'">
            <div class="border-bottom" style="line-height: 30px; font-size:14px; padding-top:10px padding-bottom: 1px "> '.$text_value.' </div>
        </div>
   </div>';
    return $inlineDisplayOnlyObject;
}