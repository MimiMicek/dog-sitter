<?php

require_once 'header.php';
require_once __DIR__ . '/db-connect.php';

ini_set('display_errors', 0);
session_start();

if (!isset($_SESSION['userId'])) {
    header('Location: login.php');
}

$ownerUserId = $_SESSION['userId'];
$sitterUserId = $_GET['id'];

//var_dump($sitterUserId);

?>

<head>
    <link rel="stylesheet" href="fullcalendar/fullcalendar.min.css" />
    <script src='http://fullcalendar.io/js/fullcalendar-2.1.1/lib/moment.min.js'></script>
    <script src='http://fullcalendar.io/js/fullcalendar-2.1.1/lib/jquery.min.js'></script>
    <script src="http://fullcalendar.io/js/fullcalendar-2.1.1/lib/jquery-ui.custom.min.js"></script>
    <script src='http://fullcalendar.io/js/fullcalendar-2.1.1/fullcalendar.min.js'></script>

    <script>
        $(document).ready(function () {
            let calendar = $('#calendar').fullCalendar({
                editable: true,
                events: 'apis/api-get-bookings',
                displayEventTime: false,
                eventRender: function (event, element, view) {
                    if (event.allDay === 'true') {
                        event.allDay = true;
                    } else {
                        event.allDay = false;
                    }
                },
                selectable: true,
                selectHelper: true,
                select: function (start, end, allDay) {
                    let typeOption = prompt('Please enter a number of the booking type\n' +
                                            '1. Walk (1 day)\n' +
                                            '2. Overnight stay (2 days) or\n' +
                                            '3. Vacation (3 or more)');

                    if (typeOption) {
                        let startDate = moment(start, 'DD.MM.YYYY').format('YYYY-MM-DD');
                        let endDate = moment(end, 'DD.MM.YYYY').format('YYYY-MM-DD');
                        let sitterUserId = "<?php echo $sitterUserId;?>";

                        console.log(sitterUserId);

                        $.ajax({
                            url: 'apis/api-add-booking',
                            data: 'sitterUserId='+ sitterUserId +
                                  '&typeOption=' + typeOption +
                                  '&startDate=' + startDate +
                                  '&endDate=' + endDate,
                            type: 'POST',
                            success: function (data) {
                                displayMessage('Booked Successfully');
                            }
                        });
                        calendar.fullCalendar('renderEvent',
                            {
                                title: typeOption,
                                start: startDate,
                                end: endDate,
                                allDay: allDay
                            },
                            true
                        );
                    }
                    calendar.fullCalendar('unselect');
                }


               /* eventDrop: function (event, delta) {
                    var start = $.fullCalendar.formatDate(event.start, "YYYY-MM-DD");
                    var end = $.fullCalendar.formatDate(event.end, "YYYY-MM-DD");
                    $.ajax({
                        url: 'edit-event.php',
                        data: 'title=' + event.title + '&start=' + start + '&end=' + end + '&id=' + event.id,
                        type: "POST",
                        success: function (response) {
                            displayMessage("Updated Successfully");
                        }
                    });
                },
                eventClick: function (event) {
                    var deleteMsg = confirm("Do you really want to delete?");
                    if (deleteMsg) {
                        $.ajax({
                            type: "POST",
                            url: "delete-event.php",
                            data: "&id=" + event.id,
                            success: function (response) {
                                if(parseInt(response) > 0) {
                                    $('#calendar').fullCalendar('removeEvents', event.id);
                                    displayMessage("Deleted Successfully");
                                }
                            }
                        });
                    }
                }*/

            });
        });

        function displayMessage(message) {
            $(".response").html("<div class='success'>"+message+"</div>");
            setInterval(function() { $(".success").fadeOut(); }, 1000);
        }
    </script>

    <style>

        .response {
            height: 60px;
        }

        .success {
            background: #cdf3cd;
            padding: 10px 60px;
            border: #c3e6c3 1px solid;
            display: inline-block;
        }

        .fc-content{
            background: tomato;
        }

        .fc-event{
            border: unset;
            font-size: medium;
        }
    </style>
</head>
<body>
    <h2 class="pt-3">Book a dog sitter</h2>

    <div id="calendar"></div>
    <div class="response"></div>
</body>



<?php

require_once 'footer.php';

?>
