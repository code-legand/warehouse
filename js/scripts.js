p_image.onchange = evt => {
    const [file] = p_image.files
    if (file) {
      product_image.src = URL.createObjectURL(file)
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