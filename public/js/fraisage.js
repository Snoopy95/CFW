function checkDossier() {

    let content = document.querySelector('#inputDossier')
    console.log("je cherche le dossier :", content.value)

    var alertNode = document.querySelector('.alert')
    if (alertNode) {
        new bootstrap.Alert(alertNode).close()
    }

    axios
        .post("checkdossier", content.value)
        .then(function (response) {
            dossier = response.data
            const obj = JSON.parse(dossier)
            const data = obj[0]
            document.querySelector('#inputClient').value = data.client
            document.querySelector('#inputRef').value = data.refpiece
            document.querySelector('#inputInd').value = data.ind
            document.querySelector('#inputDesign').value = data.desigpiece
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
                content.value = null
            }
        })
}

const check_dossier = document.querySelector("#checkDossier")
if (check_dossier) { check_dossier.addEventListener("click", checkDossier) }