class Events {
    constructor(App) {
        this.App = App;
        this.init();
    }

    /**
     *
     */
    init() {
        this.bindToggle().createBackup().saveOptions();
    }

    /**
     * Toggles
     */
    bindToggle() {
        let el = this.App.getElement('[data-action=toggle]');

        el.each((i, e) => {
            let el = jQuery(e),
                container_name = el.data('param-container'),
                container = this.App.getElement('[data-container=' + container_name + ']');

            if (container === undefined) {
                return;
            }

            if (el.data('param-show') === true) {
                container.show();
            } else {
                container.hide();
            }

            el.click(() => {
                container.toggle('normal');
            });
        });

        return this;
    }

    /**
     * @return Events
     */
    createBackup() {
        let el = this.App.getElement('button[data-action=create]');

        el.click(() => {
            this.App.Actions.createBackup();
        });

        return this;
    }

    /**
     *
     */
    saveOptions() {
        let el = this.App.getElement('[data-action=saveOptions]'),
            self = this;

        el.click(function () {
            if (jQuery(this).data('param') === 'checkAmazonS3') {
                return self.App.Actions.saveOptions({amazon_s3: true});
            }

            self.App.Actions.saveOptions();
        });

        return this;
    }
}