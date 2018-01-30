var googleSynsCalendarOptions = {
	today: new Date(),
	clientId: '',
    timeZone: 'Asia/Bangkok',
    discoveryDocs: ["https://www.googleapis.com/discovery/v1/apis/calendar/v3/rest"],
    calendarList: [
        {
            id: 'th.th#holiday@group.v.calendar.google.com',
        },
        {
            id: 'thaipropertyguide.com_ombpkb4tec2qvm697bvs8chnnk@group.calendar.google.com',
        }
    ],
	scopes: [
        'https://www.googleapis.com/auth/calendar', 
        "https://www.googleapis.com/auth/calendar.readonly",
    ],
};

var GoogleSynsCalendar = {
	render: function (options) {
		var self = this;

        self.arr = {
            items: []
        };
		self.options = $.extend( {}, googleSynsCalendarOptions, options );

		gapi.load("client:auth", function() {
	   	 	self.checkAuth();
	  	});

        // self.handleClientLoad();
	},
    actions: function () {
        var self = this;

        // $('')
    },

    handleClientLoad: function () {
        var self = this;

        gapi.load('client:auth2', function () {
            self.initClient();
        });
    },
    initClient: function () {
        var self = this;

        gapi.client.init({
            discoveryDocs: self.options.discoveryDocs,
            clientId: self.options.clientId,
            scope: self.options.scopes
        }).then(function (res) {

            // console.log( res );
        });
    },

	checkAuth: function() {
        var self = this;
        // console.log('checkAuth');

        var a = gapi.auth.authorize({
            'client_id': self.options.clientId,
            'scope': self.options.scopes.join(' '),
            'immediate': true,
            'visibility': 'default',
        }).then( function ( res ) {
            self.handleAuthResult( res );
        });

    },

    handleAuthResult: function (authResult) {
        var self = this;

    	// console.log('handleAuthResult', authResult);
    	if (authResult && !authResult.error) {
            // Hide auth UI, then load client library.
            self.loadCalendarApi();
        }
    },

    loadCalendarApi: function () {
        var self = this;

        gapi.client.load("https://content.googleapis.com/discovery/v1/apis/calendar/v3/rest")
        .then(function() {

            if( self.options.calendarList ){
                self.buildFragEvents( self.options.calendarList );
            }
            else{
                self.calendarList();
            }

        }, function(error) {
            console.error("Error loading GAPI client for API");
        });
    },


    getCalendar: function ( id ) {
        return gapi.client.calendar.calendarList.get({ "calendarId": id });
    },

    // 
    calendarList: function () {
    	var self = this;

    	gapi.client.calendar.calendarList.list({
	      "minAccessRole": "reader",
	      "showDeleted": "false",
	      "showHidden": "false",
	      "prettyPrint": "true"
	    })
        .then(function(response) {

	        var result = response.result;
            self.buildFragEvents( result.items );

        }, function(error) {
          	console.error("Execute error", error);
        });
    },


    buildFragEvents: function ( items ) {
        var self = this;
        if (items.length > 0) {
            for (i = 0; i < items.length; i++) {

                var last = i == (items.length-1) ? true: false;
                self.listEvents( items[i], last );

                /*if( typeof items[i].summary === 'undefined' ){
                    self.getCalendar( items[i].id ).then(function ( response ) {
                        self.listEvents( response.result, last );
                    }, function(error) {

                    });
                }
                else{
                    self.listEvents( items[i], last );
                }*/
            }
        }    
    },

    listEvents: function ( data, last ) {
    	var self = this;

    	var request = gapi.client.calendar.events.list({
            'calendarId': data.id,

            'timeMin': (self.options.startDate).toISOString(),
            'timeMax': (self.options.endDate).toISOString(),

            'showDeleted': false,
            'singleEvents': true,
            'orderBy': 'startTime',

            // 'syncToken': '',
            'timeZone': self.options.timeZone,
        })
        .then(function(response) {
          // Handle the results here (response.result has the parsed body).
            // console.log("Response", response);
            // console.log("=====> list", data.id);

            var res = response.result;

            var events = res.items;
            if (events.length > 0) {
                for (i = 0; i < events.length; i++) {
                    var event = events[i];

                    self.arr.items.push( event );
                    // console.log( last, event.summary );
                    // var when = event.start.dateTime;
                    /*if (!when) {
                        when = event.start.date;
                    }
                    appendPre(event.summary + ' (' + when + ')')*/
                }
            }

            if( last && typeof self.options.then === 'function' ){
                self.options.then( self.arr );
            }
        }, function(error) {

            self.insertCalendarList( data.id );
            // console.error("Execute error", error);
        });
    },
    // insert
    insertCalendarList: function ( id ) {
        
        console.log('insertCalendarList:', id);
        gapi.client.calendar.calendarList.insert({
            "resource": {
                "id": id
            }
        })
        .then(function(response) {
            // Handle the results here (response.result has the parsed body).
            console.log("Response", response);

        }, function(error) {
            console.error("Execute error", error);
        });
    },


    // Events
    insertEvent: function () {
        var self = this;


        console.log('insert');
        var event = {
            'summary': 'Google I/O Admin',
            // 'location': '800 Howard St., San Francisco, CA 94103',
            // 'description': 'A chance to hear more about Google\'s developer products.',
            'start': {
                'dateTime': (new Date('2017-10-11')).toISOString(),
                'timeZone': googleSynsCalendarOptions.timeZone,
            },
            'end': {
                'dateTime': (new Date('2017-10-11')).toISOString(),
                'timeZone': googleSynsCalendarOptions.timeZone,
            },
            /*'recurrence': [ // การเกิดขึ้นอีก
                'RRULE:FREQ=DAILY;COUNT=2'
            ],*/
            /*'attendees': [ // ผู้เข้าร่วมประชุม
                {'email': 'lpage@example.com'},
                {'email': 'sbrin@example.com'}
            ],*/
            /*'reminders': { // การแจ้งเตือน
                'useDefault': false,
                'overrides': [
                    {'method': 'email', 'minutes': 24 * 60},
                    {'method': 'popup', 'minutes': 10}
                ]
            }*/
        };

        // console.log( event );

        /*var request = gapi.client.calendar.events.insert({
            'calendarId': 'thaipropertyguide.com_ombpkb4tec2qvm697bvs8chnnk@group.calendar.google.com',
            'resource': event
        });

        request.execute(function(event) {
            console.log('Event created: ', event);
        });*/

        // console.log( 'insert', request );
    }
}