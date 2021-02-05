setTimeout(() => {
    $(".alert").alert("close");
}, 5000);

$(function () {
    $('[data-toggle="tooltip"]').tooltip()
})

const fileplan = document.querySelector('.inputPlan')
if (fileplan) {fileplan.addEventListener('change', addname)}

function addname() {
    newtext = fileplan.value.split('\\')
    document.querySelector('.labelplan').textContent = newtext[newtext.length-1]
}

