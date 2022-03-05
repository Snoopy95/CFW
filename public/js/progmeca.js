const check_dossier = document.querySelector("#checkDossier")
if (check_dossier) { check_dossier.addEventListener("click", checkDossier) }

const input_dossier = document.querySelector("#inputDossier")
if (input_dossier) {
    input_dossier.addEventListener("keydown", event => {
        console.log(event.keyCode)
        if (event.keyCode === 13) {
            checkDossier()
        }
    })
}

const alertPlaceholder = document.getElementById('liveAlertPlaceholder')

function alert(message, type) {
    var wrapper = document.createElement('div')
    wrapper.classList = 'col-10'
    wrapper.innerHTML = '<div class="alert alert-' + type + ' alert-dismissible" role="alert"><i class="fas fa-exclamation-triangle"></i><span> ' + message + '</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
    alertPlaceholder.append(wrapper)
    input_dossier.value = null
}

function remplir(data) {
    document.querySelector('#add_prog_client').value = data.client
    document.querySelector('#add_prog_refpiece').value = data.refpiece
    document.querySelector('#add_prog_ind').value = data.ind
    document.querySelector('#add_prog_desigpiece').value = data.desigpiece
    let plan = document.querySelector('#add_prog_plan')
    let label_plan = document.querySelector('.label-plan')
    let retour_plan = document.querySelector('.retourplan')
    if (data.plan) {
        plan.disabled= true
        label_plan.textContent = data.plan
        retour_plan.value = data.plan
    }
    let step = document.querySelector('#add_prog_step')
    let label_step = document.querySelector('.label-step')
    let retour_step = document.querySelector('.retourstep')
    if (data.step) {
        step.disabled= true
        label_step.textContent = data.step
        retour_step.value = data.step
    }
}

function checkDossier() {
    let content = input_dossier.value
    const alertNode = document.querySelector('.alert')
    if (alertNode) {
        new bootstrap.Alert(alertNode).close()
    }

    axios
        .post("checkdossier", content)
        .then(function (response) {
            const obj = JSON.parse(response.data)
            data = obj[0]
            remplir(data)
        })
        .catch(function (error) {
            if (error.response.status === 400) {
                alert(error.response.data, 'danger')
            }
        });
}