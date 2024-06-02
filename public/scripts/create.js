$(document).ready(function() {
    $("#create_form").submit(function(event){
        event.preventDefault();
        const formData = new FormData(event.target);

        const keys = ['publication_title', "publication_text"];

        for(key of keys){
            if(formData.get(key) == ""){
                let status = "error";
                let message = "Не все поля заполнены";
                let received = "Поле '"+ (key == "publication_title" ? "Название публикации ": "Текст публикации") + "' не заполнено"
                showPopup(status, message, received);
                return;
            }
        }


        keys.forEach(key => {
            if(formData.get(key) == "")
                return
        })
        formData.forEach((value, key) => {
            console.log(`${key}: ${value}`);
        });

        
        $.ajax({
            url: '../../vendor/publications/create_publication.php',
            type: 'POST', 
            data: formData, 
            processData: false, 
            contentType: false, 
            success: function(response) { 
                console.log("received")
                data = JSON.parse(response);

                let status = data['status'];
                let message = data['message'];
                console.log(data);
                console.log(data['got_iamge'])
               
                let received =
                    "<div>" +
                        "<p> Создана публикация: " +  data['publication_title']+ "</p>" +
                        "<i>" + (data['got_image'] ? "Имеет изображение" : "Без изображения") + "</i>"+
                    "</div>"
                
                showPopup(status, message, received);
                
                
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
    })
})