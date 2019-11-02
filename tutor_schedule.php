<?php
require_once("php/classes/InfoManager.php");
checkLoggedIn(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Tutor Schedule</title>
    <link rel="shortcut icon" href="<?= SITE_URL ?>/img/favicon.ico" />
    <link rel="stylesheet" href="<?= SITE_URL ?>assets/fullcalendar/fullcalendar.min.css" />
    <script src="<?= SITE_URL ?>assets/fullcalendar/lib/jquery.min.js"></script>
    <script src="<?= SITE_URL ?>assets/fullcalendar/lib/moment.min.js"></script>
    <script src="<?= SITE_URL ?>assets/fullcalendar/fullcalendar.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#calendar").fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month'
                },
                defaultDate: '<?= getToday(); ?>',
                editable: true,
                eventLimit: true,
                defaultView: 'month',
                dayClick: function(date, jsEvent, view) {
                    if (view.name == 'agendaDay') {
                        alert(date.format());
                    } else {
                        $("#calendar").fullCalendar('changeView', 'agendaDay');
                    }
                },
                minTime: '08:00',
                maxTime: '21:00',
                allDaySlot: false,
                slotDuration: '01:00',
                contentHeight: 'auto',
                events: <?= jgetTutorSchedule($_GET["tutor_id"]); ?>
            });
        });

    </script>
</head>

<body>
    <div id="calendar"></div>

    <?php require_once("html/html_footer.html"); ?>
