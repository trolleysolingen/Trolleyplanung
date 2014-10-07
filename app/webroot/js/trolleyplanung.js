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
	$('#guestModal').modal('hide');
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
                html += " <a href='javascript:void(0)' style='float:right;' onclick='deletePublisher(\"" + reservationDay + "\"," + reservationTimeslot + ", " +  (reservation.Reservation.publisher2_id ? "true" : "false") + ");'><span class='glyphicon glyphicon-remove'></span></a>";
            } else {
				if(reservation.Publisher1.phone) {
					html += " <a href='javascript:void(0)' style='float:right;' tabindex='0' data-toggle='popover' data-trigger='focus' data-placement='top' data-content='" + reservation.Publisher1.phone + "'><span class='glyphicon glyphicon-iphone'></span></a>";
				}
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
                html += " <a href='javascript:void(0)' style='float:right;' onclick='deletePublisher(\"" + reservationDay + "\"," + reservationTimeslot + ", true);'><span class='glyphicon glyphicon-remove'></span></a>";
            } else {
				if(reservation.Publisher2.phone) {
					html += " <a href='javascript:void(0)' style='float:right;' tabindex='0' data-toggle='popover' data-trigger='focus' data-placement='top' data-content='" + reservation.Publisher2.phone + "'><span class='glyphicon glyphicon-iphone'></span></a>";
				}
			}
            html += "<br/>";
        } else {
            if (reservation.Reservation.publisher1_id == publisher.Publisher.id) {
                html += "<div id='guestDiv_" + reservationDay + "_" + reservationTimeslot + "'>" +
                          "<a href='javascript:void(0)' title='Partner eintragen' onclick='displayGuestField(\"" + reservationDay + "\"," + reservationTimeslot + ");'><span class='glyphicon glyphicon-plus' style='margin-top:-5px;'></span> Partner</a></div>";
            } else {
                html += "<a href='javascript:void(0)' onclick='addPublisher(\"" + reservationDay + "\"," + reservationTimeslot + ");'><span class='glyphicon glyphicon-user_add'></span></a><br/>";
            }

        }
    } else {
        html += "<a href='javascript:void(0)' onclick='addPublisher(\"" + reservationDay + "\"," + reservationTimeslot + ");'><span class='glyphicon glyphicon-user_add'></span></a>" + "<br/>";
    }
	
	if (reservation && reservation.Reservation) {
		if (reservation.Reservation.publisher1_id) {
			$('#td_' + reservationDay + '_' + reservationTimeslot).attr('class', 'warning');
		}
		if (reservation.Reservation.publisher2_id) {
			$('#td_' + reservationDay + '_' + reservationTimeslot).attr('class', 'danger');
		}
		if ((reservation.Reservation.publisher1_id == publisher.Publisher.id) || (reservation.Reservation.publisher2_id == publisher.Publisher.id)) {
			$('#td_' + reservationDay + '_' + reservationTimeslot).attr('class', 'info');
		}
	}
	else {
		$('#td_' + reservationDay + '_' + reservationTimeslot).attr('class', '');
	}

    $('#td_' + reservationDay + '_' + reservationTimeslot).html(html);
}

function displayGuestField(reservationDay, reservationTimeslot) {
	html = '<div class="form-group">';
    html += '<label for="guestname_' + reservationDay + '_' + reservationTimeslot + '">Name:</label>';
    html += '<input type="text" class="form-control" id="guestname_' + reservationDay + '_' + reservationTimeslot + '" name="guestname_' + reservationDay + '_' + reservationTimeslot + '" autocomplete="off"/>';
	html += '</div>';
	
	body = '<div class="btn-group">';
	body += '<button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>';
	body += "<a href='javascript:void(0)' class='btn btn-primary' onclick='addGuest(\"" + reservationDay + "\"," + reservationTimeslot + ");'>Eintragen</a>";
    body += '</div>';

    $('#guestModalDiv').html(html);
	$('#guestModalBody').html(body);

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
	
	$('#guestModal').modal('show');
}