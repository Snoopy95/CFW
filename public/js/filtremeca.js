console.log("je vais filtrer")

const client= document.querySelectorAll(".client")

client.forEach(function (selected) {
    selected.addEventListener("click", selectionner)
})

function selectionner() {
    client.forEach(function (e) {
        e.classList.remove("select")
    })
    this.classList.add("select")
    content=[]
    content.push(this.dataset.mac)
    content.push(this.textContent)
    ajaxpost(content)
}

function ajaxpost(data) {
    axios
        .post('../filtreclient', data)
        .then(function (response) {
            const listes = JSON.parse(response.data)
            console.log(listes)
            var template = document.querySelector("#ligne")
            var tbody = document.querySelector("tbody")

            var oldtr = document.querySelectorAll(".ligne")

            oldtr.forEach((e) => e.remove())

            listes.forEach (function(liste) {
            var clone = document.importNode(template.content, true)
            var th = clone.querySelector("th")
            th.textContent = liste.numprog
            var td = clone.querySelectorAll("td");
            td[0].textContent = liste.client
            td[1].textContent = liste.refpiece
            td[2].textContent = liste.ind
            td[3].textContent = liste.desigpiece
            if (liste.plan === null & liste.step === null) {
                td[4].textContent = null
            } else {
                aplan = clone.querySelector(".plan")
                astep = clone.querySelector(".step")
            if (liste.plan) {
                aplan.href = '../meca/plan/'+liste.plan
            } else {
                aplan.remove()
            }
            if (liste.step) {
                astep.href = '../meca/3d/'+liste.step
            } else {
                astep.remove()
            }
        }
            date = new Date(liste.datecreat)
            td[5].textContent = date
            tbody.appendChild(clone);
            })
        })
        .catch(function (error) {
            if (error.response.status === 400) {
                alert(error.response.data)
            }
        })
}