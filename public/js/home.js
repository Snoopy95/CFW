setTimeout(() => {
    var alertList = document.querySelectorAll('.alert')
    alertList.forEach(function(alert) {
        new bootstrap.Alert(alert).close()
    })
}, 5000);

const fileplan = document.querySelector('.inputPlan')
if (fileplan) { fileplan.addEventListener('change', addname) }

function addname() {
    newtext = fileplan.value.split('\\')
    document.querySelector('.labelplan').textContent = newtext[newtext.length - 1]
}

const filestep = document.querySelector('.inputStep')
if (filestep) { filestep.addEventListener('change', addnamestep) }

function addnamestep() {
    newtext = filestep.value.split('\\')
    document.querySelector('.labelstep').textContent = newtext[newtext.length - 1]
}