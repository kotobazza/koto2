$(document).ready(function() {
    $('#register_form').submit(function(event) { 
        event.preventDefault(); 

        const formData = new FormData(event.target);

        const keys = ['login', "password", "confirm"];
        const map_keys = {
            "login": "Логин",
            "password": "Пароль",
            "confirm": "Подтрвеждение пароля",
        }
        

        for(key of keys){
            console.log(formData.get(key))
            if(formData.get(key) == ""){
                let status = "error";
                let message = "Не все поля заполнены";
                let received = "Поле '"+ map_keys[key] + "' не заполнено"
                showPopup(status, message, received);
                return;
            }
        }


        $.ajax({
            url: '../../vendor/users/signup.php', 
            type: 'POST', 
            data: formData, 
            processData: false, 
            contentType: false, 
            success: function(response) { 
                console.log("registerYAY");
                console.log(response);
                data = JSON.parse(response);
                let received = ""

                if(data['status'] == "success"){
                    received = "Скоро вас перенаправят на основную страницу"
                }

                showPopup(data['status'], data['message'], received)
                if(data['status'] == "success"){
                    console.log("set timeout")
                    setTimeout(function(){
                        window.location.href="/"
                    }, 3000)
                }


                
            },
            error: function(xhr, status, error) { 
                if (xhr.status === 400) {
                    showPopup(xhr.status, "Ошибка 400", "");
                } else {
                    showPopup(xhr.status, "Ошибка", String(error));
                    console.log(String(error));
                }
            }
        });
    });
    $('#login_form').submit(function(event) { 
        event.preventDefault(); 

        const formData = new FormData(event.target);

        const keys = ['login', "password"];
        const map_keys = {
            "login": "Логин",
            "password": "Пароль",
            "confirm": "Подтрвеждение пароля",
        }
        
        for(key of keys){
            console.log(formData.get(key))
            if(formData.get(key) == ""){
                let status = "error";
                let message = "Не все поля заполнены";
                let received = "Поле '"+ map_keys[key] + "' не заполнено"
                showPopup(status, message, received);
                return;
            }
        }

        $.ajax({
            url: '../../vendor/users/signin.php', 
            type: 'POST', 
            data: formData, 
            processData: false, 
            contentType: false, 
            success: function(response) { 
                console.log("loginYAY");
                console.log(response);

                data = JSON.parse(response);
                let received = ""

                if(data['status'] == "success"){
                    received = "Скоро вас перенаправят на основную страницу"
                }

                showPopup(data['status'], data['message'], received)
                

                if(data['status'] == "success"){
                    setTimeout(function(){
                        window.location.href="/"
                    }, 3000)
                }

                

               
                
            },
            error: function(xhr, status, error) { 
                if (xhr.status === 400) {
                    showPopup(xhr.status, "Ошибка 400", "");
                } else {
                    showPopup(xhr.status, "Ошибка", String(error));
                    console.log(String(error));
                }
            }
        });
    });
});