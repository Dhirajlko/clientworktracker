document.addEventListener("DOMContentLoaded", function () {

    // ==========================
    // ADD CLIENT
    // ==========================
    const form = document.getElementById("clientForm");

    if (form) {

        form.addEventListener("submit", async function (e) {

            e.preventDefault();

            const formData = Object.fromEntries(new FormData(form).entries());

            try {

                const response = await fetch("../api/save_client.php", {

                    method: "POST",

                    headers: {
                        "Content-Type": "application/json"
                    },

                    body: JSON.stringify(formData)

                });

                const result = await response.json();

                if (result.success) {

                    Swal.fire({
                        icon: "success",
                        title: "Success",
                        text: result.message
                    }).then(() => {

                        location.reload();

                    });

                } else {

                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: result.message
                    });

                }

            } catch (err) {

                Swal.fire({
                    icon: "error",
                    title: "Server Error",
                    text: err.message
                });

            }

        });

    }

    // ==========================
    // DELETE CLIENT
    // ==========================

    document.querySelectorAll(".deleteClient").forEach(function (btn) {

        btn.addEventListener("click", function () {

            let id = this.dataset.id;

            Swal.fire({

                title: "Delete Client?",

                text: "This action cannot be undone.",

                icon: "warning",

                showCancelButton: true,

                confirmButtonText: "Delete",

                confirmButtonColor: "#d33"

            }).then(async (result) => {

                if (!result.isConfirmed) return;

                const response = await fetch("../api/delete_client.php", {

                    method: "POST",

                    headers: {
                        "Content-Type": "application/json"
                    },

                    body: JSON.stringify({
                        id: id
                    })

                });

                const data = await response.json();

                if (data.success) {

                    Swal.fire({

                        icon: "success",

                        title: "Deleted",

                        timer: 1200,

                        showConfirmButton: false

                    });

                    setTimeout(function () {

                        location.reload();

                    }, 1200);

                } else {

                    Swal.fire({

                        icon: "error",

                        title: "Error",

                        text: data.message

                    });

                }

            });

        });

    });

});