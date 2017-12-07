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
            success: (response) => {
                this.App.showMessage(response.message, response.success);
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
            success: (response) => {
                this.App.showMessage(response.message, response.success);
                if (true === response.success) {
                    this.addBackupRow(response.backup);
                }
            },
            error: () => {
                this.App.showMessage('Server error. Please, try again', false);
            }
        });
    }

    /**
     *
     * @param backup
     */
    addBackupRow(backup) {
        let table = this.App.getElement('[data-element=backupsTable]'),
            no_backups = table.find('tr.no-backups-yet');

        if (no_backups !== undefined) {
            no_backups.hide();
        }

        table.find('tbody')
            .append('<tr><td></td><td>' + backup.name + '</td><td>' + backup.size_mb + '</td><td>' + backup.date_i18n + '</td><td><a href="' + backup.url + '" class="button button-cancel"><span class="icon dashicons dashicons-download"></span>Download</a><button data-action="deleteBackup" data-param="' + backup.name + '" class="button button-cancel"><span class="icon dashicons dashicons-trash"></span>Delete</button></td></tr>');

        this.App.Events.deleteBackup();
    }

    /**
     * @param backup
     * @param el
     */
    deleteBackup(backup, el) {
        jQuery.ajax({
            type: 'post',
            url: this.getApiUrl(),
            dataType: 'json',
            data: {
                action: this.getApiAction('delete'),
                nonce: this.getApiNonce(),
                backup: backup
            },
            success: (response) => {
                this.App.showMessage(response.message, response.success);
                if (true === response.success) {
                    this.deleteBackupRow(el);
                }
            },
            error: () => {
                this.App.showMessage('Server error. Please, try again', false);
            }
        });
    }

    /**
     *
     * @param el
     */
    deleteBackupRow(el) {
        el.closest('tr').fadeOut('normal');
    }
}