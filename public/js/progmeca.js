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

function checkDossier() {

    let content = input_dossier.value
    console.log("je cherche le dossier :", content)

    var alertNode = document.querySelector('.alert')
    if (alertNode) {
        new bootstrap.Alert(alertNode).close()
    }

    axios
        .post("checkdossier", content)
        .then(function (response) {
            dossier = response.data
            const obj = JSON.parse(dossier)
            const data = obj[0]
            document.querySelector('#inputClient').value = data.client
            document.querySelector('#inputRef').value = data.refpiece
            document.querySelector('#inputInd').value = data.ind
            document.querySelector('#inputDesign').value = data.desigpiece
            let plan = document.querySelector('#inputPlan')
            plan.name = data.plan
            plan.textContent = data.plan
            let step = document.querySelector('#inputStep')
            step.name = data.step
            step.textContent = data.step
        })
        .catch(function (error) {
            if (error.response.status === 400) {
                var alertPlaceholder = document.getElementById('liveAlertPlaceholder')

                function alert(message, type) {
                    var wrapper = document.createElement('div')
                    wrapper.classList = 'col-10'
                    wrapper.innerHTML = '<div class="alert alert-' + type + ' alert-dismissible" role="alert"><i class="fas fa-exclamation-triangle"></i><span> ' + message + '</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'

                    alertPlaceholder.append(wrapper)
                }
                alert(error.response.data, 'danger')
                input_dossier.value = null
            }
        })
}