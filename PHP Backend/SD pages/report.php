<?php
include 'config.php';
?>

<div class="main">
    <div class="top side-by-side">
        <form action="" method="post" class="side-by-side">
            <div>
                <div>
                    <span class="text">Filter By</span><br>
                    <div>
                        <label>School Year</label><br>
                        <select name="AY" id="yearSelect" onchange="this.form.submit()">
                            <?php
                            $currentYear = date("Y");
                            for ($i = -1; $i < 4; $i++) {
                                $year = $currentYear + $i;
                                $nextYear = $year + 1;
                                $optionValue = $year . "-" . $nextYear;
                            ?>
                                <option value="<?php echo $optionValue ?>" <?php if (isset($_POST['AY']) && $_POST['AY'] == $optionValue) echo "selected"; ?>><?php echo $optionValue ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>

                    <div>
                        <label>Set Semester</label><br>
                        <input type="radio" name="SetSem" id="firstSemester" value="1st" <?php if (isset($_POST['SetSem']) && $_POST['SetSem'] == '1st') echo "checked"; ?> onchange="this.form.submit()" required>
                        <label>1st</label>

                        <input type="radio" name="SetSem" id="secondSemester" value="2nd" <?php if (isset($_POST['SetSem']) && $_POST['SetSem'] == '2nd') echo "checked"; ?> onchange="this.form.submit()" required>
                        <label>2nd</label>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="reports-content">
        <section>
            <div class="text">
                <span>Classes</span>
            </div>
            <input type="text" id="searchBar" placeholder="Search for classes...">
            <div class="container" id="classContainer">
                <?php
                if (isset($_POST['AY']) && isset($_POST['SetSem'])) {

                    $countClassWithSubjects = $con->prepare("SELECT
                    c.class_id,
                    c.class_courseStrand,
                    c.class_year,
                    c.class_section,
                    c.class_department,
                    d.department_name,
                    c.class_standing,
                    COUNT(DISTINCT s.subject_id) AS num_subjects
                FROM
                    class_tb c
                LEFT JOIN
                    schedule_tb s ON c.class_id = s.class_id
                                  AND s.schedule_SY = ?
                                  AND s.schedule_semester = ?
                LEFT JOIN
                    department_tb d ON c.class_department = d.department_id
                GROUP BY
                    c.class_id,
                    c.class_courseStrand,
                    c.class_year,
                    c.class_section,
                    c.class_department,
                    d.department_name,
                    c.class_standing");

                    $countClassWithSubjects->bind_param("ss", $_POST['AY'], $_POST['SetSem']);
                    $countClassWithSubjects->execute();
                    $resultcountClassWithSubjects = $countClassWithSubjects->get_result();
                    while ($row = $resultcountClassWithSubjects->fetch_assoc()) {
                ?>
                        <div class="card">
                            <h2><?php echo htmlspecialchars($row['class_courseStrand']) . " " . htmlspecialchars($row['class_year']) . "-" . htmlspecialchars($row['class_section']); ?></h2>
                            <p>Department: <?php echo htmlspecialchars($row['department_name']); ?></p>
                            <p>Standing: <?php echo htmlspecialchars($row['class_standing']); ?></p>
                            <p>Total Subjects: <?php echo htmlspecialchars($row['num_subjects']); ?></p>
                        </div>
                <?php
                    }
                }
                ?>
            </div>
            <p id="classContainer-not-found" class="not-found-message" style="display: none;">Not Found</p>
            <a id="toggleClassBtn" class="see-more" data-target="classContainer" style="display: none;">See More</a>
        </section>

        <section>
            <div class="text">
                <span>Teachers</span>
            </div>
            <input type="text" id="teacherSearchBar" placeholder="Search for teachers...">
            <div class="container" id="teacherContainer">
                <?php
                if (isset($_POST['AY']) && isset($_POST['SetSem'])) {

                    $countTeacherWithSubjects = $con->prepare("SELECT
                        t.`teacher_pic`,
                        `t`.`status`,
                        t.teacher_id,
                        t.teacher_name,
                        COUNT(DISTINCT s.subject_id) AS total_subjects_taught,
                        COUNT(DISTINCT s.class_id) AS total_classes_taught
                    FROM
                        teacher_tb t
                    LEFT JOIN
                        schedule_tb s ON t.teacher_id = s.teacher_id
                        AND s.schedule_SY = ?
                        AND s.schedule_semester = ?
                    GROUP BY
                        t.teacher_id,
                        t.teacher_name");

                    $countTeacherWithSubjects->bind_param("ss", $_POST['AY'], $_POST['SetSem']);
                    $countTeacherWithSubjects->execute();
                    $resultcountTeacherWithSubjects = $countTeacherWithSubjects->get_result();
                    while ($row = $resultcountTeacherWithSubjects->fetch_assoc()) {
                ?>
                        <div class="card">
                            <h2><?php echo htmlspecialchars($row['teacher_name']); ?></h2>
                            <p>Total Classes: <?php echo htmlspecialchars($row['total_classes_taught']); ?></p>
                            <p>Total Subjects: <?php echo htmlspecialchars($row['total_subjects_taught']); ?></p>
                            <p>Is Active: <?php echo htmlspecialchars($row['status']) == 1 ? 'Yes' : 'No'; ?></p>
                            <div class="profile-picture-container">
                                <div class="file-input-wrapper">
                                    <img src="./profile_pictures/<?php echo $row['teacher_pic']; ?>" alt="profile picture" style="width: 100px;">
                                </div>
                            </div>
                        </div>
                <?php
                    }
                }
                ?>
            </div>
            <p id="teacherContainer-not-found" class="not-found-message" style="display: none;">Not Found</p>
            <a id="toggleTeacherBtn" class="see-more" data-target="teacherContainer" style="display: none;">See More</a>
        </section>

        <section>
            <div class="text">
                <span>Rooms</span>
            </div>
            <input type="text" id="roomSearchBar" placeholder="Search for rooms...">
            <div class="container" id="roomContainer">
                <?php
                if (isset($_POST['AY']) && isset($_POST['SetSem'])) {

                    $roomsSQL = $con->query("SELECT `room_id`, `room_name`, `room_type`, `room_floor`, `room_building` FROM `room_tb`");
                    $rooms = $roomsSQL->fetch_all(MYSQLI_ASSOC);

                    foreach ($rooms as $room) {
                        echo '<div class="card">';
                        echo '<h2>' . htmlspecialchars($room['room_name']) . '</h2>';
                        echo '<p>Room Type: ' . htmlspecialchars($room['room_type']) . '</p>';
                        echo '<p>Floor: ' . htmlspecialchars($room['room_floor']) . '</p>';
                        echo '<p>Building: ' . htmlspecialchars($room['room_building']) . '</p>';
                        echo '<h3>Available Time Slots</h3>';

                        $scheduleSQL = $con->prepare("SELECT * FROM schedule_tb WHERE room_id = ? AND schedule_SY = ? AND schedule_semester = ?");
                        $scheduleSQL->bind_param("iss", $room['room_id'], $_POST['AY'], $_POST['SetSem']);
                        $scheduleSQL->execute();
                        $resultscheduleSQL = $scheduleSQL->get_result();

                        $vacantTimes = [];
                        $daysOfWeek = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
                        $times = [
                            "07:00", "07:30",
                            "08:00", "08:30",
                            "09:00", "09:30",
                            "10:00", "10:30",
                            "11:00", "11:30",
                            "12:00", "12:30",
                            "13:00", "13:30",
                            "14:00", "14:30",
                            "15:00", "15:30",
                            "16:00", "16:30",
                            "17:00", "17:30",
                            "18:00", "18:30",
                            "19:00", "19:30",
                            "20:00", "20:30",
                            "21:00", "21:30",
                            "22:00"
                        ];

                        foreach ($daysOfWeek as $day) {
                            $occupiedTimes = [];
                            $resultscheduleSQL->data_seek(0);

                            while ($row = $resultscheduleSQL->fetch_assoc()) {
                                $occupiedTimeStart = strtotime($row['schedule_time_start']);
                                $occupiedTimeEnd = strtotime($row['schedule_time_end']);
                                if (strpos($row['schedule_day'], $day) !== false) {
                                    $startIndex = array_search(date('H:i', $occupiedTimeStart), $times);
                                    $endIndex = array_search(date('H:i', $occupiedTimeEnd), $times);
                                    for ($i = $startIndex; $i < $endIndex; $i++) {
                                        $occupiedTimes[$i] = true;
                                    }
                                }
                            }
                            $vacantTimes[$day] = [];
                            for (
                                $i = 0;
                                $i < count($times);
                                $i++
                            ) {
                                if (!isset($occupiedTimes[$i])) {
                                    $vacantTimes[$day][] = $times[$i];
                                }
                            }
                        }

                        foreach ($vacantTimes as $day => $times) {
                            echo "<h4>$day</h4>";
                            echo "<p>" . implode(" | ", $times) . "</p>";
                        }
                        echo "</div>";
                    }
                }
                ?>
            </div>
            <p id="roomContainer-not-found" class="not-found-message" style="display: none;">Not Found</p>
            <a id="toggleRoomBtn" class="see-more" data-target="roomContainer" style="display: none;">See More</a>
        </section>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleButtons = document.querySelectorAll('.see-more');

        toggleButtons.forEach(button => {
            const targetId = button.getAttribute('data-target');
            const container = document.getElementById(targetId);
            const cards = container.querySelectorAll('.card');
            const notFoundMessage = document.getElementById(`${targetId}-not-found`);

            function toggleSeeMoreButton() {
                const visibleCards = Array.from(cards).filter(card => card.style.display !== 'none');
                if (visibleCards.length > 3) {
                    button.style.display = 'inline-block';
                } else {
                    button.style.display = 'none';
                }
            }

            toggleSeeMoreButton();

            for (let i = 0; i < cards.length; i++) {
                if (i >= 3) {
                    cards[i].style.display = 'none';
                }
            }

            button.addEventListener('click', function() {
                const isExpanded = this.getAttribute('data-expanded') === 'true';

                if (isExpanded) {
                    for (let i = 0; i < cards.length; i++) {
                        if (i >= 3) {
                            cards[i].style.display = 'none';
                        }
                    }
                    this.textContent = 'See More';
                    button.style.display = 'inline-block';
                } else {
                    for (let i = 0; i < cards.length; i++) {
                        cards[i].style.display = 'block';
                    }
                    this.textContent = 'See Less';
                }

                this.setAttribute('data-expanded', !isExpanded);
            });
        });

        function handleSearchInput(inputId, containerId) {
            const searchBar = document.getElementById(inputId);
            const cards = document.querySelectorAll(`#${containerId} .card`);
            const notFoundElement = document.getElementById(`${containerId}-not-found`);

            searchBar.addEventListener('input', function() {
                const searchTerm = this.value.trim().toLowerCase();
                let foundResults = false;

                cards.forEach(card => {
                    const textContent = card.textContent.toLowerCase();
                    if (textContent.includes(searchTerm)) {
                        card.style.display = 'block';
                        foundResults = true;
                    } else {
                        card.style.display = 'none';
                    }
                });

                if (!foundResults) {
                    notFoundElement.style.display = 'block';
                } else {
                    notFoundElement.style.display = 'none';
                }

                toggleSeeMoreButton();
            });
        }

        handleSearchInput('searchBar', 'classContainer');
        handleSearchInput('teacherSearchBar', 'teacherContainer');
        handleSearchInput('roomSearchBar', 'roomContainer');
    });
</script>