function inscription() {
    let emailValid = false;
    let lastNameValid = false;
    let firstNameValid = false;
    let gpdrValid = false;
    const btnSubmit = document.querySelector("#registration_form_Inscription");
    btnSubmit.disabled = true;
    
    function toutValid() {
        btnSubmit.disabled = !(emailValid && lastNameValid && firstNameValid && gpdrValid);
    }

    const email = document.querySelector("#registration_form_email");
    email.addEventListener("change", function () {
        const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!regex.test(email.value)) {
            this.classList.add("is-invalid");
            this.classList.remove("is-valid");
            emailValid = false;
        } else {
            this.classList.remove("is-invalid");
            this.classList.add("is-valid");
            emailValid = true;
        }
        toutValid();
    });

    const lastname = document.querySelector("#registration_form_lastname");
    lastname.addEventListener("change", function () {
        if (lastname.value.length < 2 || lastname.value.length > 100) {
            this.classList.add("is-invalid");
            this.classList.remove("is-valid");
            lastNameValid = false;
        } else {
            this.classList.add("is-valid");
            this.classList.remove("is-invalid");
            lastNameValid = true;
        }
        toutValid();
    });

    const firstname = document.querySelector("#registration_form_firstname");
    firstname.addEventListener("change", function () {
        if (firstname.value.length < 2 || firstname.value.length > 100) {
            this.classList.add("is-invalid");
            this.classList.remove("is-valid");
            firstNameValid = false;
        } else {
            this.classList.add("is-valid");
            this.classList.remove("is-invalid");
            firstNameValid = true;
        }
        toutValid();
    });

    const gpdr = document.querySelector("#registration_form_agreeTerms");
    gpdr.addEventListener("change", function () {
        if (!gpdr.checked) {
            this.classList.add("is-invalid");
            this.classList.remove("is-valid");
            gpdrValid = false;
        } else {
            this.classList.remove("is-invalid");
            this.classList.add("is-valid");
            gpdrValid = true;
        }
        toutValid();
    });

    const form = document.querySelector("#inscription");
    form.addEventListener("submit", function (e) {
        e.preventDefault();
        if (emailValid && lastNameValid && firstNameValid && gpdrValid) {
            btnSubmit.removeAttribute("disabled");
            form.submit();
        } else {
            const evenement = new Event("change");
            email.dispatchEvent(evenement);
            lastname.dispatchEvent(evenement);
            firstname.dispatchEvent(evenement);
            gpdr.dispatchEvent(evenement);
        }
    });
}

inscription();