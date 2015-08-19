var debug = true;
var fullPublisherList = publisherList.slice();

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
    publisherList = fullPublisherList.slice();
}

function deletePublisher() {
	var reservationDay = $("#deleteReservationDay").val(); 
	var reservationTimeslot = $("#deleteReservationTimeslot").val(); 
	
	var checkboxes = document.querySelectorAll('input[name="partner"]:checked'), values = [];
    Array.prototype.forEach.call(checkboxes, function(el) {
        values.push(el.value);
    });
    
    values.push($("#deletePublisherId").val());
	
    var data = { reservationDay: reservationDay, reservationTimeslot: reservationTimeslot, deletePartners: values };
    ajaxCallReservation(reservationDay, reservationTimeslot, "/reservations/deletePublisher.json", data);
	
	$('#deleteModal').modal('hide');
}

function addGuest(reservationDay, reservationTimeslot, displayTime) {
    var guestname = $('#guestname_' + reservationDay + '_' + reservationTimeslot).val();
    if (guestname) {
    	var publisherIndex = $.inArray(guestname, publisherList);
        var guestsNotAllowed = $('#guestsNotAllowed').val();
        if (guestsNotAllowed != 1 || publisherIndex >= 0) {
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
        
        if(publisherIndex >= 0) {
        	publisherList.splice(publisherIndex, 1);
        }
    }

}

function displayReservation(reservationDay, reservationTimeslot, reservation, publisher, displayTime) {

    if (reservation && reservation.error) {
        displayError(reservationDay, reservationTimeslot, reservation.error);
    }
	
	html = "";

    if (!reservation || !reservation.Reservation) {
    	html += "<div class='row'>";
    	html += "<div style='padding-right: 5px;' class='col-sm-10 col-xs-8 cut-div-text pull-left'>";
		html += "<a href='javascript:void(0)' onclick='addPublisher(\"" + reservationDay + "\"," + reservationTimeslot + ",\"" + displayTime + "\");'><span class='glyphicon glyphicon-user_add'></span></a>";
		html += "</div>";
		html += "</div>";
    } else {
    	var me = false;
		for (i = 0; i < reservation.Publisher.length; ++i) {
			html += "<div class='row'>";
			html += "<div style='padding-right: 5px;' class='col-sm-10 col-xs-8 cut-div-text pull-left'>";
			
			if(reservation.Publisher[i].id == publisher.Publisher.id) {
				me = true;
			}
			
			if(publisher.Congregation.key_management == 1 && reservation.Publisher[i].kdhall_key == 1) {
				html += "<span class='glyphicon glyphicon-keys' style='margin-right:5px; margin-top:-5px; color:#f0ad4e'></span>";
			}
			
			if (reservation.Publisher[i].role_id == 3) {
				html += reservation.PublisherReservation[i].guestname;
			} else {
				html += reservation.Publisher[i].prename + ' ' + reservation.Publisher[i].surname;
			}
			
			if(reservation.Publisher.length == i+1 && reservation.Route.publishers > reservation.Publisher.length) {
				if(me) {
					html += "<div id='guestDiv_" + reservationDay + "_" + reservationTimeslot + "'>" +
                    "<a href='javascript:void(0)' title='Partner eintragen' onclick='displayGuestField(\"" + reservationDay + "\"," + reservationTimeslot + ",\"" + displayTime + "\");'><span class='glyphicon glyphicon-plus' style='margin-top:-5px;'></span> Partner</a></div>";
				} else {
					 html += "<br/><a href='javascript:void(0)' onclick='addPublisher(\"" + reservationDay + "\"," + reservationTimeslot + ",\"" + displayTime + "\");'><span class='glyphicon glyphicon-user_add'></span></a><br/>";
				}
			}
			
			html += "</div>";
			html += "<div class='col-sm-2' style='padding-right: 10px;'>";
			
			if (reservation.Publisher[i].id == publisher.Publisher.id) {
				html += " <a href='javascript:void(0)' style='float:right;' onclick='showDeleteModal(\"" + addslashes(JSON.stringify(reservation)) + "\", \"" + publisher.Publisher.id + "\", \"" +reservationDay + "\"," + reservationTimeslot + ");'><span class='glyphicon glyphicon-remove'></span></a>";
			}
			
			html += "</div>";
			html += "</div>";
			html += "</div>";
		}
	}
	
	if (reservation && reservation.Reservation) {
		if(reservation.Route.publishers > reservation.Publisher.length) {
			$.each(displaySizes, function( index, displaySize ) {
                $('#td_' + displaySize + '_' + reservationDay + '_' + reservationTimeslot).attr('class', 'warning');
            });
		}
		if(reservation.Route.publishers <= reservation.Publisher.length) {
			$.each(displaySizes, function( index, displaySize ) {
    			$('#td_' + displaySize + '_' + reservationDay + '_' + reservationTimeslot).attr('class', 'danger');
            });
		}
		if(me) {
			$.each(displaySizes, function( index, displaySize ) {
                $('#td_' + displaySize + '_' + reservationDay + '_' + reservationTimeslot).attr('class', 'info');
            });
		}
	} else {
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

function showDeleteModal(reservation, publisherId, reservationDay, reservationTimeslot) {
	html = '<input type="hidden" id="deleteReservationDay" value=' + reservationDay + '></input>';
	html += '<input type="hidden" id="deleteReservationTimeslot" value=' + reservationTimeslot + '></input>';
	
	reservation = JSON.parse(reservation);
	
	$('#hiddenParams').html(html);
	$("#partnerCheckboxes").html('');
	
	if(reservation.Publisher.length > 1) {
		html = 'Falls du auch <b>Partner löschen</b> willst, wähle diese bitte aus um mit zu löschen.';
		html += '</br>';
		html += '</br>';
	}
	
	$("#partnerCheckboxes").html(html);
	
	for(i = 0; i < reservation.Publisher.length; ++i) {
		if(reservation.Publisher[i].id != publisherId) {
			// create the necessary elements
			var label= document.createElement("label");
			var desc;
			
			if (reservation.Publisher[i].role_id == 3) {
				desc = reservation.PublisherReservation[i].guestname;
			} else {
				desc = reservation.Publisher[i].prename + ' ' + reservation.Publisher[i].surname;
			}
			var description = document.createTextNode(desc);
			var checkbox = document.createElement("input");
			var br = document.createElement('br');

			checkbox.type = "checkbox";    // make the element a checkbox
			checkbox.name = "partner";      // give it a name we can check on the server side
			checkbox.value = reservation.PublisherReservation[i].id;         // make its value "pair"

			label.appendChild(checkbox);   // add the box to the element
			label.appendChild(description);// add the description to the element

			// add the label element to your div
			document.getElementById('partnerCheckboxes').appendChild(label);
			document.getElementById('partnerCheckboxes').appendChild(br);
		} else {
			$('#hiddenParams').append('<input type="hidden" id="deletePublisherId" value=' + reservation.PublisherReservation[i].id + '></input>')
		}
	}
	
	$('#deleteModal').modal('show');
}

function setReportDate(congregationId) {
	var reportDate = $("#reportDate").val();
	
    var data = { reportDate: reportDate };
    ajaxCallCongregationReportDate(congregationId, "/congregations/changeReportDate.json", data);
}

function ajaxCallCongregationReportDate(congregationId, url, data) {

    if (!preventDoubleClick) {
        preventDoubleClick = true;

        $.ajax({
            type: "POST",
            url: url,
            data: data,
            timeout: 10000,//in ms
            dataType: "json",
            success: function(response){
				preventDoubleClick = false;
                window.location.href = '/congregations/edit/' + congregationId;
            },
            error: function(response) {
                preventDoubleClick = false;
				window.location.href = '/congregations/edit/' + congregationId;
            }
        });
    }
}

function addslashes( str ) {
    return (str + '').replace(/[\\"']/g, '\\$&').replace(/\u0000/g, '\\0');
}
