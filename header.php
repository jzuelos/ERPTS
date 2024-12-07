<!-- Main Navigation -->
<nav id="nav" style="background-color: #3C3D37;">
    <div class="navTop" style="display: flex; justify-content: space-between; align-items: center;">
        <!-- Burger Icon (Navbar Toggler) inside the main navigation -->
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAccount"
            aria-controls="offcanvasAccount"
            style="background: transparent; border: none; display: flex; flex-direction: column; align-items: center;">
            <img src="img/burger.png" alt="Burger Icon" class="navbar-toggler-image"
                style="max-width: 20%; height: auto;">
            <small style="margin-top: 5px;">Menu</small>
        </button>

        <div class="navItem" style="flex-grow: 1; text-align: center;">
            <!-- Optional Logo/Image could go here -->
        </div>

        <!-- Search, Text and Menu Items Aligned Horizontally -->
        <div class="navItem"
            style="display: flex; align-items: center; justify-content: space-between; width: 100%; padding-right: 20px;">
            <!-- Menu Items aligned horizontally -->
            <div class="menuItems" style="display: flex; align-items: center; margin-left: 20px;">
                <h3 class="menuItem" style="color: lightgray; margin-right: 15px;">BOM X</h3>
                <h3 class="menuItem" style="color: lightgray; margin-right: 15px;">ASIO</h3>
                <h3 class="menuItem" style="color: lightgray; margin-right: 15px;">TRC</h3>
                <h3 class="menuItem" style="color: lightgray; margin-right: 15px;">RCB</h3>
                <h3 class="menuItem" style="color: lightgray;">MUTTARU</h3>
            </div>

            <div class="search"
                style="display: flex; align-items: center; background-color: gray; padding: 10px 20px; border-radius: 10px;">
                <input type="text" placeholder="Search..." class="searchInput"
                    style="border: none; background-color: transparent; padding: 5px; width: 150px;">
                <img src="img/search.png" width="20" height="20" alt="" class="searchIcon">
            </div>
            <span class="limitedOffer"
                style="font-size: 16px; font-weight: bold; color: #fff; cursor: pointer; padding: 10px; margin-left: 20px;">New
                Arrivals!</span>
        </div>
    </div>
</nav>


<!-- Off-Canvas Sidebar (Account Menu) -->
<div class="offcanvas offcanvas-start text-bg-dark" tabindex="-1" id="offcanvasAccount"
    aria-labelledby="offcanvasAccountLabel">
    <div class="offcanvas-header">
        <h4 class="offcanvas-title" id="offcanvasAccountLabel">Menu</h4>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <!-- Navigation Links -->
        <ul class="nav flex-column mb-4">
            <li class="nav-item">
                <a class="nav-link text-white fw-bold">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white fw-bold" href="#">News</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white fw-bold" href="#">Magazine</a>
            </li>
        </ul>

        <!-- Divider -->
        <hr class="border-light">

        <!-- Login Button -->
        <div class="d-grid gap-2 mb-4">
            <p>Already have an account?</p>
            <button class="btn btn-outline-light fw-bold text-uppercase" type="button">Login</button>
        </div>

        <!-- Divider -->
        <hr class="border-light">

         <!-- Signup Button -->
         <div class="d-grid gap-2 mb-4">
            <p>Doesn't have an account?</p>
            <button class="btn btn-outline-light fw-bold text-uppercase" type="button">Signup</button>
        </div>
    </div>
</div>