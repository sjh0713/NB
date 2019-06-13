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

