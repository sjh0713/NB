


- 常用功能

        多个选中获取值：
        var expert_id = [];
        $('input[name="expert_id"]:checked').each(function(index,item){
            expert_id.push($(item).val());
        });
