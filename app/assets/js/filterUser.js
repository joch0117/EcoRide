    function filterUsers() {
        const select = document.getElementById("filter");
        const value = select.value;
        const cards = document.querySelectorAll(".user-card");

        cards.forEach(card => {
            const status = card.getAttribute("data-status");
            if (!value || value === status) {
                card.style.display = "block";
            } else {
                card.style.display = "none";
            }
        });
    }


    filterUsers();