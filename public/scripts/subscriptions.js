$(document).ready(function(){
    $(".subscribe").submit(submit_subscription)
    $(".unsubscribe").submit(submit_unsubscription)
})

function submit_subscription(event){
    event.preventDefault();
    const form = $(this)
    const user_id = form.find("button").attr("data-id");
    const formData = new FormData();
    formData.append("subscribe_to", user_id)

    $.ajax({
        url: '../../vendor/users/subscribe.php', 
        type: 'POST', 
        data: formData, 
        processData: false, 
        contentType: false, 
        success: function(response) { 
            console.log(response)
            
            data = JSON.parse(response);
            console.log("Received from server:", data);

            let status = data['status'];
            let message = data['message'];
            
            
            let received = ""
            if(status == "success"){
                received = "<div>" +
                            "<p> Вы подписались на @" +  data['login']+ "</p>"
                        "</div>"
                update_subscribtion(user_id)
            }
                
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
    })
}

function submit_unsubscription(event){
    event.preventDefault();
    const form = $(this)
    const user_id = form.find("button").attr("data-id");

    const formData = new FormData();
    formData.append("unsubscribe_from", user_id)
    
    $.ajax({
        url: '../../vendor/users/subscribe.php', 
        type: 'POST', 
        data: formData, 
        processData: false, 
        contentType: false, 
        success: function(response) { 
            console.log(response)
            
            data = JSON.parse(response);
            console.log("Received from server:", data);
            
            let status = data['status'];
            let message = data['message'];
        
            let received = ""
            if(status == "success"){
                received = "<div>" +
                                "<p> Вы отписались от @" +  data['login']+ "</p>"
                            "</div>"
                update_unsubscribtion(user_id)
            }

            
            
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

}


function update_subscribtion(id){
    const subber = $("[data-id="+id+"]").closest(".subber");
    subber.html("<div class = 'status'>Подписан</div>" + 
                    "<form class='unsubscribe'>" + 
                        "<button type='submit' data-id = " + id + ">Отписаться</button>" +
                    "</form>")
    subber.find("form").submit(submit_unsubscription)

}

function update_unsubscribtion(id){
    const subber = $("[data-id="+id+"]").closest(".subber");
    subber.html("<div class = 'status'>Не подписан</div>" + 
                    "<form class='subscribe'>" + 
                        "<button type='submit' data-id = " + id + ">Подписаться</button>" +
                    "</form>")
    subber.find("form").submit(submit_subscription)
}


