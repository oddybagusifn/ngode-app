function toggleCartSidebar() {
    const body = document.body
    const sidebar = document.getElementById("cartSidebar");
    const overlay = document.getElementById("cartOverlay");

    const isVisible = sidebar.style.transform === "translateX(0%)";

    if (isVisible) {
        sidebar.style.transform = "translateX(100%)";
        overlay.style.display = "none";
        body.style.overflow = "auto";
    } else {
        sidebar.style.transform = "translateX(0%)";
        overlay.style.display = "block";
        body.style.overflow = "hidden";
        loadCart();
    }
}

function loadCart() {
    fetch(fetchCartURL)
        .then((res) => res.text())
        .then((html) => {
            document.getElementById("cartContent").innerHTML = html;
        })
        .catch(() => {
            document.getElementById("cartContent").innerHTML =
                "<p>Gagal memuat keranjang.</p>";
        });
}
