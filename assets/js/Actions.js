class Actions {
    constructor(App) {
        this.App = App;
    }

    /**
     *
     * @param action
     * @returns {*}
     */
    getApiAction(action) {
        return this.App.apiAction + action
    }

    /**
     *
     */
    getApiNonce() {
        return this.App.nonce;
    }

    /**
     *
     */
    getApiUrl() {
        return this.App.url;
    }

    /**
     *
     */
    saveOptions(options) {
        let data,
            formData,
            form = this.App.getElement('[data-element=optionsForm]');

        formData = form.serializeArray();

        data = {
            action: this.getApiAction('options'),
            nonce: this.getApiNonce(),
            options: {},
            amazon_s3: false,
        };

        if (options && options.amazon_s3 === true) {
            data.amazon_s3 = true;
        }

        formData.map((el) => {
            data.options[el.name] = el.value;
        });

        jQuery.ajax({
            type: 'post',
            url: this.getApiUrl(),
            data: data,
            dataType: 'json',
            success: (data) => {
                this.App.showMessage(data.message, data.success);
            },
            error: () => {
                this.App.showMessage('Server error. Please, try again', false);
            }
        });
    }

    /**
     *
     */
    createBackup() {
        let data;

        data = {
            action: this.getApiAction('create'),
            nonce: this.getApiNonce(),
        };

        jQuery.ajax({
            type: 'post',
            url: this.getApiUrl(),
            data: data,
            dataType: 'json',
            success: (data) => {
                this.App.showMessage('Backup created');
            }
        });
    }

    /**
     *
     */
    deleteBackup() {
        jQuery.ajax({
            type: 'post',
            url: this.getApiUrl(),
            data: {
                action: this.getApiAction('delete'),
                nonce: this.getApiNonce(),
            },
            success: function (data) {
                console.log(data);
            }
        });
    }
}