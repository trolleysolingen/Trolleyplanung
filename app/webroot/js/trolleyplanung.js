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

function ajaxCallReservation(reservationDay, reservationTimeslot, url, data) {
    $.ajax({
        type: "POST",
        url: url,
        data: data,
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

function addPublisher(reservationDay, reservationTimeslot) {
    var data = { reservationDay: reservationDay, reservationTimeslot: reservationTimeslot };
    ajaxCallReservation(reservationDay, reservationTimeslot, "/reservations/addPublisher.json", data);
}

function deletePublisher(reservationDay, reservationTimeslot, askPartner) {
    var deleteBoth = false;
    if (askPartner) {
        deleteBoth = window.confirm("Soll der Partner ebenfalls gelöscht werden?");
    }
    var data = { reservationDay: reservationDay, reservationTimeslot: reservationTimeslot, deleteBoth: deleteBoth };
    ajaxCallReservation(reservationDay, reservationTimeslot, "/reservations/deletePublisher.json", data);
}

function addGuest(reservationDay, reservationTimeslot) {
    var guestname = $('#guestname_' + reservationDay + '_' + reservationTimeslot).val();
    if (guestname) {
        var data = {reservationDay: reservationDay, reservationTimeslot: reservationTimeslot, guestname: guestname};
        ajaxCallReservation(reservationDay, reservationTimeslot, "/reservations/addGuest.json", data);
    }
}

function displayReservation(reservationDay, reservationTimeslot, reservation, publisher) {
    html = '';

    if (reservation && reservation.Reservation) {
        if (reservation.Reservation.publisher1_id) {
            if (reservation.Publisher1.role_id == 3) {
                html += reservation.Reservation.guestname;
            } else {
                html += reservation.Publisher1.prename + ' ' + reservation.Publisher1.surname;
            }
            if (reservation.Reservation.publisher1_id == publisher.Publisher.id) {
                html += " <a href='javascript:void(0)' onclick='deletePublisher(\"" + reservationDay + "\"," + reservationTimeslot + ", " +  (reservation.Reservation.publisher2_id ? "true" : "false") + ");'>X</a>";
            }
            html += "<br/>";
        }
        if (reservation.Reservation.publisher2_id) {
            if (reservation.Publisher2.role_id == 3) {
                html += reservation.Reservation.guestname;
            } else {
                html += reservation.Publisher2.prename + ' ' + reservation.Publisher2.surname
            }
            if (reservation.Reservation.publisher2_id == publisher.Publisher.id) {
                html += " <a href='javascript:void(0)' onclick='deletePublisher(\"" + reservationDay + "\"," + reservationTimeslot + ", true);'>X</a>";
            }
            html += "<br/>";
        } else {
            if (reservation.Reservation.publisher1_id == publisher.Publisher.id) {
                html += "<div id='guestDiv_" + reservationDay + "_" + reservationTimeslot + "'>" +
                          "<a href='javascript:void(0)' onclick='displayGuestField(\"" + reservationDay + "\"," + reservationTimeslot + ");'>Partner eintragen</a></div>";
            } else {
                html += "<a href='javascript:void(0)' onclick='addPublisher(\"" + reservationDay + "\"," + reservationTimeslot + ");'><span class='glyphicon glyphicon-user_add'></span></a><br/>";
            }

        }
    } else {
        html += "<a href='javascript:void(0)' onclick='addPublisher(\"" + reservationDay + "\"," + reservationTimeslot + ");'><span class='glyphicon glyphicon-user_add'></span></a>" + "<br/>";
    }

    $('#td_' + reservationDay + '_' + reservationTimeslot).html(html);
}

function displayGuestField(reservationDay, reservationTimeslot) {
    html = '<input type="text" id="guestname_' + reservationDay + '_' + reservationTimeslot + '" name="guestname_' + reservationDay + '_' + reservationTimeslot + '" autocomplete="off"/>';
    html += "<a href='javascript:void(0)' onclick='addGuest(\"" + reservationDay + "\"," + reservationTimeslot + ");'>Speichern</a>";

    $('#guestDiv_' + reservationDay + '_' + reservationTimeslot).html(html);

    $('#guestname_' + reservationDay + '_' + reservationTimeslot).typeahead({
        ajax: {
            url: "/publishers/autocomplete.json",
            timeout: 1000,
            displayField: "name",
            valueField: "id",
            triggerLength: 1,
            preProcess: function (data) {
                //showLoadingMask(false);
                if (data.success === false) {
                    // Hide the list, there was some error
                    return false;
                }
                // We good!
                return data.publishers;
            }
        },
        matcher: function (obj) {
            return true;
        }

    });
}