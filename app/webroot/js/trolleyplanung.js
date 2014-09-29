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

function addPublisher(reservationDay, reservationtimeslot) {

    $.ajax({
        type: "POST",
        url: "/reservations/addPublisher.json",
        data: { reservationDay: reservationDay, reservationTimeslot: reservationtimeslot }
    }).done(function(data) {
        reservation = data.reservation;

        html = '';
        if (reservation.Reservation.publisher1_id) {
            html += reservation.Publisher1.prename + ' ' + reservation.Publisher1.surname + " <a href='javascript:void(0)' onclick='alert(1);'>X</a>" + "<br/>";
        }
        if (reservation.Reservation.publisher2_id) {
            html += reservation.Publisher2.prename + ' ' + reservation.Publisher2.surname + " <a href='javascript:void(0)' onclick='alert(1);'>X</a>" + "<br/>";
        } else {
            html += "<a href='javascript:void(0)' onclick='alert(1);'>Partner eintragen</a><br/>";

        }
        $('#td_' + reservationDay + '_' + reservationtimeslot).html(html);

    });
}

function deletePublisher() {

}

function addGuest() {

}