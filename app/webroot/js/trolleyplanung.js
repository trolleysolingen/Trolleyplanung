function addPublisher() {
    console.log("addPublisher");

    $.ajax({
        type: "POST",
        url: "/reservations/addPublisher",
        data: { name: "John", location: "Boston" }
    }).done(function( msg ) {
        alert( "Data Saved: " + msg );
    });
}

function deletePublisher() {

}

function addGuest() {

}