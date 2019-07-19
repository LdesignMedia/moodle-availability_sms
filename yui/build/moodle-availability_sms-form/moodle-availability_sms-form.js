YUI.add('moodle-availability_sms-form', function (Y, NAME) {

/**
 * Availability sms YUI code
 *
 * @package   availability_sms
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright 2019-07-19 Mfreak.nl | LdesignMedia.nl - Luuk Verhoeven
 */

/**
 * JavaScript for form editing grade conditions.
 *
 * @module moodle-availability_sms-form
 */
M.availability_sms = M.availability_sms || {};

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

    // Validation?

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
    var html, node, id;

    // Make sure we work with unique id.
    id = 'sms' + M.availability_sms.form.instId;
    M.availability_sms.form.instId += 1;

    // Create HTML structure.
    html = '';
    html += '<span class="availability-group"><label for="' + id + '"><span class="p-r-1">' +
        M.util.get_string('title', 'availability_sms') + ' </span></label>';
    node = Y.Node.create('<span class="form-inline">' + html + '</span>');

    return node;
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
};

/**
 * FillErrors
 *
 * @param {object} errors
 * @param {object} node
 */
M.availability_sms.form.fillErrors = function(errors, node) {
    "use strict";
    var value = {};
    this.fillValue(value, node);
};

}, '@VERSION@', {"requires": ["base", "node", "event", "moodle-core_availability-form"]});
