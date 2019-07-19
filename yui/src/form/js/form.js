/**
 * Availability sms YUI code
 *
 * @package   availability_sms
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright 2019-05-14 Mfreak.nl | LdesignMedia.nl - Luuk Verhoeven
 */

/**
 * JavaScript for form editing grade conditions.
 *
 * @module moodle-availability_sms-form
 */
M.availability_sms = M.availability_sms || {};

// MIT https://github.com/sindresorhus/ip-regex
// Advanced ip-address regex for validating.
M.availability_sms.v4 = '(?:25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9][0-9]|[0-9])(?:\\.(?:25[0-5]|2[0-4][0-9]|1[0-9]' +
    '[0-9]|[1-9][0-9]|[0-9])){3}';
M.availability_sms.v6 = "^((?:[a-fA-F\\d]{1,4}:){7}(?:[a-fA-F\\d]{1,4}|:)|(?:[a-fA-F\\d]{1,4}:){6}(?:(?:25[0-5]|2[0-4]" +
    "\\d|1\\d\\d|[1-9]\\d|\\d)(?:\\.(?:25[0-5]|2[0-4]\\d|1\\d\\d|[1-9]\\d|\\d)){3}|:[a-fA-F\\d]{1,4}|:)|(?:[a-fA-F\\d]{1,4}:){5}" +
    "(?::(?:25[0-5]|2[0-4]\\d|1\\d\\d|[1-9]\\d|\\d)(?:\\.(?:25[0-5]|2[0-4]\\d|1\\d\\d|[1-9]\\d|\\d)){3}|(:[a-fA-F\\d]{1,4})" +
    "{1,2}|:)|(?:[a-fA-F\\d]{1,4}:){4}(?:(:[a-fA-F\\d]{1,4}){0,1}:(?:25[0-5]|2[0-4]\\d|1\\d\\d|[1-9]\\d|\\d)(?:\\.(?:25[0-5]" +
    "|2[0-4]\\d|1\\d\\d|[1-9]\\d|\\d)){3}|(:[a-fA-F\\d]{1,4}){1,3}|:)|(?:[a-fA-F\\d]{1,4}:){3}(?:(:[a-fA-F\\d]{1,4}){0,2}:" +
    "(?:25[0-5]|2[0-4]\\d|1\\d\\d|[1-9]\\d|\\d)(?:\\.(?:25[0-5]|2[0-4]\\d|1\\d\\d|[1-9]\\d|\\d)){3}|(:[a-fA-F\\d]{1,4}){1,4}|:)|" +
    "(?:[a-fA-F\\d]{1,4}:){2}(?:(:[a-fA-F\\d]{1,4}){0,3}:(?:25[0-5]|2[0-4]\\d|1\\d\\d|[1-9]\\d|\\d)(?:\\.(?:25[0-5]|2[0-4]" +
    "\\d|1\\d\\d|[1-9]\\d|\\d)){3}|(:[a-fA-F\\d]{1,4}){1,5}|:)|(?:[a-fA-F\\d]{1,4}:){1}(?:(:[a-fA-F\\d]{1,4}){0,4}:(?:25[0-5]" +
    "|2[0-4]\\d|1\\d\\d|[1-9]\\d|\\d)(?:\\.(?:25[0-5]|2[0-4]\\d|1\\d\\d|[1-9]\\d|\\d)){3}|(:[a-fA-F\\d]{1,4}){1,6}|:)|(?::" +
    "((?::[a-fA-F\\d]{1,4}){0,5}:(?:25[0-5]|2[0-4]\\d|1\\d\\d|[1-9]\\d|\\d)(?:\\.(?:25[0-5]|2[0-4]\\d|1\\d\\d|[1-9]\\d|\\d)){3}" +
    "|(?::[a-fA-F\\d]{1,4}){1,7}|:)))(%[0-9a-zA-Z]{1,})?";

/**
 * @class M.availability_sms.form
 * @extends M.core_availability.plugin
 */
M.availability_sms.form = Y.Object(M.core_availability.plugin);

/**
 * Initialises this plugin.
 *
 * @method initInner
 * @param {Array} param Array of objects
 */
M.availability_sms.form.initInner = function() {
    "use strict";
    Y.log('M.availability_sms 1.10');
};

/**
 * Gets the numeric value of an input field. Supports decimal points (using
 * dot or comma).
 *
 * @method getValue
 * @param {string} field
 * @param {object} node
 * @return {Number|String} Value of field as number or string if not valid
 */
M.availability_sms.form.getValue = function(field, node) {
    "use strict";
    // Get field value.
    var value = node.one('input[name=' + field + ']').get('value');
    Y.log('ip_address:' + value);

    // If it is not a valid positive number, return false.
    if (M.availability_sms.validateIpaddress(value)) {
        Y.log('Valid ip-address');
        return value;
    }

    return value;
};

/**
 * Get node
 *
 * @param {object} json
 * @returns {*}
 */
M.availability_sms.form.getNode = function(json) {
    "use strict";
    var html, node, root, id;

    // Make sure we work with unique id.
    id = 'ipaddresses' + M.availability_sms.form.instId;
    M.availability_sms.form.instId += 1;

    // Create HTML structure.
    html = '';
    html += '<span class="availability-group"><label for="' + id + '"><span class="p-r-1">' +
        M.util.get_string('title', 'availability_sms') + ' </span></label>';
    html += '<input type="text" placeholder="192.168.178.1,231.54.211.0/20,231.3.56.211" name="ipaddresses" id="' + id + '">';
    node = Y.Node.create('<span class="form-inline">' + html + '</span>');

    // Set initial values, if specified.
    if (json.ipaddresses !== undefined) {
        node.one('input[name=ipaddresses]').set('value', json.ipaddresses);
    }

    // Add event handlers (first time only).
    if (!M.availability_sms.form.addedEvents) {
        M.availability_sms.form.addedEvents = true;
        root = Y.one('.availability-field');
        root.delegate('valuechange', function() {
            // Trigger the updating of the hidden availability data whenever the ipaddress field changes.
            M.core_availability.form.update();
        }, '.availability_sms input[name=ipaddresses]');
    }

    return node;
};

/**
 * Validate ipaddresses
 *
 * @param {string[]} ipaddresses
 * @returns {boolean}
 */
M.availability_sms.validateIpaddress = function(ipaddresses) {
    'use strict';
    Y.log(ipaddresses);
    ipaddresses = ipaddresses.split(',');
    for (var i in ipaddresses) {

        // Test normal ip format.
        // Strict ipv4 check.
        if (new RegExp(/^(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)(?:\.(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)){3}$/gm)
            .test(ipaddresses[i])) {
            Y.log('Correct ipv4');
            continue;
        }

        if (new RegExp(M.availability_sms.v6)
            .test(ipaddresses[i])) {
            Y.log('Correct ipv6');
            continue;
        }

        // Test subnet with a regex.
        if (new RegExp("^(?:".concat(M.availability_sms.v4 + "\\/(3[0-2]|[12]?[0-9])", ")|(?:")
            .concat(M.availability_sms.v6 + "\\/(12[0-8]|1[01][0-9]|[1-9]?[0-9])", ")?\\/gm"))
            .test(ipaddresses[i])) {
            Y.log('Correct subnet');
            continue;
        }

        Y.log('Incorrect ip');
        return false;
    }

    Y.log('Valid ipaddresses');
    return true;
};

/**
 * FillValue
 *
 * @param {object} value
 * @param {object} node
 */
M.availability_sms.form.fillValue = function(value, node) {
    // This function gets passed the node (from above) and a value
    // object. Within that object, it must set up the correct values
    // to use within the JSON data in the form. Should be compatible
    // with the structure used in the __construct and save functions
    // within condition.php.
    value.ipaddresses = this.getValue('ipaddresses', node);
};

/**
 * FillErrors
 * @param {object} errors
 * @param {object} node
 */
M.availability_sms.form.fillErrors = function(errors, node) {
    "use strict";
    var value = {};
    this.fillValue(value, node);

    // Basic ipaddresses checks.
    if (M.availability_sms.validateIpaddress(value.ipaddresses) === false) {
        errors.push('availability_sms:error_ipaddress');
    }
};