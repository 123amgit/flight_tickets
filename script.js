document.addEventListener("DOMContentLoaded", function() {
    const hearts = document.querySelectorAll(".heart-icon");

    hearts.forEach(function(heart) {
        heart.addEventListener("click", function(event) {
            event.preventDefault();
            const flightId = this.getAttribute("data-flight-id");
            const heartIcon = this;

            fetch(`addFavourite.php?flight_id=${flightId}`)
                .then(response => response.text())
                .then(data => {
                    // Przełączanie ikony serca
                    if (heartIcon.getAttribute("src") === "obrazki/heart_empty.png") {
                        heartIcon.setAttribute("src", "obrazki/heart_filled.png");
                        heartIcon.setAttribute("alt", "Usuń z ulubionych");
                    } else {
                        heartIcon.setAttribute("src", "obrazki/heart_empty.png");
                        heartIcon.setAttribute("alt", "Dodaj do ulubionych");
                    }
                })
                .catch(error => {
                    console.error("Błąd:", error);
                });
        });
    });
});
