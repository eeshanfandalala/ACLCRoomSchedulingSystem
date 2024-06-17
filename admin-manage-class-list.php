<?php
session_start();
include 'config.php';

if (!isset($_SESSION['sd_id'])) {
    header("Location: index.html");
    exit;
} else {
    $user_id = $_SESSION['sd_id'];

    $sql = mysqli_query($con, "SELECT * FROM `sd_tb` WHERE `SD_id` = '$user_id'");
    while ($row = mysqli_fetch_array($sql)) {
        $SD_name = $row['SD_firstname'] . " " . $row['SD_lastname'];

?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Manage Class List</title>
            <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
            <link rel="stylesheet" href="css/SD/sidebar.css">
            <link rel="stylesheet" href="css/SD/manage-class-list.css">
        </head>

        <body>
            <div class="sidebar close">
                <div class="logo-details">
                    <img src="media/ACLC-logo.png">
                    <span class="logo-name">SchedSystem</span>
                </div>
                <ul class="nav-links">
                    <li>
                        <div class="icon-link">
                            <a href="#">
                                <i class='bx bx-collection'></i>
                                <span class="link-name">Manage</span>
                            </a>
                            <i class='bx bxs-chevron-down arrow'></i>
                        </div>
                        <ul class="sub-menu">
                            <li><a href="admin-manage-activate-teachers.php">Teacher List</a></li>
                            <li><a href="admin-manage-class-list.php">Class List</a></li>
                            <li><a href="admin-manage-department-list.php">Department List</a></li>
                            <li><a href="admin-manage-room-list.php">Room List</a></li>
                            <li><a href="admin-manage-subject-list.php">Subject List</a></li>
                        </ul>
                    </li>
                    <li>
                        <div class="icon-link">
                            <a href="#">
                                <i class='bx bx-calendar'></i>
                                <span class="link-name">Schedule</span>
                            </a>
                            <i class='bx bxs-chevron-down arrow'></i>
                        </div>
                        <ul class="sub-menu">
                            <li><a class="link-name">Schedule</a></li>
                            <li><a href="admin-create-class-schedule.php">Create</a></li>
                            <li><a href="admin-view-room-schedule.php">View by Room</a></li>
                            <li><a href="admin-view-class-schedule.php">View by Class</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="admin-manage-account.php">
                            <i class='bx bxs-cog'></i>
                            <span class="link-name">Manage Account</span>
                        </a>
                        <ul class="sub-menu blank">
                            <li><a class="link-name" href="admin-manage-account.php">Manage Account</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="admin-report.php">
                            <i class='bx bxs-report'></i>
                            <span class="link-name">Reports</span>
                        </a>
                        <ul class="sub-menu blank">
                            <li><a class="link-name" href="admin-report.php">Reports</a></li>
                        </ul>
                    </li>
                    <li>
                        <div class="profile-details">
                            <a href="admin-manage-account.php">
                                <div class="profile-content">
                                    <i class='bx bxs-user-circle' id="profile-img"></i>
                                </div>
                                <div class="name-job">
                                    <div class="profile-name"><?php echo $SD_name ?></div>
                                </div>
                            </a>
                            <a class="link-name" href="./logout.php"><i class='bx bx-log-out' id="logout"></i></a>
                            <ul class="sub-menu blank">
                                <li><a class="link-name" href="./logout.php"><i class='bx bx-log-out' id="logout"></i>Logout</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>

            <section class="home-section">
                <div class="home-content">
                    <i class='bx bx-menu'></i> <!-- button -->
                    <span class="text">Manage</span>
                </div>
                <?php include './PHP Backend/SD pages/manage/createClass.php'; ?>
            </section>
        </body>
<?php
    }
}
?>
<script src="./PHP Backend/SD pages/manage/searchtable.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarItems = document.querySelectorAll('.sidebar-item');
        const mainContent = document.getElementById('main-content');

        sidebarItems.forEach(item => {
            item.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent the default link behavior
                const page = this.getAttribute('href');
                fetch(page)
                    .then(response => response.text())
                    .then(html => {
                        mainContent.innerHTML = html;
                    })
                    .catch(error => {
                        mainContent.innerHTML = '<p>Error loading content. Please try again later.</p>';
                        console.error('Error:', error);
                    });
            });
        });
    });

    let arrow = document.querySelectorAll(".arrow");
    for (var i = 0; i < arrow.length; i++) {
        arrow[i].addEventListener("click", (e) => {
            let arrowParent = e.target.parentElement.parentElement;
            arrowParent.classList.toggle("showMenu");
        });
    }
    let sidebar = document.querySelector(".sidebar");
    let sidebarBtn = document.querySelector(".bx-menu");
    console.log(sidebarBtn);
    sidebarBtn.addEventListener("click", () => {
        sidebar.classList.toggle("close");
    });
</script>

        </html>