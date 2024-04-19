const passwordInput = document.querySelector('#registration_form_plainPassword_first')
// const regex = /^(?=.*[!@#$%^&*()\[\]{};:<>|.\/?])(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{12,}$/
const caractere = document.querySelector('.caractere')
const majuscule = document.querySelector('.majuscule')
const minuscule = document.querySelector('.minuscule')
const chiffre = document.querySelector('.chiffre')
const longueur = document.querySelector('.longueur')
const regexCaractere = /(?=.*[!@#$%^&*()\[\]{};:<>|.\/?])/
const regexMajuscule = /(?=.*[A-Z])/
const regexMinuscule = /(?=.*[a-z])/
const regexChiffre = /(?=.*[0-9])/
const regexLongeur = /.{12,}/


passwordInput.addEventListener('input', function(){
    
    if(regexCaractere.test(this.value)){
        caractere.classList.add('success')
    }else{
        caractere.classList.remove('success')
    }

    if(regexMajuscule.test(this.value)){
        majuscule.classList.add('success')
    }else{
        majuscule.classList.remove('success')
    }

    if(regexMinuscule.test(this.value)){
        minuscule.classList.add('success')
    }else{
        minuscule.classList.remove('success')
    }

    if(regexChiffre.test(this.value)){
        chiffre.classList.add('success')
    }else{
        chiffre.classList.remove('success')
    }

    if(regexLongeur.test(this.value)){
        longueur.classList.add('success')
    }else{
        longueur.classList.remove('success')
    }

    // if(regex.test(this.value)){
    //     console.log('ok')
    // }else{
    //     console.log('erreur')
    // }
})
