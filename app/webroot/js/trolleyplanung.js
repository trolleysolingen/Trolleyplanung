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
        data: { reservationDay: reservationDay, reservationTimeslot: reservationTimeslot }
    }).done(function(data) {
        displayReservation(reservationDay, reservationTimeslot, data.reservation, data.publisher);
    });
}

function deletePublisher(reservationDay, reservationTimeslot, publisherNumber) {
    $.ajax({
        type: "POST",
        url: "/reservations/deletePublisher.json",
        data: { reservationDay: reservationDay, reservationTimeslot: reservationTimeslot, publisherNumber: publisherNumber }
    }).done(function(data) {
        displayReservation(reservationDay, reservationTimeslot,  data.reservation, data.publisher);
    });
}

function addGuest() {

}

function displayReservation(reservationDay, reservationTimeslot, reservation, publisher) {
    html = '';

    if (reservation && reservation.Reservation) {
        if (reservation.Reservation.publisher1_id) {
            html += reservation.Publisher1.prename + ' ' + reservation.Publisher1.surname +
                    " <a href='javascript:void(0)' onclick='deletePublisher(\"" + reservationDay + "\"," + reservationTimeslot + ", 1)'>X</a>" + "<br/>";

        }
        if (reservation.Reservation.publisher2_id) {
            html += reservation.Publisher2.prename + ' ' + reservation.Publisher2.surname +
                    " <a href='javascript:void(0)' onclick='deletePublisher(\"" + reservationDay + "\"," + reservationTimeslot + ", 2)'>X</a>" + "<br/>";
        } else {
            if (reservation.Reservation.publisher1_id == publisher.Publisher.id) {
                html += "<a href='javascript:void(0)' onclick='alert(1);'>Partner eintragen</a><br/>";
            } else {
                html += "<a href='javascript:void(0)' onclick='addPublisher(\"" + reservationDay + "\"," + reservationTimeslot + ")'>Eintragen</a><br/>";
            }

        }
    } else {
        html += "<a href='javascript:void(0)' onclick='addPublisher(\"" + reservationDay + "\"," + reservationTimeslot + ")'>Eintragen</a>" + "<br/>";
    }

    $('#td_' + reservationDay + '_' + reservationTimeslot).html(html);
}