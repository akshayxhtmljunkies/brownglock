/**
 * Log model
 */
var Log = function() {
	var self = this;
	this.items = m.prop([]);

	// add line to items array
	this.addLine = function( text ) {

		var line = {
			time: new Date(),
			text: text
		};

		self.items().push( line );
		m.redraw();
	};

	// add some text to last log item
	this.addTextToLastLine = function( text ) {
		var line = self.items().pop();
		line.text += " " + text;
		self.items().push( line );
		m.redraw();
	};

	/**
	 * Scroll to bottom of log
	 *
	 * @param element
	 * @param initialized
	 * @param context
	 */
	this.scrollToBottom = function( element, initialized, context ) {
		element.scrollTop = element.scrollHeight;
	};

	// render all lines
	this.render = function() {
		return m("div.log", { config: self.scrollToBottom }, [
			self.items().map( function( item ) {

				var timeString =
					("0" + item.time.getHours()).slice(-2)   + ":" +
					("0" + item.time.getMinutes()).slice(-2) + ":" +
					("0" + item.time.getSeconds()).slice(-2);

				return m("p", [
					m('span.time', timeString),
					m.trust(item.text )
				] )
			})
		]);
	};
};

module.exports = Log;