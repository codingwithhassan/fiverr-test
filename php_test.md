# Test

The test consists of writing two scripts which should be run on the command line via the php executable.

## Guidelines

- The files fetch_and_redact.php & query.php are intended for you (the developer) to complete. Any code currently in those files should not be changed.
- The PHP code written should be compatible with PHP 7.0.
- In addition to coding them and handling any errors you believe could occur, you should also comment appropriately.
- If you are running out of time, focus on implementing the requirements well rather than attempting to complete all of them at a lower quality.
- Use snake_case for variables & function names.
- Please use curl for the HTTP request and avoid the use of third-party libraries.

The two scripts which require implementation are:

### fetch_and_redact.php

- Fetch a JSON file using a HTTP GET call to a known URL. The URL has Basic Authentication enabled.
    - URL: https://tst-api.feeditback.com/exam.users
    - Username: `dev_test_user`
    - Password: `V8(Zp7K9Ab94uRgmmx2gyuT.`
- Redact personal data from the the JSON:
    - Remove the latitude and longitude fields
    - Replace the content of the email field with an encrypted version, using the provided hash function (hash_value).
        - An appropriate salt should be used.
        - Note that in addition, we intend to search these users by email address later, so this should be taken into account.
    - Obfuscate the address field in the following way:
        - For each word, if the string length is <= 2 characters, leave it as it is.
        - Otherwise replace all but the first 2 characters with *s. (For example, '15 High Street' would be replaced by '15 Hi** St****')
    - Save the JSON data as users.json.

### query.php

The query & report functions have been left to implement. Below these are a series of tests which will be run when the script is run.

- query()
    - query should search the json data in users.json and echo out the first_name and last_name fields of matching users.
        - `$field` identifies the user field which should be inspected
        - `$value` represents the data to match against.
        - `$exact` indicates whether the field exactly matches $value (TRUE) or whether the result should be returned if the field contains any instance of $value.
    - Note that searches by email address should do so by comparing email hashes.

- report()
    - A report should be generated and written to disk called users-report.json.
    - It should contain the following information in an appropriate format:
        - The full name of the users who were created first & last.
        - The most common favorite_colour used.
        - The average age of a user, to 2 decimal places
        - From the 'about' fields across all users, a breakdown of word occurrence.