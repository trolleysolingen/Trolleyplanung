var debug = true;

var substringMatcher = function(strs) {
  return function findMatches(q, cb) {
	var matches, substrRegex;
 
	// an array that will be populated with substring matches
	matches = [];
 
	// regex used to determine if a string contains the substring `q`
	substrRegex = new RegExp(q, 'i');
 
	// iterate through the pool of strings and for any string that
	// contains the substring `q`, add it to the `matches` array
	$.each(strs, function(i, str) {
	  if (substrRegex.test(str)) {
		// the typeahead jQuery plugin expects suggestions to a
		// JavaScript object, refer to typeahead docs for more info
		matches.push({ value: str });
	  }
	});
 
	cb(matches);
  };
};

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

        var htmlOld = $('#td_' + displaySizes[0] + '_' + reservationDay + '_' + reservationTimeslot).html();
        $.each(displaySizes, function( index, displaySize ) {
            $('#td_' + displaySize + '_' + reservationDay + '_' + reservationTimeslot).html("<img src='/img/ajax-loader.gif' alt='Lädt...'/>");
        });

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
                $.each(displaySizes, function( index, displaySize ) {
                    $('#td_' + displaySize + '_' + reservationDay + '_' + reservationTimeslot).html(htmlOld);
                });
            }
        });
    }
}

function addPublisher(reservationDay, reservationTimeslot, displayTime) {
    var data = { reservationDay: reservationDay, reservationTimeslot: reservationTimeslot, displayTime: displayTime };
    ajaxCallReservation(reservationDay, reservationTimeslot, "/reservations/addPublisher.json", data);
}

function deletePublisher() {
	var reservationDay = $("#deleteReservationDay").val(); 
	var reservationTimeslot = $("#deleteReservationTimeslot").val(); 
    var deleteBoth = $("#deletePartner").is(":checked");
	
    var data = { reservationDay: reservationDay, reservationTimeslot: reservationTimeslot, deleteBoth: deleteBoth };
    ajaxCallReservation(reservationDay, reservationTimeslot, "/reservations/deletePublisher.json", data);
	
	$('#deleteModal').modal('hide');
	$('#deletePartner').prop('checked', false);
}

function addGuest(reservationDay, reservationTimeslot, displayTime) {
    var guestname = $('#guestname_' + reservationDay + '_' + reservationTimeslot).val();
    if (guestname) {
        var guestsNotAllowed = $('#guestsNotAllowed').val();
        if (guestsNotAllowed != 1 || $.inArray(guestname, publisherList) >= 0) {
            var data = {
                reservationDay: reservationDay,
                reservationTimeslot: reservationTimeslot,
                displayTime: displayTime,
                guestname: guestname
            };
            ajaxCallReservation(reservationDay, reservationTimeslot, "/reservations/addGuest.json", data);
            $('#guestModal').modal('hide');
        } else {
            $('#guestname_' + reservationDay + '_' + reservationTimeslot + '_errorMsg').html('Bitte wähle einen zugelassenen Verkündiger aus. Die automatische Vorschlagsliste hilft dir dabei.');
            $('#guestname_' + reservationDay + '_' + reservationTimeslot + '_errorMsg').show();
        }
    }

}

function displayReservation(reservationDay, reservationTimeslot, reservation, publisher, displayTime) {
    if (reservation && reservation.error) {
        displayError(reservationDay, reservationTimeslot, reservation.error);
    }
    html = "<div class='row'>";
	html += "<div style='padding-right: 5px;' class='col-sm-10 col-xs-8 cut-div-text pull-left'>";

    if (!reservation || !reservation.Reservation) {
		html += "<a href='javascript:void(0)' onclick='addPublisher(\"" + reservationDay + "\"," + reservationTimeslot + ",\"" + displayTime + "\");'><span class='glyphicon glyphicon-user_add'></span></a>";
	} else {
        if (reservation.Reservation.publisher1_id) {
			if(publisher.Congregation.key_management && reservation.Publisher1.kdhall_key == 1) {
				html += "<span class='glyphicon glyphicon-keys' style='margin-right:5px; margin-top:-5px; color:#f0ad4e'></span>";
			}
            if (reservation.Publisher1.role_id == 3) {
                html += reservation.Reservation.guestname;
            } else {
                html += reservation.Publisher1.prename + ' ' + reservation.Publisher1.surname;
            }
		}
		html += "</div>";
		html += "<div class='col-sm-2' style='padding-right: 10px;'>";
		html += "<div class='hidden-xs'>";
		if (reservation.Reservation.publisher1_id) {
            if (reservation.Reservation.publisher1_id == publisher.Publisher.id) {
                html += " <a href='javascript:void(0)' style='float:right;' onclick='showDeleteModal(\"" + reservationDay + "\"," + reservationTimeslot + ", " +  (reservation.Reservation.publisher2_id ? "true" : "false") + ");'><span class='glyphicon glyphicon-remove'></span></a>";
            }
        }

		html += "</div>";
		html += "</div>";
		html += "</div>";
		html += "<div class='row'>";
		html += "<div style='padding-right: 5px;' class='col-sm-10 col-xs-8 cut-div-text pull-left'>";
		
        if (reservation.Reservation.publisher2_id) {
			if(publisher.Congregation.key_management && reservation.Publisher2.kdhall_key == 1) {
				html += "<span class='glyphicon glyphicon-keys' style='margin-right:5px; margin-top:-5px; color:#f0ad4e'></span>";
			}
            if (reservation.Publisher2.role_id == 3) {
                html += reservation.Reservation.guestname;
            } else {
                html += reservation.Publisher2.prename + ' ' + reservation.Publisher2.surname
            }
		} else {
            if (reservation.Reservation.publisher1_id == publisher.Publisher.id) {
                html += "<div id='guestDiv_" + reservationDay + "_" + reservationTimeslot + "'>" +
                          "<a href='javascript:void(0)' title='Partner eintragen' onclick='displayGuestField(\"" + reservationDay + "\"," + reservationTimeslot + ",\"" + displayTime + "\");'><span class='glyphicon glyphicon-plus' style='margin-top:-5px;'></span> Partner</a></div>";
            } else {
                html += "<a href='javascript:void(0)' onclick='addPublisher(\"" + reservationDay + "\"," + reservationTimeslot + ",\"" + displayTime + "\");'><span class='glyphicon glyphicon-user_add'></span></a><br/>";
            }

        }
		html += "</div>";
		html += "<div class='col-sm-2 col-xs-4' style='padding-right: 10px;'>";
		html += "<div class='hidden-xs'>";
		 if (reservation.Reservation.publisher2_id) {
            if (reservation.Reservation.publisher2_id == publisher.Publisher.id) {
                html += " <a href='javascript:void(0)' style='float:right;' onclick='showDeleteModal(\"" + reservationDay + "\"," + reservationTimeslot + ", true);'><span class='glyphicon glyphicon-remove'></span></a>";
            }
        }
		html += "</div>";
		
		html += "<div class='visible-xs-block' style='margin-top:-15px;'>";
		html += "<div class='btn-group'>";
		if (reservation.Reservation.publisher1_id) {
            if (reservation.Reservation.publisher1_id == publisher.Publisher.id) {
                html += " <a href='javascript:void(0)' class='btn btn-danger btn-sm' onclick='showDeleteModal(\"" + reservationDay + "\"," + reservationTimeslot + ", " +  (reservation.Reservation.publisher2_id ? "true" : "false") + ");'><span class='glyphicon glyphicon-remove'></span></a>";
            }
        }
		
		if (reservation.Reservation.publisher2_id) {
            if (reservation.Reservation.publisher2_id == publisher.Publisher.id) {
                html += " <a href='javascript:void(0)' class='btn btn-danger btn-sm' onclick='showDeleteModal(\"" + reservationDay + "\"," + reservationTimeslot + ", true);'><span class='glyphicon glyphicon-remove'></span></a>";
            }
        }
		
		html += "</div>";
		html += "</div>";
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


function displayGuestField(reservationDay, reservationTimeslot, displayTime) {
	html = '<div class="form-group">';
    html += '<div id="guestname_' + reservationDay + '_' + reservationTimeslot + '_errorMsg" class="error alert alert-danger"></div>';
    html += '<label for="guestname_' + reservationDay + '_' + reservationTimeslot + '">Name:</label>';
    html += '<input type="text" class="typeahead form-control" id="guestname_' + reservationDay + '_' + reservationTimeslot + '" name="guestname_' + reservationDay + '_' + reservationTimeslot + '" placeholder="Verkündiger eingeben"/>';
	html += '</div>';
	
	body = '<div class="btn-group">';
	body += '<button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>';
	body += "<a href='javascript:void(0)' class='btn btn-primary' onclick='addGuest(\"" + reservationDay + "\"," + reservationTimeslot + ", \"" + displayTime + "\");'>Eintragen</a>";
    body += '</div>';

    $('#guestModalDiv').html(html);
	$('#guestModalBody').html(body);
	
	$('#guestname_' + reservationDay + '_' + reservationTimeslot).typeahead({
	  hint: true,
	  highlight: true,
	  minLength: 1
	},
	{
	  name: 'publisherList',
	  displayKey: 'value',
	  source: substringMatcher(publisherList)
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

function showDeleteModal(reservationDay, reservationTimeslot, showCheckbox) {
	html = '<input type="hidden" id="deleteReservationDay" value=' + reservationDay + '></input>';
	html += '<input type="hidden" id="deleteReservationTimeslot" value=' + reservationTimeslot + '></input>';
	
	$('#hiddenParams').html(html);
	if(showCheckbox) {
		$("#partnerCheckbox").attr('style', '');
	} else {
		$("#partnerCheckbox").attr('style', 'display:none');
	}
	
	$('#deleteModal').modal('show');
}
