
/**
 * Tests if the user is really 18
 */
FormValidator.registerCallback('is_18', function(value) {
    var today = new Date();
    var rawDate = value.split("/");
    var date = new Date(rawDate[2], rawDate[1]-1, rawDate[0], 0, 0, 0, 0);

    return (Math.floor((today.getTime() - date.getTime()) / (31556926*1000)) >= 18);
})
.setMessage('is_18', 'Vous n\'avez pas 18 ans');
