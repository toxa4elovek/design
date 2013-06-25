<script type="text/javascript">
    // You probably don't want to use globals, but this is just example code


    function postLike() {
        FB.api(
                'me/godesigner:create',
                'post',
                {
                    pitch: "http://www.godesigner.ru/pitches/details/100940"
                },
                function(response) {
                    if (!response) {
                        alert('Error occurred.');
                    } else if (response.error) {
                        document.getElementById('result').innerHTML = 'Error: ' + response.error.message;
                    } else {
                        document.getElementById('result').innerHTML =
                                '<a href=\"https://www.facebook.com/me/activity/' + response.id + '\">' +
                                        'Story created.  ID is ' + response.id + '</a>';
                    }
                }
        );
    }
</script>

<!--
  Login Button - https://developers.facebook.com/docs/reference/plugins/login

  This example needs the 'publish_actions' permission in order to publish an
  action.  The scope parameter below is what prompts the user for that permission.
-->

<div
        class="fb-login-button"
        data-show-faces="true"
        data-width="200"
        data-max-rows="1"
        data-scope="publish_actions">
</div>

<div style="background-color: white; height: 400px">
    This example creates a story on Facebook using the <a href="https://developers.facebook.com/docs/reference/ogaction/og.likes"><code>og.likes</code></a> API.  That story will just say that you like an <a href="http://techcrunch.com/2013/02/06/facebook-launches-developers-live-video-channel-to-keep-its-developer-ecosystem-up-to-date/">article on TechCrunch</a>.  The story should only be visible to you.
</div>

<div>
    <input type="button" value="Create a story with an og.likes action" onclick="postLike();">
</div>

<div id="result"></div>