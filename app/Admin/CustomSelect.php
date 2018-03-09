<?php
/**
 * Created by PhpStorm.
 * User: Vk
 * Date: 2018/3/9
 * Time: 下午7:06
 */

namespace App\Admin\Extensions;



use Encore\Admin\Facades\Admin;
use Encore\Admin\Form\Field\Select;

class CustomSelect extends Select
{

    public function cusLoad($field,$field1, $sourceUrl, $textField = 'soft', $textField1= 'hardware'){

        $script = <<<EOT
$(document).off('change', "{$this->getElementClassSelector()}");
$(document).on('change', "{$this->getElementClassSelector()}", function () {
    var target = $(this).closest('.fields-group').find(".$field");
    var target1 = $(this).closest('.fields-group').find(".$field1");
    
    $.get("$sourceUrl?q="+this.value, function (data) {
   
        target.find("option").remove();
        $(target).select2({
            data: $.map(data, function (d) {
//                d.id = d.$textField;
                d.text = d.$textField;
                return d;
            })
        }).trigger('change');
        $(target1).select2({
            data: $.map(data, function (d) {
//                d.id = d.$textField1;
                d.text = d.$textField1;
                return d;
            })
        }).trigger('change');
    });
});
EOT;

        Admin::script($script);

        return this ;

    }

}