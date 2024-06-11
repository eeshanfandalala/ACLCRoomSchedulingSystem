<?php
include 'config.php';
?>

<main>
    <div class="filter-container">
        <div class="text">
            <span>Filter By</span>
        </div>
        <form action="" method="post">
            <div>
                <label>School Year</label><br>
                <select name="AY" id="yearSelect" onchange="this.form.submit()">
                    <?php
                    include 'config.php';
                    // Get current year
                    $currentYear = date("Y");

                    // Generate options for the current year and the next 5 years
                    for ($i = -1; $i < 4; $i++) {
                        $year = $currentYear + $i;
                        $nextYear = $year + 1;
                        $optionValue = $year . "-" . $nextYear;
                        ?>
                        <option value=<?php echo $optionValue ?>     <?php if (isset($_POST['AY'])) {
                                    echo ($_POST['AY'] == "$optionValue") ? "selected" : "";
                                } ?> required><?php echo $optionValue ?>
                        </option>
                        <?php
                    }
                    ?>
                </select>
            </div>

            <div>
                <label>Set Semester</label><br>
                <input type="radio" name="SetSem" id="firstSemester" value="1st" <?php if (isset($_POST['SetSem'])) {
                    echo ($_POST['SetSem'] == '1st') ? "checked" : "";
                } ?> onchange="this.form.submit()" required>
                <label>1st</label>

                <input type="radio" name="SetSem" id="secondSemester" value="2nd" <?php if (isset($_POST['SetSem'])) {
                    echo ($_POST['SetSem'] == '2nd') ? "checked" : "";
                } ?> onchange="this.form.submit()" required>
                <label>2nd</label>
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
                $countClassWithSubjects = $con->prepare("SELECT c.class_id, c.class_courseStrand, c.class_year, c.class_section, c.class_department, c.class_standing, COUNT(DISTINCT s.subject_id) AS total_subjects
                                                FROM class_tb c
                                                LEFT JOIN schedule_tb s ON c.class_id = s.class_id
                                                WHERE s.schedule_SY = ? AND s.schedule_semester = ?
                                                GROUP BY c.class_id, c.class_courseStrand, c.class_year, c.class_section, c.class_department, c.class_standing;");
                $countClassWithSubjects->bind_param("ss", $_POST['AY'], $_POST['SetSem']);
                $countClassWithSubjects->execute();
                $resultcountClassWithSubjects = $countClassWithSubjects->get_result();
                while ($row = $resultcountClassWithSubjects->fetch_assoc()) {
                    ?>
                    <div class="card">
                        <h2><?php echo htmlspecialchars($row['class_courseStrand']) . " " . htmlspecialchars($row['class_year']) . "-" . htmlspecialchars($row['class_section']); ?>
                        </h2>
                        <p><strong>Class ID:</strong> <?php echo htmlspecialchars($row['class_id']); ?></p>
                        <p><strong>Department:</strong> <?php echo htmlspecialchars($row['class_department']); ?></p>
                        <p><strong>Standing:</strong> <?php echo htmlspecialchars($row['class_standing']); ?></p>
                        <p><strong>Total Subjects:</strong> <?php echo htmlspecialchars($row['total_subjects']); ?></p>
                    </div>
                    <?php
                }
                ?>
            </div>
            <a id="toggleClassBtn" class="see-more" data-target="classContainer">See More</a>
        </section>

        <section>
            <div class="text">
                <span>Teachers</span>
            </div>
            <input type="text" id="teacherSearchBar" placeholder="Search for teachers...">
            <div class="container" id="teacherContainer">
                <?php
                $countTeacherWithSubjects = $con->prepare("SELECT t.teacher_name, t.teacher_id, COUNT(DISTINCT s.schedule_id) AS total_classes, COUNT(DISTINCT s.subject_id) AS total_subjects
                                                    FROM teacher_tb t
                                                    LEFT JOIN schedule_tb s ON t.teacher_id = s.teacher_id
                                                    WHERE s.schedule_SY = ? AND s.schedule_semester = ?
                                                    GROUP BY t.teacher_id, t.teacher_name;");
                $countTeacherWithSubjects->bind_param("ss", $_POST['AY'], $_POST['SetSem']);
                $countTeacherWithSubjects->execute();
                $resultcountTeacherWithSubjects = $countTeacherWithSubjects->get_result();
                while ($row = $resultcountTeacherWithSubjects->fetch_assoc()) {
                    ?>
                    <div class="card">
                        <h2><?php echo htmlspecialchars($row['teacher_name']); ?></h2>
                        <p><strong>Teacher ID:</strong> <?php echo htmlspecialchars($row['teacher_id']); ?></p>
                        <p><strong>Total Classes:</strong> <?php echo htmlspecialchars($row['total_classes']); ?></p>
                        <p><strong>Total Subjects:</strong> <?php echo htmlspecialchars($row['total_subjects']); ?></p>
                    </div>
                    <?php
                }
                ?>
            </div>
            <a id="toggleTeacherBtn" class="see-more" data-target="teacherContainer">See More</a>
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
                        echo '<p><strong>Room Type:</strong> ' . htmlspecialchars($room['room_type']) . '</p>';
                        echo '<p><strong>Floor:</strong> ' . htmlspecialchars($room['room_floor']) . '</p>';
                        echo '<p><strong>Building:</strong> ' . htmlspecialchars($room['room_building']) . '</p>';

                        $scheduleSQL = $con->prepare("SELECT `schedule_day`, `schedule_time` FROM schedule_tb WHERE room_id = ? AND schedule_SY = ? AND schedule_semester = ?");
                        $scheduleSQL->bind_param("sss", $room['room_id'], $_POST['AY'], $_POST['SetSem']);
                        $scheduleSQL->execute();
                        $resultscheduleSQL = $scheduleSQL->get_result();

                        $vacantTimes = [];
                        $daysOfWeek = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
                        $times = [
                            "07:00:00",
                            "07:30:00",
                            "08:00:00",
                            "08:30:00",
                            "09:00:00",
                            "09:30:00",
                            "10:00:00",
                            "10:30:00",
                            "11:00:00",
                            "11:30:00",
                            "12:00:00",
                            "12:30:00",
                            "13:00:00",
                            "13:30:00",
                            "14:00:00",
                            "14:30:00",
                            "15:00:00",
                            "15:30:00",
                            "16:00:00",
                            "16:30:00",
                            "17:00:00",
                            "17:30:00",
                            "18:00:00",
                            "18:30:00",
                            "19:00:00",
                            "19:30:00",
                            "20:00:00",
                            "20:30:00",
                            "21:00:00",
                            "21:30:00",
                            "22:00:00"
                        ];

                        foreach ($daysOfWeek as $day) {
                            $occupiedTimes = [];

                            $resultscheduleSQL->data_seek(0); // Reset result pointer for each day
                            while ($row = $resultscheduleSQL->fetch_assoc()) {
                                $occupiedRoomTime = json_decode($row['schedule_time'], true);
                                $occupiedRoomDays = json_decode($row['schedule_day'], true);

                                if (in_array($day, $occupiedRoomDays)) {
                                    $occupiedTimeStart = strtotime($occupiedRoomTime['start']);
                                    $occupiedTimeEnd = strtotime($occupiedRoomTime['end']);

                                    $startIndex = array_search(date('H:i:s', $occupiedTimeStart), $times);
                                    $endIndex = array_search(date('H:i:s', $occupiedTimeEnd), $times);

                                    for ($i = $startIndex; $i < $endIndex; $i++) {
                                        $occupiedTimes[] = $times[$i];
                                    }
                                }
                            }

                            $vacantTimes[$day] = array_diff($times, $occupiedTimes);
                        }

                        foreach ($vacantTimes as $day => $times) {
                            echo "<h2>$day</h2>";
                            echo "<p>" . implode(", ", $times) . "</p>";
                        }
                        echo "</div>";
                    }
                }
                ?>
            </div>
            <a id="toggleTeacherBtn" class="see-more" data-target="roomContainer">See More</a>
        </section>
    </div>
</main>

<script>
    document.querySelectorAll('.see-more').forEach(button => {
        button.addEventListener('click', function () {
            const containerId = this.getAttribute('data-target');
            const container = document.getElementById(containerId);
            const cards = container.querySelectorAll('.card');
            const isExpanded = this.getAttribute('data-expanded') === 'true';
            if (isExpanded) {
                cards.forEach((card, index) => {
                    if (index >= 3) {
                        card.style.display = 'none';
                    }
                });
                this.textContent = 'See More';
            } else {
                cards.forEach(card => {
                    card.style.display = 'block';
                });
                this.textContent = 'See Less';
            }
            this.setAttribute('data-expanded', !isExpanded);
        });
    });

    // Initial state: show only the first 3 cards in each container
    document.addEventListener('DOMContentLoaded', function () {
        const containers = document.querySelectorAll('.container');
        containers.forEach(container => {
            const cards = container.querySelectorAll('.card');
            cards.forEach((card, index) => {
                if (index >= 3) {
                    card.style.display = 'none';
                }
            });
        });
    });

    // Search functionality for classes
    document.getElementById('searchBar').addEventListener('input', function () {
        const searchTerm = this.value.toLowerCase();
        const cards = document.querySelectorAll('#classContainer .card');
        cards.forEach(card => {
            const classText = card.querySelector('h2').textContent.toLowerCase();
            if (classText.includes(searchTerm)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });

    // Search functionality for teachers
    document.getElementById('teacherSearchBar').addEventListener('input', function () {
        const searchTerm = this.value.toLowerCase();
        const cards = document.querySelectorAll('#teacherContainer .card');
        cards.forEach(card => {
            const teacherText = card.querySelector('h2').textContent.toLowerCase();
            if (teacherText.includes(searchTerm)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });

    // Search functionality for rooms
    document.getElementById('roomSearchBar').addEventListener('input', function () {
        const searchTerm = this.value.toLowerCase();
        const cards = document.querySelectorAll('#roomContainer .card');
        cards.forEach(card => {
            const roomText = card.querySelector('h2').textContent.toLowerCase();
            if (roomText.includes(searchTerm)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });
</script>