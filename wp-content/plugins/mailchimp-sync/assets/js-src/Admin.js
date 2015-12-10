var Admin = (function() {
	'use strict';

	var Wizard = require('./Wizard.js');
	var FieldMapper = require('./FieldMapper.js');
	var $ = window.jQuery;

	// init wizard
	var wizardContainer = document.getElementById('wizard');
	if( wizardContainer ) {
		m.module( wizardContainer , Wizard );
	}

	// init fieldmapper
	new FieldMapper($('.mc4wp-sync-field-map'));
})();

module.exports = Admin;