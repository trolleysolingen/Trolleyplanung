var debug = true;

function logDebug(obj){
    if (debug) {
        logError(obj);
    }
}

function logError(obj){
    if (typeof(console) !== 'undefined' && console != null) {
        console.log(obj);
    }
}

function addPublisher(reservationDay, reservationTimeslot) {
    $.ajax({
        type: "POST",
        url: "/reservations/addPublisher.json",
        data: { reservationDay: reservationDay, reservationTimeslot: reservationTimeslot },
        timeout: 10000,//in ms
        dataType: "json",
        success: function(data) {
            displayReservation(reservationDay, reservationTimeslot, data.reservation, data.publisher);
        },
        error: function(request, status, err) {
            logError(status);
            logError(err);
        }
    });
}

function deletePublisher(reservationDay, reservationTimeslot, publisherNumber) {
    $.ajax({
        type: "POST",
        url: "/reservations/deletePublisher.json",
        data: { reservationDay: reservationDay, reservationTimeslot: reservationTimeslot, publisherNumber: publisherNumber },
        timeout: 10000,//in ms
        dataType: "json",
        success: function(data) {
            displayReservation(reservationDay, reservationTimeslot, data.reservation, data.publisher);
        },
        error: function(request, status, err) {
            logError(status);
            logError(err);
        }
    });
}

function addGuest(reservationDay, reservationTimeslot) {
    var guestname = $('#guestname_' + reservationDay + '_' + reservationTimeslot).val();
    if (guestname) {
        $.ajax({
            type: "POST",
            url: "/reservations/addGuest.json",
            data: {reservationDay: reservationDay, reservationTimeslot: reservationTimeslot, guestname: guestname},
            timeout: 10000,//in ms
            dataType: "json",
            success: function(data) {
                displayReservation(reservationDay, reservationTimeslot, data.reservation, data.publisher);
            },
            error: function(request, status, err) {
                logError(status);
                logError(err);
            }
        });
    }
}

function displayReservation(reservationDay, reservationTimeslot, reservation, publisher) {
    html = '';

    if (reservation && reservation.Reservation) {
        if (reservation.Reservation.publisher1_id) {
            html += reservation.Publisher1.prename + ' ' + reservation.Publisher1.surname +
                    " <a href='javascript:void(0)' onclick='deletePublisher(\"" + reservationDay + "\"," + reservationTimeslot + ", 1);'>X</a>" + "<br/>";

        }
        if (reservation.Reservation.publisher2_id) {
            if (reservation.Publisher2.role_id == 3) {
                html += reservation.Reservation.guestname;
            } else {
                html += reservation.Publisher2.prename + ' ' + reservation.Publisher2.surname
            }
            html += " <a href='javascript:void(0)' onclick='deletePublisher(\"" + reservationDay + "\"," + reservationTimeslot + ", 2);'>X</a>" + "<br/>";
        } else {
            if (reservation.Reservation.publisher1_id == publisher.Publisher.id) {
                html += "<div id='guestDiv_" + reservationDay + "_" + reservationTimeslot + "'>" +
                          "<a href='javascript:void(0)' onclick='displayGuestField(\"" + reservationDay + "\"," + reservationTimeslot + ");'>Partner eintragen</a></div>";
            } else {
                html += "<a href='javascript:void(0)' onclick='addPublisher(\"" + reservationDay + "\"," + reservationTimeslot + ");'>Eintragen</a><br/>";
            }

        }
    } else {
        html += "<a href='javascript:void(0)' onclick='addPublisher(\"" + reservationDay + "\"," + reservationTimeslot + ");'>Eintragen</a>" + "<br/>";
    }

    $('#td_' + reservationDay + '_' + reservationTimeslot).html(html);
}

function displayGuestField(reservationDay, reservationTimeslot) {
    html = '<input type="text" id="guestname_' + reservationDay + '_' + reservationTimeslot + '" name="guestname_' + reservationDay + '_' + reservationTimeslot + '"/>';
    html += "<a href='javascript:void(0)' onclick='addGuest(\"" + reservationDay + "\"," + reservationTimeslot + ");'>Speichern</a>";

    $('#guestDiv_' + reservationDay + '_' + reservationTimeslot).html(html);
}