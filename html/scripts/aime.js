

function aime(){
    const boutonAime = document.getElementById("pouceHaut");
    //alert("BOO !");
    nbPouce=parseInt(boutonAime.value.split("")[0]);
    nbPouce+=1;
    console.log(nbPouce);
    boutonAime.value=nbPouce+"👍";
    boutonAime.disabled=true;
}

function aimePas(){
    const boutonAimePas = document.getElementById("pouceBas")
    //alert("BOO !");
    nbPouce=parseInt(boutonAimePas.value.split("")[0]);
    nbPouce+=1;
    console.log(nbPouce);
    boutonAimePas.value=nbPouce+"👍";
    boutonAimePas.disabled=true;
}
