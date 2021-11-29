console.log("hello de lu dans activer");

let listdel = new Array();
let content = new Object();

function ajaxpost(data) {
    axios
        .post(data.url, data.content)
        .then(function (response) {
            data.reload ? document.location.reload() : null
        })
        .catch(function (error) {
            if (error.response.status === 400) {
                alert(error.response.data)
            }
        })
}

function ajaxget(data) {
    axios
        .get(data.url)
        .then(function (response) {
            data.reload ? document.location.reload() : null
        })
        .catch(function (error) {
            if (error.response.status === 400) {
                alert(error.response.data)
            }
        })
}

function statut() {
    data = {
        url: `${this.dataset.url}/${this.dataset.id}`,
        reload: false,
    }
    ajaxget(data);
}

function valide_delete() {
    data = {
        url: this.dataset.url,
        reload: true,
        content: listdel,
    }
    ajaxpost(data);
}

function add_selection() {
    let id = this.dataset.id
    listdel.push(id)
    cadre_delete(true)
    card_fournisseur(id, false)
    badge()
}

function undo_selection() {
    let id = listdel.pop()
    badge()
    card_fournisseur(id, true)
    if (listdel.length < 1) {
        cadre_delete(false)
    }
}

function card_fournisseur(id, show) {
    cardfournisseur = document.querySelector("#card" + id);
    if (cardfournisseur) {
        show
            ? cardfournisseur.classList.remove("invisible")
            : cardfournisseur.classList.add("invisible");
    }
}

function cadre_delete(show) {
    cadre_del = document.querySelector("#cadre-del")
    show
        ? cadre_del.classList.remove("invisible")
        : cadre_del.classList.add("invisible")
}

function badge() {
    badget = document.querySelector(".badge")
    badget.textContent = listdel.length
}

function upcontact() {
    input = document.querySelector('#label' + this.dataset.id)
    text = input.textContent
    var re = /\s*(,|$)\s*/
    var nameList = text.split(re)
    val = {
        'mail': nameList[0],
        'nom': nameList[2],
        'id': this.dataset.id,
        'url': this.dataset.url
    }
    addcontact(val)
}

function addcontact(updata) {
    data = {}
    const titre = document.querySelector("#titreformulaire")
    const bouton = document.querySelector("#validercontact")
    valnom = document.querySelector("#username")
    valmail = document.querySelector("#mail")
    errormail = document.querySelector("#feedback-email").classList
    errorname = document.querySelector('#feedback-name').classList
    if (updata.id) {
        valmail.value = updata.mail
        valnom.value = updata.nom
        content['id'] = updata.id
        data.url = updata.url
        data.reload = true
        titre.textContent = 'Edition contact'
        bouton.textContent = 'Mettre à jour'
    } else {
        content["id"] = this.dataset.id
        data.url = this.dataset.url
        data.reload = true
        valmail.value = null
        valnom.value = null
        titre.textContent = 'Nouveau contact'
        bouton.textContent = 'Ajouter'
    }
    bouton.addEventListener("click", function () {
        if (
            /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(
                valmail.value
            )
        ) {
            errormail.add("invisible")
            Validmail = true
            content["name"] = valnom.value
        } else {
            errormail.remove("invisible")
            valmail.value = null
            Validmail = false
        }
        if (/^([a-zA-Z]+\s?)/.test(valnom.value)) {
            errorname.add("invisible")
            validname = true
            content["mail"] = valmail.value
        } else {
            errorname.remove("invisible")
            valnom.value = null
            validname = false
        }
        if (validname && Validmail) {
            data.content = content
            // $("#addcontact").modal("hide")
            ajaxpost(data)
        }
    });
}

function selectfamille() {
    data = {
        url: '../selectfamille',
        reload: false,
        content: {
            idfourn: this.dataset.idfourn,
            idfamille: this.dataset.idfamille
        }
    }
    ajaxpost(data)
}

function selectnuance() {
    data = {
        url: '../selectnuance',
        reload: false,
        content: {
            idfourn: this.dataset.idfourn,
            idnuance: this.dataset.idnuance
        }
    }
    ajaxpost(data)
}

function nameedit() {
    const fenetresaisie= document.querySelector("#name")
    const titrename = document.querySelector('#titrename')
    fenetresaisie.classList.toggle('invisible')   
    fenetresaisie.value= null
    fenetresaisie.addEventListener('change' ,saisie) 
    function saisie() {
        data=[]
        fenetresaisie.classList.toggle('invisible')
        content['name'] = fenetresaisie.value
        content['id'] = this.dataset.id
        data.content = content
        data.url = '../updatefournisseur'
        data.reload = false
        titrename.textContent = fenetresaisie.value
        ajaxpost(data)
    }
}

function codeedit() {
    const fenetresaisie = document.querySelector("#code")
    const titrecode = document.querySelector('#titrecode')
    fenetresaisie.classList.toggle('invisible')
    fenetresaisie.value = null
    fenetresaisie.addEventListener('change', saisie)
    function saisie() {
        data = []
        fenetresaisie.classList.toggle('invisible')
        content['code'] = fenetresaisie.value
        content['id'] = this.dataset.id
        data.content = content
        data.url = '../updatefournisseur'
        data.reload = false
        titrecode.textContent = fenetresaisie.value
        ajaxpost(data)
    }
}

document.querySelectorAll(".switch-tab").forEach(function (selected) {
    selected.addEventListener("change", statut)
})

document.querySelectorAll(".del-btn").forEach(function (selected) {
    selected.addEventListener("click", add_selection)
})

document.querySelectorAll(".addcont").forEach(function (selected) {
    selected.addEventListener("click", addcontact)
})

document.querySelectorAll(".upcont").forEach(function (selected) {
    selected.addEventListener("click", upcontact)
})

document.querySelectorAll('.selectfamille').forEach(function (selected) {
    selected.addEventListener('change', selectfamille)
})

document.querySelectorAll('.selectnuance').forEach(function (selected) {
    selected.addEventListener('change', selectnuance)
})

const btn_delete = document.querySelector("#btn-delete")
if (btn_delete) { btn_delete.addEventListener("click", valide_delete) }

const btn_undo = document.querySelector("#btn-undo")
if (btn_undo) { btn_undo.addEventListener("click", undo_selection) }

const btn_name = document.querySelector('#nameedit')
if (btn_name) { btn_name.addEventListener('click', nameedit) }

const btn_code = document.querySelector('#codeedit')
if (btn_code) { btn_code.addEventListener('click', codeedit) }