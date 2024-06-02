
$(document).ready(function() {
    $(".drop").click(function(event){
        console.log("clicked");
        console.log($(this));

        var button = $(this);
        const publication_id = event.target.dataset['id'];
        console.log(publication_id);

        let formData = new FormData();
        formData.append("pending_to_delete", publication_id);

        $.ajax({
            url: '../../vendor/publications/delete_publication.php', 
            type: 'POST',
            data: formData, 
            processData: false, 
            contentType: false, 
            success: function(response) { 
                console.log("registerYAY");
                console.log(response)
                data = JSON.parse(response)
                let received = "";
                if(data['status'] == 'success'){
                    button.closest('.publication').remove();
                    console.log("YAYAYY");
                    received = 
                        "<div>"+
                            "<p> Название удаленной публикации: " + data["publication_title"]+"</p>"+
                        "</div>";

                }
                let status = data['status'];
                let message = data['message'];


                showPopup(status, message, received);
                console.log(data);
                
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

    $(".edit").click(function(event){

        const publication_id = event.target.dataset['id'];

        var destinationImg = document.getElementById('destination_image');
        destinationImg.src = ""
        destinationImg.alt = ""
        var editable1 = $(".edit_form").find(".publication_title")
        editable1.text("");
        var editable2 = $(".edit_form").find(".publication_text");
        editable2.text("");



        console.log("clicked");
        var button = $(this);
        var parent = button.closest(".publication")

        var publication_image = parent.find(".publication_image img")
        var publication_title = parent.find(".publication_title").text()
        var publication_text = parent.find(".publication_text").text()



        $(".edit_form").removeClass("hidden");
        var editable1 = $(".edit_form").find(".publication_title")
        editable1.text(publication_title);
        var editable2 = $(".edit_form").find(".publication_text");
        editable2.text(publication_text);

        var edtiable3 = $(".edit_form").find(".publication_image img")
        edtiable3.attr("src", publication_image.attr("src")).attr("alt", publication_image.attr("alt"))

        $(".send_editing").data("id", publication_id)
        console.log(publication_id)
        window.scrollTo(0, 0)
        
    })

    $(".editable").click(function(event){

        if ($(this).hasClass("publication_image")){
            $(this).replaceWith("<div class = publication_image><p>Измените изображение</p><input type=file name='publication_image'></div>")
        }
        else if ($(this).hasClass("publication_text")){
            var text = $(this).html();
            $(this).replaceWith("<div class = publication_text><p>Измените текст</p><textarea name='publication_text' placeholder ='" + text + "'></textarea></div>")
            
        }
        else{
            var text = $(this).html();
            $(this).replaceWith("<div class=publication_title><p>Измените название</p><input type=text name='publication_title' placeholder='" + text + "'></div>")
        }
    })

    document.querySelector("#edit_form").addEventListener("submit", function(event){
        event.preventDefault()
        console.log("edtiting accepted")
        

        const button = $(".send_editing")
        console.log(event.target);
        const formData = new FormData(event.target)
        formData.append("publication_id", button.data("id"))
        $.ajax({
            url: '../../vendor/publications/edit_publication.php', 
            type: 'POST', 
            data: formData, 
            processData: false, 
            contentType: false, 
            success: function(response) { 
                console.log("registerYAY");
                console.log(response)
                data = JSON.parse(response)
                console.log(data);
                replace_default();
                let received = "";
                if(data['status'] == 'success'){

                    let old_title = data['old_publication_title'];
                    let old_text  = data['old_publication_text'];
                    let old_image = data['old_publication_image'];

                    let new_title = data['title'];
                    let new_text  = data['text'];
                    let new_image = data['image'];

                    let pub_id = data['publication_id'];

                    replace_data(pub_id, new_title, new_text, new_image)

                    received = "<div>"+
                        "<p> Публикация '" + old_title + "' обновлена</p>"+
                        "<b> Старые данные: </b><br/>"+
                        "<i> Старый текст: " + old_text + "</i>"+
                        (old_image == "" ? "<i> Изображения нет</i>" : "<i> Название старого изображения: "+ old_image + "</i>") 

                }
                let status = data['status'];
                let message = data['message'];
  
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


    $(".stop_editing").click(function(event){
        console.log("stop_editing");
        replace_default();
    })


    
});

function replace_data(id, title, text, image_path){
    console.log($("[data-id="+id+"]").first());
    console.log()
    let replacing_publication = $("[data-id="+id+"]").first().closest(".publication");
    replacing_publication.find(".publication_title").html(title);
    replacing_publication.find(".publication_text").html(text);
    
    if (image_path!=""){
        if(replacing_publication.has("img").length){
            replacing_publication.find("img").attr("src", "../../uploads/"+image_path).attr("alt", image_path);
        }
        else{
            replacing_publication.find(".publication_image").append("<img src = ../../uploads/" + image_path + " alt="+image_path+">");
        }
    }
    else{
        if(replacing_publication.has("img").length){
            replacing_publication.find(".publication_image").html(" ");
        }
    }
    
    

}


function replace_default(){
    $(".edit_form").addClass("hidden");
    $(".edit_form .publication_image").html("<p>Измените изображение кликом по области</p><img id = destination_image width=400 />");
    $(".edit_form .publication_image").addClass("editable");
    $(".edit_form .publication_text").replaceWith("<p class = 'publication_text editable'>jyst_test</p>");
    $(".edit_form .publication_title").replaceWith("<p class = 'publication_title editable'>jyst_test</p>");
    
    $(".editable").click(function(event){

        if ($(this).hasClass("publication_image")){
            $(this).replaceWith("<div class = publication_image><p>Измените изображение</p><input type=file name='publication_image'></div>")
        }
        else if ($(this).hasClass("publication_text")){
            var text = $(this).html();
            $(this).replaceWith("<div class = publication_text><p>Измените текст</p><textarea name='publication_text' placeholder ='" + text + "'></textarea></div>")
            
        }
        else{
            var text = $(this).html();
            $(this).replaceWith("<div class=publication_title><p>Измените название</p><input type=text name='publication_title' placeholder='" + text + "'></div>")
        }
    })
}


