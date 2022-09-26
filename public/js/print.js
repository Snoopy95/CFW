function print(pdf) {
    // Créer un IFrame.
    var iframe = document.createElement('iframe');  
    // Cacher le IFrame.    
    iframe.style.visibility = "hidden"; 
    // Définir la source.    
    iframe.src = pdf;        
    // Ajouter le IFrame sur la page Web.    
    document.body.appendChild(iframe);  
    iframe.contentWindow.focus();       
    iframe.contentWindow.print(); // Imprimer.
}