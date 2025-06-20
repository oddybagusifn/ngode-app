<!-- Overlay -->
<div id="cartOverlay"
    style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
    background-color: rgba(0, 0, 0, 0.5); z-index: 998;"
    onclick="toggleCartSidebar()">
</div>

<!-- Sidebar -->
<div id="cartSidebar"
    style="
        position: fixed;
        top: 0;
        right: 0;
        height: 100%;
        background-color: white;
        z-index: 9999;
        transform: translateX(100%);
        transition: transform 0.3s ease;
        width: 100%;
        max-width: 500px;
        box-shadow: -4px 0 10px rgba(0, 0, 0, 0.1);
    ">
    <div id="cartContent" class="px-3 py-2  h-100">
        <div class="h-100 d-flex justify-content-center align-items-center">
            Memuat keranjang...
        </div>
    </div>
</div>
