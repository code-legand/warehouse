function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#product_image')
                .attr('src', e.target.result);
        };

        reader.readAsDataURL(input.files[0]);
    }
}

function resize(){
    if(document.getElementById("log-tab").style.display == "none"){
        document.getElementById("log-tab").style.display = "block";
        document.getElementById("log-tab").style.height = "50%";
        document.getElementById("log-trigger").innerText = "Hide Log";
    }
    else{
        document.getElementById("log-tab").style.display = "none";
        document.getElementById("log-trigger").innerText = "Show Log";
    }
}