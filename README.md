ReelGoodPicks
1. Front-End Changes 
- Redesigned the user interface for test.html, index.html, and watchlist.html for better usability.
- Created a movie preference test with dynamic question loading and progress bar.
- Implemented a swipe-based recommendation system after the test using dynamic JavaScript.
- Added dynamic movie card updates for random movie suggestions without page reloads.
- Enhanced the watchlist page to allow users to remove items dynamically without refreshing.
- Ensured responsive design by using flexible CSS layouts and modern styling practices.
- Added loading indicators and button text changes (e.g., "Added!" or "Removed!") based on user actions.

2. Usage of AJAX and jQuery

AJAX (Fetch API) was used for sending and receiving data without reloading pages:
test.html → After completing the questionnaire, AJAX is used to:
- Send user answers to test_and_swipe.php (POST request)
- Retrieve personalized movie recommendations (JSON response)
- Swipe Section → AJAX sends "like" actions to test_and_swipe.php to add movies to the watchlist.
- index.html → Clicking "Get Another Movie" triggers an AJAX request to random.php to fetch a new random movie.
- index.html and watchlist.html → Adding/removing movies from the watchlist using AJAX POST requests to add_to_watchlist.php and remove_from_watchlist.php.

jQuery was used for:
- Handling document ready states ($(document).ready())
- Attaching event listeners dynamically (e.g., clicking the "Add to Watchlist" and "Remove from Watchlist" buttons)
- Simplifying AJAX calls and making DOM manipulations smoother.

Pages where jQuery is used:
- index.html
- watchlist.html

3. Online Resources Referenced

Resource	                            Usage
MDN Web Docs (developer.mozilla.org)	For Fetch API syntax, JavaScript event handling, and DOM manipulation reference
W3Schools	                            For quick CSS and JavaScript examples (button styling, responsive layouts)
jQuery Official Documentation	        For correct usage of jQuery event handling and AJAX calls
StackOverflow	                        For debugging fetch issues and correct formatting of POST requests
Bootstrap Documentation             	For some button classes and responsive layout tips

4. Comments Usage
   
Extensive inline comments were added across JavaScript files to explain:
- What each function does (e.g., fetchMovies(), handleSwipe()).
- Why certain event listeners or AJAX calls are used.
- The flow of control for the test-to-swipe transition.

Comments are included inside PHP files like test_and_swipe.php, add_to_watchlist.php, and remove_from_watchlist.php to explain:
- Database connection
- Handling of POST requests
- Response structure (success/fail JSON)
