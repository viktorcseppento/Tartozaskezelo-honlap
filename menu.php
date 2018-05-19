<div class="container">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <a class="navbar-brand" href="index.php">Főoldal</a>
        <ul class="navbar-nav mr-auto">
            <?php if (isset($_SESSION['LOGGED_IN']) && $_SESSION['LOGGED_IN'] === true): ?>
                <li class="nav-item">
                    <a class="nav-link" href="newLending.php">Új tartozás</a>
                </li>
                <?php if ($_SESSION['admin'] == true): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="userAdministration.php">Tagok</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="lendingAdministration.php">Tartozások</a>
                    </li>
                <?php endif; ?>
            <?php endif; ?>
        </ul>
        <?php
        if (isset($_SESSION['LOGGED_IN']) && $_SESSION['LOGGED_IN'] === true):
            ?>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" href="userSettings.php">
                        <i class="fas fa-user"></i><?= " " . $_SESSION['userName'] ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" name="logout" href="index.php?logout">Kijelentkezés
                        <i class="fa fa-sign-out-alt"></i>
                    </a>
                </li>
            </ul>
        <?php endif; ?>
    </nav>
</div>