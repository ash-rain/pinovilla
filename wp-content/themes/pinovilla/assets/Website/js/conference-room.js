document.addEventListener("DOMContentLoaded", function () {
    // Select the radio inputs by the name "IsHourly"
    const paymentOptions = document.querySelectorAll("input[name='IsHourly']");
    const paymentContainers = document.querySelectorAll(".conference-payment");
    const forms = document.querySelectorAll(".reserve-form-container");

    function updateActiveForm() {
        paymentOptions.forEach((option, index) => {
            if (option.checked) {
                paymentContainers.forEach(c => c.classList.remove("active"));
                paymentContainers[index].classList.add("active");

                forms.forEach((form, formIndex) => {
                    if (formIndex === index) {
                        form.classList.add("active-form");
                        form.classList.remove("hidden-form");
                        form.querySelectorAll("input, select").forEach(input => input.disabled = false);
                    } else {
                        form.classList.add("hidden-form");
                        form.classList.remove("active-form");
                        form.querySelectorAll("input, select").forEach(input => input.disabled = true);
                    }
                });
            }
        });
    }

    window.addEventListener('resize', updateActiveForm);
    updateActiveForm();
    paymentOptions.forEach(option => option.addEventListener("change", updateActiveForm));
});