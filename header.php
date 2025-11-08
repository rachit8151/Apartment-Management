<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<header>
    <div class="container">
        <div class="logo">
            <img src="img/house Logo copy.png" alt="Logo">
        </div>
        <nav>
            <ul>
                <li><a href="javascript:void(0)" id="loadProfile">Welcome <?php echo htmlspecialchars($_SESSION['username']); ?></a></li>
                <li><a href="dashboard.php">Home</a></li>
                <li><a href="javascript:void(0)" id="loadAbout">About</a></li>
                <li><a href="javascript:void(0)" id="loadService">Service</a></li>
                <li><a href="javascript:void(0)" id="loadContact">Contact</a></li>
            </ul>
        </nav>
    </div>
</header>
