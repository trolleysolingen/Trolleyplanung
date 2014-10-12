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

var preventDoubleClick = false;
var displaySizes = new Array('lg', 'sm_md', 'xs');

function ajaxCallReservation(reservationDay, reservationTimeslot, url, data) {
    clearError(reservationDay, reservationTimeslot);

    if (!preventDoubleClick) {
        preventDoubleClick = true;

        $.ajax({
            type: "POST",
            url: url,
            data: data,
            timeout: 10000,//in ms
            dataType: "json",
            success: function(data) {
                preventDoubleClick = false;
                if (data.publisher) {
                    displayReservation(reservationDay, reservationTimeslot, data.reservation, data.publisher, data.displayTime);
                } else {
                    displayError(reservationDay, reservationTimeslot,
                        "Deine Sitzung ist abgelaufen. Bitte melde dich <a href='/'>hier</a> erneut an.");
                }
            },
            error: function(request, status, err) {
                preventDoubleClick = false;
                displayError(reservationDay, reservationTimeslot,
                    "Der Server ist momentan nicht erreichbar. Bitte überprüfe, ob eine Internetverbindung zur Verfügung steht.");
            }
        });
    }
}

function addPublisher(reservationDay, reservationTimeslot, displayTime) {
    var data = { reservationDay: reservationDay, reservationTimeslot: reservationTimeslot, displayTime: displayTime };
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

function displayReservation(reservationDay, reservationTimeslot, reservation, publisher, displayTime) {
    if (reservation && reservation.error) {
        displayError(reservationDay, reservationTimeslot, reservation.error);
    }
    html = "<div class='row'>";
	html += "<div style='padding-right: 5px;' class='col-xs-10 cut-div-text'>";

    if (!reservation || !reservation.Reservation) {
		html += "<a href='javascript:void(0)' onclick='addPublisher(\"" + reservationDay + "\"," + reservationTimeslot + ",\"" + displayTime + "\");'><span class='glyphicon glyphicon-user_add'></span></a>";
	} else {
        if (reservation.Reservation.publisher1_id) {
            if (reservation.Publisher1.role_id == 3) {
                html += reservation.Reservation.guestname;
            } else {
                html += reservation.Publisher1.prename + ' ' + reservation.Publisher1.surname;
            }
		}
		html += "</div>";
		html += "<div class='col-xs-2' style='padding-right: 10px;'>";
		if (reservation.Reservation.publisher1_id) {
            if (reservation.Reservation.publisher1_id == publisher.Publisher.id) {
                html += " <a href='javascript:void(0)' style='float:right;' onclick='deletePublisher(\"" + reservationDay + "\"," + reservationTimeslot + ", " +  (reservation.Reservation.publisher2_id ? "true" : "false") + ");'><span class='glyphicon glyphicon-remove'></span></a>";
            } else {
				if(reservation.Publisher1.phone) {
					html += " <a href='javascript:void(0)' style='float:right;' tabindex='0' data-toggle='popover' data-trigger='focus' data-placement='top' data-content='" + reservation.Publisher1.phone + "'><span class='glyphicon glyphicon-iphone'></span></a>";
				}
			}
        }

		html += "</div>";
		html += "</div>";
		html += "<div class='row'>";
		html += "<div style='padding-right: 5px;' class='col-xs-10 cut-div-text'>";
		
        if (reservation.Reservation.publisher2_id) {
            if (reservation.Publisher2.role_id == 3) {
                html += reservation.Reservation.guestname;
            } else {
                html += reservation.Publisher2.prename + ' ' + reservation.Publisher2.surname
            }
		} else {
            if (reservation.Reservation.publisher1_id == publisher.Publisher.id) {
                html += "<div id='guestDiv_" + reservationDay + "_" + reservationTimeslot + "'>" +
                          "<a href='javascript:void(0)' title='Partner eintragen' onclick='displayGuestField(\"" + reservationDay + "\"," + reservationTimeslot + ");'><span class='glyphicon glyphicon-plus' style='margin-top:-5px;'></span> Partner</a></div>";
            } else {
                html += "<a href='javascript:void(0)' onclick='addPublisher(\"" + reservationDay + "\"," + reservationTimeslot + ",\"" + displayTime + "\");'><span class='glyphicon glyphicon-user_add'></span></a><br/>";
            }

        }
		html += "</div>";
		html += "<div class='col-xs-2' style='padding-right: 10px;'>";
		 if (reservation.Reservation.publisher2_id) {
            if (reservation.Reservation.publisher2_id == publisher.Publisher.id) {
                html += " <a href='javascript:void(0)' style='float:right;' onclick='deletePublisher(\"" + reservationDay + "\"," + reservationTimeslot + ", true);'><span class='glyphicon glyphicon-remove'></span></a>";
            } else {
				if(reservation.Publisher2.phone) {
					html += " <a href='javascript:void(0)' style='float:right;' tabindex='0' data-toggle='popover' data-trigger='focus' data-placement='top' data-content='" + reservation.Publisher2.phone + "'><span class='glyphicon glyphicon-iphone'></span></a>";
				}
			}
        }
		html += "</div>";
		html += "</div>";
    }
	
	if (reservation && reservation.Reservation) {
		if (reservation.Reservation.publisher1_id) {
            $.each(displaySizes, function( index, displaySize ) {
                $('#td_' + displaySize + '_' + reservationDay + '_' + reservationTimeslot).attr('class', 'warning');
            });
		}
		if (reservation.Reservation.publisher2_id) {
            $.each(displaySizes, function( index, displaySize ) {
    			$('#td_' + displaySize + '_' + reservationDay + '_' + reservationTimeslot).attr('class', 'danger');
            });
		}
		if ((reservation.Reservation.publisher1_id == publisher.Publisher.id) || (reservation.Reservation.publisher2_id == publisher.Publisher.id)) {
            $.each(displaySizes, function( index, displaySize ) {
                $('#td_' + displaySize + '_' + reservationDay + '_' + reservationTimeslot).attr('class', 'info');
            });
		}
	}
	else {
        $.each(displaySizes, function( index, displaySize ) {
		    $('#td_' + displaySize + '_' + reservationDay + '_' + reservationTimeslot).attr('class', '');
        });
	}

    $.each(displaySizes, function( index, displaySize ) {
        $('#td_' + displaySize + '_' + reservationDay + '_' + reservationTimeslot).html(html);
    });
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
        source: publisherList
    });
	
	$('#guestModal').modal('show');
}

function clearError(reservationDay, reservationTimeslot) {
    $.each(displaySizes, function( index, displaySize ){
        $('.error').html("");
        $('.error').hide();
    });

}

function displayError(reservationDay, reservationTimeslot, errorMsg) {
    $.each(displaySizes, function( index, displaySize ) {
        var nextErrorElement = $('#td_' + displaySize + '_' + reservationDay + '_' + reservationTimeslot).closest('.table').prev();
        nextErrorElement.html(errorMsg);
        nextErrorElement.show();
    });
}
