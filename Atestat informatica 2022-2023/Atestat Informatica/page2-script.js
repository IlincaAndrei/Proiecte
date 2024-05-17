var ct = -1;
const BtnAdd = document.getElementById("nH");
const progressbar = document.getElementById("p_bar");
Resizer(document.getElementById('prv'));

NewHeader();

function NewLiner() {
    text = document.getElementById('txtar').value;
    text = text.replace(/ /g, "[sp]");
    text = text.replace(/\n/g, "[nl]");
    document.getElementById('txtar').value = text;
    return false;
}

function NewHeader() {
    

    ct++;
    var brk = [];
    const nmb_brk = 6;

    for (i = 0; i < nmb_brk; i ++) {
        brk[i] = document.createElement("br");
        brk[i].className = "brk" + ct;
    }

    const img = document.createElement("img");
    img.id = "img" + ct;
    img.className = "img";

    const imag = document.createElement("input");
    imag.type = "file";
    imag.accept = "image/png, image/jpeg";
    imag.id = "imag" + ct;
    imag.className = "imag_cls";
    imag.name = "imag" + ct;

    const label = document.createElement("label");
    label.setAttribute('for', 'imag' + ct);
    label.id = "label" + ct;
    label.className = "label_file";
    label.innerHTML = "Aduaga imagine de sectiune ";

    const lbl = document.createElement("label");
    lbl.innerHTML = "Titlu sectiune:";
    lbl.id = "lbl" + ct;
    lbl.className = "lbl";

    const newHeader = document.createElement("input");
    newHeader.type = "text";
    newHeader.id = "head" + ct;
    newHeader.name = "head" + ct;
    newHeader.className = "head";
    newHeader.maxLength = "35";

    const txtarr = document.createElement("textarea");
    txtarr.className = "ip2";
    txtarr.id = "txt" + ct;
    txtarr.name = "txt" + ct;
    txtarr.className = "txt";
    txtarr.style = "resize:none; min-height:100px;";
  
    Resizer(txtarr);

    const BtnDel = document.createElement("input");
    BtnDel.type = "button";
    BtnDel.id = "del" + ct;
    BtnDel.className = "btn-del";
    BtnDel.value = "Sterge aceasta sectiune";
    BtnDel.onclick = function () { RemoveHeader(BtnDel.id.slice(BtnDel.id.indexOf('l') + 1)); }  

    const form = document.getElementById("form1");

    form.insertBefore(brk[0], BtnAdd);
    form.insertBefore(lbl, BtnAdd);
    form.insertBefore(newHeader, BtnAdd);
    form.insertBefore(brk[4], BtnAdd);
    form.insertBefore(brk[5], BtnAdd);
    form.insertBefore(BtnDel, BtnAdd);
    form.insertBefore(imag, BtnAdd);
    form.insertBefore(label, BtnAdd);
    form.insertBefore(brk[1], BtnAdd);
    form.insertBefore(img, BtnAdd);
    form.insertBefore(brk[2], BtnAdd);
    form.insertBefore(txtarr, BtnAdd);
    form.insertBefore(brk[3], BtnAdd);

   
    imag.addEventListener("change", function () {
        const file = this.files[0];

        const reader = new FileReader();
        reader.addEventListener("load", function () {
            img.style = " max-height: 250px;max-width: 250px;display:inline-block; border-radius:5px; margin-bottom:10px ;";
            img.src = reader.result;
        });
        reader.readAsDataURL(file);
    });

    update(progressbar);
}

function RemoveHeader(clicked_id) {
    

    var dumm0 = document.getElementById("del" + clicked_id);
    var dumm1 = document.getElementById("head" + clicked_id);
    var dumm2 = document.getElementById("txt" + clicked_id);
    var dumm3 = document.getElementById("lbl" + clicked_id);
    var dumm4 = document.getElementById("imag" + clicked_id);
    var dumm5 = document.querySelectorAll(".brk" + clicked_id);
    var dumm6 = document.getElementById("img" + clicked_id);
    var dumm7 = document.getElementById("label" + clicked_id);

    const form = document.getElementById("form1");

    form.removeChild(dumm0);
    form.removeChild(dumm1);
    form.removeChild(dumm2);
    form.removeChild(dumm3);
    form.removeChild(dumm4);
    
    for (i = 0; i < dumm5.length; i++)
        form.removeChild(dumm5[i]);

    form.removeChild(dumm6);
    form.removeChild(dumm7);
   
    for (var cont = +clicked_id; cont < ct ; cont++)
    {
        dumm0 = document.getElementById("del" + (cont + 1));
        dumm0.id = "del" + cont;
        
            
        dumm1 = document.getElementById("head" + (cont + 1));
        dumm1.id = "head" + cont;
            
                
        dumm2 = document.getElementById("txt" + (cont + 1));
        dumm2.id = "txt" + cont;
                
                   
        dumm3 = document.getElementById("lbl" + (cont + 1));
        dumm3.id = "lbl" + cont;
                    
                       
        dumm4 = document.getElementById("imag" + (cont + 1));
        dumm4.id = "imag" + cont;
                        
        dumm5 = document.querySelectorAll(".brk" + (cont + 1));
        for (i = 0; i < dumm5.length; i++)
            dumm5[i].className = "brk" + cont;
        
        dumm6 = document.getElementById("img" + (cont + 1));
        dumm6.id = "img" + cont;

        dumm7 = document.getElementById("label" + (cont + 1));
        dumm7.id = "label" + cont;
        
        
    }
    ct--;
    update(progressbar);
}

function Resizer(txtarr) {
    txtarr.addEventListener('input', (e) => {
        e.target.style.height = 'auto';
        const textareaHeight = e.target.scrollHeight;
        e.target.style.height = textareaHeight + 'px';
        e.target.style.height = e.target.scrollHeight + 'px';

    });
}


imag_prv = document.getElementById("prv_img");
inp_prv = document.getElementById("file0");

inp_prv.addEventListener("change", function () {
    const file = this.files[0];

    const reader = new FileReader();
    reader.addEventListener("load", function () {
        imag_prv.style = " max-height: 250px;max-width: 250px;display:inline-block; border-radius:5px; margin-bottom:10px ;";
        imag_prv.src = reader.result;
    });
    reader.readAsDataURL(file);
});


function update(progressbar) {
    let h = document.documentElement;

    let st = h.scrollTop || document.body.scrollTop;
    let sh = h.scrollHeight || document.body.scrollHeight;

    let percent = st / (sh - h.clientHeight) * 100;

    progressbar.style.width = percent + "%";
}