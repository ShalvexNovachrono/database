    <div class="navbar">
        <div class="logo"><a href="../index.php"><img src="../assets/icon/favicon.ico"><div class="title">Database - by Nava</div></a></div>
        <div class="right">
            <div class="directory-open">
                <a href="../user/connection.php">connection</a>
                <a href="../user/capsule.php">capsule</a>
                <a href="../user/index.php?user"><?php echo $_SESSION['username']; ?></a>
                <a href="../pages/logout.php">logout</a>
            </div>
            <div class="directory-collapsed">
                <div class="btn">
                    <img src="../assets/images/user.webp">
                </div>
                <div class="dropdown">
                    <div class="directory-drop-down">
                        <a href="../user/connection.php">connection</a>
                        <a href="../user/capsule.php">capsule</a>
                        <a href="../user/index.php?user"><?php echo $_SESSION['username']; ?></a>
                        <a href="../pages/logout.php">logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>