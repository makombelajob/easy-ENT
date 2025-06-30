function connexion() {
    let emailValid = false;
    const btnSubmit = document.querySelector("#loginBtn");
    const email = document.querySelector("#username");
    const form = document.querySelector("#connexion");

    btnSubmit.disabled = true;

    function validerEmail() {
        const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!regex.test(email.value)) {
            email.classList.add("is-invalid");
            email.classList.remove("is-valid");
            emailValid = false;
        } else {
            email.classList.remove("is-invalid");
            email.classList.add("is-valid");
            emailValid = true;
        }
        btnSubmit.disabled = !emailValid;
    }

    email.addEventListener("input", validerEmail);

    form.addEventListener("submit", function (e) {
        if (!emailValid) {
            e.preventDefault();
            validerEmail();
        }
        // Sinon, laisse le formulaire se soumettre normalement
    });
}

connexion();
