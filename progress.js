dojo.require('dijit.ProgressBar')
dojo.addOnLoad(function() {

    if (!dojo.byId('progressBar')) {
        return;
    }

    var pb = new dijit.ProgressBar({}, dojo.byId('progressBar'));
    var interval = null;
    var maximum = 0;

    interval = setInterval(function() {
        dojo.xhrGet({
            url: globals.statusUrl,
            load: function(response) {
                if (response[0] == 0) { // job is unknown, assume we're done
                    pb.update({maximum: maximum, progress: maximum});
                    window.location = globals.returnUrl
                    dojo.byId('status').innerHTML = 'Comlete';
                } else if (response[1]) { // job in progress, update
                    pb.update({maximum: response[3], progress: response[2]});
                    maximum = response[3];
                    dojo.byId('status').innerHTML = 'In Progress';
                }
            },
            handleAs: "json",
            error: function() {
                clearInterval(interval);
                alert('Could not track progress');
            }
        });

    }, 1000);
    
});

