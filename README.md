## Moodle - availability SMS plugin
Restrict access to any activity by a validation SMS.

## Author
![MFreak.nl](http://MFreak.nl/logo_small.png)

* Author: Luuk Verhoeven, [MFreak.nl](https://MFreak.nl/)
* Min. required: Moodle 3.2.x
* Supports PHP: 7.0 | 7.1 

[![Build Status](https://travis-ci.org/MFreakNL/moodle-availability_sms.svg?branch=master)](https://travis-ci.org/MFreakNL/moodle-availability_sms)

## List of features
- Implements cmtelecoms Messaging provider API
- Authenticated for the duration of the session
- Blocking popup prevent doing other things in the course
- Using user there phone field for SMS

## Installation
1.  Copy this plugin to the `availability\condition\sms` folder on the server
2.  Login as administrator
3.  Go to Site Administrator > Notification
4.  Install the plugin

## TODO 
- Behat tests SMS validation

## Security

If you discover any security related issues, please email [luuk@MFreak.nl](mailto:luuk@MFreak.nl) instead of using the issue tracker.

## License

The GNU GENERAL PUBLIC LICENSE. Please see [License File](LICENSE) for more information.

## Contributing

Contributions are welcome and will be fully credited. We accept contributions via Pull Requests on Github.
