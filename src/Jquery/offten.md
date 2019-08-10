## 常用功能

- 单选按钮
      
      $(".jobType").on("change","input[type='radio']",function () {
          var val = $(this).val();
          if (val == "1") {
              $(this).parents(".form-group").siblings(".jobType_name").show();
          }else{
              $(this).parents(".form-group").siblings(".jobType_name").hide();
          }
      })
      
      解析：
          jobType为大标签类，input[type='radio']单选按钮改变的时候执行之后的方法
          input中的值获取用 $(this).val()
          $(this).parents(".form-group").siblings(".jobType_name").show();点击的父亲节点，的兄弟节点展示或隐藏

- 通过身份证获取性别和出生年月

        案例：
            $("#idCard").blur(function(){
                    GetBirthdatByIdNo($(this).val());
            });
            
            function GetBirthdatByIdNo(iIdNo){
                var tmpStr = "";
                var birthday = "";
                iIdNo = $.trim(iIdNo);
                if(iIdNo.length == 15){
                    tmpStr = iIdNo.substring(6, 12);
                    tmpStr = "19" + tmpStr;
                    tmpStr = tmpStr.substring(0, 4) + "-" + tmpStr.substring(4, 6) + "-" + tmpStr.substring(6);
                    sexStr = parseInt(iIdNo.substring(14, 1),10) % 2 ? "男" : "女";
                }else{
                    tmpStr = iIdNo.substring(6, 14);
                    tmpStr = tmpStr.substring(0, 4) + "-" + tmpStr.substring(4, 6) + "-" + tmpStr.substring(6);
                    sexStr = parseInt(iIdNo.substring(17, 1),10) % 2 ? "男" : "女";
                }
                $("#js_birthday").val(tmpStr);
                $("#sex").val(sexStr);
        
            }
