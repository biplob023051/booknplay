<div class="inner-page">
		<div class="wrap">
			<div class="center">
				<div id="form-page">
					<div class="sign">
						<h3>Sign Up</h3>
						
<?php echo $this->Form->create('User',array('id'=>'contact-form')); ?>
	
	<div class="form-element">
		<label>Your Name:</label>
		<?php
		echo $this->Form->input('display_name',array('label'=>false,'type'=>'text','required'=>'required','maxlength'=>'25'));
		?>
	</div>
	
	<div class="form-element">
		<label>Email Address:</label>
		<?php
		echo $this->Form->input('email',array('label'=>false,'type'=>'text','required'=>'required','pattern'=>'[^@]+@[^@]+\.[a-zA-Z]{2,6}'));
		?>
	</div>
	
	
	
	<div class="form-element">
		<label>Phone Number:</label>
		<?php
		echo $this->Form->input('phone',array('type'=>'text','label'=>false,'required'=>'required','maxlength'=>'10','pattern'=>'[789][0-9]{9}'));
		
		?>
	</div>

	<div class="form-element">
		<label>Age:</label>
		<?php
		echo $this->Form->input('age',array('type'=>'text','label'=>false,'required'=>'required','maxlength'=>'2','pattern'=>'[0-9]{2}'));
		
		?>
	</div>
	
	
	<div class="form-element">
		<label>Password:</label>
		<?php
		echo $this->Form->input('password',array('label'=>false,'required'=>'required','maxlength'=>'10'));
		?>
	</div>
	<div class="form-element submit">
		<input type="submit" value="Sign Up">
	</div>
	<?php echo $this->Form->end(); ?>
	<div class="clear"></div>
</div>
</div>

				<div class="clear"></div>
			</div>
		</div>
</div>
	

<script>
  // This is called with the results from from FB.getLoginStatus().
  function statusChangeCallback(response) {
    console.log('statusChangeCallback');
    console.log(response);
    // The response object is returned with a status field that lets the
    // app know the current login status of the person.
    // Full docs on the response object can be found in the documentation
    // for FB.getLoginStatus().
    if (response.status === 'connected') {
      // Logged into your app and Facebook.
      testAPI();
    } else if (response.status === 'not_authorized') {
      // The person is logged into Facebook, but not your app.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into this app.';
    } else {
      // The person is not logged into Facebook, so we're not sure if
      // they are logged into this app or not.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into Facebook.';
    }
  }

  // This function is called when someone finishes with the Login
  // Button.  See the onlogin handler attached to it in the sample
  // code below.
  function checkLoginState() {
    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });
  }

  window.fbAsyncInit = function() {
  FB.init({
    appId      : '1593930784251661',
    cookie     : true,  // enable cookies to allow the server to access 
                        // the session
    xfbml      : true,  // parse social plugins on this page
    version    : 'v2.5' // use graph api version 2.5
  });

  // Now that we've initialized the JavaScript SDK, we call 
  // FB.getLoginStatus().  This function gets the state of the
  // person visiting this page and can return one of three states to
  // the callback you provide.  They can be:
  //
  // 1. Logged into your app ('connected')
  // 2. Logged into Facebook, but not your app ('not_authorized')
  // 3. Not logged into Facebook and can't tell if they are logged into
  //    your app or not.
  //
  // These three cases are handled in the callback function.

  FB.getLoginStatus(function(response) {
    statusChangeCallback(response);
  });

  };

  // Load the SDK asynchronously
  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));

  // Here we run a very simple test of the Graph API after login is
  // successful.  See statusChangeCallback() for when this call is made.
  function testAPI() {
    console.log('Welcome!  Fetching your information.... ');
    FB.api('/me', function(response) {
      console.log('Successful login for: ' + response.name);
      document.getElementById('status').innerHTML =
        'Thanks for logging in, ' + response.name + '!';
    });
  }
</script>

<!--
  Below we include the Login Button social plugin. This button uses
  the JavaScript SDK to present a graphical Login button that triggers
  the FB.login() function when clicked.
-->

<fb:login-button scope="public_profile,email, user_mobile_phone" onlogin="checkLoginState();">
</fb:login-button>

<div id="status">
</div>



<div id="gConnect">
  <div id="signin-button"></div>
</div>
<div id="authOps" style="display:none">
  <h2>User is now signed in to the app using Google+</h2>
  <button id="signOut" onclick="auth2.signOut()">Sign Out</button>
  <p>If the user chooses to disconnect, the app must delete all stored
  information retrieved from Google for the given user.</p>
  <button id="disconnect" >Disconnect your Google account from this app</button>

  <h2>User's profile information</h2>
  <div id="profile"></div>

  <h2>User's friends that are visible to this app</h2>
  <div id="visiblePeople"></div>

  <h2>Authentication Logs</h2>
  <pre id="authResult"></pre>
</div>
<div id="loaderror">
  This section will be hidden by jQuery. If you can see this message, you
  may be viewing the file rather than running a web server.<br />
  The sample must be run from http or https. See instructions at
  <a href="https://developers.google.com/+/quickstart/javascript">
  https://developers.google.com/+/quickstart/javascript</a>.
</div>

<script type="text/javascript">
var auth2 = {};
var helper = (function() {
  return {
    /**
     * Hides the sign in button and starts the post-authorization operations.
     *
     * @param {Object} authResult An Object which contains the access token and
     *   other authentication information.
     */
    onSignInCallback: function(authResult) {
      $('#authResult').html('Auth Result:<br/>');
      for (var field in authResult) {
        $('#authResult').append(' ' + field + ': ' +
            authResult[field] + '<br/>');
      }
      if (authResult.isSignedIn.get()) {
        $('#authOps').show('slow');
        $('#gConnect').hide();
        helper.profile();
        helper.people();
      } else {
          if (authResult['error'] || authResult.currentUser.get().getAuthResponse() == null) {
            // There was an error, which means the user is not signed in.
            // As an example, you can handle by writing to the console:
            console.log('There was an error: ' + authResult['error']);
          }
          $('#authResult').append('Logged out');
          $('#authOps').hide('slow');
          $('#gConnect').show();
      }

      console.log('authResult', authResult);
    },

    /**
     * Calls the OAuth2 endpoint to disconnect the app for the user.
     */
    disconnect: function() {
      // Revoke the access token.
      auth2.disconnect();
    },

    /**
     * Gets and renders the list of people visible to this app.
     */
    people: function() {
      gapi.client.plus.people.list({
        'userId': 'me',
        'collection': 'visible'
      }).then(function(res) {
        var people = res.result;
        $('#visiblePeople').empty();
        $('#visiblePeople').append('Number of people visible to this app: ' +
            people.totalItems + '<br/>');
        for (var personIndex in people.items) {
          person = people.items[personIndex];
          $('#visiblePeople').append('<img src="' + person.image.url + '">');
        }
      });
    },

    /**
     * Gets and renders the currently signed in user's profile data.
     */
    profile: function(){
      gapi.client.plus.people.get({
        'userId': 'me'
      }).then(function(res) {
        var profile = res.result;
        console.log(profile);
        $('#profile').empty();
        $('#profile').append(
            $('<p><img src=\"' + profile.image.url + '\"></p>'));
        $('#profile').append(
            $('<p>Hello ' + profile.displayName + '!<br />Tagline: ' +
            profile.tagline + '<br />About: ' + profile.aboutMe + '</p>'));
        if (profile.emails) {
          $('#profile').append('<br/>Emails: ');
          for (var i=0; i < profile.emails.length; i++){
            $('#profile').append(profile.emails[i].value).append(' ');
          }
          $('#profile').append('<br/>');
        }
        if (profile.cover && profile.coverPhoto) {
          $('#profile').append(
              $('<p><img src=\"' + profile.cover.coverPhoto.url + '\"></p>'));
        }
      }, function(err) {
        var error = err.result;
        $('#profile').empty();
        $('#profile').append(error.message);
      });
    }
  };
})();

/**
 * jQuery initialization
 */
$(document).ready(function() {
  $('#disconnect').click(helper.disconnect);
  $('#loaderror').hide();
  if ($('meta')[0].content == 'YOUR_CLIENT_ID') {
    alert('This sample requires your OAuth credentials (client ID) ' +
        'from the Google APIs console:\n' +
        '    https://code.google.com/apis/console/#:access\n\n' +
        'Find and replace YOUR_CLIENT_ID with your client ID.'
    );
  }
});

/**
 * Handler for when the sign-in state changes.
 *
 * @param {boolean} isSignedIn The new signed in state.
 */
var updateSignIn = function() {
  console.log('update sign in state');
  if (auth2.isSignedIn.get()) {
    console.log('signed in');
    helper.onSignInCallback(gapi.auth2.getAuthInstance());
  }else{
    console.log('signed out');
    helper.onSignInCallback(gapi.auth2.getAuthInstance());
  }
}

/**
 * This method sets up the sign-in listener after the client library loads.
 */
function startApp() {
  gapi.load('auth2', function() {
    gapi.client.load('plus','v1').then(function() {
      gapi.signin2.render('signin-button', {
          scope: 'https://www.googleapis.com/auth/plus.login',
          fetch_basic_profile: false });
      gapi.auth2.init({fetch_basic_profile: false,
          scope:'https://www.googleapis.com/auth/plus.login'}).then(
            function (){
              console.log('init');
              auth2 = gapi.auth2.getAuthInstance();
              auth2.isSignedIn.listen(updateSignIn);
              auth2.then(updateSignIn);
            });
    });
  });
}
</script>
<script src="https://apis.google.com/js/client:platform.js?onload=startApp"></script>