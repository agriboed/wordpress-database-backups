/**
 * global document, $
 */

class App {
    /**
     *
     */
    constructor(options) {
        this.key = options.key;
        this.url = options.admin_url;
        this.nonce = options.nonce;
        this.apiAction = 'database-backups_';
        this.container = jQuery('.database-backups');

        this.Events = new Events(this);
        this.Actions = new Actions(this);
    }

    /**
     * @param selector
     * @return object
     */
    getElement(selector) {
        return this.container.find(selector);
    }

    /**
     *
     * @param message
     * @param success
     */
    showMessage(message, success = true) {
        let el = this.getElement('[data-container=message]');

        el.html('').removeClass('success error');

        if (true === success) {
            el.addClass('success')
        }
        else {
            el.addClass('error');
        }

        el.html(message).fadeIn('normal');

        setTimeout(() => {
            el.fadeOut('normal');
        }, 5000);
    }
}

let DatabaseBackups = function (options) {
    new App(options);
};