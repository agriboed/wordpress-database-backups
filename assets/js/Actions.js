/* eslint-disable no-undef */
class Actions {
  constructor (App) {
    this.App = App
  }

  /**
   *
   * @param action
   * @returns {*}
   */
  getApiAction (action) {
    return this.App.apiAction + action
  }

  /**
   *
   */
  getApiNonce () {
    return this.App.nonce
  }

  /**
   *
   */
  getApiUrl () {
    return this.App.url
  }

  /**
   *
   */
  saveOptions (options) {
    let data
    let formData
    let form = this.App.getElement('[data-element=optionsForm]')

    this.App.showLoader()

    formData = form.serializeArray()

    data = {
      action: this.getApiAction('options'),
      nonce: this.getApiNonce(),
      options: {},
      amazon_s3: false
    }

    if (options && options.amazon_s3 === true) {
      data.amazon_s3 = true
    }

    formData.map((el) => {
      data.options[el.name] = el.value
    })

    jQuery.ajax({
      type: 'post',
      url: this.getApiUrl(),
      data: data,
      dataType: 'json',
      success: (response) => {
        this.App.showMessage(response.message, response.success)
        this.App.hideLoader()
      },
      error: () => {
        this.App.showMessage('Server error. Please, try again', false)
        this.App.hideLoader()
      }
    })

    return this
  }

  /**
   *
   */
  createBackup () {
    let data
    this.App.showLoader()

    data = {
      action: this.getApiAction('create'),
      nonce: this.getApiNonce()
    }

    jQuery.ajax({
      type: 'post',
      url: this.getApiUrl(),
      data: data,
      dataType: 'json',
      success: (response) => {
        this.App.showMessage(response.message, response.success)
        if (response.success === true) {
          this.addBackupRow(response.backup)
        }
        this.App.hideLoader()
      },
      error: () => {
        this.App.showMessage('Server error. Please, try again', false)
        this.App.hideLoader()
      }
    })
  }

  /**
   *
   * @param backup
   */
  addBackupRow (backup) {
    let table = this.App.getElement('[data-element=backupsTable]')
    let noBackups = this.App.getElement('[data-element=noBackups]')

    if (noBackups.attr('display') !== 'none') {
      noBackups.hide()
    }

    table.find('tbody tr:first').after('<tr><td></td><td>' +
      '<span class="icon dashicons ' + backup.icon + '"></span>' + backup.name +
      '</td>' +
      '<td>' + backup.size_mb + '</td><td>' + backup.date_i18n + '</td>' +
      '<td><a href="' + backup.url + '" class="button button-cancel">' +
      '<span class="icon dashicons dashicons-download"></span> Download</a>' +
      ' <button data-action="deleteBackup" data-param="' + backup.name +
      '" class="button button-cancel">' +
      ' <span class="icon dashicons dashicons-trash"></span> Delete</button></td></tr>')

    this.App.Events.deleteBackup()
    this.rebuildBackupsTable()
  }

  /**
   * @param backup
   * @param el
   */
  deleteBackup (backup, el) {
    if (!confirm('Are you sure?')) {
      return
    }

    this.App.showLoader()

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
        this.App.showMessage(response.message, response.success)
        if (response.success === true) {
          this.deleteBackupRow(el)
        }
        this.App.hideLoader()
      },
      error: () => {
        this.App.showMessage('Server error. Please, try again', false)
        this.App.hideLoader()
      }
    })
  }

  /**
   *
   * @param el
   *
   */
  deleteBackupRow (el) {
    el.closest('tr').remove()
    this.rebuildBackupsTable()
  }

  /**
   *
   */
  rebuildBackupsTable () {
    let table = this.App.getElement('[data-element=backupsTable]')
    let noBackups = this.App.getElement('[data-element=noBackups]')
    let count = table.find('tbody tr:not([data-element=noBackups])')
    let numberRow = table.find(
      'tbody tr:not([data-element=noBackups]) td:first-child')

    if (count.length === 0) {
      noBackups.fadeIn('normal')
    } else {
      jQuery.each(numberRow, (i, el) => {
        jQuery(el).html(i)
      })
      noBackups.fadeOut('normal')
    }
  }
}