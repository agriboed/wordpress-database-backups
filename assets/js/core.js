/**
 * global document, $
 */
class App {

    /**
     *
     */
    constructor() {
        this.key = window.DatabaseBackups.key;
        this.url = window.DatabaseBackups.admin_url;
        this.nonce = window.DatabaseBackups.nonce;
        this.container = jQuery('.database-backups');

        //  try {
        this.init();
        // } catch (e) {
        //    console.log('Error:' + e);
        //   }
    }

    /**
     * @param selector
     * @return object
     */
    getElement(selector) {
        return this.container.find(selector);
    }

    /**
     * @return App
     */
    init() {
        this.bindCreate().bindOptions().bindCronOption().bindDeleteOption().bindAmazonS3Option();

        return this;
    }

    /**
     * @return App
     */
    bindCreate() {
        let createButton = this.getElement('button[data-action=create]');
        createButton.click(() => {
            this.createBackupCall();
        });
        return this;
    }

    /**
     *
     * @returns {App}
     */
    bindOptions() {
        let button = this.getElement('button[data-action=options-toggle]'),
            container = this.getElement('[data-container=options]');

        button.click(() => {
            container.toggle('slow');
        });

        return this;
    }

    /**
     *
     * @returns {App}
     */
    bindCronOption() {
        let button = this.getElement('input#cron'),
            container = this.getElement('[data-container=cron]');

        let check = () => {
            if (button.attr('checked')) {
                container.fadeIn('slow');
            }
            else {
                container.fadeOut('slow');
            }
        };

        button.click(() => {
            check()
        });

        return this;
    }

    /**
     *
     * @returns {App}
     */
    bindDeleteOption() {
        let button = this.getElement('input#delete'),
            container = this.getElement('[data-container=delete]');

        let check = () => {
            if (button.attr('checked')) {
                container.fadeIn('slow');
            }
            else {
                container.fadeOut('slow');
            }
        };

        button.click(() => {
            check()
        });

        return this;
    }

    /**
     *
     * @returns {App}
     */
    bindAmazonS3Option() {
        let button = this.getElement('input#amazon_s3'),
            container = this.getElement('[data-container=amazon_s3]');

        let check = () => {
            if (button.attr('checked')) {
                container.fadeIn('slow');
            }
            else {
                container.fadeOut('slow');
            }
        };

        button.click(() => {
            check()
        });

        return this;
    }

    /**
     *
     */
    createBackupCall() {
        jQuery.ajax({
            type: 'POST',
            url: this.url,
            data: {
                action: 'database-backups_create',
                nonce: this.nonce,
            },
            success: function (data) {
                console.log(data);
            }
        });
    }

    /**
     *
     */
    deleteBackupCall() {
        jQuery.ajax({
            type: 'POST',
            url: this.url,
            data: {
                action: 'database-backups_delete',
                nonce: this.nonce,
            },
            success: function (data) {
                console.log(data);
            }
        });
    }

    /**
     *
     */
    checkAmazonS3Call() {
        jQuery.ajax({
            type: 'POST',
            url: this.url,
            data: {
                action: 'database-backups_amazon_s3',
                nonce: this.nonce,
            },
            success: function (data) {
                console.log(data);
            }
        });
    }
}

(function ($) {
    $(document).ready(() => {
        new App();
    });
}(jQuery));