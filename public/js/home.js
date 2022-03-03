
setTimeout(() => {
    var alertList = document.querySelectorAll('.alert')
    alertList.forEach(function (alert) {
        new bootstrap.Alert(alert).close()
    })
}, 5000);

const fileplan = document.querySelector('.inputPlan')
if (fileplan) { fileplan.addEventListener('change', addname) }

function addname() {
    newtext = fileplan.value.split('\\')
    document.querySelector('.labelplan').textContent = newtext[newtext.length - 1]
} ''